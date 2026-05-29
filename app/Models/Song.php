<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = ['user_id', 'title', 'artist', 'album', 'genre', 'year'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
