<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $post->comments()->make($validated);
        $comment->author()->associate(auth()->user());
        $comment->save();

        return redirect()->route('posts.show', $post);
    }

    public function destroy(Post $post, Comment $comment)
    {
        $comment->delete();

        return redirect()->route('posts.show', $post);
    }
}
