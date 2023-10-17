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
    <button class="btn btn-sm btn-info" id="btn-sinkron" disabled><i class="fa fa-gears"></i> Sinkron Data </button> 
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
	<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
                @include('backend.elements.notification')
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:5%">No.</th>
                                    <th>Uraian Penerimaan</th>
                                    <th style="text-align:right">Jumlah</th>
                                </tr>
                            </thead>
                            @if($unit_select == 4)
                            <tbody>
                                <tr>
                                	<td>1.</td>
                                    <td>Tingkat KB</td>
                                    @foreach ($data_kb as $dkb)
                                    <td id="satu" value="{{$dkb->total}}" style="text-align:right"><?=number_format($dkb->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
								<tr>
                                	<td>2.</td>
                                    <td>Tingkat TK</td>
                                    @foreach ($data_tk as $dtk)
                                    <td id="dua" value="{{$dtk->total}}" style="text-align:right"><?=number_format($dtk->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
								<tr>
                                	<td>3.</td>
                                    <td>Tingkat SD</td>
                                    @foreach ($data_sd as $dsd)
                                    <td id="tiga" value="{{$dsd->total}}" style="text-align:right"><?=number_format($dsd->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
								<tr>
                                	<td>4.</td>
                                    <td>Tingkat SMP</td>
                                    @foreach ($data_smp as $dsmp)
                                    <td id="empat" value="{{$dsmp->total}}" style="text-align:right"><?=number_format($dsmp->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
                                <tr>
                                	<td>5.</td>
                                    <td>Tingkat SMA</td>
                                    @foreach ($data_sma as $dsma)
                                    <td id="empat" value="{{$dsma->total}}" style="text-align:right"><?=number_format($dsma->total,0,',','.');?></td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td colspan=5 align=right>
                                        <h4>Grand Total : Rp. <?=number_format($grand_total_ppdb,0,',','.');?></h4>
                                    </td>
                                </tr>
                            </tbody>    
                            @else
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
                            @endif
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