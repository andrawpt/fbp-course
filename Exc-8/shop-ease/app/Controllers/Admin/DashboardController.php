<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;

class DashboardController extends BaseController
{
    /**
     * Menampilkan Dashboard Administrator (FR-018).
     */
    public function index(): string
    {
        $db = \Config\Database::connect();

        $totalProducts  = $db->table('products')->where('status', 'active')->countAllResults();
        $totalCustomers = $db->table('users')->where('role', 'customer')->countAllResults();
        $totalOrders    = $db->table('orders')->countAllResults();
        $waitingVerify  = $db->table('orders')->where('order_status', 'waiting_verification')->countAllResults();

        $salesRow = $db->table('orders o')
            ->selectSum('o.total', 'total_sales')
            ->join('payments p', 'p.order_id = o.id', 'inner')
            ->where('p.payment_status', 'verified')
            ->get()
            ->getRowArray();

        $orderModel   = new OrderModel();
        $recentOrders = array_slice($orderModel->getAllOrdersForStaff(), 0, 8);

        return view('admin/dashboard', [
            'title'          => 'Admin Dashboard – ShopEase',
            'totalProducts'  => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalOrders'    => $totalOrders,
            'waitingVerify'  => $waitingVerify,
            'totalSales'     => (float) ($salesRow['total_sales'] ?? 0),
            'recentOrders'   => $recentOrders,
        ]);
    }
}
