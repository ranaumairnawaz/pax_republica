<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Scene;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scene  $scene
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Scene $scene)
    {
        $request->validate([
            'character_id' => ['required', 'exists:characters,id'],
            'content' => ['required', 'string'],
        ]);
        
        // Verify the character belongs to the user
        $character = Character::findOrFail($request->character_id);
        $this->authorize('use', $character);
        
        // Verify the character is participating in the scene
        if (!$scene->participants()->where('character_id', $character->id)->exists()) {
            return back()->with('error', 'Your character needs to join the scene before posting.');
        }
        
        // Check if the scene is active
        if (!$scene->isActive()) {
            return back()->with('error', 'You can only post in active scenes.');
        }
        
        // Create the post
        $post = $scene->posts()->create([
            'character_id' => $character->id,
            'content' => $request->content,
        ]);
        
        return back()->with('success', 'Post added successfully.');
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        // Verify the character belongs to the user
        $this->authorize('update', $post);
        
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Post $post)
    {
        // Verify the character belongs to the user
        $this->authorize('update', $post);
        
        $request->validate([
            'content' => ['required', 'string'],
        ]);
        
        // Update the post content (creates an edit record)
        $post->updateContent($request->content);
        
        return redirect()->route('scenes.show', $post->scene)
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified post from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Post $post)
    {
        // Verify the character belongs to the user
        $this->authorize('delete', $post);
        
        $scene = $post->scene;
        $post->delete();
        
        return redirect()->route('scenes.show', $scene)
            ->with('success', 'Post deleted successfully.');
    }
}
