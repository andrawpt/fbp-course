<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id',
        'address_id',
        'order_number',
        'order_date',
        'subtotal',
        'shipping_cost',
        'total',
        'order_status',
        'notes',
    ];

    /**
     * Ambil daftar pesanan milik user beserta status pembayaran.
     *
     * @return list<array<string, mixed>>
     */
    public function getOrdersByUser(int $userId): array
    {
        return $this->db->table('orders o')
            ->select('o.*, p.payment_status, s.shipment_status, s.courier, s.tracking_number')
            ->join('payments p', 'p.order_id = o.id', 'left')
            ->join('shipments s', 's.order_id = o.id', 'left')
            ->where('o.user_id', $userId)
            ->orderBy('o.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil detail lengkap pesanan milik user (BR-006: hanya milik user bersangkutan).
     */
    public function getOrderDetailForUser(string $orderNumber, int $userId): ?array
    {
        $order = $this->db->table('orders o')
            ->select('o.*, a.recipient_name, a.phone, a.address, a.city, a.province, a.postal_code')
            ->join('addresses a', 'a.id = o.address_id', 'inner')
            ->where('o.order_number', $orderNumber)
            ->where('o.user_id', $userId)
            ->get()
            ->getRowArray();

        if (! is_array($order)) {
            return null;
        }

        $order['items'] = $this->db->table('order_items')
            ->where('order_id', $order['id'])
            ->get()
            ->getResultArray();

        $order['payment'] = $this->db->table('payments')
            ->where('order_id', $order['id'])
            ->get()
            ->getRowArray();

        $order['shipment'] = $this->db->table('shipments')
            ->where('order_id', $order['id'])
            ->get()
            ->getRowArray();

        return $order;
    }

    /**
     * Ambil semua pesanan untuk Admin/Operator beserta data customer, pembayaran, dan pengiriman.
     *
     * @return list<array<string, mixed>>
     */
    public function getAllOrdersForStaff(?string $statusFilter = null): array
    {
        $builder = $this->db->table('orders o')
            ->select('o.*, u.name AS customer_name, u.email AS customer_email, p.payment_status, p.proof_file, s.shipment_status, s.courier, s.tracking_number')
            ->join('users u', 'u.id = o.user_id', 'inner')
            ->join('payments p', 'p.order_id = o.id', 'left')
            ->join('shipments s', 's.order_id = o.id', 'left');

        if ($statusFilter !== null && $statusFilter !== '') {
            $builder->where('o.order_status', $statusFilter);
        }

        return $builder->orderBy('o.id', 'DESC')->get()->getResultArray();
    }

    /**
     * Ambil detail lengkap pesanan untuk Admin/Operator berdasarkan nomor pesanan.
     */
    public function getOrderDetailForStaff(string $orderNumber): ?array
    {
        $order = $this->db->table('orders o')
            ->select('o.*, u.name AS customer_name, u.email AS customer_email, a.recipient_name, a.phone, a.address, a.city, a.province, a.postal_code')
            ->join('users u', 'u.id = o.user_id', 'inner')
            ->join('addresses a', 'a.id = o.address_id', 'inner')
            ->where('o.order_number', $orderNumber)
            ->get()
            ->getRowArray();

        if (! is_array($order)) {
            return null;
        }

        $order['items'] = $this->db->table('order_items')
            ->where('order_id', $order['id'])
            ->get()
            ->getResultArray();

        $order['payment'] = $this->db->table('payments')
            ->where('order_id', $order['id'])
            ->get()
            ->getRowArray();

        $order['shipment'] = $this->db->table('shipments')
            ->where('order_id', $order['id'])
            ->get()
            ->getRowArray();

        return $order;
    }
}
