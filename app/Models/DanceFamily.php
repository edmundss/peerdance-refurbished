<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanceFamily extends Model
{
    use SoftDeletes;
    use Userstamps;
    
    protected $fillable = ['name'];

    public function dances()
    {
        return $this->hasMany('App\Models\Dance');
    }
}
