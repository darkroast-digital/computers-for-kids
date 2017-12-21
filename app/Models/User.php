<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'position',
        'phone',
        'avatar',
        'role',
    ];

    public function Posts()
    {
        return $this->hasMany(Post::class);
    }
}
