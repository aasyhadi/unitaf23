<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Laporan Penjualan Jajanan';
	$breadcrumb[1]['url'] = url('backend/report-umkm');

    $id_unit = array( 
		"0"=>""
		/* "1"=>'Kampus Mesjid Agung', 
		"2"=>'Kampus Simpang Kawat SD', 
		"3"=>'Kampus Simpang Kawat SMP SMA'*/);  
	$unit_select = request('id_unit');

?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Penjualan Jajanan')

<!-- CONTENT -->
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Laporan Penjualan Jajanan</h3>
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
                                Tanggal
                            </div>
                            <div class="col-xs-12 col-sm-3 date">
                                <div class='input-group date' id='myDatepicker'>
                                    <input type='text' class="form-control" name="startDate" value=<?=$startDate;?> />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 date">
                                <div class='input-group date' id='myDatepicker2'>
                                    <input type='text' class="form-control" name="endDate" value=<?=$endDate;?> />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 text-right">
                                <?php
                                    $checked = "";
                                    if ($mode == "all"){
                                        $checked = "checked";
                                    }
                                ?>
                                <div class="checkbox">
                                    <input type="checkbox" name="mode" value="all" id="show-all" <?=$checked;?>>Tampilkan semua
                                </div>
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
                                    <th>ID</th>
                                    <th>No Nota</th>
                                    <th>Tanggal</th>
                                    <th>Nama Unit</th>
                                    <th style="text-align: right;">Pembayaran</th>
                                    <th style="text-align: right;">Bagi Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $grandTotalBagiHasil = 0; // Total semua bagi hasil
                                    $subTotalBagiHasil = 0; // Total bagi hasil per tanggal
                                    $currentDate = null; // Menyimpan tanggal yang sedang diproses

                                    foreach ($data as $item):
                                        // Jika tanggal berubah atau belum diatur, tampilkan subtotal bagi hasil
                                        if ($currentDate !== null && $currentDate != $item->tanggal):
                                ?>
                                        <tr>
                                            <td colspan="5" align="right"><strong>Subtotal Bagi Hasil Tanggal <?= date('d M Y', strtotime($currentDate)); ?>:</strong></td>
                                            <td align="right"><strong>Rp. <?= number_format($subTotalBagiHasil, 0, ',', '.'); ?></strong></td>
                                        </tr>
                                <?php
                                            // Reset subtotal setelah ditampilkan
                                            $subTotalBagiHasil = 0;
                                        endif;

                                        // Menampilkan data baris
                                ?>
                                        <tr>
                                            <td><?= $item->id; ?></td>
                                            <td><b><a href="<?= url('backend/penjualan-umkm/'.$item->id); ?>" target="_blank"><?= $item->no_inv; ?></a></b></td>
                                            <td><?= date('d M Y', strtotime($item->tanggal)); ?></td>
                                            <td><?= $item->nama_unit; ?></td>
                                            <td align="right"><?= number_format($item->total, 0, ',', '.'); ?></td>
                                            <td align="right"><?= number_format($item->bagi_hasil, 0, ',', '.'); ?></td>
                                        </tr>
                                <?php
                                        // Menambahkan total per item ke subtotal dan grand total
                                        $subTotalBagiHasil += $item->bagi_hasil;
                                        $grandTotalBagiHasil += $item->bagi_hasil;
                                        $currentDate = $item->tanggal;
                                    endforeach;

                                    // Tampilkan subtotal untuk tanggal terakhir
                                    if ($currentDate !== null):
                                ?>
                                    <tr>
                                        <td colspan="5" align="right"><strong>Subtotal Bagi Hasil Tanggal <?= date('d M Y', strtotime($currentDate)); ?>:</strong></td>
                                        <td align="right"><strong>Rp. <?= number_format($subTotalBagiHasil, 0, ',', '.'); ?></strong></td>
                                    </tr>
                                <?php endif; ?>

                                <!-- Grand Total -->
                                <tr>
                                    <td colspan="5" align="right"><h4>Grand Total Bagi Hasil:</h4></td>
                                    <td align="right"><h4>Rp. <?= number_format($grandTotalBagiHasil, 0, ',', '.'); ?></h4></td>
                                </tr>
                                </tbody>
                        </table>
                    </div>
				</div>
			</div>
		</div>					
	</div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel" style="background-color: #eab676;">
                <div class="x_content">
                    <h2>Laporan Export Excel</h2>
                    <form id="form-work" class="form-horizontal" action="{{ url('/backend/user/export/xls') }}" role="form" autocomplete="off" method="GET" >
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
                                Tanggal
                            </div>
                            <div class="col-xs-12 col-sm-3 date">
                                <div class='input-group date' id='myDatepicker'>
                                    <input type='text' class="form-control" name="startDate" value=<?=$startDate;?> />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3 date">
                                <div class='input-group date' id='myDatepicker2'>
                                    <input type='text' class="form-control" name="endDate" value=<?=$endDate;?> />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 text-right">
                                <?php
                                    $checked = "";
                                    if ($mode == "all"){
                                        $checked = "checked";
                                    }
                                ?>
                                <div class="checkbox">
                                    <input type="checkbox" name="mode" value="all" id="show-all" <?=$checked;?>>Tampilkan semua
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2">
                                <button type="submit" class="btn btn-primary btn-block">Export Excel</button>
                            </div>
                        </div>
                    </form>
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
	</script>
@endsection