<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Penjualan';
	$breadcrumb[1]['url'] = url('backend/penjualan-barcode');
	$breadcrumb[2]['title'] = 'Add';
	$breadcrumb[2]['url'] = url('backend/penjualan-barcode/create');
	if (isset($data)){
		$breadcrumb[2]['title'] = 'Edit';
		$breadcrumb[2]['url'] = url('backend/penjualan-barcode/'.$data[0]->id.'/edit');
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
    Penjualan  - <?=$mode;?>
@endsection

<!-- CONTENT -->
@section('content')
	<?php
        //$no_nota = old('no_nota');
        $unitid =  Session::get('userinfo')['id_unit'];
        $userid = Session::get('userinfo')['user_id'];
        $urutan = DB::table('penjualan_h')->max('id');
        
        $huruf = "INV-B";
        $no_nota = $huruf.$userid.$unitid.$urutan;
        $tanggal = date('d-m-Y');
        $keterangan = old('keterangan');
		$active = 1;
		$method = "POST";
		$mode = "Create";
		$url = "backend/penjualan-barcode/";
        $detail_count = 0;
		if (isset($data)){
            $detail_count = count($detail);
            $no_nota = $data[0]->no_nota;
            $tanggal = date('d-m-Y', strtotime($data[0]->tanggal));
            $keterangan = $data[0]->keterangan;
			$active = $data[0]->active;
			$method = "PUT";
			$mode = "Edit";
			$url = "backend/penjualan-barcode/".$data[0]->id;
		}
    ?>
	<div class="page-title">
		<div class="title_left">
			<h3>Penjualan Barcode - <?=$mode;?></h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
                @include('backend.elements.back_button',array('url' => '/backend/penjualan-barcode'))
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
                        <div class="form-group">
                            <label class="col-sm-3 form-control-label">Kode Barcode Barang</label>
                            <div class="col-sm-9">
                                <input type="text" id="input_scanner" name="input_scanner" required="required" 
                                    class="form-control col-md-7 col-xs-12" placeholder="input barcode" autofocus>
                            </div>
                            <br/>
                            <br/>
                        </div>

                    {{ Form::open(['url' => $url, 'method' => $method,'class' => 'form-horizontal form-label-left']) }}
                        {!! csrf_field() !!}                        
                        <div class="form-group">
                            <label class="col-sm-3 form-control-label">Item Barang : </label>
                            <div class="col-sm-9">
                                <div class="field_wrapper">
                                    <?php
                                        if (isset($data)){
                                            $i = 1;
                                            foreach ($detail as $key => $value):
                                    ?>
                                    <div class="row" style="margin-bottom:10px;">
                                        <div class="col-sm-3">
                                            <input type="hidden" name="id_bahan_baku[]" id="id_bahan_baku_<?=$i;?>" value="<?=$value['id_barang'];?>">
                                            <input readonly type="text" name="nama_bahan_baku[]" id="nama_bahan_baku_<?=$i;?>" class="form-control" placeholder="Nama Barang" required value="<?=$value['barang']->nama;?>">
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="<?=url('backend/penjualan-barcode/barang/popup-media/'.$i);?>" class="btn btn-success browse-bahan-baku" title="Browse">Browse</a>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <input type="text" name="harga[]" id="harga_<?=$i;?>" class="form-control" placeholder="Harga" required value="<?=$value['harga'];?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <input type="text" name="jumlah[]" id="jumlah_<?=$i;?>" class="form-control" placeholder="Jumlah" required value="<?=$value['jumlah'];?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <?php
                                                if ($i == 1){
                                            ?>
                                            <a href="javascript:void(0);" class="add_button btn btn-primary" title="Tambah Baris">Tambah Baris</a>
                                            <?php
                                                } else {
                                            ?>
                                            <a href="javascript:void(0);" class="remove_button btn btn-danger" title="Hapus Baris">Hapus Baris</a>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                            $i++;
                                            endforeach;
                                        }
                                    ?>
                                   
                                </div>
                            </div>
                        </div>
                        <hr>

                        
                        <input type="hidden" name="no_nota" value="<?=$no_nota;?>">
                        <input type="hidden" name="tanggal" value="<?=$tanggal;?>">

                        <div class="form-group">
                            <div class="col-sm-6 offset-sm-6 text-right">
                                <a href="<?=url('/backend/penjualan-barcode')?>" class="btn btn-warning">Cancel</a>
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
<script type="text/javascript">
    $(document).ready(function(){
        var addButton = $('.add_button');
        var wrapper = $('.field_wrapper');
        var x = <?=$detail_count + 1;?>;
        $(addButton).click(function(){ //Once add button is clicked
            x++;
            var url = "<?=url('/')?>";
            url = url + '/backend/penjualan-barcode/barang/popup-media/' + x;
            $(wrapper).append('<div class="row" style="margin-bottom:10px;"><div class="col-sm-3"><input type="hidden" name="id_bahan_baku[]" id="id_bahan_baku_'+x+'"> <input readonly type="text" name="nama_bahan_baku[]" id="nama_bahan_baku_'+x+'" class="form-control" placeholder="Nama Barang" required></div><div class="col-sm-2"><a href="'+url+'" class="btn btn-success browse-bahan-baku" title="Browse">Browse</a></div><div class="col-sm-3"><input type="text" name="harga[]" id="harga_'+x+'" class="form-control" placeholder="Harga" required></div><div class="col-sm-2"><div class="input-group"><input type="text" name="jumlah[]" id="jumlah_'+x+'" class="form-control" placeholder="Jumlah" required></div></div><div class="col-sm-2"><a href="javascript:void(0);" class="remove_button btn btn-danger" title="Hapus Baris">Hapus Baris</a></div><br/></div>'); 
        });

        var scanner = $('#input_scanner');
        $(scanner).keyup(function(e){
            var tex = $(this).val();
            if(tex !=="" && e.keyCode===13){
                $('scanner').val(tex).focus();
                x++;
                $.get("{{url('/backend/penjualan-barcode/barang/')}}/"+ tex, function(respon){
                    if(respon.status==true){
                        $(wrapper).append('<div class="row" style="margin-bottom:10px;"><div class="col-sm-5"><input type="hidden" name="id_bahan_baku[]" id="id_bahan_baku_'+x+'" value="'+respon.data.id+'"> <input readonly type="text" name="nama_bahan_baku[]" id="nama_bahan_baku_'+x+'" class="form-control" value="'+respon.data.nama+'" placeholder="Nama Barang" required></div><div class="col-sm-3"><input readonly type="text" name="harga[]" id="harga_'+x+'" class="form-control" value="'+respon.data.harga_jual+'" placeholder="Harga" required></div><div class="col-sm-2"><div class="input-group"><input type="text" name="jumlah[]" id="jumlah_'+x+'" class="form-control" value="1" placeholder="Jumlah" required></div></div><div class="col-sm-2"><a href="javascript:void(0);" class="remove_button btn btn-danger" title="Hapus Baris">Hapus Baris</a></div><br/></div>'); 
                        $('#input_scanner').val("").focus();
                    }else{
                        errorNotify(respon.message);
                    } 
                })
                }
            e.preventDefault();
        });

        $(wrapper).on('click', '.remove_button', function(e){ 
            if (confirm("Apakah anda yakin mau menghapus baris ini?")) {
                e.preventDefault();
                $(this).parent().parent().remove(); 
            }
        });
    });
</script>

<script>
	$('body').on('click', '.browse-bahan-baku', function (e) {
		$.colorbox({
            'width'				: '90%',
            'height'			: '95%',
            'maxWidth'			: '75%',
            'maxHeight'			: '95%',
            'transition'		: 'elastic',
            'scrolling'			: true,
            'href'              : $(this).attr('href')
        });
		e.preventDefault();
	});

    $('#myDatepicker').datetimepicker({
        format: 'DD-MM-YYYY'
    });
    
</script>
@endsection