@extends('layouts.app')

@section('content')
    <h1>Post: {{ $post->title }}</h1>

    <div class="mb-2">{{ $post->content }}</div>

    <a href="{{ route('posts.index') }}">All posts</a>


    <h2 class="mt-3">Comments</h2>

    @forelse ($post->comments as $comment)
        <div class="card mb-2">
            <div class="card-header d-flex justify-content-between">
                <div>
                    {{ $comment->author->name }}
                    {{ $comment->created_at }}
                </div>

                <div>
                    <a href="{{ route('posts.comments.destroy', [$post, $comment]) }}" data-method="DELETE"
                        data-confirm="Are you sure?">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>
            </div>

            <div class="card-body">
                {{ $comment->content }}
            </div>

        </div>
    @empty
        No comments :(
    @endforelse

    <h3 class="mt-3">New Comment</h3>

    {{ $html->form('POST', route('posts.comments.store', $post))->open() }}
    {{ $html->textarea('content')->class('form-control mb-2') }}
    {{ $html->submit('Create')->class('btn btn-primary') }}
    {{ $html->form()->close() }}
@endsection
