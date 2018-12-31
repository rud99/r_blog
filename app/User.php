<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $hidden = ['password'];

    public static function register($data)
    {
        $user = new self;
        if ($data['password'] == $data['password_confirmation']) {
            $user->fill($data);
            $user->setPassword($data['password']);
            $user->save();
        } else {
            echo "Пароли не совпадают!!!"; die;
            }
    }

    private function setPassword($password)
    {
        $this->password = bcrypt($password);
    }

    public function posts()
    {
        return $this->hasMany('App\User');
//        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
