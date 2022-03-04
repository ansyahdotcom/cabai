<?php

namespace App\Controllers;

use App\Models\NilaiAwalModel;

class Index extends BaseController
{
    protected $NilaiAwalModel;
    public function __construct()
    {
        $this->NilaiAwalModel = new NilaiAwalModel;
    }

    public function index()
    {
        echo view('v_index');
    }
}
