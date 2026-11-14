<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\PaymentModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class OrderController extends BaseController
{
    /**
     * Menampilkan daftar riwayat pesanan milik customer (FR-017, BR-006).
     */
    public function index(): string
    {
        $userId     = (int) session()->get('user_id');
        $orderModel = new OrderModel();
        $orders     = $orderModel->getOrdersByUser($userId);

        return view('frontend/orders', [
            'title'     => 'Riwayat Pesanan – ShopEase',
            'orders'    => $orders,
            'cartCount' => $this->getCartCount(),
        ]);
    }

    /**
     * Menampilkan detail pesanan milik customer (BR-006).
     */
    public function detail(string $orderNumber): string
    {
        $userId     = (int) session()->get('user_id');
        $orderModel = new OrderModel();
        $order      = $orderModel->getOrderDetailForUser($orderNumber, $userId);

        if ($order === null) {
            throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        }

        return view('frontend/order_detail', [
            'title'     => 'Detail Pesanan ' . $order['order_number'] . ' – ShopEase',
            'order'     => $order,
            'cartCount' => $this->getCartCount(),
        ]);
    }

    /**
     * Mengunggah bukti pembayaran manual (FR-014, PRD Bagian 13).
     */
    public function uploadPayment(string $orderNumber): RedirectResponse
    {
        $userId     = (int) session()->get('user_id');
        $orderModel = new OrderModel();
        $order      = $orderModel->getOrderDetailForUser($orderNumber, $userId);

        if ($order === null) {
            return redirect()->to(site_url('pesanan'))
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        if (! in_array($order['order_status'], ['pending_payment', 'waiting_verification'], true)) {
            return redirect()->back()
                ->with('error', 'Pesanan ini tidak lagi menerima unggahan bukti pembayaran.');
        }

        $rules = [
            'proof_file' => [
                'label' => 'Bukti Pembayaran',
                'rules' => 'uploaded[proof_file]|max_size[proof_file,2048]|ext_in[proof_file,jpg,jpeg,png,pdf]|mime_in[proof_file,image/jpeg,image/png,application/pdf]',
                'errors' => [
                    'uploaded' => 'Silakan pilih file bukti pembayaran terlebih dahulu.',
                    'max_size' => 'Ukuran file maksimal 2 MB.',
                    'ext_in'   => 'Format file harus JPG, JPEG, PNG, atau PDF.',
                    'mime_in'  => 'Tipe file tidak diizinkan.',
                ],
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('proof_file');
        if ($file === null || ! $file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'File gagal diunggah.');
        }

        $uploadDir = FCPATH . 'uploads/payments';
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadDir, $newName);

        $paymentModel = new PaymentModel();
        if (is_array($order['payment'])) {
            $paymentModel->update($order['payment']['id'], [
                'proof_file'     => 'uploads/payments/' . $newName,
                'payment_status' => 'submitted',
            ]);
        }

        $orderModel->update($order['id'], [
            'order_status' => 'waiting_verification',
        ]);

        return redirect()->to(site_url('pesanan/' . $orderNumber))
            ->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi admin.');
    }
}
