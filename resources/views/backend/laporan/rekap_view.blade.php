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
        "5" => 'Perlengkapan Tera',
        "8" => 'Kangen Water'
    );
    $kategori_select = request('kategori');

    $bulan = array(
        "1" => 'Januari',
        "2" => 'Februari',
        "3" => 'Maret',
        "4" => 'April',
        "5" => 'Mei',
        "6" => 'Juni',
        "7" => 'Juli',
        "8" => 'Agustus',
        "9" => 'September',
        "10" => 'Oktober',
        "11" => 'November',
        "12" => 'Desember'
    );
    $bulan_select = request('bulan') ?: (int)date('m');

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
            <h3>Laporan Penjualan Kategori</h3>
        </div>
        <div class="title_right">
            <div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    @include('backend.elements.breadcrumb', ['breadcrumb' => $breadcrumb])
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
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    @include('backend.elements.notification')
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Barcode</th>
                                    <th>Nama Barang</th>
                                    <th style="text-align: right;">Jumlah</th>
                                    <th style="text-align: right;">Harga Jual</th>
                                    <th style="text-align: right;">Total Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $groupedData = $data->groupBy('tanggal'); // Mengelompokkan data berdasarkan tanggal
                                $grandTotal = 0; 
                                ?>
                                @foreach ($groupedData as $tanggal => $items)
                                    <?php $subTotal = 0; ?>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>{{ $item->kode }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td align="right">{{ $item->jumlah }}</td>
                                            <td align="right">{{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td align="right">{{ number_format($item->total, 0, ',', '.') }}</td>
                                        </tr>
                                        <?php $subTotal += $item->total; ?>
                                    @endforeach
                                    <tr>
                                        <td colspan="5" align="right"><strong>Subtotal ({{ $tanggal }}):</strong></td>
                                        <td align="right"><strong>{{ number_format($subTotal, 0, ',', '.') }}</strong></td>
                                    </tr>
                                    <?php $grandTotal += $subTotal; ?>
                                @endforeach
                                <tr>
                                    <td colspan="5" align="right"><h4>Grand Total:</h4></td>
                                    <td align="right"><h4>Rp. {{ number_format($grandTotal, 0, ',', '.') }}</h4></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Export Excel -->
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel" style="background-color: #eab676;">
                <div class="x_content">
                    <h2>Laporan Export Excel</h2>
                    <form id="form-work" class="form-horizontal" action="{{ url('/backend/user/export/xls') }}" method="GET">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-1" style="margin-top:7px;">Periode</div>
                            <div class="col-xs-12 col-sm-2">
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
                                <button type="submit" class="btn btn-primary btn-block">Export Excel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
