<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KaryawanModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class KaryawanController extends BaseController
{
    public function index(): string
    {
        $keyword  = trim((string) $this->request->getGet('q'));
        $position = trim((string) $this->request->getGet('position'));

        $model = new KaryawanModel();

        if ($keyword !== '') {
            $model->groupStart()
                ->like('name', $keyword)
                ->orLike('nip', $keyword)
                ->groupEnd();
        }

        if ($position !== '') {
            $model->where('position', $position);
        }

        $karyawanList = $model->orderBy('id', 'DESC')->paginate(8);

        return view('admin/karyawan/index', [
            'title'        => 'Kelola Data Karyawan – ShopEase',
            'karyawanList' => $karyawanList,
            'pager'        => $model->pager,
            'positions'    => KaryawanModel::POSITIONS,
            'keyword'      => $keyword,
            'position'     => $position,
        ]);
    }

    public function create(): string
    {
        return view('admin/karyawan/form', [
            'title'     => 'Tambah Karyawan – ShopEase',
            'karyawan'  => null,
            'positions' => KaryawanModel::POSITIONS,
            'action'    => site_url('admin/karyawan/store'),
        ]);
    }

    public function store(): RedirectResponse
    {
        $positionsList = implode(',', KaryawanModel::POSITIONS);
        $rules = [
            'nip'       => 'required|numeric|min_length[8]|max_length[20]|is_unique[karyawan.nip]',
            'name'      => 'required|min_length[3]|max_length[100]',
            'position'  => 'required|in_list[' . $positionsList . ']',
            'email'     => 'required|valid_email|max_length[100]|is_unique[karyawan.email]',
            'phone'     => 'required|numeric|min_length[10]|max_length[15]',
            'hire_date' => 'required|valid_date[Y-m-d]',
            'status'    => 'required|in_list[aktif,nonaktif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));

        (new KaryawanModel())->insert([
            'nip'       => trim((string) $this->request->getPost('nip')),
            'name'      => $name,
            'position'  => trim((string) $this->request->getPost('position')),
            'email'     => strtolower(trim((string) $this->request->getPost('email'))),
            'phone'     => trim((string) $this->request->getPost('phone')),
            'hire_date' => trim((string) $this->request->getPost('hire_date')),
            'status'    => trim((string) $this->request->getPost('status')),
        ]);

        return redirect()->to(site_url('admin/karyawan'))
            ->with('success', 'Data karyawan "' . $name . '" berhasil ditambahkan.');
    }

    public function edit(int $id): string
    {
        $karyawan = (new KaryawanModel())->find($id);
        if (! is_array($karyawan)) {
            throw PageNotFoundException::forPageNotFound('Data karyawan tidak ditemukan.');
        }

        return view('admin/karyawan/form', [
            'title'     => 'Edit Karyawan – ShopEase',
            'karyawan'  => $karyawan,
            'positions' => KaryawanModel::POSITIONS,
            'action'    => site_url('admin/karyawan/update/' . $id),
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $model    = new KaryawanModel();
        $karyawan = $model->find($id);

        if (! is_array($karyawan)) {
            throw PageNotFoundException::forPageNotFound('Data karyawan tidak ditemukan.');
        }

        $positionsList = implode(',', KaryawanModel::POSITIONS);
        $rules = [
            'nip'       => 'required|numeric|min_length[8]|max_length[20]|is_unique[karyawan.nip,id,' . $id . ']',
            'name'      => 'required|min_length[3]|max_length[100]',
            'position'  => 'required|in_list[' . $positionsList . ']',
            'email'     => 'required|valid_email|max_length[100]|is_unique[karyawan.email,id,' . $id . ']',
            'phone'     => 'required|numeric|min_length[10]|max_length[15]',
            'hire_date' => 'required|valid_date[Y-m-d]',
            'status'    => 'required|in_list[aktif,nonaktif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));

        $model->update($id, [
            'nip'       => trim((string) $this->request->getPost('nip')),
            'name'      => $name,
            'position'  => trim((string) $this->request->getPost('position')),
            'email'     => strtolower(trim((string) $this->request->getPost('email'))),
            'phone'     => trim((string) $this->request->getPost('phone')),
            'hire_date' => trim((string) $this->request->getPost('hire_date')),
            'status'    => trim((string) $this->request->getPost('status')),
        ]);

        return redirect()->to(site_url('admin/karyawan'))
            ->with('success', 'Data karyawan "' . $name . '" berhasil diperbarui.');
    }

    public function delete(int $id): RedirectResponse
    {
        $model    = new KaryawanModel();
        $karyawan = $model->find($id);

        if (! is_array($karyawan)) {
            return redirect()->to(site_url('admin/karyawan'))
                ->with('error', 'Data karyawan tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to(site_url('admin/karyawan'))
            ->with('success', 'Data karyawan "' . $karyawan['name'] . '" berhasil dihapus.');
    }
}
