<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Super Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">My Super Blog</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item @if(Route::is('home')) active @endif">
                <a class="nav-link" href="/">Home</a>
            </li>
            @auth
                <li class="nav-item @if(Route::is('addpost')) active @endif">
                    <a class="nav-link" href="/addpost">Add post</a>
                </li>
                @if (Auth::user()->is_admin)
                <li class="nav-item @if(Request::is('tags')) active @endif">
                    <a class="nav-link" href="/tags">Manage tags</a>
                </li>
                @endif
                <li class="nav-item @if(Request::is('comments')) active @endif">
                    <a class="nav-link" href="/comments">Manage my comments</a>
                </li>
            @endauth
        </ul>

        <div class="top-right links">
                @auth
                    User: <b>{{Auth::user()->name}}</b>
                    <a href="/logout">Logout</a>
                    @else
                    <a href="/login">Login</a>
                    <a href="/register">Register</a>
                @endauth
            </div>
    </div>
</nav>
@yield('content')
</body>
</html>