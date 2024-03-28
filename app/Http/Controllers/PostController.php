<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return view('post.index', compact('posts'));
    }

    public function create()
    {
        $post = new Post();

        $html = html()->model($post);

        return view('post.create', compact('html'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $post = new Post($validated);
        $post->author()->associate(auth()->user());
        $post->save();

        return redirect()->route('posts.index');
    }

    public function show(Post $post)
    {
        $post->load('comments');
        $html = html()->model(new Comment());
        return view('post.show', compact('post', 'html'));
    }

    public function edit(Post $post)
    {
        $html = html()->model($post);

        return view('post.edit', compact('post', 'html'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'string',
            'content' => 'string',
        ]);

        $post->fill($validated);
        $post->save();

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index');
    }
}
