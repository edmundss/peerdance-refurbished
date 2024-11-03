<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlternateName extends Model
{
    use Userstamps;
    use SoftDeletes;

    protected $fillable = ['name', 'parent_class', 'parent_id'];
}
