<?php

namespace App\Http\Controllers\Backend;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Model\PurchaseH;
use App\Model\PenjualanH;
use App\Model\KeepH;
use App\Model\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Datatables;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_purchase()
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
		if (isset($_GET['status']) && $_GET['status']!=""){
			$status = $_GET['status'];
		}else{
			$status = 0;
        }
        
		$startDateQuery = date("Y-m-d", strtotime($startDate));
        $endDateQuery = date("Y-m-d", strtotime($endDate));
        if ($status == '0'){
            if ($mode == "all"){
                $data = PurchaseH::select('purchase_h.*','supplier.nama')->leftJoin('supplier', 'purchase_h.id_sup', '=', 'supplier.id')
                    ->where('purchase_h.active', '!=', 0)
                    ->where('purchase_h.id_unit','=',Session::get('userinfo')['id_unit'])
                    ->orderBy('purchase_h.id', 'desc');
            } else 
            if ($mode == "limited"){
                $data = PurchaseH::select('purchase_h.*','supplier.nama')->leftJoin('supplier', 'purchase_h.id_sup', '=', 'supplier.id')->where('purchase_h.active', '!=', 0)->where('purchase_h.id_unit','=',Session::get('userinfo')['id_unit'])->whereBetween('tanggal', [$startDateQuery, $endDateQuery]);
            }
        } else 
        {
            if ($mode == "all"){
                $data = PurchaseH::select('purchase_h.*','supplier.nama')->leftJoin('supplier', 'purchase_h.id_sup', '=', 'supplier.id')->where('purchase_h.active', '!=', 0)->where('purchase_h.id_unit','=',Session::get('userinfo')['id_unit'])->where('purchase_h.status', '=', $status);
            } else 
            if ($mode == "limited"){
                $data = PurchaseH::select('purchase_h.*','supplier.nama')->leftJoin('supplier', 'purchase_h.id_sup', '=', 'supplier.id')->where('purchase_h.active', '!=', 0)->where('purchase_h.status', '=', $status)->where('purchase_h.id_unit','=',Session::get('userinfo')['id_unit'])->whereBetween('tanggal', [$startDateQuery, $endDateQuery]);
            }
        }
        $data = $data->get();

		view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);
		view()->share('status',$status);
        view()->share('mode',$mode);
		return view ('backend.laporan.purchase',['data'=>$data]);
    }

    public function index_penjualan()
    {
        //
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
			if (!isset($_GET["mode"])){
				$mode = "limited";
			}
        }
		if (isset($_GET['status']) && $_GET['status']!=""){
			$status = $_GET['status'];
		}else{
			$status = 0;
        }
        
		$startDateQuery = date("Y-m-d", strtotime($startDate));
        $endDateQuery = date("Y-m-d", strtotime($endDate));
        if ($mode == "all"){
            $data = PenjualanH::where('penjualan_h.active', '!=', 0)
                ->where('penjualan_h.id_unit','=',Session::get('userinfo')['id_unit'])
                ->orderBy('penjualan_h.id', 'desc');
        } else 
        if ($mode == "limited"){
            $data = PenjualanH::where('penjualan_h.active', '!=', 0)->where('penjualan_h.id_unit','=',Session::get('userinfo')['id_unit'])->whereBetween('tanggal', [$startDateQuery, $endDateQuery]);
        }
        $data = $data->get();

		view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);
        view()->share('mode',$mode);
		return view ('backend.laporan.penjualan',['data'=>$data]);
    }

    public function rekap_penjualan()
    {
        //
        $list_kategori = DB::select("
                        select 
                        id_kategori as value, 
                        nama_kategori as text
                        from kategori_barang");
        return view('backend.laporan.rekap_index', compact('list_kategori'));
    }

    public function rekap_penjualan_view($id_kategori, $bulan, $year){
        
        $bulan_select = $bulan;
        $id_unit = Session::get('userinfo')['id_unit'];
        $data  = DB::select("SELECT
                    b.id,
                    b.kode,
                    b.nama,
                    sum(p.jumlah) as jumlah,
                    p.harga,
                    ( sum(p.jumlah) * p.harga ) AS total 
                FROM
                    penjualan_d AS p
                    LEFT JOIN penjualan_h AS h ON p.id_penjualan = h.id
                    LEFT JOIN barang AS b ON b.id = p.id_barang
                    LEFT JOIN kategori_barang AS k ON k.id_kategori = b.id_kategori 
                WHERE
                    b.id_kategori = $id_kategori
                    AND h.id_unit = $id_unit 
                    AND h.active != 0 
                    AND substr( p.created_at, 6, 2 ) = $bulan_select
                    AND left(p.created_at,4) = $year
                GROUP BY b.id,b.kode,b.nama,p.harga");

        return view('backend.laporan.rekap_view', compact('data'));
    }

    public function index_umkm()
    {
        //
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
			if (!isset($_GET["mode"])){
				$mode = "limited";
			}
        }
		if (isset($_GET['status']) && $_GET['status']!=""){
			$status = $_GET['status'];
		}else{
			$status = 0;
        }
        
		$startDateQuery = date("Y-m-d", strtotime($startDate));
        $endDateQuery = date("Y-m-d", strtotime($endDate));
        if ($mode == "all"){
            $data = KeepH::where('keep_h.active', '!=', 0)
                    ->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit'])
                    ->orderBy('keep_h.id', 'desc');
        } else 
        if ($mode == "limited"){
            $data = KeepH::where('keep_h.active', '!=', 0)->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit'])->whereBetween('tanggal', [$startDateQuery, $endDateQuery]);
        }
        $data = $data->get();

		view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);
        view()->share('mode',$mode);
		return view ('backend.laporan.umkm',['data'=>$data]);
    }

    public function index_stok()
    {
        //
        $data = Barang::where('active', '!=', 0)->where('id_kategori', '!=', 4)->where('id_unit','=',Session::get('userinfo')['id_unit'])->orderBy('stok_total', 'DESC')->get(); 
        return view ('backend.laporan.stok',['data'=>$data]);
    }

    
}