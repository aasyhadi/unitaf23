<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Penjualan UMKM';
    $breadcrumb[1]['url'] = url('backend/penjualan-umkm');
	if (isset($data)){
		$breadcrumb[2]['title'] = $data[0]->no_inv;
		$breadcrumb[2]['url'] = url('backend/penjualan-umkm/'.$data[0]->id.'/edit');
	}

   // var_dump($detail);

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
                <a href="<?=url('/backend/penjualan-umkm');?>" class="btn-index btn btn-primary btn-block" title="Back"><i class="fa fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
	<div class="clearfix"></div>
	@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<div class="row">
                        <div class="col-xs-12">
                            <h4>Penjualan UMKM</h4>
                            No INV : <b><?=$data[0]->no_inv;?></b><br/>
                            Tanggal : <b><?=date('d F Y', strtotime($data[0]->tanggal))?></b><br/>
                            Pembayaran : <b>Rp. <?=number_format($data[0]->total, 0, ',','.');?></b><br/>
                            Hasil Bagi : <b>Rp. <?=number_format($data[0]->bagi_hasil, 0, ',','.');?></b><br/>
                            <?php
                                if ($data[0]->status == "keep"){
                                    $text = "Keep";
                                    $label = "info";
                                } else 
                                if ($data[0]->status == "pull"){
                                    $text = "Pull";
                                    $label = "success";
                                }
                            ?>
                            Status : <?="<span class='badge badge-" . $label . "'>". $text . "</span>";?><br/>
                            Keterangan : <?=nl2br($data[0]->keterangan);?><br/>
                            <br/>
                        </div>
                    </div>
                    <table class="table table-striped table-hover table-bordered dt-responsive nowrap dataTable" cellspacing="0" width="100%">
						<thead>
							<tr>
                                <th>ID / Nama UMKM</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Sisa</th>
                                <th>Harga Keep</th>
                                <th>Harga Jual</th>
                                <th>Pembayaran</th>
                                <th>Bagi Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($detail as $detail):
                            ?>
                                <tr>
                                    <td><?=$detail->id_supplier . ' - ' . $detail->supplier->nama;?></td>
                                    <td><?=$detail->barang->nama;?></td>
                                    <td><?=number_format($detail->jumlah,0,',','.');?></td>
                                    <td><?=number_format($detail->sisa,0,',','.');?></td>
                                    <td><?=number_format($detail->harga_keep,0,',','.');?></td>
                                    <td><?=number_format($detail->harga_jual,0,',','.');?></td>
                                    <td><?=number_format($detail->harga_keep*($detail->jumlah - $detail->sisa),0,',','.');?></td>
                                    <td><?=number_format(($detail->harga_jual-$detail->harga_keep)*($detail->jumlah - $detail->sisa),0,',','.');?></td>
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
@endsection

<!-- CSS -->
@section('css')

@endsection

<!-- JAVASCRIPT -->
@section('script')

@endsection