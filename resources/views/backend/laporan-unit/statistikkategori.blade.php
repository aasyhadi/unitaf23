<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Statistik Kategori';
	$breadcrumb[1]['url'] = url('backend/');

    $semester = array( 
		"0"=>"",
		"1"=>'Semester Ganjil', 
		"2"=>'Semester Genap'); 
	$semester_select = request('semester');

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
            <h3>Statistik Penjualan Kategori</h3>
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
                            Periode 
                        </div>
                        <div class="col-xs-12 col-sm-2">
                            <select class="form-control" name="semester" id="semester" required="required">
							@if(count($semester))
                                <?php $n = 0;?>
								@foreach($semester as $b)
									<option value="{{$n}}" @if($semester_select==$n) selected="selected" @endif>
										@if($n>0) @else Pilih Semester... @endif {{$b}}
									</option>
									<?php $n++;?>
								@endforeach
							@endif
							</select>
                        </div>

                        <div class="col-xs-12 col-sm-2">
                            <select class="form-control" name="tahun" id="tahun" required="required">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $year == $activeYear ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
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
                        <table id="table-statistik" class="table table-striped table-hover table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    @foreach($bulan as $b)
                                        <th style="text-align: right;">{{ $b }}</th>
                                    @endforeach
                                    <th style="text-align: right;">Total Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>{{ $row->nama_kategori }}</td>
                                        @foreach($bulan as $key => $b)
                                            <td align="right">
                                                {{ number_format($row->{'sale_' . strtolower($b)}, 0) ?? 0 }}
                                            </td>
                                        @endforeach
                                        <td align="right">{{ number_format($row->total_penjualan, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Grafik Penjualan -->
                <h3 class="text-center">Grafik Penjualan per Kategori</h3>
                <canvas id="storeSalesChart" ></canvas>

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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = {!! json_encode(array_column($data, 'nama_kategori')) !!};
        const salesData = [
            @foreach ($data as $row)
                {{ $row->total_penjualan }},
            @endforeach
        ];

        const ctx = document.getElementById('storeSalesChart').getContext('2d');
        const storeSalesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, // Nama kategori
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: salesData, // Total penjualan
                    backgroundColor: labels.map(() => 'rgba(54, 162, 235, 0.2)'), // Warna dinamis
                    borderColor: labels.map(() => 'rgba(54, 162, 235, 1)'), // Border warna
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                return 'Rp ' + tooltipItem.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function (value) {
                                return labels[value]; // Pastikan label sesuai indeks
                            },
                            maxRotation: 90, // Rotasi maksimal
                            minRotation: 90  // Rotasi minimal
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>

@endsection