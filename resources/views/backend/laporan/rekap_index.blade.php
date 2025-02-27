<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Laporan Penjualan Kategori';
	$breadcrumb[1]['url'] = url('backend/report-rekap-penjualan');

    $kategori = array(
        "1" => 'Minuman & Ice Cream',
        "2" => 'Alat Tulis Sekolah',
        "3" => 'Seragam & Perlengkapan Sekolah',
        "5" => 'TERA',
        "8" => 'Kangen Water'
    );
    $kategori_select = request('kategori');

    if(!$list_kategori){
		$list_kategori = 1;
	}

    $bulan = array( 
		"0"=>"",
		"1"=>'Januari', 
		"2"=>'Februari', 
		"3"=>'Maret', 
		"4"=>'April', 
		"5"=>'Mei', 
		"6"=>'Juni',  
		"7"=>'Juli', 
		"8"=>'Agustus', 
		"9"=>'September', 
		"10"=>'Oktober', 
		"11"=>'November',
		"12"=>'Desember'); 
	$bulan_select = request('bulan');

	if(!$bulan_select){
		$bulan_ini  = (int)date('m');
		$bulan_select = $bulan_ini;
	}

    $currentYear = date('Y');
    $tahun_select = request('tahun') ?: $currentYear;

    $years = range($currentYear - 1, $currentYear + 5);
    $activeYear = $tahun_select;

?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Penjualan')

<!-- CONTENT -->
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Laporan Penjualan Kategori Harian</h3>
        </div>
        <div class="title_right">
            <div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
    @include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-1" style="margin-top:7px;">Periode</div>
                        <div class="col-xs-12 col-sm-3">
                            <select class="form-control" name="bulan" id="bulan" required="required">
                                @foreach($bulan as $key => $value)
                                    <option value="{{ $key }}" {{ $bulan_select == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xs-12 col-sm-2">
                            <select class="form-control" name="tahun" id="tahun" required="required">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == $activeYear ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xs-12 col-sm-2" style="margin-top:7px;">Kategori Barang</div>
                        <div class="col-xs-12 col-sm-4">
                            <select class="form-control" name="kategori" id="kategori" required="required">
                                <option value="">Pilih Kategori...</option>
                                @foreach($kategori as $key => $value)
                                    <option value="{{ $key }}" {{ $kategori_select == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
							</select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="row">
				
	</div>
@endsection

<!-- CSS -->
@section('css')

@endsection

<!-- JAVASCRIPT -->
@section('script')
    <script type="text/javascript">
        $(function(){
            $('#kategori, #bulan, #tahun').on('change', function(){
                var tahun = $('#tahun').val();
                var bulan = $('#bulan').val();
                var kategori = $('#kategori').val();

                if(tahun && bulan && kategori) {
                    window.location.href = '{{ url("backend/report-rekap-penjualan") }}/' + kategori + '/' + bulan + '/' + tahun;
                }
            });
        });
    </script>
@endsection