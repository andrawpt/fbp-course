<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class OrderController extends BaseController
{
    /**
     * Menampilkan daftar seluruh pesanan untuk Admin (FR-015).
     */
    public function index(): string
    {
        $status     = trim((string) $this->request->getGet('status'));
        $orderModel = new OrderModel();
        $orders     = $orderModel->getAllOrdersForStaff($status !== '' ? $status : null);

        return view('admin/orders', [
            'title'          => 'Kelola Pesanan & Verifikasi – Admin ShopEase',
            'orders'         => $orders,
            'selectedStatus' => $status,
        ]);
    }

    /**
     * Menampilkan detail pesanan & bukti pembayaran untuk diverifikasi Admin.
     */
    public function detail(string $orderNumber): string
    {
        $orderModel = new OrderModel();
        $order      = $orderModel->getOrderDetailForStaff($orderNumber);

        if ($order === null) {
            throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        }

        return view('admin/order_detail', [
            'title' => 'Verifikasi Pesanan ' . $order['order_number'] . ' – Admin ShopEase',
            'order' => $order,
        ]);
    }

    /**
     * Memverifikasi atau menolak bukti pembayaran (FR-015, BR-007, BR-012, AC-11).
     */
    public function verifyPayment(string $orderNumber): RedirectResponse
    {
        $orderModel = new OrderModel();
        $order      = $orderModel->getOrderDetailForStaff($orderNumber);

        if ($order === null || ! is_array($order['payment'])) {
            return redirect()->to(site_url('admin/pesanan'))
                ->with('error', 'Data pesanan atau pembayaran tidak ditemukan.');
        }

        $decision = (string) $this->request->getPost('decision');
        if (! in_array($decision, ['verify', 'reject'], true)) {
            return redirect()->back()->with('error', 'Keputusan verifikasi tidak valid.');
        }

        $adminId = (int) session()->get('user_id');
        $now     = date('Y-m-d H:i:s');

        $paymentModel = new PaymentModel();

        if ($decision === 'verify') {
            $paymentModel->update($order['payment']['id'], [
                'payment_status' => 'verified',
                'verified_by'    => $adminId,
                'verified_at'    => $now,
            ]);

            $orderModel->update($order['id'], [
                'order_status' => 'processing',
            ]);

            return redirect()->to(site_url('admin/pesanan/' . $orderNumber))
                ->with('success', 'Pembayaran berhasil diverifikasi! Status pesanan kini "processing" dan diteruskan ke Operator.');
        }

        $paymentModel->update($order['payment']['id'], [
            'payment_status' => 'rejected',
            'verified_by'    => $adminId,
            'verified_at'    => $now,
        ]);

        $orderModel->update($order['id'], [
            'order_status' => 'pending_payment',
        ]);

        return redirect()->to(site_url('admin/pesanan/' . $orderNumber))
            ->with('success', 'Bukti pembayaran ditolak. Customer dapat mengunggah ulang bukti pembayaran.');
    }
}
