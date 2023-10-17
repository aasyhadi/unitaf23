<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Rekap Penerimaan';
	$breadcrumb[1]['url'] = url('backend/rekap-penerimaan');

	$unit_id = Session::get('userinfo')['id_unit'];

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
@section('title', 'Penerimaan')

<!-- CONTENT -->
@section('content')
<div class="page-title">
        <div class="title_left">
            <h3>Rekap Penerimaan </h3>
        </div>
        <div class="title_right">
            <div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
                @include('backend.elements.back_button',array('url' => '/backend/rekap-penerimaan'))
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
    @include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
    <button class="btn btn-sm btn-info" id="btn-hitung" disabled><i class="fa fa-gears"></i> Hitung Semua </button>
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
										@if($n>0) Bulan @else Pilih Periode... @endif {{$b}}
									</option>
									<?php $n++;?>
								@endforeach
							@endif
							</select>
                            <input type="hidden" id="unit" name="unit" value="{{$unit_id}}">
                        </div>

						<div class="col-xs-12 col-sm-2" style="margin-top:0px;">
							<button type="submit" class="btn btn-primary btn-block" id="btn-sinkron">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
            @include('backend.elements.notification')
                    
				<div class="x_content">
                     <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:5%">No.</th>
                                    <th>Uraian</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                	<td>1.</td>
                                    <td>Minuman & Ice Cream</td>
                                    @foreach ($data_minuman as $dm)
                                    <td id="satu" value="{{$dm->total}}" style="text-align:right"><?=number_format($dm->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
								<tr>
                                	<td>2.</td>
                                    <td>Alat Tulis Sekolah</td>
                                    @foreach ($data_alat_tulis as $dat)
                                    <td id="dua" value="{{$dat->total}}" style="text-align:right"><?=number_format($dat->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
								<tr>
                                	<td>3.</td>
                                    <td>Seragam & Perlengkapan Sekolah</td>
                                    @foreach ($data_seragam as $ds)
                                    <td id="tiga" value="{{$ds->total}}" style="text-align:right"><?=number_format($ds->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
								<tr>
                                	<td>4.</td>
                                    <td>Bagi Hasil Titipan UMKM</td>
                                    @foreach ($data_umkm as $du)
                                    <td id="empat" value="{{$du->total}}" style="text-align:right"><?=number_format($du->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
                                <tr>
                                	<td>5.</td>
                                    <td>Seragam TERA</td>
                                    @foreach ($data_tera as $dr)
                                    <td id="lima" value="{{$dr->total}}" style="text-align:right"><?=number_format($dr->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
                                <tr>
                                	<td>6.</td>
                                    <td>Kangen Water</td>
                                    @foreach ($data_kangen as $dk)
                                    <td id="enam" value="{{$dk->total}}" style="text-align:right"><?=number_format($dk->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td colspan=5 align=right>
                                        <h4>Grand Total : Rp. <?=number_format($grand_total,0,',','.');?></h4>
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
			$("#btn-sinkron").on('click', function(){
				$.get("{{url('backend/rekapan/sinkron')}}",function(){
					$val_bulan = $('#bulan').val();
					$val_unit = $('#unit').val()
					if($val_bulan !='' && $val_unit !=''){
						window.location = '{{url("backend/rekap-penerimaan")}}/'+$val_unit+'/'+$val_bulan;
					} 
                });
			})

            $("#btn-hitung").on('click', function(){
				$.get("{{url('backend/rekapan/sinkron')}}",function(){
					window.location = '{{url("backend/rekapan/sinkron")}}';
                });
			})
		})
</script>
@endsection