<?php

namespace App\Http\Controllers\Backend;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Model\Barang;
use App\Model\PurchaseD;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Datatables;

class BarangPpdbController extends Controller
{
    public function index()
    {
       return view ('backend.barang-ppdb.index');
    }

    public function create()
    {
    	return view ('backend.barang-ppdb.update');
    }

    public function store(Request $request)
    {
        $data = new Barang();
        $data->kode = $request->kode;
		$data->nama = $request->nama;
        $data->img_id = $request->img_id;
        $data->harga_jual = $request->harga_jual / 1;
        $data->harga_beli = $request->harga_beli / 1;
        $data->stok_awal = $request->stok_awal / 1;
        $data->stok_total = $request->stok_awal / 1;
        $data->keterangan = $request->keterangan;
		$data->active = $request->active;
        $data->id_kategori = $request->id_kategori;
		$data->user_modified = Session::get('userinfo')['user_id'];
        $data->id_unit = Session::get('userinfo')['id_unit'];
		if($data->save()){
			return Redirect::to('/backend/barang-ppdb')->with('success', "Data saved successfully")->with('mode', 'success');
		}
    }

    public function show($id)
    {
        $data = Barang::with(['user_modify'])->where('id', $id)->get();
		if ($data->count() > 0){
			return view ('backend.barang-ppdb.view', ['data' => $data]);
		}
    }

    public function edit($id)
    {
		$data = Barang::where('id', $id)->where('active', '!=', 0)->get();
		if ($data->count() > 0){
			return view ('backend.barang-ppdb.update', ['data' => $data]);
		}
    }

    public function update(Request $request, $id)
    {
        //
        $data = Barang::find($id);
        $data->kode = $request->kode;
		$data->nama = $request->nama;
        $data->img_id = $request->img_id;
        $data->harga_jual = $request->harga_jual / 1;
        $data->harga_beli = $request->harga_beli / 1;
        $data->stok_total = ($data->stok_total / 1) + (($request->stok_awal / 1) - ($data->stok_awal / 1));
        $data->stok_awal = $request->stok_awal / 1;
        $data->keterangan = $request->keterangan;
		$data->active = $request->active;
        $data->id_kategori = $request->id_kategori;
		$data->user_modified = Session::get('userinfo')['user_id'];
        $data->id_unit = Session::get('userinfo')['id_unit'];
		if($data->save()){
			return Redirect::to('/backend/barang-ppdb')->with('success', "Data saved successfully")->with('mode', 'success');
		}
    }

    public function destroy(Request $request, $id)
    {
		$data = Barang::find($id);
		$data->active = 0;
		$data->user_modified = Session::get('userinfo')['user_id'];
		if($data->save()){
			Session::flash('success', 'Data deleted successfully');
			Session::flash('mode', 'success');
			return new JsonResponse(["status"=>true]);
		}else{
			return new JsonResponse(["status"=>false]);
		}
    }
	
	public function datatable() {	
		$userinfo = Session::get('userinfo');
		//$data = Barang::where('active', '!=', 0);
        $query= "SELECT
                b.id,
                b.kode,
                b.nama,
                b.harga_beli,
                b.harga_jual,
                b.jender_seragam,
                t.id_tingkat,
                t.nama_tingkatan,
                b.size,
                b.active,
                b.id_unit
            FROM
                barang AS b
                LEFT JOIN tingkatan AS t ON t.id_tingkat = b.id_tingkat 
            WHERE
                b.active != 0 
            AND 
                b.id_unit = $userinfo[id_unit]";

        $data = DB::table(DB::raw("($query) as x"))
                    ->select(['id'
                    , 'kode'
                    , 'nama'
                    , 'harga_beli'
                    , 'harga_jual'
                    , 'jender_seragam'
                    , 'size'
                    , 'id_tingkat'
                    , 'nama_tingkatan'
                    , 'active'
                    , 'id_unit']);
	
        return Datatables::of($data)
			->addColumn('action', function ($data) {
				$userinfo = Session::get('userinfo');
				$access_control = Session::get('access_control');
				$segment =  \Request::segment(2);
				$url_edit = url('backend/barang-ppdb/'.$data->id.'/edit');
                $url = url('backend/barang-ppdb/'.$data->id);
                $url_harga = url('backend/barang-ppdb/harga/'.$data->id);
				$view = "<a class='btn-action btn btn-primary btn-view' href='".$url."' title='View'><i class='fa fa-eye'></i></a>";
				$edit = "<a class='btn-action btn btn-info btn-edit' href='".$url_edit."' title='Edit'><i class='fa fa-edit'></i></a>";
               // $delete = "<button data-url='".$url."' onclick='deleteData(this)' class='btn-action btn btn-danger btn-delete' title='Delete'><i class='fa fa-trash-o'></i></button>";
                $harga = "<a class='btn-action btn btn-success btn-view' href='".$url_harga."' title='Histori'>Histori Beli</a>";
				if (!empty($access_control)) {
					if ($access_control[$userinfo['user_level_id']][$segment] == "v"){
						return $view;
					} else if ($access_control[$userinfo['user_level_id']][$segment] == "vu"){
						return $view." ".$edit." ".$harga;
					} else if ($access_control[$userinfo['user_level_id']][$segment] == "a"){
						//return $view." ".$edit." ".$delete." ".$harga;
                        return $view." ".$edit." ".$harga;
					}
				} else {
					return "";
				}
            })
            ->editColumn('harga_beli', function($data) {
                return number_format($data->harga_beli,0,',','.');
            })
            ->editColumn('harga_jual', function($data) {
                return number_format($data->harga_jual,0,',','.');
            })
            ->make(true);		
	}

	public function datatable_barang() {

        $data = Barang::select('barang.*')
            ->where('barang.id_kategori', '>=', 6)
		    ->where('barang.active', '!=', 0)
            ->where('barang.stok_total', '>', 0)
            ->where('barang.id_unit', '=', Session::get('userinfo')['id_unit']);
		
        return Datatables::of($data)
            ->make(true);		
    }        
    
	public function histori($id) {
        //
		$data = PurchaseD::with(['purchase'])->where('id_barang', $id)->orderBy('id', 'DESC')->limit(3)->get();
		return view ('backend.barang-ppdb.histori', ['data' => $data]);
	}
    
}
