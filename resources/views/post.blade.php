@extends('layout')

@section('content')
    <div class="container">
        <h1 align="center">{{$post->title}}</h1>
        <div class="row">
                <div class="col-md-12 gallery-item">
                    <div class="post-req">
                        {{--{{var_dump($post->user)}}--}}
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
                        {{$post->text}}
                    </div>
                    <div>
                        Tags:
                        @foreach($post->tags as $tag)
                            <b>{{$tag->tag}}</b>
                        @endforeach
                    </div>
                    <div class="detail-view-button">
                    <a href="/" class="btn btn-info">Go Back <</a>
                    </div>
                </div>
            <div>
                <div>
                    <h4>Comments:</h4><br>
                </div>
                @forelse($post->comments->sortByDesc('updated_at') as $comment)
                    User: <b>{{$comment->user->name}}({{$comment->user->email}})</b>
                    Date: <b>{{date('d.m.Y', strtotime($comment->updated_at))}}</b><br>
                    Comment: {{$comment->comment}}<hr>

                @empty
                    <p>No Comments!
                @endforelse

            </div>
            <div class="col-md-12" style="margin-top: 20px;">
                @auth
                <h4>Write a comment</h4>
                <span style="color: red;">
                    @foreach($errors->all() as $error)
                        {{$error}}<br>
                    @endforeach
                </span>
                <form action="/storecomment" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="post_id" value="{{$post->id}}">
                    <div class="form-group">
                        <label>You are logged as: <b>{{Auth::user()->name}}</b></label>
                    </div>
                    <div class="form-group">
                        <label>Comment</label>
                        <textarea type="text" name="comment" class="form-control">{{old('comment')}}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success my_sub_btn">Submit</button>
                </form>
                @else
                        <p>Please, login to make comment <a href="/login">LOGIN</a>
                @endauth
            </div>
        </div>
    </div>
@endsection