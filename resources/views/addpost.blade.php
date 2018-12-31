@extends('layout')

@section('content')
    <div class="container">
        <h1 align="center">Add new Post</h1>
        <div class="row">
            <div class="col-md-5">
                <span style="color: red;">
                    @foreach($errors->all() as $error)
                        {{$error}}<br>
                    @endforeach
                </span>
                <form action="/storepost" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Post title</label>
                        <input type="text" name="title" class="form-control" value="{{old('title')}}">
                    </div>
                    <div class="form-group">
                        <label>Select post image file</label>
                        <input type="file" name="image" class="form-control" value="{{old('image')}}">
                    </div>
                    <div class="form-group">
                        <label>Post text</label>
                        <textarea type="text" name="text" class="form-control">{{old('text')}}</textarea>
                    </div>
                    <div class="form-group">
                        <h4>Tags</h4>
                        @foreach($tags as $tag)
                        <label><input type="checkbox" name="tags[{{$tag->id}}]"/> {{$tag->tag}}</label><br>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-success my_sub_btn">Submit</button>
                </form>
        </div>
    </div>
@endsection