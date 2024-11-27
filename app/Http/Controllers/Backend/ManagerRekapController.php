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

    public function penerimaan_view($id_unit, $bulan, $tahun){
        Session::forget('success');
        Session::forget('mode');
        $bulan_select = $bulan;
        $tahun_select = $tahun;
        $unit_select = $id_unit;
    
        $nm_unit = DB::table("unit")->where('id_unit',$id_unit)->pluck('nama_unit')[0];
        
        $data_minuman = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                            id_kategori = 1 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan AND
                            tahun = $tahun");
    
        $data_alat_tulis = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                            id_kategori = 2 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan AND
                            tahun = $tahun");
    
        $data_seragam = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                            id_kategori = 3 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan AND
                            tahun = $tahun");
    
        $data_tera = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                            id_kategori = 5 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan AND
                            tahun = $tahun");
    
        $data_kangen = DB::select("SELECT sum(penjualan) as total FROM rekapan_unit WHERE 
                            id_kategori = 8 AND 
                            id_unit = $id_unit AND
                            bulan = $bulan AND
                            tahun = $tahun");
    
        $data_umkm = DB::select("SELECT sum(bagi_hasil) as total FROM keep_h WHERE 
                            id_unit = $id_unit AND
                            substr(created_at, 6, 2 ) = $bulan AND
                            LEFT(created_at, 4) = $tahun");
    
        $tot_minuman = $data_minuman[0]->total ?? 0;
        $tot_peralatan = $data_alat_tulis[0]->total ?? 0;
        $tot_seragam = $data_seragam[0]->total ?? 0;
        $tot_tera = $data_tera[0]->total ?? 0;
        $tot_kangen = $data_kangen[0]->total ?? 0;
        $tot_umkm = $data_umkm[0]->total ?? 0;
    
        $grand_total = $tot_minuman + $tot_peralatan + $tot_seragam + $tot_tera + $tot_kangen + $tot_umkm;
    
        /* Awal Unit PPDB */
        $data_kb = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 1 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan AND
                        tahun = $tahun");
        $data_tk = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 2 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan AND
                        tahun = $tahun");
        $data_sd = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 3 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan AND
                        tahun = $tahun");
        $data_smp = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 4 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan AND
                        tahun = $tahun");
        $data_sma = DB::select("SELECT sum(penerimaan) as total FROM rekapan_unit WHERE 
                        id_tingkat = 5 AND 
                        id_unit = $id_unit AND
                        bulan = $bulan AND
                        tahun = $tahun");
    
        $tot_kb = $data_kb[0]->total ?? 0;
        $tot_tk = $data_tk[0]->total ?? 0;
        $tot_sd = $data_sd[0]->total ?? 0;
        $tot_smp = $data_smp[0]->total ?? 0;
        $tot_sma = $data_sma[0]->total ?? 0;
    
        $grand_total_ppdb = $tot_kb + $tot_tk + $tot_sd + $tot_smp + $tot_sma;
        /* Akhir unit PPDB */
    
        Session::flash('success','Rekap Penerimaan Unit '.$nm_unit);
        Session::flash('mode','success');
        return view('backend.rekapan-unit.penerimaan_view', 
            compact(['tahun','data_minuman','data_alat_tulis','data_seragam','data_tera','data_kangen','data_umkm','grand_total',
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
            ->with('success', 'Data Berhasil Disinkronisasi.')
            ->with('mode', 'success');
    }
}
