<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    public $fillable =[
      'title',
      'image',
      'text',
      'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'posts_tags')->withTimestamps();
    }

    public function getPostPreview($maxLength = 300)
    {
        if (strlen($this->text) > $maxLength) return str_limit($this->text, $maxLength);
            else return $this->text;
    }
}
