@extends('layout')

@section('content')
    <div class="container">
        <h1 align="center">Edit Comment</h1>
        <div class="row">
            <div class="col-md-5">
                <span style="color: red;">
                    @foreach($errors->all() as $error)
                        {{$error}}<br>
                    @endforeach
                </span>
                <form action="/updatecomment" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$comment->id}}">
                    <div class="form-group">
                        <label>Comment</label>
                        <textarea type="text" name="comment" class="form-control">{{$comment->comment}}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success my_sub_btn">Update</button>
                </form>
        </div>
    </div>
@endsection