<?php

namespace App\Http\Controllers\Backend;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Model\KeepH;
use App\Model\KeepD;
use App\Model\Barang;
use App\Model\Stok;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Datatables;

class PengeluaranController extends Controller
{
    //
    public function index()
    {
        //
        return view('backend.pengeluaran.index');
    }
}
