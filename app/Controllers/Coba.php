<?php

namespace App\Controllers;

use App\Models\CobaModel;

class Coba extends BaseController
{
    protected $CobaModel;
    public function __construct()
    {
        $this->CobaModel = new CobaModel;
    }

    public function index()
    {
        $data = [
            'title' => 'Coba',
            'coba' => $this->CobaModel->getAll()
        ];
        return view('coba/index', $data);
    }

    public function relay()
    {
        $data = $this->CobaModel->orderBy('id_dht', 'DESC')->limit(1)->findAll();
        foreach ($data as $dht) {
            $suhu = $dht['suhu'];
        }
        echo $suhu;
    }
}
