<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = [
        'user_id',
        'post_id',
        'type',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'post_id' => 'integer',
        'type' => 'string',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
