<?php

namespace App\Controllers;

use App\Models\PpmModel;
use App\Models\NilaiAwalModel;

class Ppm extends BaseController
{
    protected $PpmModel;
    protected $NilaiAwalModel;
    public function __construct()
    {
        $this->PpmModel = new PpmModel;
        $this->NilaiAwalModel = new NilaiAwalModel;
    }

    public function index()
    {
        $data = [
            'data_ppm' => $this->PpmModel->findAll(),
            'nilai' => $this->NilaiAwalModel->findAll()
        ];
        return view('v_ppm', $data);
    }

    public function tambah()
    {
        $data = [
            'umur' => $this->request->getVar('umur'),
            'ppm_rendah' => $this->request->getVar('ppm_cukup_min') + 50,
            'ppm_cukup_min' => $this->request->getVar('ppm_cukup_min'),
            'ppm_cukup_max' => $this->request->getVar('ppm_cukup_max'),
            'ppm_tinggi' => $this->request->getVar('ppm_cukup_max') - 50,
            'st_ppm' => 0
        ];
        $this->PpmModel->save($data);
        session()->setFlashdata('message', 'save');
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
        }
        session()->setFlashdata('message', 'ubah_stppm');
        return redirect()->to('/ppm');
    }

    public function ubah()
    {
        $data = [
            'id_ppm' => $this->request->getVar('id_ppm'),
            'umur' => $this->request->getVar('umur'),
            'ppm_rendah' => $this->request->getVar('ppm_cukup_min') + 50,
            'ppm_cukup_min' => $this->request->getVar('ppm_cukup_min'),
            'ppm_cukup_max' => $this->request->getVar('ppm_cukup_max'),
            'ppm_tinggi' => $this->request->getVar('ppm_cukup_max') - 50
        ];
        $this->PpmModel->save($data);
        session()->setFlashdata('message', 'edit');
        return redirect()->to('/ppm');
    }

    public function hapus()
    {
        $id = $this->request->getVar('id_ppm');
        $this->PpmModel->delete($id);
        session()->setFlashdata('message', 'delete');
        return redirect()->to('/ppm');
    }

    public function min()
    {
        $data = $this->PpmModel->where('st_ppm', 1)->limit(1)->findAll();
        foreach ($data as $min) {
            $ppm_cukup_min = $min['ppm_cukup_min'];
        }
        echo $ppm_cukup_min;
    }

    public function max()
    {
        $data = $this->PpmModel->where('st_ppm', 1)->limit(1)->findAll();
        foreach ($data as $max) {
            $ppm_cukup_max = $max['ppm_cukup_max'];
        }
        echo $ppm_cukup_max;
    }
}
