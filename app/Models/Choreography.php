<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Choreography extends Model
{
    use Userstamps;

    protected $fillable = ['title', 'dance_id', 'description', 'difficulty', 'author', 'created_by', 'updated_by'];

    public function dance()
    {
    	return $this->belongsTo('App\Models\Dance');
    }

    public function components()
    {
    	return $this->hasMany('App\Models\Component', 'parent_id')->where('parent_class', 'Choreography');
    }

    public function videos()
    {
    	return $this->hasMany('App\Models\Video', 'parent_id')->where('parent_class', 'Choreography');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id')->where('parent_class', 'Choreography')->orderBy('created_at', 'DESC');
    }
}