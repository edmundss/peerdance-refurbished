<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Comment extends Model
{
    use SoftDeletes;
    use \App\Traits\RecordsActivity;

    protected $dates = ['deleted_at'];

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function user_name()
	{
		$user = $this->user()->get();

		return $user->firstname;
	}

	public function parent()
	{
		return $this->belongsTo('App\Models\\' . $this->parent_class);
	}

	public static function save_comment($parent_class, $parent_id, $comment)
	{
		$c = new Comment();
		$c->parent_class = $parent_class;
		$c->parent_id = $parent_id;
		$c->comment = $comment;
		$c->user_id = Auth::user()->id;
		$c->save();
	}
}
