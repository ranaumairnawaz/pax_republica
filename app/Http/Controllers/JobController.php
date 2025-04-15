<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobComment;
use App\Models\Character;
use App\Models\User;
use App\Models\JobCommentEdit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    /**
     * Display a listing of the jobs.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $jobs = Job::with('creator', 'handler', 'character')
            ->orderBy('last_activity_at', 'desc');

        // Filter by user (if provided)
        if ($request->has('user_id') && Auth::user()->isAdmin()) {
            $jobs->where('creator_id', $request->user_id);
        } else {
            $jobs->where('creator_id', Auth::id());
        }

        // Filter by status (if provided)
        if ($request->has('status')) {
            $jobs->where('status', $request->status);
        }

        $jobs = $jobs->paginate(20); // Example pagination

        return view('jobs.index', compact('jobs'));
    }

    /**
     * Display a listing of the resource.
     */
    public function adminIndex()
    {
        $jobs = Job::all(); // Or however you want to retrieve the jobs for admin management
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = [
            Job::CATEGORY_ADVANCEMENT,
            Job::CATEGORY_APPLICATIONS,
            Job::CATEGORY_BUG_REPORTS,
            Job::CATEGORY_FEEDBACK,
            Job::CATEGORY_PITCH,
            Job::CATEGORY_REWORK,
            Job::CATEGORY_TP,
        ];
        $characters = Auth::user()->characters()->active()->get(); // For linking jobs to characters

        return view('jobs.create', compact('categories', 'characters'));
    }

    /**
     * Store a newly created job in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in([
                Job::CATEGORY_ADVANCEMENT, Job::CATEGORY_APPLICATIONS, Job::CATEGORY_BUG_REPORTS,
                Job::CATEGORY_FEEDBACK, Job::CATEGORY_PITCH, Job::CATEGORY_REWORK, Job::CATEGORY_TP
            ])],
            'character_id' => ['nullable', 'exists:characters,id'],
            'content' => ['required', 'string'], // Initial comment/description
        ]);

        // Ensure character belongs to the user if provided
        if ($request->filled('character_id')) {
            $character = Character::findOrFail($request->character_id);
            if ($character->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
        }

        $job = Job::create([
            'title' => $request->title,
            'category' => $request->category,
            'creator_id' => Auth::id(),
            'character_id' => $request->character_id,
            'status' => Job::STATUS_OPEN,
            'last_activity_at' => now(),
        ]);

        // Create the initial comment
        $job->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Job created successfully.');
    }

    /**
     * Display the specified job.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\View\View
     */
    public function show(Job $job)
    {
        $job->load(['creator', 'handler', 'character', 'comments.user']);

        if (!Auth::user()->isAdmin() && $job->creator_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified job. (Likely only for admins or specific fields)
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\View\View
     */
    public function edit(Job $job)
    {
        // TODO: Implement edit logic and authorization
        abort(501); // Not Implemented
    }

    /**
     * Update the specified job in storage. (Likely only for admins or specific fields)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Job $job)
    {
        // TODO: Implement update logic and authorization
        abort(501); // Not Implemented
    }

    /**
     * Remove the specified job from storage. (Likely only for admins)
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Job $job)
    {
        // TODO: Implement destroy logic and authorization (Admins only?)
        abort(501); // Not Implemented
    }

    // --- Comment Methods ---

    /**
     * Store a new comment for a job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeComment(Request $request, Job $job)
    {
        if (!Auth::user()->isAdmin() && $job->creator_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate(['content' => ['required', 'string']]);

        $job->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Update last activity timestamp
        $job->touch('last_activity_at');

        return redirect()->route('jobs.show', $job)->with('success', 'Comment added.');
    }

     /**
     * Update an existing job comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @param  \App\Models\JobComment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateComment(Request $request, Job $job, JobComment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate(['content' => ['required', 'string']]);

        // Store the previous content
        JobCommentEdit::create([
            'job_comment_id' => $comment->id,
            'previous_content' => $comment->content,
        ]);

        // Update the comment
        $comment->update(['content' => $request->content]);

        // Update last activity timestamp
        $job->touch('last_activity_at');

        return redirect()->route('jobs.show', $job)->with('success', 'Comment updated.');
    }

    /**
     * Delete a job comment.
     *
     * @param  \App\Models\Job  $job
     * @param  \App\Models\JobComment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyComment(Job $job, JobComment $comment)
    {
         if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the comment
        $comment->delete();

        // Update last activity timestamp
        $job->touch('last_activity_at');

        return redirect()->route('jobs.show', $job)->with('success', 'Comment deleted.');
    }

    // --- Admin Methods ---

    /**
     * Assign a handler (admin) to a job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignHandler(Request $request, Job $job)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'handler_id' => ['required', 'exists:users,id'],
        ]);

        $handler = User::findOrFail($request->handler_id);
        if (!$handler->isAdmin()) {
            abort(403, 'The selected handler is not an administrator.');
        }

        $job->update([
            'handler_id' => $handler->id,
            'last_activity_at' => now(),
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Handler assigned.');
    }

    /**
     * Update the status of a job.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Job $job)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => ['required', Rule::in([
                Job::STATUS_OPEN, Job::STATUS_CLOSED, Job::STATUS_APPROVED,
                Job::STATUS_REJECTED, Job::STATUS_CANCELED
            ])],
        ]);

        $job->update([
            'status' => $request->status,
            'last_activity_at' => now(),
        ]);

        // Add a comment indicating status change
        $job->comments()->create([
            'user_id' => Auth::id(),
            'content' => 'Status changed to ' . $request->status,
        ]);

        return redirect()->route('jobs.show', $job)->with('success', 'Job status updated.');
    }
}
