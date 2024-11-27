<?php
    $breadcrumb = [];
    $breadcrumb[0]['title'] = 'Dashboard';
    $breadcrumb[0]['url'] = url('backend/dashboard');
    $breadcrumb[1]['title'] = 'Laporan Mutasi Stok';
    $breadcrumb[1]['url'] = url('backend/report-mutasi-stok');
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Mutasi Stok')

<!-- CONTENT -->
@section('content')
<div class="page-title">
    <div class="title_left">
        <h3>Laporan Mutasi Stok</h3>
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
                <!-- Form Input ID Barang -->
                <form method="GET" action="{{ url('backend/report-mutasi-stok') }}" class="form">
                    <div class="form-group row">
                        <label for="id_bahan_baku" class="col-sm-2 col-form-label" style="margin-top:7px;">ID Barang:</label>
                        <div class="col-sm-8">
                            <input 
                                type="text" 
                                name="id_bahan_baku" 
                                id="id_bahan_baku" 
                                value="{{ $id_barang ?? '' }}" 
                                class="form-control" 
                                placeholder="Input ID Barang" 
                                required
                            >
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Informasi Barang -->
@if (!empty($data) && $data->count())
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <h4>Informasi Barang</h4>
                    <p><strong>Nama Barang:</strong> {{ $data->first()->nama ?? '-' }}</p>
                    <p><strong>Total Stok:</strong> <span style="background-color: #8aff80; padding: 5px; border-radius: 3px;">{{ number_format($data->first()->stok_total ?? 0, 0, ',', '.') }}</span></p>
                    <p><strong>Stok Awal:</strong> {{ number_format($data->first()->stok_awal ?? 0, 0, ',', '.') }}</p>
                    <p><strong>Lokasi Barang:</strong> - </p>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Tabel Mutasi Stok -->
@if (!empty($data) && $data->count())
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <h4>Tabel Mutasi Stok</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Tanggal</th>
                                    <th style="text-align: center;">Keterangan</th>
                                    <th style="text-align: center;">Type</th>
                                    <th style="text-align: center;">Jumlah</th>
                                    <th style="text-align: center;">Mutasi Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td align="center">{{ $item->tanggal }}</td>
                                        <td align="center">{{ $item->keterangan }}</td>
                                        <td align="center">{{ $item->type }}</td>
                                        <td align="center">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                        <td align="center">{{ number_format($item->mutasi_stok, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-right">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <p class="text-center">Tidak ada data untuk ID Barang yang dipilih.</p>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

<!-- CSS -->
@section('css')

@endsection

<!-- JAVASCRIPT -->
@section('script')
    <script>
        $(document).ready(function () {
            $('#myDatepicker').datetimepicker({ format: 'DD-MM-YYYY' });
            $('#myDatepicker2').datetimepicker({ format: 'DD-MM-YYYY' });
        });
    </script>
@endsection
