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
        $keyword = $this->request->getPost('keyword');
        $data = [
            'keyword' => $keyword
        ];
        if ($this->request->isAJAX()) {
            $msg = [
                'viewmodal' => view('penjualan/viewmodalcariproduk', $data)
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

    public function simpanTemp()
    {
        if ($this->request->isAJAX()) {
            $kodebarcode = $this->request->getPost('kodebarcode');
            $namaproduk = $this->request->getPost('namaproduk');
            $jumlah = $this->request->getPost('jumlah');
            $nofaktur = $this->request->getPost('nofaktur');

            if (strlen($namaproduk) > 0) {
                $queryCekProduk = $this->db->table('produk')->where('kodebarcode', $kodebarcode)->where('namaproduk', $namaproduk)->get();
            } else {
                $queryCekProduk = $this->db->table('produk')->like('kodebarcode', $kodebarcode)->orLike('namaproduk', $kodebarcode)->get();
            }

            $queryCekProduk = $this->db->table('produk')->like('kodebarcode', $kodebarcode)->orLike('namaproduk', $kodebarcode)->get();

            $totalData = $queryCekProduk->getNumRows();

            if ($totalData > 1) {
                $msg = [
                    'totaldata' => 'banyak',
                ];
            } elseif ($totalData == 1) {
                $tblTempPenjualan = $this->db->table('temp_penjualan');
                $rowProduk = $queryCekProduk->getRowArray();

                $stopProduk = $rowProduk['stok_tersedia'];

                if ($stopProduk <= 0) {
                    $msg = [
                        'error' => "Maaf stok sudah habis"
                    ];
                } else if ($jumlah > intval($stopProduk)) {
                    $msg = [
                        'error' => "Maaf stok tidak mencukupi"
                    ];
                } else {
                    $insertData = [
                        'detjual_faktur' => $nofaktur,
                        'detjual_kodebarcode' => $rowProduk['kodebarcode'],
                        'detjual_hargajual' => $rowProduk['harga_jual'],
                        'detjual_jml' => $jumlah,
                        'detjual_subtotal' => $rowProduk['harga_jual'] * $jumlah,
                    ];
                    $tblTempPenjualan->insert($insertData);

                    $msg = ['sukses' => 'berhasil'];
                }
            } else {
                $msg = [
                    'error' => 'Maaf barang tidak ditemukan'
                ];
            }
            echo json_encode($msg);
        }
    }

    public function hitungTotalBayar()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');

            $tblTempPenjualan = $this->db->table('temp_penjualan');

            $queryTotal = $tblTempPenjualan->select('SUM(detjual_subtotal) as totalbayar')->where('detjual_faktur', $nofaktur)->get();
            $rowTotal = $queryTotal->getRowArray();

            $msg = [
                'totalbayar' => number_format($rowTotal['totalbayar'], 0, ',', '.'),
            ];

            echo json_encode($msg);
        }
    }

    public function hapusItem()
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $tblTempPenjualan = $this->db->table('temp_penjualan');
            $queryHapus = $tblTempPenjualan->delete(['detjual_id' => $id]);

            if ($queryHapus) {
                $msg = [
                    'sukses' => 'berhasil'
                ];

                echo json_encode($msg);
            }
        }
    }

    public function batalTransaksi()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $tblTempPenjualan = $this->db->table('temp_penjualan');
            $hapusData = $tblTempPenjualan->emptyTable();

            if ($hapusData) {
                $msg = [
                    'sukses' => 'berhasil'
                ];

                echo json_encode($msg);
            }
        }
    }

    public function pembayaran()
    {
        if ($this->request->isAJAX()) {
            $nofaktur = $this->request->getPost('nofaktur');
            $tglfaktur = $this->request->getPost('tglfaktur');
            $kopel = $this->request->getPost('kopel');

            $tblTempPenjualan = $this->db->table('temp_penjualan');
            $cekDataTempPenjualan = $tblTempPenjualan->getWhere(['detjual_faktur' => $nofaktur]);

            $queryTotal = $tblTempPenjualan->select('SUM(detjual_subtotal) as totalbayar')->where('detjual_faktur', $nofaktur)->get();
            $rowTotal = $queryTotal->getRowArray();

            if ($cekDataTempPenjualan->getNumRows() > 0) {
                $data = [
                    'nofaktur' => $nofaktur,
                    'kopel' => $kopel,
                    'totalbayar' => $rowTotal['totalbayar'],
                ];
                $msg = [
                    'data' => view('penjualan/modalpembayaran', $data),
                ];
            } else {
                $msg = ['error' => 'Maaf Item belum ada'];
            }
            echo json_encode($msg);
        }
    }
}
