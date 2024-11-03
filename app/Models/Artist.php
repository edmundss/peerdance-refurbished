<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'spotify_id'];

    public function Songs()
    {
    	return $this->hasMany('App\Models\Music\Song');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id')->where('parent_class', 'Artist')->orderBy('created_at', 'DESC');
    }
}
