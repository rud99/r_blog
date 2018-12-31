<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function registrate(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
        ];
        User::register($data);

        return redirect('/');
    }
}
