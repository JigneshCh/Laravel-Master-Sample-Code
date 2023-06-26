<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Model as TraitModel;
use Auth;

class BaseModel extends Model
{
	use SoftDeletes;
	use TraitModel;

	protected $appends = ['created_formated', 'created_humans'];

	protected  static function booted()
	{
		parent::booted();

		static::creating(function ($model) {
			$user = Auth::user();

			if (\Schema::hasColumn($model->getTable(), "created_by") && $user) {
				$model->created_by = $user->id;
			}

			if (\Schema::hasColumn($model->getTable(), "updated_by") && $user) {
				$model->updated_by = $user->id;
			}


			if (\Schema::hasColumn($model->getTable(), "slug")) {
				$model->slug = BaseModel::createslug($model);
			}

			if (\Schema::hasColumn($model->getTable(), "status")) {
				$model->status = $model->status == "on" ? 1 : 0;
			}

			if (\Schema::hasColumn($model->getTable(), "param")) {

				$model->param = json_encode($model->param);
			}
		});

		static::updating(function ($model) {
			$user = Auth::user();


			if (\Schema::hasColumn($model->getTable(), "created_by") && $user) {
				$model->created_by = $user->id;
			}

			if (\Schema::hasColumn($model->getTable(), "updated_by") && $user) {
				$model->updated_by = $user->id;
			}


			if (\Schema::hasColumn($model->getTable(), "slug")) {
				$model->slug = BaseModel::createslug($model);
			}
		});

		static::deleted(function ($model) {
		});
	}


	public static function createslug($model)
	{
		$slug_base = uniqid();
		if ($model->slug != "") {
			$slug_base = $model->slug;
		} else if (\Schema::hasColumn($model->getTable(), "name")) {
			$slug_base = $model->name;
		} else if (\Schema::hasColumn($model->getTable(), "title")) {
			$slug_base = $model->title;
		} else if (\Schema::hasColumn($model->getTable(), "designation")) {
			$slug_base = $model->designation;
		}

		$slug_base = str_slug($slug_base);
		$slug = $slug_base;

		$get = true;
		$i = 1;
		while ($get) {

			$isexist = \DB::table($model->getTable())->where('slug', $slug)->where('id', '<>', $model->id)->count();
			if ($isexist > 0) {
				$slug = $slug_base . '-' . $i;
			} else {
				$slug_base = $slug;
				$get = false;
			}

			$i++;
		}

		return $slug_base;
	}

	public function onefile()
    {
        return $this->hasOne('App\Refefile', 'refe_field_id', 'id')
		->where('refe_table_name', $this->getTable())
		->orderby("priority","DESC")
		->orderby("created_at","DESC");
    }
	public function manyfile() 
    {
        return $this->hasMany('App\Refefile', 'refe_field_id', 'id')
		->where('refe_table_name', $this->getTable())
		->orderby("priority","DESC")
		->orderby("created_at","DESC");
		
    }
}
