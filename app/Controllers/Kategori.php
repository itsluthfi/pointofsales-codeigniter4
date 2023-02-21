<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

class Kategori extends BaseController
{
    public function __construct()
    {
        $this->kategori = new KategoriModel();
    }

    public function index()
    {
        $tombolCari = $this->request->getPost('tombolkategori');

        if (isset($tombolCari)) {
            $cari = $this->request->getPost('carikategori');
            session()->set('carikategori', $cari);
            redirect()->to('/kategori');
        } else {
            $cari = session()->get('carikategori');
        }

        $dataKategori = $cari ? $this->kategori->cariData($cari) : $this->kategori;

        $noHalaman = $this->request->getVar('page_kategori') ? $this->request->getVar('page_kategori') : 1;
        $data = [
            'datakategori' => $dataKategori->paginate(2, 'kategori'),
            'pager' => $this->kategori->pager,
            'nohalaman' => $noHalaman
        ];

        return view('kategori/data', $data);
    }
}
