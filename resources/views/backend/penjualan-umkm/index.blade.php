<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Penjualan UMKM';
	$breadcrumb[1]['url'] = url('backend/penjualan-umkm');

    if (isset($data)){
		$breadcrumb[2]['title'] = 'Edit';
		$breadcrumb[2]['url'] = url('backend/penjualan-umkm/'.$data[0]->id.'/edit');
	}
    
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Penjualan UMKM')

<!-- CONTENT -->
@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>Penjualan UMKM</h3>
        </div>
        <div class="title_right">
            <div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
                @include('backend.elements.create_button',array('url' => '/backend/penjualan-umkm/create'))
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
                                Status 
                            </div>
                            <div class="col-xs-12 col-sm-5">
                                <select name="status" class="form-control">
                                    <?php
                                        $selected = "";
                                        if ($status == "0"){
                                            $selected = "selected";
                                        }
                                    ?>
                                    <option value="0" <?=$selected;?>>All</option>
                                    <?php
                                        $selected = "";
                                        if ($status === "keep"){
                                            $selected = "selected";
                                        }
                                    ?>
                                    <option value="keep" <?=$selected;?>>Keep</option>
                                    <?php
                                        $selected = "";
                                        if ($status === "pull"){
                                            $selected = "selected";
                                        }
                                    ?>
                                    <option value="pull" <?=$selected;?>>Pull</option>
                                </select>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
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
                    <table class="table table-striped table-hover table-bordered dt-responsive nowrap dataTable" cellspacing="0" width="100%">
						<thead>
							<tr>
                                <th>ID</th>
                                <th>No Nota</th>
                                <th>Tanggal</th>
                                <th>Pembayaran</th>
                                <th>Bagi Hasil</th>
                                <th>Status</th>
                                <th>Actions</th>
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
			ajax: "<?=url('backend/penjualan-umkm/datatable?status='.$status.'&startDate='.$startDate.'&endDate='.$endDate.'&mode='.$mode);?>",
			columns: [
				{data: 'id', name: 'id'},
                {data: 'no_inv', name: 'no_inv'},
				{data: 'tanggal', name: 'tanggal'},
                {data: 'total', name: 'total'},
                {data: 'bagi_hasil', name: 'bagi_hasil'},
				{data:  'status', render: function ( data, type, row ) {
					var text = "";
					var label = "";
					if (data == "keep"){
						text = "Keep";
						label = "info";
					} else 
					if (data == "pull"){
						text = "Pull";
						label = "success";
					}
					return "<span class='badge badge-" + label + "'>"+ text + "</span>";
                }},				
				{data: 'action', name: 'action', orderable: false, searchable: false}
			],
            order: [[0, 'desc']],
			responsive: true
		});

        $('#myDatepicker').datetimepicker({
            format: 'DD-MM-YYYY'
        });

        $('#myDatepicker2').datetimepicker({
            format: 'DD-MM-YYYY'
        });
	</script>
@endsection