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
            if (session()->get('role') === 'admin') {
                return redirect()->to(site_url('admin/dashboard'));
            }
            return redirect()->to(site_url('/'));
        }

        return view('auth/login', [
            'title' => 'Masuk - Online Shop'
        ]);
    }

    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Username dan password wajib diisi.');
        }

        $user = $this->userModel->getByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        session()->set([
            'isLoggedIn'   => true,
            'userId'       => $user['id'],
            'username'     => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'email'        => $user['email'],
            'role'         => $user['role'],
        ]);

        if ($user['role'] === 'admin') {
            return redirect()->to(site_url('admin/dashboard'))->with('success', 'Selamat datang Admin, ' . $user['nama_lengkap'] . '!');
        }

        return redirect()->to(site_url('/'))->with('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(site_url('/'));
        }

        return view('auth/register', [
            'title' => 'Daftar Akun - Online Shop'
        ]);
    }

    public function attemptRegister()
    {
        $rules = [
            'username' => 'required|min_length[4]|max_length[50]|is_unique[users.username]',
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'username'     => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'         => 'customer',
        ]);

        return redirect()->to(site_url('login'))->with('success', 'Pendaftaran akun berhasil! Silakan masuk.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Anda telah keluar.');
    }
}

