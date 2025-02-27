<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('backend/login');
});

Route::match(array('GET','POST'),'/backend/login','Backend\LoginController@index');

/* SUPER ADMIN */
Route::group(array('prefix' => 'backend','middleware'=> ['token_super_admin']), function()
{
	Route::resource('/modules', 'Backend\ModuleController');
	Route::get('/datatable/module','Backend\ModuleController@datatable');
});

/* ACCESS CONTROL EDIT */
Route::group(array('prefix' => 'backend','middleware'=> ['token_admin', 'token_edit']), function()
{
	Route::get('/users-level/{id}/edit','Backend\UserLevelController@edit');
	Route::match(array('PUT','PATCH'),'/users-level/{id}','Backend\UserLevelController@update');

	Route::get('/users-user/{id}/edit','Backend\UserController@edit');
    Route::match(array('PUT','PATCH'),'/users-user/{id}','Backend\UserController@update');

	Route::get('/supplier/{id}/edit','Backend\SupplierController@edit');
	Route::match(array('PUT','PATCH'),'/supplier/{id}','Backend\SupplierController@update');
    
	Route::get('/barang/{id}/edit','Backend\BarangController@edit');
	Route::match(array('PUT','PATCH'),'/barang/{id}','Backend\BarangController@update');

	Route::get('/barang-ppdb/{id}/edit','Backend\BarangPpdbController@edit');
	Route::match(array('PUT','PATCH'),'/barang-ppdb/{id}','Backend\BarangPpdbController@update');

	Route::get('/purchase-order/{id}/edit','Backend\PurchaseController@edit');
	Route::match(array('PUT','PATCH'),'/purchase-order/{id}','Backend\PurchaseController@update');

	Route::get('/penjualan-umkm/{id}/edit','Backend\PenjualanUmkmController@edit');
	Route::match(array('PUT','PATCH'),'/penjualan-umkm/{id}','Backend\PenjualanUmkmController@update');
	Route::match(array('PUT','PATCH'),'/penjualan-umkm/pulled/{id}','Backend\PenjualanUmkmController@pulled');

});

/* ACCESS CONTROL ALL */
Route::group(array('prefix' => 'backend','middleware'=> ['token_admin', 'token_all']), function()
{
	Route::get('/users-level/create','Backend\UserLevelController@create');
	Route::post('/users-level','Backend\UserLevelController@store');
	Route::delete('/users-level/{id}','Backend\UserLevelController@destroy');
	
	Route::get('/users-user/create','Backend\UserController@create');
	Route::post('/users-user','Backend\UserController@store');
    Route::delete('/users-user/{id}','Backend\UserController@destroy');
    Route::post('/users-user/delete','Backend\UserController@deleteAll');

	Route::get('/media-library/upload','Backend\MediaLibraryController@upload');
	Route::post('/media-library/upload','Backend\MediaLibraryController@upload');	
    Route::delete('/media-library/{id}','Backend\MediaLibraryController@destroy');
    Route::post('/media-library','Backend\MediaLibraryController@deleteAll');

	Route::get('/supplier/create','Backend\SupplierController@create');
	Route::post('/supplier','Backend\SupplierController@store');
	Route::delete('/supplier/{id}','Backend\SupplierController@destroy');
    
	Route::get('/barang/create','Backend\BarangController@create');
	Route::post('/barang','Backend\BarangController@store');
	Route::delete('/barang/{id}','Backend\BarangController@destroy');

	Route::get('/barang-ppdb/create','Backend\BarangPpdbController@create');
	Route::post('/barang-ppdb','Backend\BarangPpdbController@store');
	Route::delete('/barang-ppdb/{id}','Backend\BarangPpdbController@destroy');

	Route::get('/purchase-order/create','Backend\PurchaseController@create');
	Route::post('/purchase-order','Backend\PurchaseController@store');
	Route::delete('/purchase-order/{id}','Backend\PurchaseController@destroy');
    Route::post('/purchase-order/terima/{id}','Backend\PurchaseController@received');

	Route::get('/penjualan/create','Backend\PenjualanController@create');
	Route::post('/penjualan','Backend\PenjualanController@store');
	Route::delete('/penjualan/{id}','Backend\PenjualanController@destroy');
	Route::get('penjualan/check-stok', 'Backend\PenjualanController@checkStok');

	Route::get('/penjualan-barcode/create','Backend\KasirController@create');
	Route::post('/penjualan-barcode','Backend\KasirController@store');
	Route::delete('/penjualan-barcode/{id}','Backend\KasirController@destroy');

	Route::get('/penjualan-umkm/create','Backend\PenjualanUmkmController@create');
	Route::post('/penjualan-umkm','Backend\PenjualanUmkmController@store');
	Route::delete('/penjualan-umkm/{id}','Backend\PenjualanUmkmController@destroy');

});

/* ACCESS CONTROL VIEW */
Route::group(array('prefix' => 'backend','middleware'=> ['token_admin']), function()
{
	Route::get('',function (){return Redirect::to('backend/dashboard');});
	Route::get('/logout','Backend\LoginController@logout');
	
	Route::get('/dashboard','Backend\DashboardController@dashboard');

	Route::get('/users-level/datatable','Backend\UserLevelController@datatable');	
	Route::get('/users-level','Backend\UserLevelController@index');
	Route::get('/users-level/{id}','Backend\UserLevelController@show');
	
	Route::get('/users-user/datatable','Backend\UserController@datatable');
	Route::get('/users-user','Backend\UserController@index');
	Route::get('/users-user/{id}','Backend\UserController@show');

    Route::get('/user/export/{type}','ExcelController@export_user');
	Route::get('/penjualan/export/{type}','ExcelController@export_penjualan');
	Route::get('/umkm/export/{type}','ExcelController@export_umkm');
	Route::get('/kangen-water/export/{type}','ExcelController@export_kangen_water');
	Route::get('/user/export/{type}','ExcelController@export_penjualan_unit');
	Route::get('/user/export/{type}','ExcelController@export_umkm_unit');
	Route::get('/user/export/{type}','ExcelController@export_penjualan_kategori');

	Route::get('/media-library/datatable/','Backend\MediaLibraryController@datatable');
	Route::get('/media-library','Backend\MediaLibraryController@index');
	Route::get('/media-library/popup-media/{from}/{id_count}','Backend\MediaLibraryController@popup_media');
    Route::get('/media-library/popup-media-gallery/','Backend\MediaLibraryController@popup_media_gallery');
    Route::get('/media-library/popup-media-editor/{id_count}','Backend\MediaLibraryController@popup_media_editor');
	
	Route::get('/access-control','Backend\AccessControlController@index');
	Route::post('/access-control','Backend\AccessControlController@update');

	Route::get('/setting','Backend\SettingController@index');
	Route::post('/setting','Backend\SettingController@update');
    
	Route::get('/supplier/datatable','Backend\SupplierController@datatable');
	Route::get('/supplier','Backend\SupplierController@index');
	Route::get('/supplier/{id}','Backend\SupplierController@show');
    
	Route::get('/barang/datatable','Backend\BarangController@datatable');
	Route::get('/barang','Backend\BarangController@index');
    Route::get('/barang/{id}','Backend\BarangController@show');
    Route::get('/barang/harga/{id}','Backend\BarangController@histori');

	Route::get('/barang-po/datatable','Backend\BarangPoController@datatable');
	Route::get('/barang-po','Backend\BarangPoController@index');
    Route::get('/barang-po/{id}','Backend\BarangPoControllerr@show');
    Route::get('/barang-po/harga/{id}','Backend\BarangPoController@histori');

	Route::get('/barang-koreksi/datatable','Backend\BarangKoreksiController@datatable');
	Route::get('/barang-koreksi','Backend\BarangKoreksiController@index');
    Route::get('/barang-koreksi/{id}','Backend\BarangKoreksiControllerr@show');
    Route::get('/barang-koreksi/harga/{id}','Backend\BarangKoreksiController@histori');

	Route::get('/barang-ppdb/datatable','Backend\BarangPpdbController@datatable');
	Route::get('/barang-ppdb','Backend\BarangPpdbController@index');
    Route::get('/barang-ppdb/{id}','Backend\BarangPpdbController@show');
    Route::get('/barang-ppdb/harga/{id}','Backend\BarangPpdbController@histori');

	Route::get('/purchase-order/datatable','Backend\PurchaseController@datatable');
	Route::get('/purchase-order','Backend\PurchaseController@index');
	Route::get('/purchase-order/{id}','Backend\PurchaseController@show');
    Route::get('/purchase-order/barang/popup-media/{id_count}','Backend\PurchaseController@popup_media_barang');
    Route::get('/purchase-order/supplier/popup-media','Backend\PurchaseController@popup_media_supplier');

	Route::get('/browse-barang-ppdb/datatable','Backend\BarangPpdbController@datatable_barang');

    Route::get('/browse-barang-koreksi/datatable','Backend\BarangKoreksiController@datatable_barang');
	Route::get('/browse-barang-po/datatable','Backend\BarangPoController@datatable_barang');
	Route::get('/browse-barang/datatable','Backend\BarangController@datatable_barang');
    Route::get('/browse-supplier/datatable','Backend\SupplierController@datatable_supplier');

	Route::get('/browse-barang-umkm/datatable','Backend\BarangController@datatable_barang_umkm');
    Route::get('/browse-supplier-umkm/datatable','Backend\SupplierController@datatable_supplier_umkm');

	Route::get('/inden/datatable','Backend\IndenController@datatable');
	Route::get('/inden','Backend\IndenController@index');

	Route::get('/penjualan/datatable','Backend\PenjualanController@datatable');
	Route::get('/penjualan','Backend\PenjualanController@index');
	Route::get('/penjualan/{id}','Backend\PenjualanController@show');
    Route::get('/penjualan/barang/popup-media/{id_count}','Backend\PenjualanController@popup_media_barang');

	Route::get('/penjualan-barcode/datatable','Backend\KasirController@datatable');
	Route::get('/penjualan-barcode','Backend\KasirController@index');
	Route::get('/penjualan-barcode/{id}','Backend\KasirController@show');
    Route::get('/penjualan-barcode/barang/popup-media/{id_count}','Backend\KasirController@popup_media_barang');
	Route::get('/penjualan-barcode/barang/{id_barang}','Backend\KasirController@get_barcode');

	Route::get('/penjualan-umkm/datatable','Backend\PenjualanUmkmController@datatable');
	Route::get('/penjualan-umkm','Backend\PenjualanUmkmController@index');
	Route::get('/penjualan-umkm/{id}','Backend\PenjualanUmkmController@show');
    Route::get('/penjualan-umkm/barang/popup-media/{id_count}','Backend\PenjualanUmkmController@popup_media_barang');
    Route::get('/penjualan-umkm/supplier/popup-media/{id_count}','Backend\PenjualanUmkmController@popup_media_supplier');
	Route::get('/penjualan-umkm/keep/popup-media/{id}','Backend\PenjualanUmkmController@popup_media_keep');

	Route::get('/pengeluaran','Backend\PengeluaranController@index');
	Route::get('/pengeluaran/datatable','Backend\PengeluaranController@datatable');
	Route::get('/pengeluaran/{id}','Backend\PengeluaranController@show');
    
    Route::get('/report-purchase','Backend\LaporanController@index_purchase');
    Route::get('/report-penjualan','Backend\LaporanController@index_penjualan');
	Route::get('/report-rekap-penjualan','Backend\LaporanController@rekap_penjualan');
	Route::get('/report-rekap-penjualan/{kategori}/{bulan}/{tahun}', 'Backend\LaporanController@rekap_penjualan_view');
	Route::get('/report-rekap-kategori','Backend\LaporanController@view_rekap_kategori');
	Route::get('/report-umkm','Backend\LaporanController@index_umkm');
    Route::get('/report-stok','Backend\LaporanController@index_stok');
	Route::get('/report-mutasi-stok','Backend\LaporanController@mutasi_stok');
	Route::get('/report-mutasi-stok/barang/popup-media/','Backend\LaporanController@popup_media_barang');
	Route::get('/report-harian','Backend\LaporanController@index_harian');

	Route::get('/report-penjualan-harian','Backend\LaporanHarianController@penjualan_harian');
	Route::get('/report-penjualan-harian/{kategori}/{bulan}/{tahun}', 'Backend\LaporanHarianController@penjualan_harian_view');
	
	Route::get('/rekap-penerimaan','Backend\RekapController@index_penerimaan');
	Route::get('/rekap-penerimaan/{unit}/{bulan}/{tahun}', 'Backend\RekapController@penerimaan_view');
	Route::get('/rekap-pengeluaran','Backend\RekapController@index_pengeluaran');
	Route::get('/rekap-penjualan/{unit}/{bulan}', 'Backend\RekapController@pengeluaran_view');
	Route::get('/rekapan/sinkron', 'Backend\RekapController@sinkron_data');

	// Manajer ------------------------------------------------------------------------->
	Route::get('/report-purchase-unit','Backend\ManagerController@index_purchase');
    Route::get('/report-penjualan-unit','Backend\ManagerController@index_penjualan');
	Route::get('/report-penjualan-kategori-unit','Backend\ManagerController@index_kategori');
	Route::get('/report-statistik-penjualan-unit','Backend\ManagerController@statistik_unit');
	Route::get('/report-statistik-penjualan-kategori','Backend\ManagerController@statistik_kategori');
	Route::get('/report-umkm-unit','Backend\ManagerController@index_umkm');
	Route::get('/report-tera-unit','Backend\ManagerController@index_tera');
	Route::get('/report-ppdb-unit','Backend\ManagerController@index_ppdb');
    Route::get('/report-stok-unit','Backend\ManagerController@index_stok');

	Route::get('/rekap-penerimaan-unit','Backend\ManagerRekapController@index_penerimaan');
	Route::get('/rekap-penerimaan-unit/{unit}/{bulan}/{tahun}', 'Backend\ManagerRekapController@penerimaan_view');
	Route::get('/rekap-pengeluaran-unit','Backend\ManagerRekapController@index_pengeluaran');
	Route::get('/rekap-penjualan-unit/{unit}/{bulan}', 'Backend\ManagerRekapController@pengeluaran_view');
	Route::get('/rekapan/sinkron-unit', 'Backend\ManagerRekapController@sinkron_data_unit');
	// End Manajer --------------------------------------------------------------------->

	Route::get('/koreksi-stok','Backend\KoreksiController@index');
    Route::post('/koreksi-stok','Backend\KoreksiController@update');
    Route::get('/koreksi-stok/barang/popup-media/','Backend\KoreksiController@popup_media_barang');

	Route::get('/koreksi-stok-ppdb','Backend\KoreksiPpdbController@index');
    Route::post('/koreksi-stok-ppdb','Backend\KoreksiPpdbController@update');
	Route::get('/koreksi-stok-ppdb/barang/popup-media/','Backend\KoreksiPpdbController@popup_media_barang_ppdb');

});