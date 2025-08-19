@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>View Post Details</h4>
    </div>
    <div class="card-body">
        <h5 class="card-title">Title:</h5>
        <p class="card-text">{{ $post->title }}</p>

        <h5 class="card-title">Content:</h5>
        <p class="card-text">{{ $post->content }}</p>

        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>
@endsection
