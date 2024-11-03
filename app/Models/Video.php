<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Video extends Model
{
    use Userstamps;
    protected $fillable = ['parent_id', 'parent_class', 'video_id', 'title', 'type', 'start', 'end'];

    public function components()
    {
    	return $this->belongsToMany('App\Models\Component')->withPivot('start', 'end');
    }
}
