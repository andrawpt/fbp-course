<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'password_hash',
        'role',
        'phone',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari user aktif berdasarkan email.
     */
    public function findActiveByEmail(string $email): ?array
    {
        $user = $this->where('email', mb_strtolower(trim($email)))
            ->where('is_active', 1)
            ->first();

        return is_array($user) ? $user : null;
    }
}
