<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Penjualan extends BaseController
{
    public function index()
    {
        return view('penjualan/index');
    }

    public function input()
    {
        return view('penjualan/input');
    }
}
