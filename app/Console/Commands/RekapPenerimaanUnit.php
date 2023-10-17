<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;
use App\Model\RekapanUnit;
use Session;

class RekapPenerimaanUnit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:rekappenerimaanunit {unit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Rekap Penerimaan Unit';

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
        //angka 2 ambil dari session atau yang lain?
        //$unit = $this->argument('unit');
        $unit = Session::get('userinfo')['id_unit'];
        $this->info('You call create:rekappenerimaanunit command');
        //RekapanUnit::where('id_unit', '=', $unit)->delete();
        DB::table("rekapan")->where("id_unit", "=", $unit)->delete();
        DB::statement("INSERT INTO rekapan ( id, kode, nama, id_kategori, jumlah, penjualan, modal, penerimaan, bulan, id_unit) SELECT
                            b.id,
                            b.kode,
                            b.nama,
                            b.id_kategori,
                            sum( p.jumlah ) AS jumlah,
                            sum( p.jumlah ) * p.harga AS penjualan,
                            sum( p.jumlah ) * b.harga_beli AS modal,
                            ( sum( p.jumlah ) * p.harga ) - ( sum( p.jumlah ) * b.harga_beli ) AS penerimaan,
                            substr( p.created_at, 6, 2 ) AS bulan,
                            h.id_unit 
                        FROM
                            penjualan_d AS p
                            LEFT JOIN penjualan_h AS h ON p.id_penjualan = h.id
                            LEFT JOIN barang AS b ON b.id = p.id_barang
                            LEFT JOIN kategori_barang AS k ON k.id_kategori = b.id_kategori
                        WHERE
                            h.active != 0 
                        GROUP BY
                            b.id,
                            b.kode,
                            b.nama,
                            b.id_kategori,
                            p.harga,
                            b.harga_beli,
                            p.created_at,
                            h.id_unit"); 
    }
}
