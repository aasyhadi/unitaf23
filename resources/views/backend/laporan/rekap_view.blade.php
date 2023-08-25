<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Rekap Penjualan';
	$breadcrumb[1]['url'] = url('backend/rekap-penjualan');

    $kategori = array( 
		"0"=>"",
		"1"=>'Minuman & Ice Cream', 
		"2"=>'Alat Tulis Sekolah', 
		"3"=>'Seragam & Perlengkapan Sekolah'); 
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

?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Penjualan')

<!-- CONTENT -->
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Rekap Penjualan</h3>
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
                        <div class="col-xs-12 col-sm-1" style="margin-top:7px;">
                            Periode 
                        </div>
                        <div class="col-xs-12 col-sm-5">
                            <select class="form-control" name="bulan" id="bulan" required="required">
							@if(count($bulan))
                                <?php $n = 0;?>
								@foreach($bulan as $b)
									<option value="{{$n}}" @if($bulan_select==$n) selected="selected" @endif>
										@if($n>0) Bulan @else Pilih Kategori... @endif {{$b}}
									</option>
									<?php $n++;?>
								@endforeach
							@endif
							</select>
                        </div>

                            <div class="col-xs-12 col-sm-2" style="margin-top:7px;">
                                Kategori Barang
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <select class="form-control" name="kategori" id="kategori" required="required">
									@if(count($kategori))
									    <?php $n = 0;?>
									    @foreach($kategori as $k)
										<option value="{{$n}}" @if($kategori_select==$n) selected="selected" @endif>
											@if($n>0) Kategori @else Pilih Kategori... @endif {{$k}}
										</option>
										<?php $n++;?>
										@endforeach
									@endif
								</select>
                            </div>
                        </div>
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
                                    <th>Kode Barcode</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Jual</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $total = 0;
                                    foreach ($data as $item):
                                ?>
                                    <tr>
                                       
                                        <td><?=$item->kode;?></td>
                                        <td><?=$item->nama;?></td>
                                        <td><?=$item->jumlah;?></td>
                                        <td><?=number_format($item->harga,0,',','.');?></td>
                                        <td><?=number_format($item->total,0,',','.');?></td>
                                    </tr>
                                <?php
                                        $total += $item->total;
                                    endforeach;
                                ?>
                                    <tr>
                                        <td colspan=5 align=right>
                                            <h4>Grand Total : Rp. <?=number_format($total,0,',','.');?></h4>
                                        </td>
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
    <script type="text/javascript">
        $(function(){		 
            
            $("#kategori").on('change', function(){
                $val_bulan = $('#bulan').val();
                $val_kategori = $('#kategori').val()
                if($val_bulan !='' && $val_kategori !=''){
                    /* $tabel1.ajax.url("{{url('laporan-jadwal-kunjungan-tim/dt')}}/"+$bulan+'/'+$kecamatan).load(); */
                    window.location = '{{url("backend/report-rekap-penjualan")}}/'+$val_kategori+'/'+$val_bulan;
                }
            })

            $("#bulan").on('change', function(){
                $val_bulan = $('#bulan').val();
                $val_kategori = $('#kategori').val()
                if($val_bulan !='' && $val_kategori !=''){
                    /* $tabel1.ajax.url("{{url('laporan-jadwal-kunjungan-tim/dt')}}/"+$bulan+'/'+$kecamatan).load(); */
                    window.location = '{{url("backend/report-rekap-penjualan")}}/'+$val_kategori+'/'+$val_bulan;
                }
            })
        })
    </script>

@endsection