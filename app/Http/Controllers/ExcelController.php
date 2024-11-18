<?php 
namespace App\Http\Controllers;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\objToString;
use App\Http\Controllers\Backend\ManagerController;
use App\Model\User;
use App\Model\PenjualanH;
use App\Model\KeepH;
use App\Model\KeepD;
use App\Model\Produk;
use App\Model\Supplier;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

class ExcelController extends Controller {

	public function export_user($type)
	{
		$data = User::select(DB::raw('CONCAT(firstname," ",lastname) AS nama'),'email','gender','address','phone','birthdate')
		->orderBy('id', 'ASC')->get()->toArray();
		return Excel::create('export_user', function($excel) use ($data) {
			$excel->sheet('List User', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}

	public function viewPenjualan(Request $request)
	{
		$id_unit = $request->input('id_unit');
		$startDate = $request->input('startDate'); 
		$endDate = $request->input('endDate'); 

		$data = PenjualanH::where('penjualan_h.active', '!=', 0)
			->where('penjualan_h.id_unit','=',$id_unit)
			->orderBy('penjualan_h.tanggal', 'ASC')
			->get()->toArray();

	}

	public function export_penjualan($type, Request $request)
	{

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
        
        $data = $data->get()->toArray();

	 	return Excel::create('export_penjualan', function($excel) use ($data) {
			$excel->sheet('List Penjualan', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type); 

	}

	public function export_umkm($type, Request $request)
	{

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
           /*  $data = KeepH::where('keep_h.active', '!=', 0)->where('keep_h.id_unit','=',$id_unit)->orderBy('keep_h.tanggal', 'ASC'); */
		   $data = DB::table('keep_d')->select('keep_h.tanggal','supplier.nama as nama_umkm', 
		   'barang.nama as nama_produk', 'keep_d.harga_keep', 
		   'keep_d.harga_jual', 'keep_d.jumlah', 'keep_d.sisa', 
		   DB::raw('keep_d.harga_jual * (keep_d.jumlah - keep_d.sisa) as penjualan'), 
		   DB::raw('keep_d.harga_keep * (keep_d.jumlah - keep_d.sisa) as setor_umkm'), 
		   DB::raw('(keep_d.harga_jual * (keep_d.jumlah - keep_d.sisa)) - 
		   (keep_d.harga_keep * (keep_d.jumlah - keep_d.sisa)) as bagihasi'))
		   ->leftJoin('keep_h', 'keep_h.id', '=', 'keep_d.id_keep')
		   ->leftJoin('barang', 'barang.id', '=', 'keep_d.id_barang')
		   ->leftJoin('supplier', 'supplier.id', '=', 'keep_d.id_supplier')
		   ->where('keep_h.active', '!=', 0)
		   ->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit'])
		   ->get()->toArray();

        } else 
        if ($mode == "limited"){
           /*  $data = KeepH::where('keep_h.active', '!=', 0)->where('keep_h.id_unit','=',$id_unit)->whereBetween('tanggal', [$startDateQuery, $endDateQuery])->orderBy('keep_h.tanggal', 'ASC'); */
		   $data = DB::table('keep_d')->select('keep_h.tanggal', 'supplier.nama as nama_umkm', 
		   'barang.nama as nama_produk', 'keep_d.harga_keep', 
		   'keep_d.harga_jual', 'keep_d.jumlah', 'keep_d.sisa', 
		   DB::raw('keep_d.harga_jual * (keep_d.jumlah - keep_d.sisa) as penjualan'), 
		   DB::raw('keep_d.harga_keep * (keep_d.jumlah - keep_d.sisa) as setor_umkm'), 
		   DB::raw('(keep_d.harga_jual * (keep_d.jumlah - keep_d.sisa)) - 
		   (keep_d.harga_keep * (keep_d.jumlah - keep_d.sisa)) as bagihasi'))
		   ->leftJoin('keep_h', 'keep_h.id', '=', 'keep_d.id_keep')
		   ->leftJoin('barang', 'barang.id', '=', 'keep_d.id_barang')
		   ->leftJoin('supplier', 'supplier.id', '=', 'keep_d.id_supplier')
		   ->where('keep_h.active', '!=', 0)
		   ->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit'])
		   ->whereBetween('keep_h.tanggal', [$startDateQuery, $endDateQuery])
		   ->get()->toArray(); 

        }

		/* $data = $data->get()->toArray(); */
		$data= json_decode( json_encode($data), true);

	 	return Excel::create('export_umkm', function($excel) use ($data) {
			$excel->sheet('List UMKM', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type); 

	}

	public function export_penjualan_unit($type, Request $request)
	{

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
            $data = PenjualanH::where('penjualan_h.active', '!=', 0)->where('penjualan_h.id_unit','=',Session::get('userinfo')['id_unit']);
        } else 
        if ($mode == "limited"){
            $data = PenjualanH::where('penjualan_h.active', '!=', 0)->where('penjualan_h.id_unit','=',Session::get('userinfo')['id_unit'])->whereBetween('tanggal', [$startDateQuery, $endDateQuery]);
        }
		$data = $data->get()->toArray();

	 	return Excel::create('export_penjualan_unit', function($excel) use ($data) {
			$excel->sheet('List Penjualan Unit', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type); 

	}

	public function export_umkm_unit($type, Request $request)
	{

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
		$id_unit = Session::get('userinfo')['id_unit'];

        if ($mode == "all"){
           /*  $data = KeepH::where('keep_h.active', '!=', 0)
				->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit']); */
				$data = DB::table('keep_d')->select('keep_h.tanggal','supplier.nama as nama_umkm', 
					'barang.nama as nama_produk', 'keep_d.harga_keep', 
					'keep_d.harga_jual', 'keep_d.jumlah', 'keep_d.sisa', 
					DB::raw('keep_d.harga_jual * (keep_d.jumlah - keep_d.sisa) as penjualan'), 
					DB::raw('keep_d.harga_keep * (keep_d.jumlah - keep_d.sisa) as setor_umkm'), 
					DB::raw('(keep_d.harga_jual * (keep_d.jumlah - keep_d.sisa)) - 
					(keep_d.harga_keep * (keep_d.jumlah - keep_d.sisa)) as bagihasi'))
					->leftJoin('keep_h', 'keep_h.id', '=', 'keep_d.id_keep')
					->leftJoin('barang', 'barang.id', '=', 'keep_d.id_barang')
					->leftJoin('supplier', 'supplier.id', '=', 'keep_d.id_supplier')
					->where('keep_h.active', '!=', 0)
					->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit'])
					->get()->toArray();
        } else 
        if ($mode == "limited"){
            /* $data = KeepH::where('keep_h.active', '!=', 0)
				->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit'])
				->whereBetween('tanggal', [$startDateQuery, $endDateQuery]); */
				$data = DB::table('keep_d')->select('keep_h.tanggal', 'supplier.nama as nama_umkm', 
				'barang.nama as nama_produk', 'keep_d.harga_keep', 
				'keep_d.harga_jual', 'keep_d.jumlah', 'keep_d.sisa', 
				DB::raw('keep_d.harga_jual * (keep_d.jumlah - keep_d.sisa) as penjualan'), 
				DB::raw('keep_d.harga_keep * (keep_d.jumlah - keep_d.sisa) as setor_umkm'), 
				DB::raw('(keep_d.harga_jual * (keep_d.jumlah - keep_d.sisa)) - 
				(keep_d.harga_keep * (keep_d.jumlah - keep_d.sisa)) as bagihasi'))
				->leftJoin('keep_h', 'keep_h.id', '=', 'keep_d.id_keep')
				->leftJoin('barang', 'barang.id', '=', 'keep_d.id_barang')
				->leftJoin('supplier', 'supplier.id', '=', 'keep_d.id_supplier')
				->where('keep_h.active', '!=', 0)
				->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit'])
				->whereBetween('keep_h.tanggal', [$startDateQuery, $endDateQuery])
				->get()->toArray(); 
        }

		$data= json_decode( json_encode($data), true);
		
	 	return Excel::create('export_umkm_unit', function($excel) use ($data) {
			$excel->sheet('List UMKM Unit', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type); 
	
	}

	public function export_penjualan_kategori($type, Request $request)
	{
		$tahun_select = $request->input('tahun');
		$bulan_select = $request->input('bulan');
		$id_kategori = $request->input('kategori');
        $id_unit = Session::get('userinfo')['id_unit'];
        $data  = DB::select("SELECT
                    p.created_at,
                    b.kode,
                    b.nama,
                    p.jumlah,
                    p.harga,
                    ( p.jumlah * p.harga ) AS total 
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
                	AND left(p.created_at,4) = $tahun_select
				ORDER BY p.created_at ASC");

		$data= json_decode( json_encode($data), true);
		
	 	return Excel::create('export_penjualan_kategori', function($excel) use ($data) {
			$excel->sheet('List Penjualan Kategori', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type); 


	}


}