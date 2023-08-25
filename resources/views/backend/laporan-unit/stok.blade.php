<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Laporan Stok';
	$breadcrumb[1]['url'] = url('backend/report-stok');

    $id_unit = array( 
		"0"=>"",
		"1"=>'Kampus Mesjid Agung', 
		"2"=>'Kampus Simpang Kawat SD', 
		"3"=>'Kampus Simpang Kawat SMP SMA',
        "4"=>'PPDB'); 
	$unit_select = request('id_unit');

?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Stok')

<!-- CONTENT -->
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Laporan Stok</h3>
        </div>
        <div class="title_right">
            <div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
    @include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
    <div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
                <form id="form-work" class="form-horizontal" role="form" autocomplete="off" method="GET">
                {!! csrf_field() !!}
                    <div class="col-xs-12 col-sm-1" style="margin-top:7px;">
                        Unit Usaha
                    </div>
                    <div class="col-xs-12 col-sm-7">
                        <select class="form-control" name="id_unit">
                            @if(count($id_unit))
                                <?php $n = 0;?>
                                @foreach($id_unit as $u)
                                    <option value="{{$n}}" @if($unit_select==$n) selected="selected" @endif>
                                        @if($n>0) Unit @else Pilih Unit... @endif {{$u}}
                                    </option>
                                    <?php $n++;?>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-2">
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
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
                                    <th>ID</th>
                                    <th>Kode / Barcode</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Awal</th>
                                    <th>Stok Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($data as $item):
                                ?>
                                    <tr>
                                        <td><?=$item->id;?></td>
                                        <td><?=$item->kode;?></td>
                                        <td><?=$item->nama;?></td>
                                        <td><?=number_format($item->stok_awal,0,',','.');?></td>
                                        <td><?=number_format($item->stok_total,0,',','.');?></td>
                                    </tr>
                                <?php
                                    endforeach;
                                ?>
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
    <script>
        $('#myDatepicker').datetimepicker({
            format: 'DD-MM-YYYY'
        });

        $('#myDatepicker2').datetimepicker({
            format: 'DD-MM-YYYY'
        });

        $("#id_unit").on('change', function(){
            window.location = '{{url("backend/report-stok-unit")}}';
        })

	</script>
@endsection