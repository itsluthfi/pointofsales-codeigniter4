<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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
        return view('penjualan/input');
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

        $msg = ['fakturpenjualan' => $fakturPenjualan];
        echo json_encode($msg);
    }
}
