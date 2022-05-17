<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\NilaiAwalModel;

class Profile extends BaseController
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
            'title' => 'Profil Admin',
            'admin' => $this->AdminModel->findAll(),
            'nilai' => $this->NilaiAwalModel->findAll()
        ];
        echo view('v_profil', $data);
    }
    
    public function ubahpassword()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $password_baru = $this->request->getVar('password_baru');
        $password_baru_ulang = $this->request->getVar('password_baru_ulang');

        $user = $this->AdminModel->where(['username' => $username])->first();
        if ($user == NULL) {
            session()->setFlashdata('message', 'wrong_user');
            return redirect()->to('/profile');
        } else {
            if ($password_baru == $password_baru_ulang) {
                $data = [
                    'id_adm' => $user['id_adm'],
                    'password' => password_hash($password_baru, PASSWORD_DEFAULT)
                ];
                $this->AdminModel->save($data);
                session()->setFlashdata('message', 'ubah_passwd');
                return redirect()->to('/profile');
            } else {
                session()->setFlashdata('message', 'wrong_passwd');
                return redirect()->to('/profile');
            }
        }
    }
}
