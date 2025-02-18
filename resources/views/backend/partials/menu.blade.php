<!-- sidebar menu -->
<?php
	$segment =  Request::segment(2);
	$sub_segment =  Request::segment(3);
?>
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
	<div class="menu_section">
		<?php
			// SUPER ADMIN //
			if ($userinfo['user_level_id'] == 1):
		
		?>
        <h3>General</h3>
		<ul class="nav side-menu">
			<li class="{{ ($segment == 'dashboard' ? 'active' : '') }}">
				<a href="<?=url('backend/dashboard');?>"><i class="fa fa-dashboard"></i> Dashboard</a>
			</li>
			<li class=" {{ ((($segment == 'setting') || ($segment == 'modules') || ($segment == 'access-control')) ? 'active' : '') }}">
				<a><i class="fa fa-cog"></i> System Admin <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ ((($segment == 'setting') || ($segment == 'modules') || ($segment == 'access-control')) ? 'display : block' : '') }}">
					
					
					<li class="{{ ($segment == 'setting' ? 'active' : '') }}">
						<a href="<?=url('backend/setting');?>">Setting</a>
					</li>
					<li class="{{ ($segment == 'modules' ? 'active' : '') }}">
						<a href="<?=url('backend/modules');?>">Modules</a>
					</li>
					<li class="{{ ($segment == 'access-control' ? 'active' : '') }}">
						<a href="<?=url('backend/access-control');?>">Access Control</a>
					</li>
				</ul>
            </li>
			<li class=" {{ ((($segment == 'users-level') || ($segment == 'users-user')) ? 'active' : '') }}">
				<a><i class="fa fa-users"></i> Membership <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ ((($segment == 'users-level') || ($segment == 'users-user')) ? 'display : block' : '') }}">
					<li class="{{ ($segment == 'users-level' ? 'active' : '') }}">
						<a href="<?=url('backend/users-level');?>">Master User Level</a>
					</li>
					<li class="{{ ($segment == 'users-user' ? 'active' : '') }}">
						<a href="<?=url('backend/users-user');?>">Master User</a>
					</li>
				</ul>
			</li>
		</ul>
		<?php
			endif;
			if ($userinfo['user_level_id'] <> 4):
		?>
    </div>

	<?php
		// SUPER ADMIN & ADMIN //
		endif;
		if ($userinfo['user_level_id'] == 1 && $userinfo['id_unit'] <> 4):
	?>
	<div class="menu_section">
        <h3>Master</h3>
		<ul class="nav side-menu">
			<li class=" {{ (($segment == 'supplier' || $segment == 'barang' || $segment == 'media-library' || $segment == 'koreksi-stok') ? 'active' : '') }}">
				<a><i class="fa fa-database"></i> Master <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ (($segment == 'supplier' || $segment == 'barang' || $segment == 'media-library' || $segment == 'koreksi-stok') ? 'display : block' : '') }}">
					<li class="{{ ($segment == 'supplier' ? 'active' : '') }}">
						<a href="<?=url('backend/supplier');?>"><i class="fa fa-suitcase"></i> Master Supplier</a>
					</li>
					<li class="{{ ($segment == 'barang' ? 'active' : '') }}">
						<a href="<?=url('backend/barang');?>"><i class="fa fa-file"></i> Master Barang</a>
					</li>
					<li class="{{ ($segment == 'media-library' ? 'active' : '') }}">
						<a href="<?=url('backend/media-library');?>"><i class="fa fa-picture-o"></i> Media Library</a>
					</li>
					<li class="{{ ($segment == 'koreksi-stok' ? 'active' : '') }}">
						<a href="<?=url('backend/koreksi-stok');?>"><i class="fa fa-cogs"></i> Koreksi Stok</a>
					</li>
				</ul>
			</li>
        </ul>
    </div>

	<?php
		endif;
		if ($userinfo['user_level_id'] == 1 or $userinfo['user_level_id'] == 3):
	?>

	<?php
		// SUPER ADMIN & ADMIN //
		endif;
		if ($userinfo['user_level_id'] == 1 or $userinfo['user_level_id'] == 2 && $userinfo['id_unit'] == 4):
	?>
	<div class="menu_section">
        <h3>Master PPDB</h3>
		<ul class="nav side-menu">
			<li class="{{ ($segment == 'barang-ppdb' ? 'active' : '') }}">
				<a href="<?=url('backend/barang-ppdb');?>"><i class="fa fa-file"></i> Master Barang</a>
			</li>
			<li class="{{ ($segment == 'koreksi-stok-ppdb' ? 'active' : '') }}">
                <a href="<?=url('backend/koreksi-stok-ppdb');?>"><i class="fa fa-cogs"></i> Koreksi Stok</a>
            </li>
        <ul>
    </div>

	<?php
		endif;
		if ($userinfo['user_level_id'] == 1 or $userinfo['user_level_id'] == 3 and $userinfo['id_unit'] == 4):
	?>
	<div class="menu_section">
        <h3>Transaksi PPDB</h3>
		<ul class="nav side-menu">
			<li class="{{ ($segment == 'penjualan' ? 'active' : '') }}">
				<a href="<?=url('backend/penjualan');?>"><i class="fa fa-gift"></i> Penjualan Reguler</a>
			</li>
        <ul>
    </div>
	
	<?php
		endif;
		if ($userinfo['user_level_id'] == 1 or $userinfo['user_level_id'] == 3 and $userinfo['id_unit'] <> 4):
	?>

	<div class="menu_section">
        <h3>Transaksi</h3>
		<ul class="nav side-menu">
			<li class="{{ ($segment == 'inden' ? 'active' : '') }}">
				<a href="<?=url('backend/inden');?>"><i class="fa fa-shopping-cart"></i> Daftar Inden</a>
			</li>
			<li class="{{ ($segment == 'purchase-order' ? 'active' : '') }}">
				<a href="<?=url('backend/purchase-order');?>"><i class="fa fa-shopping-cart"></i> Pembelian / PO</a>
			</li>

			<li class="{{ ($segment == 'penjualan' ? 'active' : '') }}">
				<a href="<?=url('backend/penjualan');?>"><i class="fa fa-gift"></i> Penjualan Reguler</a>
			</li>
			<li class="{{ ($segment == 'penjualan-barcode' ? 'active' : '') }}">
				<a href="<?=url('backend/penjualan-barcode');?>"><i class="fa fa-gift"></i> Penjualan Barcode</a>
			</li>
			<li class="{{ ($segment == 'penjualan-umkm' ? 'active' : '') }}">
				<a href="<?=url('backend/penjualan-umkm');?>"><i class="fa fa-certificate"></i> Penjualan Jajanan</a>
            </li>
        <ul>
    </div>
	<?php
		endif;
		if ($userinfo['user_level_id'] == 1 or $userinfo['user_level_id'] == 2 && $userinfo['id_unit'] <> 4):
	?>
	<!-- <div class="menu_section">
		<h3>Transaksi</h3>
		<ul class="nav side-menu">
			<li class="{{ ($segment == 'pengeluaran' ? 'active' : '') }}">
				<a href="<?=url('backend/pengeluaran');?>"><i class="fa fa-credit-card-alt"></i> Biaya Pengeluaran</a>
            </li>
        <ul>
    </div> -->
	<?php
		// ADMIN & OPERATOR //
		endif;
		if ($userinfo['user_level_id'] == 2 &&  $userinfo['id_unit'] <> 4):
	?>
	<div class="menu_section">
        <h3>Menu</h3>
		<ul class="nav side-menu">
			<li class=" {{ (($segment == 'supplier' || $segment == 'barang' || $segment == 'media-library' || $segment == 'koreksi-stok') ? 'active' : '') }}">
				<!-- Master -->
				<a><i class="fa fa-database"></i> Master <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ (($segment == 'supplier' || $segment == 'barang' || $segment == 'media-library' || $segment == 'koreksi-stok') ? 'display : block' : '') }}">
					<li class="{{ ($segment == 'supplier' ? 'active' : '') }}">
						<a href="<?=url('backend/supplier');?>"><i class="fa fa-suitcase"></i> Master Supplier</a>
					</li>
					<li class="{{ ($segment == 'barang' ? 'active' : '') }}">
						<a href="<?=url('backend/barang');?>"><i class="fa fa-file"></i> Master Barang</a>
					</li>
					<li class="{{ ($segment == 'media-library' ? 'active' : '') }}">
						<a href="<?=url('backend/media-library');?>"><i class="fa fa-picture-o"></i> Media Library</a>
					</li>
					<li class="{{ ($segment == 'koreksi-stok' ? 'active' : '') }}">
						<a href="<?=url('backend/koreksi-stok');?>"><i class="fa fa-cogs"></i> Koreksi Stok</a>
					</li>
				</ul>
			</li>
            
			<!-- Laporan -->
			<li class=" {{ ((($segment == 'report-purchase') || ($segment == 'report-penjualan') || ($segment == 'report-umkm') || ($segment == 'report-kategori') || ($segment == 'report-stok') || ($segment == 'report-mutasi-stok') || ($segment == 'report-harian') || ($segment == 'report-rekap-kategori')) ? 'active' : '') }}">
				<a><i class="fa fa-bar-chart-o"></i> Laporan <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ ((($segment == 'report-purchase') || ($segment == 'report-penjualan') || ($segment == 'report-umkm') || ($segment == 'report-kategori') || ($segment == 'report-stok') || ($segment == 'report-mutasi-stok') || ($segment == 'report-harian') || ($segment == 'report-rekap-kategori')) ? 'display : block' : '') }}">
					<li class="{{ ($segment == 'report-purchase' ? 'active' : '') }}">
						<a href="<?=url('backend/report-purchase');?>">Pembelian / PO</a>
					</li>
					<li class="{{ ($segment == 'report-penjualan' ? 'active' : '') }}">
						<a href="<?=url('backend/report-penjualan');?>">Penjualan Barang</a>
                    </li>
					<li class="{{ ($segment == 'report-umkm' ? 'active' : '') }}">
						<a href="<?=url('backend/report-umkm');?>">Penjualan Jajanan</a>
                    </li>
					<?php
						// ADMIN Mesjid Agung //
						if ($userinfo['user_level_id'] == 2 &&  $userinfo['id_unit'] == 1):
					?>
					<li class="{{ ($segment == 'report-tera-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/report-tera-unit');?>">Penjualan TERA</a>
                    </li>
					<?php
						endif;
					?>
					<li class="{{ ($segment == 'report-rekap-kategori' ? 'active' : '') }}">
						<a href="<?=url('backend/report-rekap-kategori');?>">Penjualan Kategori Bulanan</a>
                    </li>
					<li class="{{ ($segment == 'report-kategori' ? 'active' : '') }}">
						<a href="<?=url('backend/report-rekap-penjualan');?>">Penjualan Kategori Harian</a>
                    </li>
					<li class="{{ ($segment == 'report-stok' ? 'active' : '') }}">
						<a href="<?=url('backend/report-stok');?>">Stok Barang</a>
					</li>
					<li class="{{ ($segment == 'report-mutasi-stok' ? 'active' : '') }}">
						<a href="<?=url('backend/report-mutasi-stok');?>">Mutasi Stok</a>
					</li>
					<li class="{{ ($segment == 'report-harian' ? 'active' : '') }}">
						<a href="<?=url('backend/report-harian');?>">Harian</a>
					</li>
				</ul>
			</li>
			
			<!-- Rekapan -->
			<li class=" {{ ((($segment == 'rekap-penerimaan') || ($segment == 'rekap-pengeluaran')) ? 'active' : '') }}">
				<a><i class="fa fa-pie-chart"></i> Rekapan <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ ((($segment == 'rekap-penerimaan') || ($segment == 'rekap-pengeluaran')) ? 'display : block' : '') }}">
					<li class="{{ ($segment == 'rekap-penerimaan' ? 'active' : '') }}">
						<a href="<?=url('backend/rekap-penerimaan');?>">Penerimaan</a>
                    </li>
					<li class="{{ ($segment == 'rekap-pengeluaran' ? 'active' : '') }}">
						<a href="<?=url('backend/rekap-pengeluaran');?>">Pengeluaran</a>
					</li>
				</ul>
			</li>
        </ul>
    </div>
	
	<?php
		// SUPER ADMIN & MANAGER//
		endif;
		if ($userinfo['user_level_id'] == 1 || $userinfo['user_level_id'] == 4):
	?>

	<?php
		// UNIT PPDB //
		endif;
		if ($userinfo['user_level_id'] == 2 &&  $userinfo['id_unit'] == 4  ):
	?>
	<div class="menu_section">
        <h3>Laporan PPDB</h3>
		<ul class="nav side-menu">
			<li class=" {{ ((($segment == 'report-purchase') || ($segment == 'report-penjualan') || ($segment == 'report-umkm') || ($segment == 'report-kategori') || ($segment == 'report-stok')) ? 'active' : '') }}">
				<a><i class="fa fa-bar-chart-o"></i> Laporan <span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ ((($segment == 'report-purchase') || ($segment == 'report-penjualan') || ($segment == 'report-umkm') || ($segment == 'report-kategori') || ($segment == 'report-stok')) ? 'display : block' : '') }}">
					<li class="{{ ($segment == 'report-penjualan' ? 'active' : '') }}">
						<a href="<?=url('backend/report-penjualan');?>">Penjualan Barang</a>
                    </li>
					<li class="{{ ($segment == 'report-stok' ? 'active' : '') }}">
						<a href="<?=url('backend/report-stok');?>">Stok Barang</a>
					</li>
				</ul>
			</li>
        <ul>
    </div>
	
	<?php
		// END //
		endif;
		if ($userinfo['user_level_id'] == 1 || $userinfo['user_level_id'] == 4):
	?>
	<br>
	<div class="menu_section">
        <h3>Monitoring</h3>
		<ul class="nav side-menu">
			<li class=" {{ ((($segment == 'report-purchase-unit') || 
							($segment == 'report-penjualan-unit') || 
							($segment == 'report-umkm-unit') || 
							($segment == 'report-penjualan-kategori-unit') || 
							($segment == 'report-statistik-penjualan-unit') || 
							($segment == 'report-statistik-penjualan-kategori') || 
							($segment == 'report-stok-unit')) ? 'active' : '') }}">
				<a><i class="fa fa-bar-chart-o"></i> Laporan<span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ ((($segment == 'report-purchase-unit') || 
														($segment == 'report-penjualan-unit') || 
														($segment == 'report-umkm-unit') || 
														($segment == 'report-penjualan-kategori-unit') || 
														($segment == 'report-statistik-penjualan-unit') || 
														($segment == 'report-statistik-penjualan-kategori') || 
														($segment == 'report-stok-unit')) ? 'display : block' : '') }}">
					<li class="{{ ($segment == 'report-purchase-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/report-purchase-unit');?>">Pembelian / PO</a>
					</li>
					<li class="{{ ($segment == 'report-penjualan-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/report-penjualan-unit');?>">Penjualan Barang</a>
                    </li>
					<li class="{{ ($segment == 'report-umkm-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/report-umkm-unit');?>">Penjualan Jajanan</a>
                    </li>
					<li class="{{ ($segment == 'report-tera-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/report-tera-unit');?>">Penjualan TERA</a>
                    </li>
					<li class="{{ ($segment == 'report-ppdb-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/report-ppdb-unit');?>">Penjualan PPDB</a>
                    </li>
					<li class="{{ ($segment == 'report-penjualan-kategori-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/report-penjualan-kategori-unit');?>">Penjualan Kategori</a>
                    </li>
					<li class="{{ ($segment == 'report-statistik-penjualan-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/report-statistik-penjualan-unit');?>">Statistik Penjualan Unit</a>
                    </li>
					<li class="{{ ($segment == 'report-statistik-penjualan-kategori' ? 'active' : '') }}">
						<a href="<?=url('backend/report-statistik-penjualan-kategori');?>">Statistik Penjualan Kategori</a>
                    </li>
					<li class="{{ ($segment == 'report-stok-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/report-stok-unit');?>">Stok Barang</a>
					</li>
				</ul>
			</li>

			<li class=" {{ ((($segment == 'rekap-penerimaan-unit') || ($segment == 'rekap-pengeluaran-unit')) ? 'active' : '') }}">
				<a><i class="fa fa-pie-chart"></i> Rekapan<span class="fa fa-chevron-down"></span></a>
				<ul class="nav child_menu" style="{{ ((($segment == 'rekap-penerimaan-unit') || ($segment == 'rekap-pengeluaran-unit')) ? 'display : block' : '') }}">
					<li class="{{ ($segment == 'rekap-penerimaan-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/rekap-penerimaan-unit');?>">Penerimaan</a>
                    </li>
					<li class="{{ ($segment == 'rekap-pengeluaran-unit' ? 'active' : '') }}">
						<a href="<?=url('backend/rekap-pengeluaran-unit');?>">Pengeluaran</a>
					</li>
				</ul>
			</li>
        <ul>
    </div>
	<?php
		endif;
	?>
	</div>
</div>

