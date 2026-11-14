<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class CustomerController extends BaseController
{
    /**
     * Menampilkan Dashboard Customer: Profil pengguna & ringkasan aktivitas (Tahap 2).
     */
    public function index(): string
    {
        $userId    = (int) session()->get('user_id');
        $userModel = new UserModel();
        $user      = $userModel->find($userId);

        $orderModel   = new OrderModel();
        $orders       = $orderModel->getOrdersByUser($userId);
        $totalOrders  = count($orders);
        $pendingCount = count(array_filter($orders, static fn (array $o): bool => $o['order_status'] === 'pending_payment'));
        $activeCount  = count(array_filter($orders, static fn (array $o): bool => in_array($o['order_status'], ['waiting_verification', 'processing', 'shipped'], true)));
        $doneCount    = count(array_filter($orders, static fn (array $o): bool => $o['order_status'] === 'completed'));

        return view('frontend/customer_dashboard', [
            'title'        => 'Dashboard Customer – ShopEase',
            'user'         => $user,
            'cartCount'    => $this->getCartCount(),
            'totalOrders'  => $totalOrders,
            'pendingCount' => $pendingCount,
            'activeCount'  => $activeCount,
            'doneCount'    => $doneCount,
            'recentOrders' => array_slice($orders, 0, 5),
        ]);
    }

    /**
     * Memperbarui profil pengguna customer.
     */
    public function updateProfile(): RedirectResponse
    {
        $rules = [
            'name'    => 'required|min_length[3]|max_length[100]',
            'phone'   => 'permit_empty|max_length[20]',
            'address' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId    = (int) session()->get('user_id');
        $userModel = new UserModel();
        $newName   = trim((string) $this->request->getPost('name'));

        $userModel->update($userId, [
            'name'    => $newName,
            'phone'   => trim((string) $this->request->getPost('phone')) ?: null,
            'address' => trim((string) $this->request->getPost('address')) ?: null,
        ]);

        session()->set('name', $newName);

        return redirect()->to(site_url('akun'))
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
