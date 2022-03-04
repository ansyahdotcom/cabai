<?php

namespace App\Controllers;

use App\Models\PpmModel;

class Ppm extends BaseController
{
    protected $PpmModel;
    public function __construct()
    {
        $this->PpmModel = new PpmModel;
    }

    public function index()
    {
        $data = [
            'data_ppm' => $this->PpmModel->findAll()
        ];
        return view('v_ppm', $data);
    }

    public function tambah()
    {
        $data = [
            'umur' => $this->request->getVar('umur'),
            'ppm_rendah' => $this->request->getVar('ppm_rendah'),
            'ppm_cukup' => $this->request->getVar('ppm_cukup'),
            'ppm_tinggi' => $this->request->getVar('ppm_tinggi'),
            'st_ppm' => 0
        ];
        $this->PpmModel->save($data);
        return redirect()->to('/ppm');
    }

    public function ubah_stppm()
    {
        $id_ppm = $this->request->getVar('id_ppm');
        $st_ppm = $this->request->getVar('st_ppm');

        $data = [
            'id_ppm' => $id_ppm,
            'st_ppm' => $st_ppm
        ];
        if ($st_ppm == 1) {
            $db = \Config\Database::connect();
            $db->query("UPDATE ppm SET st_ppm = '0'");
            $this->PpmModel->save($data);
        } else {
            session()->setFlashdata('message', 'ubah_stppm');
        }
        return redirect()->to('/ppm');
    }
}
