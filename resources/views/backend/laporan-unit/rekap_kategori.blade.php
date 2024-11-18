<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Rekap Penjualan';
	$breadcrumb[1]['url'] = url('backend/rekap-penjualan');

    $id_unit = array( 
		"0"=>"",
		"1"=>'Kampus Mesjid Agung', 
		"2"=>'Kampus Simpang Kawat SD', 
		"3"=>'Kampus Simpang Kawat SMP SMA'); 
	$unit_select = request('id_unit');

    /* $kategori = array( 
		"0"=>"",
		"1"=>'Minuman & Ice Cream', 
		"2"=>'Alat Tulis Sekolah', 
		"3"=>'Seragam & Perlengkapan Sekolah',
        "4"=>"...",
        "5"=>"TERA",
        "6"=>"...",
        "7"=>"...",
        "8"=>'Kangen Water');    
    $kategori_select = request('kategori'); */

    $kategori = array(
        "1" => 'Minuman & Ice Cream',
        "2" => 'Alat Tulis Sekolah',
        "3" => 'Seragam & Perlengkapan Sekolah',
        "5" => 'Perlengkapan Tera',
        "8" => 'Kangen Water'
    );
    $kategori_select = request('kategori');

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
            <h3>Penjualan Kategori</h3>
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
                <form id="form-work" class="form-horizontal" role="form" autocomplete="off" method="GET">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-1" style="margin-top:7px;">
                            Unit Usaha
                        </div>
                        <div class="col-xs-12 col-sm-11">
                            <select class="form-control" name="id_unit">
                                @if(count($id_unit))
                                    <?php $n = 0;?>
                                    @foreach($id_unit as $u)
                                        <option value="{{$n}}" @if($unit_select==$n) selected="selected" @endif>
                                            @if($n>0) Unit @else Semua Unit Usaha @endif {{$u}}
                                        </option>
                                        <?php $n++;?>
                                    @endforeach
                                @endif
                            </select>
                        </div><br><br><br>

                        <div class="col-xs-12 col-sm-1" style="margin-top:7px;">
                            Periode 
                        </div>
                        <div class="col-xs-12 col-sm-2">
                            <select class="form-control" name="bulan" id="bulan" required="required">
							@if(count($bulan))
                                <?php $n = 0;?>
								@foreach($bulan as $b)
									<option value="{{$n}}" @if($bulan_select==$n) selected="selected" @endif>
										@if($n>0) @else Pilih Bulan... @endif {{$b}}
									</option>
									<?php $n++;?>
								@endforeach
							@endif
							</select>
                        </div>

                        <div class="col-xs-12 col-sm-2">
                            <select class="form-control" name="tahun" id="tahun" required="required">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == $activeYear ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xs-12 col-sm-1" style="margin-top:7px;">
                            Kategori 
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <select class="form-control" name="kategori" id="kategori" required="required">
                                <option value="">Pilih Kategori...</option>
                                @foreach($kategori as $key => $value)
                                    <option value="{{ $key }}" {{ $kategori_select == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-2">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
                    @include('backend.elements.notification')
                    <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nama Unit</th>
                                <th>Tanggal</th>
                                <th>Kode Barcode</th>
                                <th>Nama Barang</th>
                                <th>Jumlah (Pcs)</th>
                                <th>Harga Jual</th>
                                <th>Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $grandTotal = 0;
                                $subTotal = 0;
                                $currentDate = null;

                                foreach ($data as $item):
                                    // Jika tanggal berubah atau belum diatur, tampilkan subtotal dan reset subtotal
                                    if ($currentDate !== null && $currentDate != $item->tanggal):
                            ?>
                                    <tr>
                                        <td colspan="6" align="right"><strong>Subtotal tanggal <?= $currentDate; ?> :</strong></td>
                                        <td><strong>Rp. <?= number_format($subTotal, 0, ',', '.'); ?></strong></td>
                                    </tr>
                            <?php
                                        // Reset subtotal setelah ditampilkan
                                        $subTotal = 0;
                                    endif;

                                    // Tampilkan data barang
                            ?>
                                <tr>
                                    <td><?= $item->nama_unit; ?></td>
                                    <td><?= $item->tanggal; ?></td>
                                    <td><?= $item->kode; ?></td>
                                    <td><?= $item->nama; ?></td>
                                    <td><?= $item->jumlah; ?></td>
                                    <td><?= number_format($item->harga, 0, ',', '.'); ?></td>
                                    <td><?= number_format($item->total, 0, ',', '.'); ?></td>
                                </tr>
                            <?php
                                    // Tambahkan total per item ke subtotal dan grand total
                                    $subTotal += $item->total;
                                    $grandTotal += $item->total;
                                    $currentDate = $item->tanggal;
                                endforeach;

                                // Tampilkan subtotal untuk tanggal terakhir
                                if ($currentDate !== null):
                            ?>
                                <tr>
                                    <td colspan="6" align="right"><strong>Subtotal tanggal <?= $currentDate; ?> :</strong></td>
                                    <td><strong>Rp. <?= number_format($subTotal, 0, ',', '.'); ?></strong></td>
                                </tr>
                            <?php endif; ?>

                            <!-- Grand Total Keseluruhan -->
                            <tr>
                                <td colspan="6" align="right"><h4>Grand Total :</h4></td>
                                <td><h4>Rp. <?= number_format($grandTotal, 0, ',', '.'); ?></h4></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
				</div>
			</div>
		</div>					
	</div>
@endsection

<!-- CSS -->
@section('css')

@endsection

<!-- JAVASCRIPT -->
@section('script')

@endsection