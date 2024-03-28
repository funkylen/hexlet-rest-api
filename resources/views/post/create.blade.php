@extends('layouts.app')

@section('content')
    <h1>Create post</h1>

    {{ $html->form('POST', route('posts.store'))->open() }}

    @include('post._fields')

    {{ $html->submit('Create')->class('btn btn-primary')}}

    {{ $html->form()->close() }}

@endsection
