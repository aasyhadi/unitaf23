<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Laporan Penjualan Rekap Kategori';
	$breadcrumb[1]['url'] = url('backend/report-rekap-kategori');

    $kategori = array(
        "1" => 'Minuman & Ice Cream',
        "2" => 'Alat Tulis Sekolah',
        "3" => 'Seragam & Perlengkapan Sekolah',
        "5" => 'TERA',
        "8" => 'Kangen Water'
    );
    $kategori_select = request('kategori');

?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Penjualan Kategori Bulanan')

<!-- CONTENT -->
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Laporan Penjualan Kategori Bulanan</h3>
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
                                Tanggal
                            </div>
                            <div class="col-xs-12 col-sm-2 date">
                                <div class='input-group date' id='myDatepicker'>
                                    <input type='text' class="form-control" name="startDate" value=<?=$startDate;?> />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-2 date">
                                <div class='input-group date' id='myDatepicker2'>
                                    <input type='text' class="form-control" name="endDate" value=<?=$endDate;?> />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-1" style="margin-top:7px;">Kategori</div>
                            <div class="col-xs-12 col-sm-2">
                                <select class="form-control" name="kategori" id="kategori" required="required">
                                    <option value="">Pilih Kategori...</option>
                                        @foreach($kategori as $key => $value)
                                            <option value="{{ $key }}" {{ $kategori_select == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
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
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Kode</th>
                                    <th style="text-align: center;">Nama Barang</th>
                                    <th style="text-align: right;">Jumlah</th>
                                    <th style="text-align: right;">Harga</th>
                                    <th style="text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grandTotal = 0;
                                @endphp
                                @foreach ($data as $item)
                                <tr>
                                    <td align="center">{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td align="right">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    <td align="right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td align="right">{{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                                @php
                                    $grandTotal += $item->total;
                                @endphp
                                @endforeach
                                <tr>
                                    <td colspan="4" style="text-align: right; font-weight: bold;">Grand Total:</td>
                                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
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
    <script>
        $('#myDatepicker').datetimepicker({
            format: 'DD-MM-YYYY'
        });

        $('#myDatepicker2').datetimepicker({
            format: 'DD-MM-YYYY'
        });
	</script>
@endsection