<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Combination extends Model
{
    use Userstamps;

    protected $fillable = ['title', 'dance_id', 'description', 'difficulty', 'author', 'created_by', 'updated_by'];

    public function dance()
    {
    	return $this->belongsTo('App\Models\Dance');
    }

    public function components()
    {
    	return $this->hasMany('App\Models\Component', 'parent_id')->where('parent_class', 'Combination');
    }

    public function users()
    {
    	return $this->belongsToMany('App\Models\User')->withPivot('skill', 'relation_type');;
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id')->where('parent_class', 'Combination')->orderBy('created_at', 'DESC');
    }

    public function videos()
    {
        return $this->hasMany('App\Models\Video', 'parent_id')->where('parent_class', 'Combination  ');
    }
}
