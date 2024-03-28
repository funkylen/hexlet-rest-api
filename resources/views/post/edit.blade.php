@extends('layouts.app')

@section('content')
    <h1>Edit post: {{ $post->title }}</h1>

    {{ $html->form('PUT', route('posts.update', $post))->open() }}

    @include('post._fields')

    {{ $html->submit('Save')->class('btn btn-primary')}}

    {{ $html->form()->close() }}

@endsection
