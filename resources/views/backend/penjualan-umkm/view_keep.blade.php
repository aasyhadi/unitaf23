<?php
	$method = "PUT";
	$url = "backend/penjualan-umkm/pulled/".$data[0]->id;
	$no_inv = $data[0]->no_inv;
	$tanggal = date('d-m-Y', strtotime($data[0]->tanggal));
?>

			<div class="x_panel">
				<div class="x_content">
                    {{ Form::open(['name'=>'formKeep', 'url' => $url, 'method' => $method,'class' => 'form-horizontal form-label-left']) }}
                        {!! csrf_field() !!}
						<div class="form-group">
                            <label class="col-sm-2 form-control-label">Tanggal: </label>
                            <div class="col-sm-4">
                                <div class='input-group date' id='myDatepicker'>
                                    <input readonly type='text' class="form-control" name="tanggal" value="<?=$tanggal;?>">
                                </div>
                            </div>
                        </div>                            
                        <div class="form-group">
                            <label class="col-sm-2 form-control-label">No Invoice: </label>
                            <div class="col-sm-4">
                                <input readonly type="text" class="form-control"  name="no_inv" value="<?=$no_inv;?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 form-control-label">Barang2 Titipan: </label>
                            <div class="col-sm-10">
                                <div class="field_wrapper">
                                    <?php
                                        if (isset($data)){
                                            $i = 1;
                                            foreach ($detail as $key => $value):
                                    ?>
                                    <div class="row" style="margin-bottom:10px;">
										<input type="hidden" name="id_supplier[]" id="id_supplier_<?=$i;?>" value="<?=$value['id_supplier'];?>">
										<input type="hidden" name="id_barang[]" id="id_barang_<?=$i;?>" value="<?=$value['id_barang'];?>">
										<input type="hidden" name="harga_keep[]" id="harga_keep_<?=$i;?>" value="<?=$value['harga_keep'];?>">
										<input type="hidden" name="harga_jual[]" id="harga_jual_<?=$i;?>" value="<?=$value['harga_jual'];?>">
										<input type="hidden" name="jumlah[]" id="jumlah_<?=$i;?>" value="<?=$value['jumlah'];?>">
					
                                        <div class="col-sm-5">
                                            <input readonly type="text" class="form-control" value="<?=$value['barang']->nama;?>">
                                        </div>
                                        <div class="col-sm-2">
											<input readonly type="text" class="form-control" value="<?=$value['harga_jual'];?>">
                                        </div>
                                        <div class="col-sm-2">
											<input readonly type="text" class="form-control" value="<?=$value['jumlah'];?>">
                                        </div>
										<div class="col-sm-3">
                                            <div class="input-group">
												<input type="number" name="sisa[]" id="sisa_<?=$i;?>" class="form-control" placeholder="Sisa" min="0" max="<?=$value['jumlah'];?>" required>
                                            </div>
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
                        <div class="form-group">
                            <div class="col-sm-6 offset-sm-6 text-right">
								<a href="<?=url('/backend/penjualan-umkm')?>" class="btn btn-warning">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit </button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>

