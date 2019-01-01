@extends('layout')

@section('content')
    <div class="container">
        <h1 align="center">Edit Post</h1>
        <div class="row">
            <div class="col-md-5">
                <span style="color: red;">
                    @foreach($errors->all() as $error)
                        {{$error}}<br>
                    @endforeach
                </span>
                <form action="/updatepost" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$post->id}}">
                    <input type="hidden" name="user_id" value="{{$post->user_id}}">
                    <div class="form-group">
                        <label>Post title</label>
                        <input type="text" name="title" class="form-control" value="{{$post->title}}">
                    </div>
                    <img src="/{{$post->image}}" style="height: 150px; width: auto;">
                    <div class="form-group">
                        <label>Select post image file</label>
                        <input type="file" name="image" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label>Post text</label>
                        <textarea type="text" name="text" class="form-control">{{$post->text}}</textarea>
                    </div>
                    <div class="form-group">
                        <h4>Tags</h4>
                        @foreach($tags as $tag)
                            <label>
                                <input type="checkbox" name="tags[{{$tag->id}}]"
                                @foreach($post->tags as $p_tag)
                                    @if ($p_tag->id == $tag->id)
                                        checked
                                    @endif
                                @endforeach
                                > {{$tag->tag}}
                            </label><br>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-success my_sub_btn">Update</button>
                </form>
        </div>
    </div>
@endsection