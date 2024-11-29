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
                    h.tanggal,
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
                GROUP BY h.tanggal,b.kode,b.nama,p.harga"); 
                $data = collect($data);

        return view('backend.laporan.rekap_view', compact('data'));
    }

    public function index_kangen_water()
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
            $data = $data = DB::table('penjualan_d AS p')
                    ->select('b.kode', 'b.nama', DB::raw('SUM(p.jumlah) AS jumlah'), 'p.harga', DB::raw('(SUM(p.jumlah) * p.harga) AS total'))
                    ->leftJoin('penjualan_h AS h', 'p.id_penjualan', '=', 'h.id')
                    ->leftJoin('barang AS b', 'b.id', '=', 'p.id_barang')
                    ->leftJoin('kategori_barang AS k', 'k.id_kategori', '=', 'b.id_kategori')
                    ->where('b.id_kategori', '=', 8)
                    ->where('h.id_unit','=',Session::get('userinfo')['id_unit'])
                    ->where('h.active','!=', 0)
                    ->groupBy('b.kode', 'b.nama', 'p.harga')
                    ->orderBy('b.kode');
        } else 
        if ($mode == "limited"){
            $data = $data = DB::table('penjualan_d AS p')
                ->select('b.kode', 'b.nama', DB::raw('SUM(p.jumlah) AS jumlah'), 'p.harga', DB::raw('(SUM(p.jumlah) * p.harga) AS total'))
                ->leftJoin('penjualan_h AS h', 'p.id_penjualan', '=', 'h.id')
                ->leftJoin('barang AS b', 'b.id', '=', 'p.id_barang')
                ->leftJoin('kategori_barang AS k', 'k.id_kategori', '=', 'b.id_kategori')
                ->where('b.id_kategori', '=', 8)
                ->where('h.id_unit','=',Session::get('userinfo')['id_unit'])
                ->where('h.active','!=', 0)
                ->whereBetween('p.created_at', [$startDateQuery, $endDateQuery])
                ->groupBy('b.kode', 'b.nama', 'p.harga')
                ->orderBy('b.kode');
        }
        $data = $data->get();

		view()->share('startDate',$startDate);
		view()->share('endDate',$endDate);
        view()->share('mode',$mode);
		return view ('backend.laporan.kangenwater',['data'=>$data]);
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

    public function mutasi_stok(Request $request)
    {
        $id_barang = $request->get('id_bahan_baku');
        $data = collect(); // Default kosong jika id_barang tidak ada

        if ($id_barang) {
            $data = DB::table('stok AS s')
                ->selectRaw("
                    b.nama,
                    b.stok_awal,
                    b.stok_total,
                    s.created_at AS tanggal,
                    s.keterangan,
                    s.type,
                    s.jumlah,
                    SUM(s.jumlah) OVER (PARTITION BY s.id_barang ORDER BY s.created_at ASC) + b.stok_awal AS mutasi_stok
                ")
                ->leftJoin('barang AS b', 's.id_barang', '=', 'b.id')
                ->where('s.id_barang', $id_barang)
                ->orderBy('s.created_at', 'DESC')
                ->paginate(25)
                ->appends(['id_bahan_baku' => $id_barang]); // Menambahkan parameter ke paginasi
        }

        return view('backend.laporan.mutasi_stok', [
            'data' => $data,
            'id_barang' => $id_barang,
        ]);
    }

    public function index_harian(Request $request) {
        
        // Ambil parameter tanggal dari input
        $tanggal = $request->get('tanggal');

        // Jika tanggal tidak diisi, gunakan tanggal hari ini sebagai default
        if (!$tanggal) {
            $tanggal = date('Y-m-d');
        }

        // Query data penjualan per kategori berdasarkan tanggal
        $dataPenjualan = DB::table('penjualan_d AS p')
            ->selectRaw('
                h.tanggal,
                k.nama_kategori,
                SUM(p.jumlah * p.harga) AS total_per_kategori
            ')
            ->leftJoin('penjualan_h AS h', 'p.id_penjualan', '=', 'h.id')
            ->leftJoin('barang AS b', 'b.id', '=', 'p.id_barang')
            ->leftJoin('kategori_barang AS k', 'k.id_kategori', '=', 'b.id_kategori')
            ->whereIn('b.id_kategori', [1, 2, 3, 4, 5, 8]) // Filter kategori
            ->where('h.id_unit', '=', Session::get('userinfo')['id_unit'])
            ->where('h.active', '!=', 0)
            ->whereDate('h.tanggal', $tanggal) // Filter berdasarkan tanggal
            ->groupBy('h.tanggal', 'k.nama_kategori');

        // Query data penitipan berdasarkan tanggal
        $dataPenitipan = DB::table('keep_h')
            ->selectRaw('
                tanggal,
                "Penitipan Jajanan" AS nama_kategori,
                SUM(total) AS total_per_kategori
            ')
            ->where('active', '=', 1)
            ->where('id_unit', '=', Session::get('userinfo')['id_unit'])
            ->whereDate('tanggal', $tanggal) // Filter berdasarkan tanggal
            ->groupBy('tanggal');

        // Gabungkan data penjualan dan penitipan menggunakan UNION ALL
        $data = $dataPenjualan
            ->unionAll($dataPenitipan)
            ->orderBy('tanggal')
            ->get();

        // Kirim data ke view
        return view('backend.laporan.harian', [
            'data' => $data,
            'tanggal' => $tanggal
        ]);
	}

}