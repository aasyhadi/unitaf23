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
use Illuminate\Contracts\Session\Session as SessionSession;

class ManagerController extends Controller
{
    //
    public function index_purchase()
    {
        //
        Session::forget('success');
        Session::forget('mode');
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
        if (isset($_GET['id_unit']) && $_GET['id_unit']!=""){
			$id_unit = $_GET['id_unit'];
            $nm_unit = DB::table("unit")->where('id_unit',$id_unit)->pluck('nama_unit')[0];
            Session::flash('success', 'Data pembelian unit '.$nm_unit);
            Session::flash('mode', 'success');
		}else{
			$id_unit = 0;
        }
        
		$startDateQuery = date("Y-m-d", strtotime($startDate));
        $endDateQuery = date("Y-m-d", strtotime($endDate));
        if ($status == '0'){
            if ($mode == "all"){
                $data = PurchaseH::select('purchase_h.*','supplier.nama')->leftJoin('supplier', 'purchase_h.id_sup', '=', 'supplier.id')->where('purchase_h.active', '!=', 0)->where('purchase_h.id_unit','=',$id_unit);
            } else 
            if ($mode == "limited"){
                $data = PurchaseH::select('purchase_h.*','supplier.nama')->leftJoin('supplier', 'purchase_h.id_sup', '=', 'supplier.id')->where('purchase_h.active', '!=', 0)->where('purchase_h.id_unit','=',$id_unit)->whereBetween('tanggal', [$startDateQuery, $endDateQuery]);
            }
        } else 
        {
            if ($mode == "all"){
                $data = PurchaseH::select('purchase_h.*','supplier.nama')->leftJoin('supplier', 'purchase_h.id_sup', '=', 'supplier.id')->where('purchase_h.active', '!=', 0)->where('purchase_h.id_unit','=',$id_unit)->where('purchase_h.status', '=', $status);
            } else 
            if ($mode == "limited"){
                $data = PurchaseH::select('purchase_h.*','supplier.nama')->leftJoin('supplier', 'purchase_h.id_sup', '=', 'supplier.id')->where('purchase_h.active', '!=', 0)->where('purchase_h.status', '=', $status)->where('purchase_h.id_unit','=',$id_unit)->whereBetween('tanggal', [$startDateQuery, $endDateQuery]);
            }
        }
        $data = $data->get();

        view()->share('id_unit',$id_unit);
        view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);
		view()->share('status',$status);
        view()->share('mode',$mode);
		return view ('backend.laporan-unit.purchase',['data'=>$data]);
    }

    public function index_penjualan()
    {
        //
        Session::forget('success');
        Session::forget('mode');
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
        if (isset($_GET['id_unit']) && $_GET['id_unit']!=""){
			$id_unit = $_GET['id_unit'];
            $nm_unit = DB::table("unit")->where('id_unit','=',$_GET['id_unit'])->pluck('nama_unit')[0];
            Session::flash('success', 'Data penjualan unit '.$nm_unit);
            Session::flash('mode', 'success');
		}else{
			$id_unit = 0;
        }

		$startDateQuery = date("Y-m-d", strtotime($startDate));
        $endDateQuery = date("Y-m-d", strtotime($endDate));
        if ($mode == "all"){
            $data = PenjualanH::where('penjualan_h.active', '!=', 0)->where('penjualan_h.id_unit','=',$id_unit)->orderBy('penjualan_h.tanggal', 'ASC');
        } else 
        if ($mode == "limited"){
            $data = PenjualanH::where('penjualan_h.active', '!=', 0)->where('penjualan_h.id_unit','=',$id_unit)->whereBetween('tanggal', [$startDateQuery, $endDateQuery])->orderBy('penjualan_h.tanggal', 'ASC');
        }
        
        $data = $data->get();

		view()->share('id_unit',$id_unit);
		view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);
        view()->share('mode',$mode);
		return view ('backend.laporan-unit.penjualan',['data'=>$data]);
    }

    public function index_kategori(){
        //
        Session::forget('success');
        Session::forget('mode');
        if (isset($_GET['id_unit']) && $_GET['id_unit']!=""){
            $id_unit = $_GET['id_unit'];
            $nm_unit = DB::table("unit")->where('id_unit','=',$_GET['id_unit'])->pluck('nama_unit')[0];
            Session::flash('success', 'Data penjualan per kategori unit '.$nm_unit);
            Session::flash('mode', 'success');
        }else{
            $id_unit = 0;
        }
        if (isset($_GET['bulan']) && $_GET['bulan']!=""){
            $bulan = $_GET['bulan'];
        }else{
            $bulan = 0;
        } 
        if (isset($_GET['kategori']) && $_GET['kategori']!=""){
            $kategori = $_GET['kategori'];
        }else{
            $kategori = 0;
        }      

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
                    b.id_kategori = $kategori
                    AND h.id_unit = $id_unit 
                    AND h.active != 0 
                    AND substr( p.created_at, 6, 2 ) = $bulan
                GROUP BY b.id,b.kode,b.nama,p.harga");

        view()->share('bulan',$kategori);
        view()->share('bulan',$bulan);
        view()->share('id_unit',$id_unit);
        return view('backend.laporan-unit.rekap_kategori', compact('data'));
    }

    public function index_umkm()
    {
        //
        Session::forget('success');
        Session::forget('mode');
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
        if (isset($_GET['id_unit']) && $_GET['id_unit']!=""){
			$id_unit = $_GET['id_unit'];
            $nm_unit = DB::table("unit")->where('id_unit','=',$_GET['id_unit'])->pluck('nama_unit')[0];
            Session::flash('success', 'Data penjualan umkm unit '.$nm_unit);
            Session::flash('mode', 'success');
		}else{
			$id_unit = 0;
        }
        
		$startDateQuery = date("Y-m-d", strtotime($startDate));
        $endDateQuery = date("Y-m-d", strtotime($endDate));
        if ($mode == "all"){
            $data = KeepH::where('keep_h.active', '!=', 0)->where('keep_h.id_unit','=',$id_unit)->orderBy('keep_h.tanggal', 'ASC');
        } else 
        if ($mode == "limited"){
            $data = KeepH::where('keep_h.active', '!=', 0)->where('keep_h.id_unit','=',$id_unit)->whereBetween('tanggal', [$startDateQuery, $endDateQuery])->orderBy('keep_h.tanggal', 'ASC');
        }
        $data = $data->get();

		view()->share('id_unit',$id_unit);
        view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);
        view()->share('mode',$mode);
		return view ('backend.laporan-unit.umkm',['data'=>$data]);
    }

    public function index_tera()
    {
        //
        Session::forget('success');
        Session::forget('mode');
        if (isset($_GET['bulan']) && $_GET['bulan']!=""){
            $bulan = $_GET['bulan'];
        }else{
            $bulan = 0;
        } 

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
                    b.id_kategori = 5
                    AND h.id_unit = 2 
                    AND h.active != 0 
                    AND substr( p.created_at, 6, 2 ) = $bulan
                GROUP BY b.id,b.kode,b.nama,p.harga");

        view()->share('bulan',$bulan);
        return view('backend.laporan-unit.rekap_tera', compact('data'));

    }

    public function index_ppdb()
    {
        //
        Session::forget('success');
        Session::forget('mode');
        if (isset($_GET['bulan']) && $_GET['bulan']!=""){
            $bulan = $_GET['bulan'];
        }else{
            $bulan = 0;
        } 

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
                    b.id_kategori >= 6
                    AND h.id_unit = 4 
                    AND h.active != 0 
                    AND substr( p.created_at, 6, 2 ) = $bulan
                GROUP BY b.id,b.kode,b.nama,p.harga");

        view()->share('bulan',$bulan);
        return view('backend.laporan-unit.rekap_ppdb', compact('data'));

    }

    public function index_stok()
    {
        //
        Session::forget('success');
        if (isset($_GET['id_unit']) && $_GET['id_unit']!=""){
            $id_unit = $_GET['id_unit'];
            $nm_unit = DB::table("unit")->where('id_unit','=',$_GET['id_unit'])->pluck('nama_unit')[0];
			Session::flash('success', 'Data stok unit '.$nm_unit);
            Session::flash('mode', 'success');
		}else{
			$id_unit = 0;
        }

        $data = Barang::where('active', '!=', 0)->where('id_kategori', '!=', 4)->where('id_unit','=',$id_unit)->orderBy('stok_total', 'DESC')->get(); 
        return view ('backend.laporan-unit.stok',['data'=>$data]);

    }

}
