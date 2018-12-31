@extends('layout')

@section('content')
    <div class="container">
        <h1 align="center">Comments</h1>
        <div class="row">
            {{--@foreach($posts as $post)--}}
                {{--<div class="col-md-12 gallery-item">--}}
                    {{--<h3>{{$post->title}}</h3>--}}
                    {{--<div class="post-req">--}}
                        {{--Date: <b>{{ date('d.m.Y', strtotime($post->updated_at)) }}</b>, Author: <b>{{$post->user->name}}</b>, Views: <b>{{$post->views}}</b>--}}
                    {{--</div>--}}
                    {{--<div class="admin-posts">--}}
                        {{--<a href="/editpost/{{$post->id}}" class="btn btn-warning">Edit</a>--}}
                        {{--<a href="/deletepost/{{$post->id}}" onclick="return confirm('Удаляем?');" class="btn btn-danger">Delete</a>--}}
                    {{--</div>--}}
                    {{--<div class="thumb_im">--}}
                        {{--<img src="/{{$post->image}}" class="rounded float-left thumb-post">--}}
                        {{--{{$post->getPostPreview(600)}}--}}
                    {{--</div>--}}
                    {{--<div class="detail-view-button">--}}
                    {{--<a href="/post/{{$post->id}}" class="btn btn-info">View Detail</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--@endforeach--}}
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Post</th>
                        <th scope="col">Up. Date</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $comment)
                    <tr>
                        <th scope="row">{{$comment->id}}</th>
                        <td>{{$comment->comment}}</td>
                        <td><a href="/post/{{$comment->post->id}}" target="_blank">{{$comment->post->title}}</a></td>
                        <td>{{ date('d.m.Y', strtotime($comment->updated_at)) }}</td>
                        <td>
                            <a href="/editcomment/{{$comment->id}}" class="btn-sm btn-warning">Edit</a>
                            <a href="/deletecomment/{{$comment->id}}" onclick="return confirm('Удаляем?');" class="btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$comments->links()}}
            </div>
        </div>
    </div>
@endsection