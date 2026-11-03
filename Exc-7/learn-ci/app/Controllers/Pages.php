<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\UserModel;

class Pages extends BaseController
{
    public function dashboard()
    {
        $mahasiswaModel = new MahasiswaModel();
        $userModel = new UserModel();

        $totalMahasiswa = $mahasiswaModel->countAllResults();
        $totalUser      = $userModel->countAllResults();
        $recentMahasiswa = $mahasiswaModel->orderBy('created_at', 'DESC')->findAll(5);

        return view('pages/dashboard', [
            'title'           => 'Dashboard - SIAKAD',
            'totalMahasiswa'  => $totalMahasiswa,
            'totalUser'       => $totalUser,
            'recentMahasiswa' => $recentMahasiswa,
        ]);
    }

    public function about()
    {
        return view('pages/about', ['title' => 'About Us - SIAKAD']);
    }

    public function contact()
    {
        return view('pages/contact', ['title' => 'Contact Us - SIAKAD']);
    }

    public function profile()
    {
        return view('pages/profile', ['title' => 'Profil Saya - SIAKAD']);
    }
}
