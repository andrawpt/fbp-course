<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    public const POSITIONS = [
        'Admin Toko',
        'Staf Gudang',
        'Customer Service',
        'Kurir',
        'Kasir',
    ];

    protected $table            = 'karyawan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nip',
        'name',
        'position',
        'email',
        'phone',
        'hire_date',
        'status',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
