<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Laporan Harian';
	$breadcrumb[1]['url'] = url('backend/report-harian');

	$unit_id = Session::get('userinfo')['id_unit'];
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Laporan Harian')

<!-- CONTENT -->
@section('content')
<div class="page-title">
    <div class="title_left">
        <h3>Laporan Harian</h3>
    </div>
    <div class="title_right">
        <div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
        </div>
    </div>
</div>
<div class="clearfix"></div>
@include('backend.elements.breadcrumb', ['breadcrumb' => $breadcrumb])

<div class="row">
    <div class="col-sm-12">
        <div class="x_panel">
            <div class="x_content">
                <!-- Form Filter Tanggal -->
                <form method="GET" action="{{ url('backend/report-harian') }}" class="form-inline mb-4">
                    <div class="form-group row align-items-center">
                        <label for="tanggal" class="col-sm-4 col-form-label" style="margin-top:7px;">Tanggal</label>
                        <div class="col-sm-6">
                            <input 
                                type="date" 
                                name="tanggal" 
                                id="tanggal" 
                                value="{{ $tanggal }}" 
                                class="form-control w-100"
                                required
                            >
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
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
                <!-- Tabel Data -->
                @if ($data->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Tanggal</th>
                                    <th style="text-align: center;">Nama Kategori</th>
                                    <th style="text-align: center;">Total Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grandTotal = $data->sum('total_per_kategori'); // Menghitung Grand Total
                                @endphp
                                @foreach ($data as $item)
                                    <tr>
                                        <td align="center">{{ $item->tanggal }}</td>
                                        <td align="center">{{ $item->nama_kategori }}</td>
                                        <td align="center">{{ number_format($item->total_per_kategori, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <!-- Baris Grand Total -->
                                <tr>
                                    <td colspan="2" align="right"><strong>Grand Total:</strong></td>
                                    <td align="center"><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">Tidak ada data untuk tanggal {{ $tanggal }}.</p>
                @endif
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