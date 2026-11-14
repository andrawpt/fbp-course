<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Menampilkan halaman registrasi.
     */
    public function register()
    {
        return view('auth/register');
    }

    /**
     * Menyimpan akun baru.
     */
    public function store()
    {
        $rules = [
            'name' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama wajib diisi.',
                    'min_length' => 'Nama minimal 3 karakter.',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique'   => 'Email sudah terdaftar.',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[8]',
                'errors' => [
                    'required'   => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 8 karakter.',
                ],
            ],
            'password_confirm' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches'  => 'Konfirmasi password tidak sama.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return view('auth/register', [
                'validation' => $this->validator,
            ]);
        }

        $this->userModel->insert([
            'name'     => trim((string) $this->request->getPost('name')),
            'email'    => strtolower(trim((string) $this->request->getPost('email'))),
            'password' => password_hash(
                (string) $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'     => 'customer',
            'phone'    => $this->request->getPost('phone'),
            'address'  => $this->request->getPost('address'),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil. Silakan login.');
    }

    /**
     * Menampilkan halaman login.
     */
    public function login()
    {
        return view('auth/login');
    }

    /**
     * Memproses login.
     */
    public function authenticate()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return view('auth/login', [
                'validation' => $this->validator,
            ]);
        }

        $email    = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');

        $user = $this->userModel
            ->where('email', $email)
            ->first();

        if (! $user || ! password_verify($password, (string) $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        // Regenerasi session ID setelah autentikasi berhasil
        if (ENVIRONMENT !== 'testing') {
            session()->regenerate(true);
        }

        session()->set([
            'isLoggedIn' => true,
            'user_id'    => $user['id_user'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
        ]);

        // Redirect sesuai role
        switch ($user['role']) {
            case 'admin':
                return redirect()->to('/admin');
            case 'operator':
                return redirect()->to('/operator');
            default:
                return redirect()->to('/');
        }
    }

    /**
     * Mengakhiri session.
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
            ->with('success', 'Anda berhasil logout.');
    }
}
