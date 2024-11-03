<?php

namespace App\Models;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Dance extends Model
{
    use Userstamps;
    use RecordsActivity;

    protected $fillable = ['title', 'description', 'dance_group_id', 'created_by', 'updated_by', 'dance_family_id'];

    public function steps()
    {
        return $this->hasMany('App\Models\Step');
    }

    public function combinations()
    {
        return $this->hasMany('App\Models\Combination');
    }

    public function choreographies()
    {
        return $this->hasMany('App\Models\Choreography');
    }

    public function comments()
    {
    	return $this->hasMany('App\Models\Comment', 'parent_id')->where('parent_class', 'Dance')->orderBy('created_at', 'DESC');
    }

    public function users()
    {
    	return $this->belongsToMany('App\Models\User');
    }

    public function songs()
    {
        return $this->hasMany('App\Models\Song');
    }

    public function family()
    {
        return $this->belongsTo('App\Models\DanceFamily', 'dance_family_id');
    }

    public function forum_category()
    {
        return $this->hasOne('DevDojo\Chatter\Models\Category', 'subject_id')->where('subject_class', 'Dance');
    }

    static function top_dances ($count = 3) {
        return Dance::withCount('users')
            ->orderBy('users_count', 'desc')
            ->take($count)
            ->get();
    }
}
