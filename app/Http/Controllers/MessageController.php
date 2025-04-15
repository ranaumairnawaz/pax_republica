<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display the chat interface with a list of conversations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get unique conversation partners (users who have exchanged messages with the current user)
        $conversations = $this->getConversations();
        
        // Get the selected conversation user if any
        $selectedUser = null;
        $messages = collect([]);
        
        if (request()->has('user') && is_numeric(request('user'))) {
            $selectedUser = User::find(request('user'));
            
            if ($selectedUser) {
                $messages = $this->getConversationMessages($selectedUser->id);
                
                // Mark all messages from the selected user as read
                Message::where('sender_id', $selectedUser->id)
                    ->where('recipient_id', Auth::id())
                    ->where('is_read', false)
                    ->update(['is_read' => true, 'read_at' => now()]);
            }
        }
        
        return view('messages.chat', compact('conversations', 'selectedUser', 'messages'));
    }
    
    /**
     * Get all conversations for the current user.
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getConversations()
    {
        $userId = Auth::id();
        
        // Get all users who have messaged with the current user
        $userIds = Message::where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->orWhere('recipient_id', $userId);
            })
            ->whereNull('deleted_at')
            ->select('sender_id', 'recipient_id')
            ->get()
            ->map(function($message) use ($userId) {
                return $message->sender_id == $userId ? $message->recipient_id : $message->sender_id;
            })
            ->unique();
        
        // Get user details with last message and unread count
        $conversations = User::whereIn('id', $userIds)
            ->where('is_active', true)
            ->with(['sentMessages' => function($query) use ($userId) {
                $query->where('recipient_id', $userId)
                      ->whereNull('deleted_at')
                      ->latest()
                      ->limit(1);
            }, 'receivedMessages' => function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->whereNull('deleted_at')
                      ->latest()
                      ->limit(1);
            }])
            ->get()
            ->map(function($user) use ($userId) {
                // Get the last message
                $lastMessage = Message::where(function($query) use ($userId, $user) {
                        $query->where(function($q) use ($userId, $user) {
                            $q->where('sender_id', $userId)
                              ->where('recipient_id', $user->id);
                        })->orWhere(function($q) use ($userId, $user) {
                            $q->where('sender_id', $user->id)
                              ->where('recipient_id', $userId);
                        });
                    })
                    ->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                // Get unread count for this conversation
                $unreadCount = Message::where('sender_id', $user->id)
                    ->where('recipient_id', $userId)
                    ->where('is_read', false)
                    ->whereNull('deleted_at')
                    ->count();
                
                $user->last_message = $lastMessage;
                $user->unread_count = $unreadCount;
                
                return $user;
            })
            ->sortByDesc(function($user) {
                return $user->last_message ? $user->last_message->created_at : null;
            })
            ->values();
        
        return $conversations;
    }
    
    /**
     * Get messages between current user and specified user.
     * 
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    private function getConversationMessages($userId)
    {
        $currentUserId = Auth::id();
        
        return Message::where(function($query) use ($currentUserId, $userId) {
                $query->where(function($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $currentUserId)
                      ->where('recipient_id', $userId);
                })->orWhere(function($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $userId)
                      ->where('recipient_id', $currentUserId);
                });
            })
            ->whereNull('deleted_at')
            ->with('sender')
            ->orderBy('created_at')
            ->get();
    }
    
    /**
     * Show the form for starting a new conversation.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())
            ->where('is_active', true)
            ->orderBy('account_name')
            ->get();
            
        return view('messages.new', compact('users'));
    }
    
    /**
     * Send a message via Ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        $request->validate([
            'recipient_id' => ['required', 'exists:users,id'],
            'content' => ['required', 'string'],
        ]);
        
        // Ensure recipient is not the same as sender
        if ($request->recipient_id == Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot send a message to yourself.'
            ], 422);
        }
        
        // Check for duplicate messages (same content sent to same recipient within last 5 seconds)
        $duplicateCheck = Message::where('sender_id', Auth::id())
            ->where('recipient_id', $request->recipient_id)
            ->where('content', $request->content)
            ->where('created_at', '>=', now()->subSeconds(5))
            ->exists();
            
        if ($duplicateCheck) {
            return response()->json([
                'success' => false,
                'message' => 'Duplicate message detected.'
            ]);
        }
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'subject' => 'Chat message', // Default subject for chat messages
            'content' => $request->content,
        ]);
        
        $message->load('sender');
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
    
    /**
     * Fetch new messages for a conversation via Ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchMessages(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'last_message_id' => ['nullable', 'numeric']
        ]);
        
        $userId = $request->user_id;
        $lastMessageId = $request->last_message_id;
        $currentUserId = Auth::id();
        
        $messages = Message::where(function($query) use ($currentUserId, $userId) {
                $query->where(function($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $currentUserId)
                      ->where('recipient_id', $userId);
                })->orWhere(function($q) use ($currentUserId, $userId) {
                    $q->where('sender_id', $userId)
                      ->where('recipient_id', $currentUserId);
                });
            })
            ->when($lastMessageId, function($query, $lastMessageId) {
                return $query->where('id', '>', $lastMessageId);
            })
            ->whereNull('deleted_at')
            ->with('sender')
            ->orderBy('created_at')
            ->get();
        
        // Mark all new messages from the other user as read
        Message::where('sender_id', $userId)
            ->where('recipient_id', $currentUserId)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        
        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
    
    /**
     * Get updated conversations list via Ajax.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpdatedConversations()
    {
        $conversations = $this->getConversations();
        
        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);
    }
    
    /**
     * Check if a user is online.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUserStatus(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);
        
        $user = User::find($request->user_id);
        
        return response()->json([
            'success' => true,
            'is_online' => $user ? $user->isOnline() : false,
            'last_activity' => $user && $user->last_activity_at ? $user->last_activity_at->diffForHumans() : null
        ]);
    }
    
    /**
     * Remove the specified message from storage (soft delete).
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Message $message)
    {
        // Ensure the user is either the sender or recipient
        if ($message->sender_id !== Auth::id() && $message->recipient_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $message->delete();
        
        return back()->with('success', 'Message deleted successfully.');
    }
}
