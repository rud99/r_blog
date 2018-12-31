@extends('layout')

@section('content')
    <div class="container">
        <h1 align="center">Edit Tag</h1>
        <div class="row">
            <div class="col-md-5">
                <span style="color: red;">
                    @foreach($errors->all() as $error)
                        {{$error}}<br>
                    @endforeach
                </span>
                <form action="/updatetag" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$tag->id}}">
                    <div class="form-group">
                        <label>Tag</label>
                        <input type="text" name="tag" class="form-control" value="{{$tag->tag}}">
                    </div>
                    <button type="submit" class="btn btn-success my_sub_btn">Update</button>
                </form>
        </div>
    </div>
@endsection