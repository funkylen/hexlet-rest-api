@extends('layouts.app')

@section('content')
    <h1>Posts</h1>

    <a class="btn btn-primary" href="{{ route('posts.create') }}">Create</a>

    <table class="table">

        <thead>

            <tr>

                <th>id</th>
                <th>title</th>
                <th>content</th>
                <th>created_at</th>
                <th>actions</th>

            </tr>

        </thead>

        <tbody>

            @foreach ($posts as $post)
                <tr>

                    <td>{{ $post->id }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post) }}">
                            {{ $post->title }}
                    </td>
                    </a>
                    <td>{{ $post->content }}</td>
                    <td>{{ $post->created_at }}</td>
                    <td>
                        <a href="{{ route('posts.edit', $post) }}">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="{{ route('posts.destroy', $post) }}" data-method="DELETE" data-confirm="Are you sure?">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>
@endsection
