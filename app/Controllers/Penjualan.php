<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukdataModel;
use Config\Services;

class Penjualan extends BaseController
{
    public function __construct()
    {
        $this->db = db_connect();
    }

    public function index()
    {
        return view('penjualan/index');
    }

    public function input()
    {
        $data = [
            'nofaktur' => $this->buatFaktur()
        ];

        return view('penjualan/input', $data);
    }

    public function buatFaktur()
    {
        $tgl = $this->request->getPost('tanggal');
        $query = $this->db->query("SELECT MAX(jual_faktur) AS nofaktur FROM penjualan WHERE
            DATE_FORMAT(jual_tgl, '%Y-%m-%d') - '$tgl'");
        $hasil = $query->getRowArray();
        $data = $hasil['nofaktur'];

        $lastNoUrut = substr($data, -4);

        // nomor urut ditambah 1
        $nextNoUrut = intval($lastNoUrut) + 1;

        // membuat format nomor transaksi berikutnya
        $fakturPenjualan = 'J' . date('dmy', strtotime($tgl)) . sprintf('%04s', $nextNoUrut);

        return $fakturPenjualan;
    }

    public function dataDetail()
    {
        $nofaktur = $this->request->getPost('nofaktur');

        $tempPenjualan = $this->db->table('temp_penjualan');
        $queryTampil = $tempPenjualan->select('detjual_id as id, detjual_kodebarcode as kode, namaproduk,
            detjual_hargajual as hargajual, detjual_jml as qty, detjual_subtotal as subtotal')->join('produk', 'detjual_kodebarcode=kodebarcode')->where('detjual_faktur', $nofaktur)->orderBy('detjual_id', 'asc');

        $data = [
            'datadetail' => $queryTampil->get()
        ];

        $msg = [
            'data' => view('penjualan/viewdetail', $data)
        ];
        echo json_encode($msg);
    }

    public function viewDataProduk()
    {
        if ($this->request->isAJAX()) {
            $msg = [
                'viewmodal' => view('penjualan/viewmodalcariproduk')
            ];
            echo json_encode($msg);
        }
    }

    public function listDataProduk()
    {
        if ($this->request->isAJAX()) {

            $request = Services::request();
            $keywordkode = $this->request->getPost('keywordkode');
            $modelProduk = new ProdukdataModel($request);

            if ($request->getMethod(true) == 'POST') {
                $lists = $modelProduk->get_datatables($keywordkode);
                $data  = [];

                $no = $request->getPost("start");
            }
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->kodebarcode;
                $row[] = $list->namaproduk;
                $row[] = $list->katnama;
                $row[] = number_format($list->stok_tersedia, 0, ',', '.');
                $row[] = number_format($list->harga_jual, 0, ',', '.');
                $row[] = "<button type=\"button\" class=\"btn btn-primary\" onclick=\"pilihitem('" . $list->kodebarcode . "', '" . $list->namaproduk . "')\">Pilih</button>";
                $data[] = $row;
            }


            $output = [
                "draw" => $request->getPost('draw'),
                "recordsTotal" => $modelProduk->count_all($keywordkode),
                "recordsFiltered" => $modelProduk->count_filtered($keywordkode),
                "data" => $data
            ];

            echo json_encode($output);
        }
    }
}
