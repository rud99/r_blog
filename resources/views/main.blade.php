@extends('layout')

@section('content')
    <div class="container">
        <h1 align="center">Posts</h1>
        <div class="row">
            @foreach($posts as $post)
                <div class="col-md-12 gallery-item">
                    <h3>{{$post->title}}</h3>
                    <div class="post-req">
                        Date: <b>{{ date('d.m.Y', strtotime($post->updated_at)) }}</b>, Author: <b>{{$post->user->name}}</b>, Views: <b>{{$post->views}}</b>
                    </div>
                    @if (Auth::user() && Auth::user()->id == $post->user_id)
                    <div class="admin-posts">
                        <a href="/editpost/{{$post->id}}" class="btn btn-warning">Edit</a>
                        <a href="/deletepost/{{$post->id}}" onclick="return confirm('Удаляем?');" class="btn btn-danger">Delete</a>
                    </div>
                    @endif
                    <div class="thumb_im">
                        <img src="/{{$post->image}}" class="rounded float-left thumb-post">
                        {{$post->getPostPreview(600)}}
                    </div>
                    <div class="detail-view-button">
                    <a href="/post/{{$post->id}}" class="btn btn-info">View Detail</a>
                    </div>
                </div>
            @endforeach
            {{$posts->links()}}
        </div>
    </div>
@endsection