<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;

use File;

class Picture extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
	protected $fillable = ['parent_class', 'parent_id'];

	public static function store($parent, $picture)
	{
		$picture_model = Picture::create([
			'parent_class' => class_basename($parent),
			'parent_id' => $parent->id
		]);

		$img = Image::make($picture);

		$path = 'image/'. $picture_model->parent_class .'/' . $picture_model->id . '/';
		File::makeDirectory($path, 0775, true, true);

		$img->save($path . 'original.jpg', 80);

		foreach ($parent->picture_dimensions() as $pd) {
			if($pd['action'] == 'fit') {
				$pic = $img->fit($pd['w'], $pd['h']);
				$pic->save($path . $pd['title'] . '.jpg', 80);
			} elseif($pd['action'] == 'widen') {
				$pic = $img->widen($pd['w']);
				$pic->save($path . $pd['title'] . '.jpg', 80);
			}
		}
	}
}