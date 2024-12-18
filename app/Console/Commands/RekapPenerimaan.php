<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Model\Rekapan;

class RekapPenerimaan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:rekappenerimaan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Rekap Penerimaan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->info('You call create:rekappenerimaan command');
        DB::table("rekapan_unit")->delete();
        DB::statement("INSERT INTO rekapan_unit ( id, kode, nama, id_kategori, jumlah, id_tingkat, penjualan, modal, penerimaan, bulan, tahun, id_unit ) SELECT
                            b.id,
                            b.kode,
                            b.nama,
                            b.id_kategori,
                            sum( p.jumlah ) AS jumlah,
                            b.id_tingkat,
                            sum( p.jumlah ) * p.harga AS penjualan,
                            sum( p.jumlah ) * b.harga_beli AS modal,
                            ( sum( p.jumlah ) * p.harga ) - ( sum( p.jumlah ) * b.harga_beli ) AS penerimaan,
                            substr( p.created_at, 6, 2 ) AS bulan,
                            left(p.created_at,4) AS tahun,
                            h.id_unit 
                        FROM
                            penjualan_d AS p
                            LEFT JOIN penjualan_h AS h ON p.id_penjualan = h.id
                            LEFT JOIN barang AS b ON b.id = p.id_barang
                            LEFT JOIN kategori_barang AS k ON k.id_kategori = b.id_kategori 
                            LEFT JOIN tingkatan AS t ON t.id_tingkat = b.id_tingkat
                        WHERE
                            h.active != 0 
                        GROUP BY
                            b.id,
                            b.kode,
                            b.nama,
                            b.id_kategori,
                            b.id_tingkat,
                            p.harga,
                            b.harga_beli,
                            p.created_at,
                            h.id_unit");
    }
}
