<?php
    $breadcrumb = [];
    $breadcrumb[0]['title'] = 'Dashboard';
    $breadcrumb[0]['url'] = url('backend/dashboard');
    $breadcrumb[1]['title'] = 'Rekap Penjualan';
    $breadcrumb[1]['url'] = url('backend/rekap-penjualan');

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
            <h3>Rekap Penjualan</h3>
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
                                    <th>Kode Barcode</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Jual</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                                    </tr>
                                    <?php $total += $item->total; ?>
                                @endforeach
                                <tr>
                                    <td colspan="5" align="right">
                                        <h4>Grand Total : Rp. {{ number_format($total, 0, ',', '.') }}</h4>
                                    </td>
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
