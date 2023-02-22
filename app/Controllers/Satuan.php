<?php

namespace App\Controllers;

use Config\Services;
use App\Controllers\BaseController;
use App\Models\SatuandataModel;
use App\Models\SatuanModel;

class Satuan extends BaseController
{
    public function __construct()
    {
        $this->satuan = new SatuanModel();
    }

    public function index()
    {
        return view('satuan/data');
    }

    function ambilDataSatuan()
    {
        if ($this->request->isAJAX()) {
            $request = Services::request();
            $datasatuan = new SatuandataModel($request);
            if ($request->getMethod(true) == 'POST') {
                $lists = $datasatuan->get_datatables();
                $data = [];
                $no = $request->getPost("start");
                foreach ($lists as $list) {
                    $no++;

                    $tombolEdit = "<button type=\"button\" class=\"btn btn-sm btn-info\" onclick=\"edit('" . $list->satid . "')\"><i class=\"fas fa-edit\"></i></button>";
                    $tombolHapus = "<button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"hapus('" . $list->satid . "','" . $list->satnama . "')\"><i class=\"fas fa-trash-alt\"></i></button>";

                    $row = [];
                    $row[] = $no;
                    $row[] = $list->satnama;
                    $row[] = $tombolEdit . ' ' . $tombolHapus;
                    $data[] = $row;
                }
                $output = [
                    "draw" => $request->getPost('draw'),
                    "recordsTotal" => $datasatuan->count_all(),
                    "recordsFiltered" => $datasatuan->count_filtered(),
                    "data" => $data
                ];
                echo json_encode($output);
            }
        }
    }

    function formTambah()
    {
        if ($this->request->isAJAX()) {
            $aksi = $this->request->getPost('aksi');
            $msg = [
                'data' => view('satuan/modalformtambah', ['aksi' => $aksi])
            ];
            echo json_encode($msg);
        }
    }

    function simpandata()
    {
        if ($this->request->isAJAX()) {
            $namasatuan = $this->request->getVar('namasatuan');

            $this->satuan->insert([
                'satnama' => $namasatuan
            ]);

            $msg = [
                'sukses' => 'Satuan berhasil ditambahkan!'
            ];
            echo json_encode($msg);
        }
    }
    function hapus()
    {
        if ($this->request->isAJAX()) {
            $idSatuan = $this->request->getVar('idsatuan');

            $this->satuan->delete($idSatuan);

            $msg = [
                'sukses' => 'Satuan berhasil dihapus!'
            ];
            echo json_encode($msg);
        }
    }

    function formEdit()
    {
        if ($this->request->isAJAX()) {
            $idsatuan =  $this->request->getVar('idsatuan');

            $ambildatasatuan = $this->satuan->find($idsatuan);
            $data = [
                'idsatuan' => $idsatuan,
                'namasatuan' => $ambildatasatuan['satnama']
            ];

            $msg = [
                'data' => view('satuan/modalformedit', $data)
            ];
            echo json_encode($msg);
        }
    }

    function updatedata()
    {
        if ($this->request->isAJAX()) {
            $idSatuan = $this->request->getVar('idsatuan');
            $namaSatuan = $this->request->getVar('namasatuan');

            $this->satuan->update($idSatuan, [
                'satnama' => $namaSatuan
            ]);

            $msg = [
                'sukses' =>  'Data satuan berhasil diperbarui!'
            ];
            echo json_encode($msg);
        }
    }
}
