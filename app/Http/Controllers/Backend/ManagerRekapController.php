<?php

namespace App\Http\Controllers\Backend;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Model\PurchaseH;
use App\Model\PenjualanH;
use App\Model\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Datatables;
use Artisan;

class ManagerRekapController extends Controller
{
    //
    public function index_penerimaan()
    {
        //
        $list_unit = DB::select("
                        select 
                        id_unit as value, 
                        nama_unit as text
                        from unit");
        return view('backend.rekapan-unit.penerimaan_index', compact('list_unit'));
    }

    public function penerimaan_view($id_unit, $bulan){
        Session::forget('success');
        Session::forget('mode');
        $bulan_select = $bulan;
        $unit_select = $id_unit;

        $nm_unit = DB::table("unit")->where('id_unit',$id_unit)->pluck('nama_unit')[0];
        
        $data_minuman  = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                            id_kategori = 1 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan");

        $data_alat_tulis  = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                            id_kategori = 2 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan");

        $data_seragam  = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                            id_kategori = 3 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan");

        $data_tera  = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                            id_kategori = 5 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan");

        $data_kangen  = DB::select("SELECT sum(penjualan) as total FROM rekapan_unit WHERE 
                            id_kategori = 8 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan");

        $data_umkm = DB::select("SELECT sum(bagi_hasil) as total FROM keep_h WHERE 
                            id_unit = $id_unit AND
                            substr(created_at, 6, 2 ) = $bulan");

        $sql_minuman = "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_kategori = 1 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_minuman = DB::table(DB::raw("($sql_minuman) as x"))->select(['total'])->pluck('total')[0];
        $sql_peralatan= "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_kategori = 2 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_peralatan = DB::table(DB::raw("($sql_peralatan) as x"))->select(['total'])->pluck('total')[0];
        $sql_seragam = "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_kategori = 3 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_seragam = DB::table(DB::raw("($sql_seragam) as x"))->select(['total'])->pluck('total')[0];
        $sql_tera= "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_kategori = 5 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_tera= DB::table(DB::raw("($sql_tera) as x"))->select(['total'])->pluck('total')[0];
        $sql_kangen= "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_kategori = 8 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_kangen= DB::table(DB::raw("($sql_kangen) as x"))->select(['total'])->pluck('total')[0];
        $sql_umkm= "SELECT sum(bagi_hasil) as total FROM keep_h WHERE id_unit = $id_unit AND substr(created_at, 6, 2 ) = $bulan";
        $tot_umkm = DB::table(DB::raw("($sql_umkm) as x"))->select(['total'])->pluck('total')[0]; 

        $grand_total = 0;
        if (isset($tot_minuman) || isset($tot_peralatan) || isset($tot_seragam) || isset($tot_umkm) || isset($tot_tera) || isset($tot_kangen) ){
            $grand_total = $tot_minuman + $tot_peralatan + $tot_seragam + $tot_tera + $tot_kangen + $tot_umkm ;
        } 

        /* Awal Unit PPDB */
        $data_kb  = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 1 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan");
        $data_tk  = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 2 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan");
        $data_sd  = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 3 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan");
        $data_smp  = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 4 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan");
        $data_sma  = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 5 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan");
        
        $sql_kb = "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_tingkat = 1 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_kb = DB::table(DB::raw("($sql_kb) as x"))->select(['total'])->pluck('total')[0];
        $sql_tk = "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_tingkat = 2 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_tk = DB::table(DB::raw("($sql_tk) as x"))->select(['total'])->pluck('total')[0];
        $sql_sd = "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_tingkat = 3 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_sd = DB::table(DB::raw("($sql_sd) as x"))->select(['total'])->pluck('total')[0];
        $sql_smp = "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_tingkat = 4 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_smp = DB::table(DB::raw("($sql_smp) as x"))->select(['total'])->pluck('total')[0];
        $sql_sma = "SELECT sum(penerimaan) as total FROM rekapan_unit WHERE id_tingkat = 5 AND id_unit = $id_unit AND bulan = $bulan";
        $tot_sma = DB::table(DB::raw("($sql_sma) as x"))->select(['total'])->pluck('total')[0];

        $grand_total_ppdb = 0;
        if (isset($tot_kb) || isset($tot_tk) || isset($tot_sd) || isset($tot_smp) || isset($tot_smp) ){
            $grand_total_ppdb = $tot_kb  + $tot_tk + $tot_sd + $tot_smp + $tot_sma ;
        } 
        /* Akhir unit PPDB */
        

        Session::flash('success','Rekap Penerimaan Unit '.$nm_unit);
        Session::flash('mode','success');
        return view('backend.rekapan-unit.penerimaan_view', 
            compact(['data_minuman','data_alat_tulis','data_seragam','data_tera','data_kangen','data_umkm','grand_total',
                    'data_kb','data_tk','data_sd','data_smp','data_sma','grand_total_ppdb']));
    }

    public function index_pengeluaran()
    {
        //
        $list_unit = DB::select("
                        select 
                        id_unit as value, 
                        nama_unit as text
                        from unit");
        return view('backend.rekapan-unit.pengeluaran_index', compact('list_unit'));
    }

    public function pengeluaran_view($id_unit, $bulan){
        
        $bulan_select = $bulan;
        $unit_select = $id_unit;
       /*  $data  = DB::select("SELECT
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
                GROUP BY b.id,b.kode,b.nama,p.harga"); */
        $data_test = "dsadasdsa";
        return view('backend.rekapan-unit.pengeluaran_view', compact('data_test'));
    }

    public function sinkron_data_unit(){
        // call rekap penerimaan barang
		Artisan::call('create:rekappenerimaan');

        return Redirect::to('/backend/rekap-penerimaan-unit')
            ->with('success', "Data Berhasil Disinkronisasi.")
            ->with('mode', 'success');
    }
}
