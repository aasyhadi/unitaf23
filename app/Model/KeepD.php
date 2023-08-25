<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class KeepD extends Model
{
    //
    protected $table = 'keep_d';
	protected $hidden = ['created_at', 'updated_at'];
	
	public function keep()
	{
		return $this->belongsTo('App\Model\KeepH', 'id_keep');
	}

	public function barang()
	{
		return $this->belongsTo('App\Model\Barang', 'id_barang');
	}

    public function supplier()
	{
		return $this->belongsTo('App\Model\Supplier', 'id_supplier');
	}
}


