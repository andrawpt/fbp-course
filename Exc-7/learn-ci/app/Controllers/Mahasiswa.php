<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Mahasiswa extends BaseController
{
    protected $mahasiswaModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        helper(['form', 'url']);
    }

    
    public function index()
    {
        $data = [
            'title'     => 'Kelola Data Mahasiswa - SIAKAD',
            'mahasiswa' => $this->mahasiswaModel->orderBy('created_at', 'DESC')->findAll(),
        ];

        return view('mahasiswa/index', $data);
    }

    
    public function create()
    {
        $data = [
            'title'      => 'Tambah Mahasiswa - SIAKAD',
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
        ];

        return view('mahasiswa/create', $data);
    }

    
    public function store()
    {
        $rules = [
            'nim' => [
                'rules'  => 'required|min_length[5]|max_length[20]|is_unique[mahasiswa.nim]',
                'errors' => [
                    'required'   => 'NIM wajib diisi.',
                    'min_length' => 'NIM minimal 5 karakter.',
                    'is_unique'  => 'NIM ini sudah terdaftar di sistem.',
                ],
            ],
            'nama' => [
                'rules'  => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required'   => 'Nama lengkap wajib diisi.',
                    'min_length' => 'Nama minimal 3 karakter.',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email|max_length[150]',
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format alamat email tidak valid.',
                ],
            ],
            'jurusan' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Jurusan wajib dipilih.'],
            ],
            'jenis_kelamin' => [
                'rules'  => 'required|in_list[Laki-laki,Perempuan]',
                'errors' => ['required' => 'Jenis kelamin wajib dipilih.'],
            ],
            'foto' => [
                'rules'  => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
                'errors' => [
                    'is_image' => 'File yang diupload harus berupa gambar.',
                    'mime_in'  => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
                    'max_size' => 'Ukuran gambar maksimal adalah 2 MB.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        
        $fotoFile = $this->request->getFile('foto');
        $namaFoto = null;

        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $namaFoto = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/mahasiswa', $namaFoto);
        }

        $this->mahasiswaModel->save([
            'nim'           => $this->request->getPost('nim'),
            'nama'          => $this->request->getPost('nama'),
            'email'         => $this->request->getPost('email'),
            'jurusan'       => $this->request->getPost('jurusan'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'foto'          => $namaFoto,
        ]);

        return redirect()->to(site_url('mahasiswa'))->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }

    
    public function edit($id)
    {
        $mahasiswa = $this->mahasiswaModel->find($id);

        if (!$mahasiswa) {
            throw PageNotFoundException::forPageNotFound('Data mahasiswa tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Mahasiswa - SIAKAD',
            'mahasiswa'  => $mahasiswa,
            'validation' => session()->getFlashdata('validation') ?? \Config\Services::validation(),
        ];

        return view('mahasiswa/edit', $data);
    }

    
    public function update($id)
    {
        $mahasiswa = $this->mahasiswaModel->find($id);

        if (!$mahasiswa) {
            throw PageNotFoundException::forPageNotFound('Data mahasiswa tidak ditemukan.');
        }

        $rules = [
            'nim' => [
                'rules'  => "required|min_length[5]|max_length[20]|is_unique[mahasiswa.nim,id,{$id}]",
                'errors' => [
                    'required'   => 'NIM wajib diisi.',
                    'min_length' => 'NIM minimal 5 karakter.',
                    'is_unique'  => 'NIM ini sudah digunakan mahasiswa lain.',
                ],
            ],
            'nama' => [
                'rules'  => 'required|min_length[3]|max_length[150]',
                'errors' => [
                    'required'   => 'Nama lengkap wajib diisi.',
                    'min_length' => 'Nama minimal 3 karakter.',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email|max_length[150]',
                'errors' => [
                    'required'    => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                ],
            ],
            'jurusan' => [
                'rules'  => 'required',
                'errors' => ['required' => 'Jurusan wajib dipilih.'],
            ],
            'jenis_kelamin' => [
                'rules'  => 'required|in_list[Laki-laki,Perempuan]',
                'errors' => ['required' => 'Jenis kelamin wajib dipilih.'],
            ],
            'foto' => [
                'rules'  => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/webp]|max_size[foto,2048]',
                'errors' => [
                    'is_image' => 'File yang diupload harus berupa gambar.',
                    'mime_in'  => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
                    'max_size' => 'Ukuran gambar maksimal adalah 2 MB.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        
        $fotoFile = $this->request->getFile('foto');
        $namaFoto = $mahasiswa['foto'];

        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            
            if (!empty($mahasiswa['foto']) && file_exists(FCPATH . 'uploads/mahasiswa/' . $mahasiswa['foto'])) {
                unlink(FCPATH . 'uploads/mahasiswa/' . $mahasiswa['foto']);
            }

            $namaFoto = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/mahasiswa', $namaFoto);
        }

        $this->mahasiswaModel->update($id, [
            'nim'           => $this->request->getPost('nim'),
            'nama'          => $this->request->getPost('nama'),
            'email'         => $this->request->getPost('email'),
            'jurusan'       => $this->request->getPost('jurusan'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'foto'          => $namaFoto,
        ]);

        return redirect()->to(site_url('mahasiswa'))->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    
    public function delete($id)
    {
        $mahasiswa = $this->mahasiswaModel->find($id);

        if ($mahasiswa) {
            
            if (!empty($mahasiswa['foto']) && file_exists(FCPATH . 'uploads/mahasiswa/' . $mahasiswa['foto'])) {
                unlink(FCPATH . 'uploads/mahasiswa/' . $mahasiswa['foto']);
            }

            $this->mahasiswaModel->delete($id);
            return redirect()->to(site_url('mahasiswa'))->with('success', 'Data mahasiswa beserta foto berhasil dihapus!');
        }

        return redirect()->to(site_url('mahasiswa'))->with('error', 'Data mahasiswa tidak ditemukan.');
    }
}

