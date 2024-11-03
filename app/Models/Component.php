<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Component extends Model
{
    use Userstamps;
    
    protected $fillable = ['order_number', 'step_id', 'description', 'parent_class', 'parent_id'];

    public function parent ()
    {
    	return $this->belongsTo('App\Models\\' . $this->parent_class, 'parent_id');
    }

    public  function step()
    {
        return $this->belongsTo('App\Models\Step');
    }

    public function videos()
    {
    	return $this->belongsToMany('App\Models\Video')->withPivot('start', 'end');
    }
}
