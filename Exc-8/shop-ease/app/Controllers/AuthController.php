<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class AuthController extends BaseController
{
    /**
     * Menampilkan halaman login.
     */
    public function login(): string|RedirectResponse
    {
        if (session()->get('is_logged_in')) {
            return $this->redirectByRole((string) session()->get('user_role'));
        }

        return view('auth/login', [
            'title' => 'Masuk – ShopEase',
        ]);
    }

    /**
     * Memproses autentikasi pengguna (FR-002).
     */
    public function attemptLogin(): RedirectResponse
    {
        $rules = [
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                ],
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $userModel = new UserModel();
        $user      = $userModel->findActiveByEmail($email);

        // Pesan kesalahan generik agar tidak membocorkan apakah email terdaftar (PRD Bagian 13)
        if ($user === null || ! password_verify($password, (string) $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password tidak sesuai.');
        }

        $session = session();
        if (ENVIRONMENT !== 'testing') {
            $session->regenerate(true);
        }
        $session->set([
            'user_id'      => (int) $user['id'],
            'user_name'    => (string) $user['name'],
            'user_email'   => (string) $user['email'],
            'user_role'    => (string) $user['role'],
            'is_logged_in' => true,
        ]);

        return $this->redirectByRole((string) $user['role'])
            ->with('success', 'Selamat datang kembali, ' . $user['name'] . '!');
    }

    /**
     * Arahkan pengguna ke halaman sesuai role masing-masing.
     */
    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin'    => redirect()->to(site_url('admin')),
            'operator' => redirect()->to(site_url('operator')),
            default    => redirect()->to(site_url('/')),
        };
    }

    /**
     * Menampilkan halaman registrasi customer.
     */
    public function register(): string|RedirectResponse
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(site_url('/'));
        }

        return view('auth/register', [
            'title' => 'Daftar Akun – ShopEase',
        ]);
    }

    /**
     * Memproses pendaftaran akun customer baru (FR-001).
     */
    public function attemptRegister(): RedirectResponse
    {
        $rules = [
            'name' => [
                'label'  => 'Nama Lengkap',
                'rules'  => 'required|min_length[3]|max_length[120]',
                'errors' => [
                    'required'   => 'Nama lengkap wajib diisi.',
                    'min_length' => 'Nama lengkap minimal 3 karakter.',
                    'max_length' => 'Nama lengkap maksimal 120 karakter.',
                ],
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email|max_length[150]|is_unique[users.email]',
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique'   => 'Email ini sudah terdaftar. Silakan gunakan email lain atau masuk.',
                ],
            ],
            'phone' => [
                'label'  => 'Nomor HP',
                'rules'  => 'permit_empty|max_length[30]',
                'errors' => [
                    'max_length' => 'Nomor HP maksimal 30 karakter.',
                ],
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required|min_length[8]',
                'errors' => [
                    'required'   => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 8 karakter.',
                ],
            ],
            'password_confirm' => [
                'label'  => 'Konfirmasi Password',
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches'  => 'Konfirmasi password tidak cocok.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userModel->insert([
            'name'          => trim((string) $this->request->getPost('name')),
            'email'         => mb_strtolower(trim((string) $this->request->getPost('email'))),
            'phone'         => trim((string) $this->request->getPost('phone')) ?: null,
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => 'customer',
            'is_active'     => 1,
        ]);

        return redirect()->to(site_url('login'))
            ->with('success', 'Registrasi berhasil! Silakan masuk dengan email dan password Anda.');
    }

    /**
     * Mengakhiri session pengguna dengan aman (FR-003).
     */
    public function logout(): RedirectResponse
    {
        $session = session();
        $session->remove(['user_id', 'user_name', 'user_email', 'user_role', 'is_logged_in']);
        $session->destroy();

        return redirect()->to(site_url('/'))
            ->with('success', 'Anda telah keluar dari akun.');
    }
}
