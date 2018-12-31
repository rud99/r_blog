@extends('layout')

@section('content')
    <div class="container">
        <h1 align="center">Tags</h1>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tag</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tags as $tag)
                    <tr>
                        <th scope="row">{{$tag->id}}</th>
                        <td>{{$tag->tag}}</td>
                        <td>
                            <a href="/edittag/{{$tag->id}}" class="btn-sm btn-warning">Edit</a>
                            <a href="/deletetag/{{$tag->id}}" onclick="return confirm('Удаляем?');" class="btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                {{$tags->links()}}
                <h3>Add a new tag</h3>
                <span style="color: red;">
                    @foreach($errors->all() as $error)
                        {{$error}}<br>
                    @endforeach
                </span>
                <form action="/storetag" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Tag</label>
                        <input type="text" name="tag" class="form-control" value="{{old('tag')}}">
                    </div>
                    <button type="submit" class="btn btn-success my_sub_btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection