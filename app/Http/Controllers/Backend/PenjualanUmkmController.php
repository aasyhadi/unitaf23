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

class PenjualanUmkmController extends Controller
{
    //
    public function index()
    {
        //
    	$status = 0;
    	$startDate = "01"."-".date('m-Y');
        $endDate = date('d-m-Y');
        $mode = "all";
		if (isset($_GET["startDate"]) || isset($_GET["endDate"]) || isset($_GET["status"]) || isset($_GET["mode"])){
			if ((isset($_GET['startDate'])) && ($_GET['startDate'] != "")){
				$startDate = $_GET["startDate"];
			}
			if ((isset($_GET['endDate'])) && ($_GET['endDate'] != "")){
				$endDate = $_GET["endDate"];
			}
			if ((isset($_GET['status'])) && ($_GET['status'] != "")){
				$status = $_GET["status"];
            }
			if (!isset($_GET["mode"])){
				$mode = "limited";
			}
        }

		view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);
		view()->share('status',$status);
        view()->share('mode',$mode);
		return view ('backend.penjualan-umkm.index');
    }

    public function create()
    {
        //
		return view ('backend.penjualan-umkm.update');
    }

    public function store(Request $request)
    {
        //
        $data = new KeepH();
        $data->tanggal = date('Y-m-d', strtotime($request->tanggal));
		$data->no_inv = $request->no_inv;
        $data->keterangan = $request->keterangan;
        $data->status = "keep";
		$data->active = 1;
        $data->id_unit = $request->id_unit;
        $data->user_modified = Session::get('userinfo')['user_id'];
        $total = 0;
        $bagi_hasil = 0;
		if($data->save()){
            $id_keep = $data->id;
            if (isset($_POST['id_bahan_baku'])){
                foreach ($_POST['id_bahan_baku'] as $key => $id_bahan_baku):
                    $detail = new KeepD();
                    $detail->id_keep = $data->id;
                    $detail->id_barang = $id_bahan_baku;
                    $detail->id_supplier = $_POST['id_supplier'][$key]; 
                    $detail->jumlah = $_POST['jumlah'][$key];
                    $detail->harga_keep = $_POST['harga_keep'][$key];
                    $detail->harga_jual = $_POST['harga_jual'][$key];
                    $detail->save();
                endforeach;
            }
            $data = KeepH::find($id_keep);
            $data->total = $total;
            $data->bagi_hasil = $bagi_hasil;
            $data->save();
			return Redirect::to('/backend/penjualan-umkm/')->with('success', "Data saved successfully")->with('mode', 'success');
		}

    }

    public function show($id)
    {
        //
		$data = KeepH::with(['user_modify'])->where('id', $id)->get();
		if ($data->count() > 0){
            $detail = KeepD::with('barang')->with('supplier')->where('id_keep','=',$data[0]->id)->orderBy('id', 'ASC')->get();
			return view ('backend.penjualan-umkm.view', ['data' => $data , 'detail' => $detail]);
		}
    }

    public function edit($id)
    {
        //
        $data = KeepH::with(['user_modify'])->where('id', $id)->where('active', '!=', 0)->get();
		if ($data->count() > 0){
            $detail = KeepD::with('barang')->where('id_keep','=',$data[0]->id)->orderBy('id', 'ASC')->get();
            return view ('backend.penjualan-umkm.update', ['data' => $data, 'detail' => $detail]);
		} 

      
    }

    public function update(Request $request, $id)
    {
        //
        $data = KeepH::find($id);
        $data->tanggal = date('Y-m-d', strtotime($request->tanggal));
		$data->no_inv = $request->no_inv;
        $data->keterangan = $request->keterangan;
        $data->user_modified = Session::get('userinfo')['user_id'];
        $total = 0;
        $bagi_hasil = 0;
		if($data->save()){
            $delete = KeepD::where('id_keep', '=', $id)->delete();
            if (isset($_POST['id_bahan_baku'])){
                foreach ($_POST['id_bahan_baku'] as $key => $id_bahan_baku):
                    $detail = new KeepD();
                    $detail->id_keep = $id;
                    $detail->id_barang = $id_bahan_baku;
                    $detail->id_supplier = $_POST['id_supplier'][$key]; 
                    $detail->harga_keep = $_POST['harga_keep'][$key];
                    $detail->harga_jual = $_POST['harga_jual'][$key]; 
                    $detail->jumlah = $_POST['jumlah'][$key];
                    $detail->save();
                endforeach;
            }
            $data = KeepH::find($id);
            $data->total = $total;
            $data->bagi_hasil = $bagi_hasil;
            $data->save();
			return Redirect::to('/backend/penjualan-umkm/')->with('success', "Data saved successfully")->with('mode', 'success');
		}
    }

    public function pulled(Request $request, $id)
    {
        //
        $data = KeepH::find($id);
        $data->user_modified = Session::get('userinfo')['user_id'];
        $total = 0;
        $bagi_hasil = 0;
		if($data->save()){
            $delete = KeepD::where('id_keep', '=', $id)->delete();
            if (isset($_POST['id_barang'])){
                foreach ($_POST['id_barang'] as $key => $id_barang):
                    $detail = new KeepD();
                    $detail->id_keep = $id;
                    $detail->id_barang = $id_barang;
                    $detail->id_supplier = $_POST['id_supplier'][$key]; 
                    $detail->harga_keep = $_POST['harga_keep'][$key];
                    $detail->harga_jual = $_POST['harga_jual'][$key]; 
                    $detail->jumlah = $_POST['jumlah'][$key];
                    $detail->sisa= $_POST['sisa'][$key];
                    $total = $total + (($detail->jumlah - $detail->sisa) * $detail->harga_keep);
                    $bagi_hasil = $bagi_hasil + (($detail->jumlah - $detail->sisa) * ($detail->harga_jual - $detail->harga_keep));
                    $detail->save();
                endforeach;
            }
            $data = KeepH::find($id);
            $data->total = $total;
            $data->bagi_hasil = $bagi_hasil;
            $data->status = 'pull';
            $data->save();
			return Redirect::to('/backend/penjualan-umkm/')->with('success', "Data saved successfully")->with('mode', 'success');
        }
    }

    public function destroy(Request $request, $id)
    {
        //
		$data = KeepH::find($id);
		$userinfo = Session::get('userinfo');

		$data->active = 0;
		$data->user_modified = Session::get('userinfo')['user_id'];
		if($data->save()){
			Session::flash('success', 'Data deleted successfully');
			Session::flash('mode', 'success');
			return new JsonResponse(["status"=>true]);
		}else{
			return new JsonResponse(["status"=>false]);
		}

		return new JsonResponse(["status"=>false]);		
    }
    
	public function datatable() {
		if (isset($_GET['status']) && $_GET['status']!=""){
			$status = $_GET['status'];
		}else{
			$status = 0;
		}
		$startDate = "01"."-".date('m-Y');
		$endDate = date('d-m-Y');
		$mode = "all";
		if (isset($_GET["startDate"]) || isset($_GET["endDate"]) || isset($_GET["mode"])){
			if ((isset($_GET['startDate'])) && ($_GET['startDate'] != "")){
				$startDate = $_GET["startDate"];
			}
			if ((isset($_GET['endDate'])) && ($_GET['endDate'] != "")){
				$endDate = $_GET["endDate"];
			}
			if (isset($_GET["mode"])){
				$mode = $_GET["mode"];
			}
        }
		$startDateQuery = date("Y-m-d", strtotime($startDate));
        $endDateQuery = date("Y-m-d", strtotime($endDate));
        if ($status == '0'){
            if ($mode == "all"){
                $data = KeepH::select('keep_h.*')->where('keep_h.active', '!=', 0)->where('keep_h.id_unit', '=', Session::get('userinfo')['id_unit']);
            } else 
            if ($mode == "limited"){
                $data = KeepH::select('keep_h.*')->where('keep_h.active', '!=', 0)->whereBetween('tanggal', [$startDateQuery, $endDateQuery])->where('keep_h.id_unit', '=', Session::get('userinfo')['id_unit']);
            }
        } else 
        {
            if ($mode == "all"){
                $data = KeepH::select('keep_h.*')->where('keep_h.active', '!=', 0)->where('keep_h.status', '=', $status)->where('keep_h.id_unit', '=', Session::get('userinfo')['id_unit']);
            } else 
            if ($mode == "limited"){
                $data = KeepH::select('keep_h.*')->where('keep_h.active', '!=', 0)->where('keep_h.status', '=', $status)->whereBetween('tanggal', [$startDateQuery, $endDateQuery])->where('keep_h.id_unit', '=', Session::get('userinfo')['id_unit']);
            }
        }
        return Datatables::of($data)
			->addColumn('action', function ($data) {
				$userinfo = Session::get('userinfo');
				$access_control = Session::get('access_control');
				$segment =  \Request::segment(2);
				
				$url_edit = url('backend/penjualan-umkm/'.$data->id.'/edit');
                $url = url('backend/penjualan-umkm/'.$data->id);
                //$url_terima = url('backend/penjualan-umkm/terima/'.$data->id);
                $url_pull = url('backend/penjualan-umkm/keep/popup-media/'.$data->id);
                $view = "<a class='btn-action btn btn-primary' href='".$url."' title='View'><i class='fa fa-eye'></i></a>";
                $edit = "";
                $terima = "";
                $delete = "";
                if ($data->status == "keep"){
                    $edit = "<a class='btn-action btn btn-info btn-edit' href='".$url_edit."' title='Edit'><i class='fa fa-edit'></i></a>";
                    $terima = "<a class='btn-action btn btn-warning btn-keep' href='".$url_pull."' title='Pull Barang'>Pull</a>";
                    $delete = "<button data-url='".$url."' onclick='deleteData(this)' class='btn-action btn btn-danger btn-delete' title='Delete'><i class='fa fa-trash-o'></i></button>";
                }
				if (!empty($access_control)) {
					if ($access_control[$userinfo['user_level_id']][$segment] == "v"){
						return $view;
					} else if ($access_control[$userinfo['user_level_id']][$segment] == "vu"){
						return $view." ".$edit." ".$terima;
					} else if ($access_control[$userinfo['user_level_id']][$segment] == "a"){
						return $view." ".$edit." ".$delete." ".$terima;
					}
				} else {
					return "";
				}
            })		
            ->editColumn('tanggal', function ($data) {
                return date('d-m-Y', strtotime($data->tanggal));
            })
            ->editColumn('total', function ($data) {
                return number_format($data->total,0,',','.');
            })
            ->editColumn('bagi_hasil', function ($data) {
                return number_format($data->bagi_hasil,0,',','.');
            })
            ->make(true);		
	}

	public function popup_media_barang($id_count = null) {
		return view ('backend.penjualan-umkm.view_barang')->with('id_count', $id_count);
	}

	public function popup_media_supplier($id_count = null) {
		return view ('backend.penjualan-umkm.view_supplier')->with('id_count', $id_count);
	}

    public function popup_media_keep($id) {
		//
        $data = KeepH::with(['user_modify'])->where('id', $id)->get();
		if ($data->count() > 0){
            $detail = KeepD::with('barang')->where('id_keep','=',$data[0]->id)->orderBy('id', 'ASC')->get();
			return view ('backend.penjualan-umkm.view_keep', ['data' => $data , 'detail' => $detail]);
		}
	}
}
