<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Penjualan';
	$breadcrumb[1]['url'] = url('backend/penjualan-barcode');
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Penjualan')

<!-- CONTENT -->
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Penjualan Barcode</h3>
        </div>
        <div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
                @include('backend.elements.create_button',array('url' => '/backend/penjualan-barcode/create'))
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
    @include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					@include('backend.elements.notification')
                    <table class="table table-striped table-hover table-bordered dt-responsive nowrap dataTable" cellspacing="0" width="100%">
						<thead>
							<tr>
                                <th style="text-align: center;">ID</th>
                                <th style="text-align: center;">No Nota</th>
                                <th style="text-align: center;">Tanggal</th>
                                <th style="text-align: center;">Total</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
						</thead>
					</table>
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
		$('.dataTable').dataTable({
			processing: true,
			serverSide: true,
			ajax: "<?=url('backend/penjualan-barcode/datatable');?>",
			columns: [
				{data: 'id', name: 'id', className: 'text-center'},
                {data: 'no_nota', name: 'no_nota', className: 'text-center'},
				{data: 'tanggal', name: 'tanggal', className: 'text-center'},
                {data: 'total', name: 'total', className: 'text-center'},
				{data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
			],
			order: [[0, 'desc']],
			responsive: true
		});
	</script>
@endsection