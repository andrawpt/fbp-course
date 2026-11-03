<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nim',
        'nama',
        'email',
        'jurusan',
        'jenis_kelamin',
        'foto',
    ];

    
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    
    protected $validationRules = [
        'nim'           => 'required|min_length[5]|max_length[20]',
        'nama'          => 'required|min_length[3]|max_length[150]',
        'email'         => 'required|valid_email|max_length[150]',
        'jurusan'       => 'required',
        'jenis_kelamin' => 'required|in_list[Laki-laki,Perempuan]',
    ];

    protected $validationMessages = [
        'nim' => [
            'required'   => 'NIM wajib diisi.',
            'min_length' => 'NIM minimal 5 karakter.',
            'is_unique'  => 'NIM ini sudah terdaftar.',
        ],
        'nama' => [
            'required'   => 'Nama mahasiswa wajib diisi.',
            'min_length' => 'Nama minimal 3 karakter.',
        ],
        'email' => [
            'required'    => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
        ],
        'jurusan' => [
            'required' => 'Jurusan wajib dipilih.',
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin wajib dipilih.',
        ],
    ];
}

