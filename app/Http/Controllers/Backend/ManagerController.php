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
            $data = PenjualanH::where('penjualan_h.active', '!=', 0)
                ->whereBetween('tanggal', [$startDateQuery, $endDateQuery])
                ->orderBy('penjualan_h.tanggal', 'ASC')
                ->leftJoin('unit', 'unit.id_unit', '=', 'penjualan_h.id_unit');
        } else 
        if ($mode == "limited"){
            $data = PenjualanH::where('penjualan_h.active', '!=', 0)
                ->where('penjualan_h.id_unit','=',$id_unit)
                ->whereBetween('tanggal', [$startDateQuery, $endDateQuery])
                ->orderBy('penjualan_h.tanggal', 'ASC')
                ->leftJoin('unit', 'unit.id_unit', '=', 'penjualan_h.id_unit');
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
        if (isset($_GET['tahun']) && $_GET['tahun'] != "") {
            $year = $_GET['tahun'];
        } else {
            $year = 0;
        }

        if (isset($_GET['id_unit']) && $_GET['id_unit']!="" && $_GET['id_unit']!="0"){
            $id_unit = $_GET['id_unit'];
            $nm_unit = DB::table("unit")->where('id_unit','=',$_GET['id_unit'])->pluck('nama_unit')[0];
            Session::flash('success', 'Data penjualan per kategori unit '.$nm_unit);
            Session::flash('mode', 'success');
            
            $data  = DB::select("SELECT
                        u.nama_unit,
                        h.tanggal,
                        b.kode,
                        b.nama,
                        sum( p.jumlah ) AS jumlah,
                        p.harga,
                        ( sum( p.jumlah ) * p.harga ) AS total 
                    FROM
                        penjualan_d AS p
                        LEFT JOIN penjualan_h AS h ON p.id_penjualan = h.id
                        LEFT JOIN barang AS b ON b.id = p.id_barang
                        LEFT JOIN kategori_barang AS k ON k.id_kategori = b.id_kategori
                        LEFT JOIN unit AS u ON u.id_unit = b.id_unit 
                    WHERE
                        b.id_kategori = $kategori
                        AND h.id_unit = $id_unit
                        AND h.active != 0 
                        AND MONTH(h.tanggal) = $bulan
                        AND YEAR(h.tanggal) = $year
                    GROUP BY
                        u.nama_unit,
                        h.tanggal,
                        b.kode,
                        b.nama,
                        p.harga"); 
        } else {
            $id_unit = 0;
            $data  = DB::select("SELECT
                        u.nama_unit,
                        h.tanggal,
                        b.kode,
                        b.nama,
                        sum( p.jumlah ) AS jumlah,
                        p.harga,
                        ( sum( p.jumlah ) * p.harga ) AS total 
                    FROM
                        penjualan_d AS p
                        LEFT JOIN penjualan_h AS h ON p.id_penjualan = h.id
                        LEFT JOIN barang AS b ON b.id = p.id_barang
                        LEFT JOIN kategori_barang AS k ON k.id_kategori = b.id_kategori
                        LEFT JOIN unit AS u ON u.id_unit = b.id_unit 
                    WHERE
                        b.id_kategori = $kategori
                        AND h.id_unit > $id_unit
                        AND h.active != 0 
                        AND MONTH(h.tanggal) = $bulan
                    AND YEAR(h.tanggal) = $year
                    GROUP BY
                        u.nama_unit,
                        h.tanggal,
                        b.kode,
                        b.nama,
                        p.harga"); 
        } 
        
        view()->share('kategori',$kategori);
        view()->share('bulan',$bulan);
        view()->share('tahun',$year);
        view()->share('id_unit',$id_unit);
        return view('backend.laporan-unit.rekap_kategori', compact('data'));
    
    }

    public function statistik_kategori()
    {
        Session::forget('success');
        Session::forget('mode');
        $semester = request('semester', 1); // Default semester 1 if not provided
        $year = request('tahun', now()->year); // Default to current year if not provided
        $semester = in_array($semester, [1, 2]) ? $semester : 1;
    
        // Determine semester months and flash session messages
        if ($semester == 1) {
            $startMonth = 1;
            $endMonth = 6;
            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
            Session::flash('success', 'Statistik penjualan semester ganjil ' . $year);
        } else {
            $startMonth = 7;
            $endMonth = 12;
            $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            Session::flash('success', 'Statistik penjualan semester genap ' . $year);
        }
    
        Session::flash('mode', 'success');
        $data = DB::select("
            SELECT
                k.id_kategori,
                k.nama_kategori,
                SUM(CASE WHEN MONTH(h.tanggal) = 1 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_januari,
                SUM(CASE WHEN MONTH(h.tanggal) = 2 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_februari,
                SUM(CASE WHEN MONTH(h.tanggal) = 3 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_maret,
                SUM(CASE WHEN MONTH(h.tanggal) = 4 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_april,
                SUM(CASE WHEN MONTH(h.tanggal) = 5 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_mei,
                SUM(CASE WHEN MONTH(h.tanggal) = 6 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_juni,
                SUM(CASE WHEN MONTH(h.tanggal) = 7 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_juli,
                SUM(CASE WHEN MONTH(h.tanggal) = 8 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_agustus,
                SUM(CASE WHEN MONTH(h.tanggal) = 9 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_september,
                SUM(CASE WHEN MONTH(h.tanggal) = 10 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_oktober,
                SUM(CASE WHEN MONTH(h.tanggal) = 11 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_november,
                SUM(CASE WHEN MONTH(h.tanggal) = 12 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_desember,
                SUM(p.jumlah * p.harga) AS total_penjualan
            FROM
                penjualan_d AS p
                LEFT JOIN barang AS b ON b.id = p.id_barang
                LEFT JOIN kategori_barang AS k ON b.id_kategori = k.id_kategori
                LEFT JOIN penjualan_h AS h ON p.id_penjualan = h.id
            WHERE
                h.active = 1
                AND YEAR(h.tanggal) = :year
                AND MONTH(h.tanggal) BETWEEN :startMonth AND :endMonth
            GROUP BY
                k.id_kategori, k.nama_kategori
            UNION ALL
            SELECT
                0 AS id_kategori,
                'Penitipan Kantin' AS nama_kategori,
                SUM(CASE WHEN MONTH(tanggal) = 1 THEN bagi_hasil ELSE 0 END) AS sale_januari,
                SUM(CASE WHEN MONTH(tanggal) = 2 THEN bagi_hasil ELSE 0 END) AS sale_februari,
                SUM(CASE WHEN MONTH(tanggal) = 3 THEN bagi_hasil ELSE 0 END) AS sale_maret,
                SUM(CASE WHEN MONTH(tanggal) = 4 THEN bagi_hasil ELSE 0 END) AS sale_april,
                SUM(CASE WHEN MONTH(tanggal) = 5 THEN bagi_hasil ELSE 0 END) AS sale_mei,
                SUM(CASE WHEN MONTH(tanggal) = 6 THEN bagi_hasil ELSE 0 END) AS sale_juni,
                SUM(CASE WHEN MONTH(tanggal) = 7 THEN bagi_hasil ELSE 0 END) AS sale_juli,
                SUM(CASE WHEN MONTH(tanggal) = 8 THEN bagi_hasil ELSE 0 END) AS sale_agustus,
                SUM(CASE WHEN MONTH(tanggal) = 9 THEN bagi_hasil ELSE 0 END) AS sale_september,
                SUM(CASE WHEN MONTH(tanggal) = 10 THEN bagi_hasil ELSE 0 END) AS sale_oktober,
                SUM(CASE WHEN MONTH(tanggal) = 11 THEN bagi_hasil ELSE 0 END) AS sale_november,
                SUM(CASE WHEN MONTH(tanggal) = 12 THEN bagi_hasil ELSE 0 END) AS sale_desember,
                SUM(bagi_hasil) AS total_penjualan
            FROM
                keep_h 
            WHERE
                active = 1
                AND YEAR(tanggal) = $year
                AND MONTH(tanggal) BETWEEN $startMonth AND $endMonth"
        ,[
            'year' => $year,
            'startMonth' => $startMonth,
            'endMonth' => $endMonth,
        ]);
    
        return view('backend.laporan-unit.statistikkategori', compact('data', 'semester', 'bulan', 'year'));
    }

    public function statistik_unit()
    {
        Session::forget('success');
        Session::forget('mode');
        $semester = request('semester', 1); // Default 1 jika tidak ada
        $year = request('tahun', now()->year); // Default tahun saat ini jika tidak ada
        $semester = in_array($semester, [1, 2]) ? $semester : 1;

        if ($semester == 1) {
            // Semester Ganjil (Januari–Juni)
            $startMonth = 1;
            $endMonth = 6;
            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
            Session::flash('success', 'Statistik penjualan semester ganjil ' . $year);
        } else {
            // Semester Genap (Juli–Desember)
            $startMonth = 7;
            $endMonth = 12;
            $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            Session::flash('success', 'Statistik penjualan semester genap ' . $year);
        }

        Session::flash('mode', 'success');
        $data = DB::select("
            SELECT
                k.id_unit,
                k.nama_unit,
                SUM(CASE WHEN MONTH(h.tanggal) = 1 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_januari,
                SUM(CASE WHEN MONTH(h.tanggal) = 2 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_februari,
                SUM(CASE WHEN MONTH(h.tanggal) = 3 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_maret,
                SUM(CASE WHEN MONTH(h.tanggal) = 4 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_april,
                SUM(CASE WHEN MONTH(h.tanggal) = 5 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_mei,
                SUM(CASE WHEN MONTH(h.tanggal) = 6 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_juni,
                SUM(CASE WHEN MONTH(h.tanggal) = 7 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_juli,
                SUM(CASE WHEN MONTH(h.tanggal) = 8 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_agustus,
                SUM(CASE WHEN MONTH(h.tanggal) = 9 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_september,
                SUM(CASE WHEN MONTH(h.tanggal) = 10 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_oktober,
                SUM(CASE WHEN MONTH(h.tanggal) = 11 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_november,
                SUM(CASE WHEN MONTH(h.tanggal) = 12 THEN (p.jumlah * p.harga) ELSE 0 END) AS sale_desember,
                SUM(p.jumlah * p.harga) AS total_penjualan
            FROM
                penjualan_d AS p
            LEFT JOIN barang AS b ON b.id = p.id_barang
            LEFT JOIN unit AS k ON b.id_unit= k.id_unit
            LEFT JOIN penjualan_h AS h ON p.id_penjualan = h.id
            WHERE
                h.active = 1
                AND YEAR(h.tanggal) = :year
                AND MONTH(h.tanggal) BETWEEN :startMonth AND :endMonth
            GROUP BY
                k.id_unit, k.nama_unit
            ORDER BY
                k.id_unit ASC
        ", [
            'year' => $year,
            'startMonth' => $startMonth,
            'endMonth' => $endMonth,
        ]);

        return view('backend.laporan-unit.statistikunit', compact('data', 'semester', 'bulan', 'year'));
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
            Session::flash('success', 'Data penjualan jajanan unit '.$nm_unit);
            Session::flash('mode', 'success');
		}else{
			$id_unit = 0;
        }
        
		$startDateQuery = date("Y-m-d", strtotime($startDate));
        $endDateQuery = date("Y-m-d", strtotime($endDate));
        if ($mode == "all"){
            $data = KeepH::where('keep_h.active', '!=', 0)
                ->whereBetween('tanggal', [$startDateQuery, $endDateQuery])
                ->orderBy('keep_h.tanggal', 'ASC')
                ->leftJoin('unit', 'unit.id_unit', '=', 'keep_h.id_unit');
        } else 
        if ($mode == "limited"){
            $data = KeepH::where('keep_h.active', '!=', 0)
                ->where('keep_h.id_unit','=',$id_unit)
                ->whereBetween('tanggal', [$startDateQuery, $endDateQuery])
                ->orderBy('keep_h.tanggal', 'ASC')
                ->leftJoin('unit', 'unit.id_unit', '=', 'keep_h.id_unit');
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
        // Mengambil parameter bulan dan tahun
        $bulan = $_GET['bulan'] ?? 0;
        $year = $_GET['tahun'] ?? 0;

        $data  = DB::select("SELECT
                    h.tanggal,
                    b.kode,
                    b.nama,
                    sum( p.jumlah ) AS jumlah,
                    p.harga,
                    ( sum( p.jumlah ) * p.harga ) AS total 
                    FROM
                    penjualan_d AS p
                    LEFT JOIN penjualan_h AS h ON p.id_penjualan = h.id
                    LEFT JOIN barang AS b ON b.id = p.id_barang
                    LEFT JOIN kategori_barang AS k ON k.id_kategori = b.id_kategori
                    LEFT JOIN unit AS u ON u.id_unit = b.id_unit 
                    WHERE
                    b.id_kategori = 5
                    AND h.id_unit = 1
                    AND h.active != 0 
                    AND MONTH(h.tanggal) = $bulan
                    AND YEAR(h.tanggal) = $year
                    GROUP BY
                    u.nama_unit,
                    h.tanggal,
                    b.kode,
                    b.nama,
                    p.harga"); 
        
        // Cek apakah data kosong
        if (empty($data)) {
            Session::flash('error', 'Data tidak tersedia untuk bulan dan tahun yang dipilih.');
            Session::flash('mode', 'error');
        } else {
            Session::flash('success', 'Success.');
            Session::flash('mode', 'success');
        }
        
        view()->share('bulan',$bulan);
        return view('backend.laporan-unit.rekap_tera', compact('data'));
    }

    public function index_ppdb()
    {
        //
        Session::forget('success');
        Session::forget('mode');

        // Mengambil parameter bulan dan tahun
        $bulan = $_GET['bulan'] ?? 0;
        $year = $_GET['tahun'] ?? date('Y');
        
        $data  = DB::select("SELECT
                    h.tanggal,
                    b.kode,
                    b.nama,
                    sum( p.jumlah ) AS jumlah,
                    p.harga,
                    ( sum( p.jumlah ) * p.harga ) AS total 
                    FROM
                    penjualan_d AS p
                    LEFT JOIN penjualan_h AS h ON p.id_penjualan = h.id
                    LEFT JOIN barang AS b ON b.id = p.id_barang
                    LEFT JOIN kategori_barang AS k ON k.id_kategori = b.id_kategori
                    LEFT JOIN unit AS u ON u.id_unit = b.id_unit 
                    WHERE
                    b.id_kategori >= 6
                    AND h.id_unit = 4
                    AND h.active != 0 
                    AND MONTH(h.tanggal) = $bulan
                    AND YEAR(h.tanggal) = $year
                    GROUP BY
                    u.nama_unit,
                    h.tanggal,
                    b.kode,
                    b.nama,
                    p.harga"); 

        // Cek apakah data kosong
        if (empty($data)) {
            Session::flash('error', 'Data tidak tersedia untuk bulan dan tahun yang dipilih.');
            Session::flash('mode', 'error');
        } else {
            Session::flash('success', 'Success.');
            Session::flash('mode', 'success');
        }
        
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
