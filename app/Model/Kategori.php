<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    //
    protected $table = 'kategori_barang';
	protected $hidden = ['created_at', 'updated_at'];
}
