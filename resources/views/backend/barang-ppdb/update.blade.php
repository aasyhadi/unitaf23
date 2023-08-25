<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Barang PPDB';
	$breadcrumb[1]['url'] = url('backend/barang-ppdb');
	$breadcrumb[2]['title'] = 'Add';
	$breadcrumb[2]['url'] = url('backend/barang-ppdb/create');
	if (isset($data)){
		$breadcrumb[2]['title'] = 'Edit';
		$breadcrumb[2]['url'] = url('backend/barang-ppdb/'.$data[0]->id.'/edit');
	}
?>

<?php
	$cover_1 = [];
	if (isset($data)){
		$cover_1 = $data[0];
		$cover_1->field = 'img_id';
	}
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title')
	<?php
		$mode = "Create";
		if (isset($data)){
			$mode = "Edit";
		}
	?>
    Master Barang - <?=$mode;?>
@endsection

<!-- CONTENT -->
@section('content')
    <?php
        $kode = old('kode');
        $nama = old('nama');
        $jenis_mobil = old('jenis_mobil');
        $harga_jual = old('harga_jual');
        $harga_beli = old('harga_beli');
        $stok_awal = old('stok_awal');
        $keterangan = old('keterangan');
        $img_id = old('img_id', 0);
		$active = 1;
		$id_kategori = 1;
		$size = 1;
		$jender = 1;
		$id_tingkat = 1;
		$method = "POST";
		$mode = "Create";
		$url = "backend/barang/";
		if (isset($data)){
            $kode = $data[0]->kode;
            $nama = $data[0]->nama;
            $jenis_mobil = $data[0]->jenis_mobil;
            $harga_jual = $data[0]->harga_jual;
            $harga_beli = $data[0]->harga_beli;
            $stok_awal = $data[0]->stok_awal;
            $keterangan = $data[0]->keterangan;
			$id_kategori = $data[0]->id_kategori;
			$size = $data[0]->size;
			$jender = $data[0]->jender;
			$id_tingkat = $data[0]->id_tingkat;
            $active = $data[0]->active;
            $img_id = $data[0]->img_id;
			$method = "PUT";
			$mode = "Edit";
			$url = "backend/barang-ppdb/".$data[0]->id;
		}
	?>
	<div class="page-title">
		<div class="title_left">
			<h3>Master Barang PPDB- <?=$mode;?></h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
				<a href="<?=url('/backend/barang-ppdb');?>" class="btn-index btn btn-primary btn-block" title="Back"><i class="fa fa-arrow-left"></i></a>
			</div>
        </div>
        <div class="clearfix"></div>
		@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	</div>
	<div class="clearfix"></div>
	<br/><br/>	
	<div class="row">
		<div class="col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					{{ Form::open(['url' => $url, 'method' => $method,'class' => 'form-horizontal form-label-left']) }}
                        {!! csrf_field() !!}
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Kode <span class="required">*</span></label>
							<div class="col-sm-7 col-xs-12">
								<input type="text" id="kode" name="kode" required="required" class="form-control col-md-7 col-xs-12" value="<?=$kode;?>" autofocus>
							</div>
                        </div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Nama <span class="required">*</span></label>
							<div class="col-sm-7 col-xs-12">
								<input type="text" id="nama" name="nama" required="required" class="form-control col-md-7 col-xs-12" value="<?=$nama;?>" autofocus>
							</div>
                        </div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Image</label>
							<div class="col-sm-6 col-xs-9">
								<input type="hidden" name="img_id" value=<?=$img_id;?> id="id-cover-image_1">
								@include('backend.elements.change_cover',array('cover' => $cover_1, 'id_count' => 1))	
							</div>
						</div>
    					<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Stok Awal</label>
							<div class="col-md-5 col-xs-12">
								<input type="text" name="stok_awal" class="form-control col-md-7 col-xs-12" value="<?=$stok_awal;?>">
								*) Stok awal boleh 0 jika barang titipan UMKM.
							</div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-sm-3 col-xs-12">Harga Beli</label>
                            <div class="col-md-5 col-xs-12">
                                <input type="text" name="harga_beli" class="form-control col-md-7 col-xs-12" value="<?=$harga_beli;?>">
                            </div>
                        </div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Harga Jual</label>
							<div class="col-md-5 col-xs-12">
								<input type="text" name="harga_jual" class="form-control col-md-7 col-xs-12" value="<?=$harga_jual;?>">
							</div>
                        </div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Satuan</label>
							<div class="col-md-5 col-xs-12">
								<input type="text" name="keterangan" class="form-control col-md-7 col-xs-12" value="<?=$keterangan;?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Size: </label>
							<div class="col-sm-5 col-xs-12">
								{{
								Form::select(
									'size',
									['1' => 'S, M, L', 
									'2' => 'XL, XXL',
									'3' => 'Standar'],
									$size,
									array(
										'class' => 'form-control',
									))
								}}								
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Tingkatan: </label>
							<div class="col-sm-5 col-xs-12">
								{{
								Form::select(
									'id_tingkat',
									['1' => 'KB', 
									'2' => 'TK',
									'3' => 'SD',
									'4' => 'SMP',
									'5' => 'SMA'],
									$id_tingkat,
									array(
										'class' => 'form-control',
									))
								}}								
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Kategori: </label>
							<div class="col-sm-5 col-xs-12">
								{{
								Form::select(
									'id_kategori',
									['6' => 'Seragaman PPDB', 
									'7' => 'Perlengkapan PPDB'],
									$id_kategori,
									array(
										'class' => 'form-control',
									))
								}}								
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12"> Seragaman: </label>
							<div class="col-sm-5 col-xs-12">
								{{
								Form::select(
									'jender',
									['1' => 'All', 
									'2' => 'Laki-laki',
									'3' => 'Perempuan'],
									$jender,
									array(
										'class' => 'form-control',
									))
								}}								
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Status: </label>
							<div class="col-sm-5 col-xs-12">
								{{
								Form::select(
									'active',
									['1' => 'Active', '2' => 'Deactive'],
									$active,
									array(
										'class' => 'form-control',
									))
								}}								
							</div>
						</div>
						
						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-sm-6 col-xs-12 col-sm-offset-3">
								<a href="<?=url('/backend/barang-ppdb')?>" class="btn btn-warning">Cancel</a>
								<button type="submit" class="btn btn-primary">Submit </button>
							</div>
						</div>
					{{ Form::close() }}
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