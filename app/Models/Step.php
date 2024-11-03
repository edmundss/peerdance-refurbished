<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use App\Traits\RecordsActivity;

class Step extends Model
{
    use Userstamps;
    use RecordsActivity;

    protected $fillable = ['dance_id', 'title', 'description', 'difficulty', 'created_by', 'updated_by'];

    public function dance()
    {
    	return $this->belongsTo('App\Models\Dance');
    }

    public function users()
    {
    	return $this->belongsToMany('App\Models\User')->withPivot('skill', 'relation_type');;
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id')->where('parent_class', 'Step')->orderBy('created_at', 'DESC');
    }

    public function alternate_names()
    {
        return $this->hasMany('App\Models\AlternateName', 'parent_id')->where('parent_class', 'Step')->orderBy('name');
    }

    public function videos()
    {
        return $this->hasMany('App\Models\Video', 'parent_id')->where('parent_class', 'Step');
    }
}
