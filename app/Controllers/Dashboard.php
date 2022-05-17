<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\NilaiAwalModel;

class Dashboard extends BaseController
{
    protected $AdminModel;
    protected $NilaiAwalModel;
    public function __construct()
    {
        $this->AdminModel = new AdminModel;
        $this->NilaiAwalModel = new NilaiAwalModel;
    }

    public function index()
    {
        $username = $this->AdminModel->where(['username' => session()->get('username')])->first();
		$data = [
			'title' => 'Dashboard',
			'nama' => $username,
            'nilai' => $this->NilaiAwalModel->findAll()
		];

		if ($username == NULL) {
			return redirect()->to('/auth');
		} else {
			echo view('v_dashboard', $data);
		}
    }
}