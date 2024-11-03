<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyChallenge extends Model
{
    protected $fillable = ['gold', 'silver', 'bronze', 'name', 'description', 'parent_type', 'parent_id', 'end', 'status'];
}
