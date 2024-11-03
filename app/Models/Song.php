<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Song extends Model
{
	use Userstamps;
    use \App\Traits\RecordsActivity;

    protected $fillable = ['name', 'spotify_id', 'duration_ms', 'tempo', 'dance_id'];

    public function artist()
    {
    	return $this->belongsTo('App\Models\Artist');
    }

    public function dance()
    {
    	return $this->belongsTo('App\Models\Dance');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id')->where('parent_class', 'Song')->orderBy('created_at', 'DESC');
    }
}
