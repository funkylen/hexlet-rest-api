<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return view('post.index', compact('posts'));
    }

    public function create()
    {
        Gate::authorize('create', Post::class);

        $post = new Post();

        $html = html()->model($post);

        return view('post.create', compact('html'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Post::class);

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
        Gate::authorize('update', $post);

        $html = html()->model($post);

        return view('post.edit', compact('post', 'html'));
    }

    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

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
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index');
    }

    public function attachImage(Request $request, Post $post)
    {
        $request->validate(['image' => 'required|image']);

        $post->image_filepath = $request->file('image')->store('images');
        $post->save();

        return redirect()->route('posts.show', $post);
    }
}
