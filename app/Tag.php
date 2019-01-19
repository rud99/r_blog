<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $fillable =[
    'tag'
    ];

    public function posts()
    {
        return $this->belongsToMany('App\Post', 'posts_tags');
    }
}
