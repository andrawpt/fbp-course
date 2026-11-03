<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    
    public function login()
    {
        
        if (session()->get('isLoggedIn')) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('auth/login', [
            'title' => 'Login - SIAKAD SB Admin'
        ]);
    }

    
    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Username dan Password wajib diisi.');
        }

        $user = $this->userModel->getByUsername($username);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Username tidak ditemukan.');
        }

        
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password yang Anda masukkan salah.');
        }

        
        session()->set([
            'isLoggedIn'   => true,
            'userId'       => $user['id'],
            'username'     => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'role'         => $user['role'],
        ]);

        return redirect()->to(site_url('dashboard'))->with('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');
    }

    
    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}

