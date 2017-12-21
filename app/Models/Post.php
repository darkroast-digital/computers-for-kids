<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'slug',
        'title',
        'author',
        'body',
        'user_id',
        'deleted_at',
        'active',
        'page_desc',
        'page_keys',
    ];

    protected $dates = ['deleted_at'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
