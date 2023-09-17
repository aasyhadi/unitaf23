<?php 
namespace App\Http\Controllers;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Backend\ManagerController;
use App\Model\User;
use App\Model\PenjualanH;
use App\Model\KeepH;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

		$data = $data->get()->toArray();

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
        if ($mode == "all"){
            $data = KeepH::where('keep_h.active', '!=', 0)->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit']);
        } else 
        if ($mode == "limited"){
            $data = KeepH::where('keep_h.active', '!=', 0)->where('keep_h.id_unit','=',Session::get('userinfo')['id_unit'])->whereBetween('tanggal', [$startDateQuery, $endDateQuery]);
        }

		$data = $data->get()->toArray();

	 	return Excel::create('export_umkm_unit', function($excel) use ($data) {
			$excel->sheet('List UMKM Unit', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type); 
	
	}


}