<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Rekap Penerimaan';
	$breadcrumb[1]['url'] = url('backend/rekap-penerimaan');

	$unit = array( 
		"0"=>"",
		"1"=>'Kampus Mesjid Agung', 
		"2"=>'Kampus Simpang Kawat SD', 
		"3"=>'Kampus Simpang Kawat SMP SMA',
		"4"=>'PPDB'); 
	$unit_select = request('unit');

 	if(!$list_unit){
		$list_unit = 1;
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
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
    @include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	<button class="btn btn-sm btn-info" id="btn-sinkron"><i class="fa fa-gears"></i> Sinkron Data </button> :: Menarik data dari masing-masing unit.
	@include('backend.elements.notification')
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_content">
					<div class="row">
                        <div class="col-xs-12 col-sm-1" style="margin-top:7px;">
                            Periode 
                        </div>
                        <div class="col-xs-12 col-sm-11">
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
                        </div><br><br><br>

						<div class="col-xs-12 col-sm-1" style="margin-top:7px;">
                                Unit Usaha
                        </div>
                        <div class="col-xs-12 col-sm-7">
                            <select class="form-control" name="unit" id="unit" required="required">
									@if(count($unit))
									    <?php $n = 0;?>
									    @foreach($unit as $u)
										<option value="{{$n}}" @if($unit_select==$n) selected="selected" @endif>
											@if($n>0) Unit @else Pilih Unit... @endif {{$u}}
										</option>
										<?php $n++;?>
										@endforeach
									@endif
							</select>
                        </div><br><br><br>
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
            
            $("#unit").on('change', function(){
                $val_bulan = $('#bulan').val();
                $val_unit = $('#unit').val()
                if($val_bulan !='' && $val_unit !=''){
                    /* $tabel1.ajax.url("{{url('laporan-jadwal-kunjungan-tim/dt')}}/"+$bulan+'/'+$kecamatan).load(); */
                    window.location = '{{url("backend/rekap-penerimaan-unit")}}/'+$val_unit+'/'+$val_bulan;
                }
            })

            $("#bulan").on('change', function(){
                $val_bulan = $('#bulan').val();
                $val_unit = $('#unit').val()
                if($val_bulan !='' && $val_unit !=''){
                    /* $tabel1.ajax.url("{{url('laporan-jadwal-kunjungan-tim/dt')}}/"+$bulan+'/'+$kecamatan).load(); */
                    window.location = '{{url("backend/rekap-penerimaan-unit")}}/'+$val_unit+'/'+$val_bulan;
                }
            })


        })

        $(function(){
			$("#btn-sinkron").on('click', function(){
				$.get("{{url('backend/rekapan/sinkron-unit')}}",function(){
                    window.location = '{{url("backend/rekapan/sinkron-unit")}}';
                });
                
			})
		})

</script>
@endsection