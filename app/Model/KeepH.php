<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KeepH extends Model
{
    //
    protected $table = 'keep_h';
	protected $hidden = ['created_at', 'updated_at'];
	
	public function user_modify()
	{
		return $this->belongsTo('App\Model\User', 'user_modified');
	}

	
}
