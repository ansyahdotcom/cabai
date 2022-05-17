<?php

namespace App\Controllers;

use App\Models\NilaiAwalModel;
use App\Models\AdminModel;

class Auth extends BaseController
{
    protected $NilaiAwalModel;
    protected $AdminModel;
    public function __construct()
    {
        $this->NilaiAwalModel = new NilaiAwalModel;
        $this->AdminModel = new AdminModel;
    }

    public function index()
    {
        if (session()->get('username') != NULL) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login Admin',
        ];
        echo view('v_login', $data);
    }

    public function login()
    {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $login = $this->AdminModel->where(['username' => $username])->first();

        if ($login) {
            if (password_verify($password, $login['password'])) {
                $data = [
                    'username' => $login['username']
                ];
                session()->set($data);
                session()->setFlashdata('message', 'login');
                return redirect()->to('/dashboard');
            } else {
                session()->setFlashdata('message', 'wrong_passwd');
                return redirect()->to('/auth');
            }
        } else {
            session()->setFlashdata('message', 'belum_terdaftar');
            return redirect()->to('/auth');
        }
    }

    public function logout()
    {
        session()->destroy(true);
        session()->setFlashdata('message', 'logout');
        return redirect()->to('/auth');
    }

    public function tambah()
    {
        $data = [
            'username' => $this->request->getVar('username'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ];
        $this->AdminModel->save($data);
        return redirect()->to('/index/login');
    }
}
