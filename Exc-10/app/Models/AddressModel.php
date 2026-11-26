<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table            = 'addresses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id',
        'recipient_name',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'is_default',
    ];

    /**
     * Ambil seluruh alamat milik customer tertentu.
     *
     * @return list<array<string, mixed>>
     */
    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('is_default', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Cari alamat berdasarkan ID dan kepemilikan user.
     */
    public function findOwned(int $addressId, int $userId): ?array
    {
        $row = $this->where('id', $addressId)
            ->where('user_id', $userId)
            ->first();

        return is_array($row) ? $row : null;
    }

    /**
     * Cek apakah alamat sudah dipakai pada tabel orders.
     */
    public function isUsedInOrders(int $addressId): bool
    {
        return $this->db->table('orders')
            ->where('address_id', $addressId)
            ->countAllResults() > 0;
    }
}
