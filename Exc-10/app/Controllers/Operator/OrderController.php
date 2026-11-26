<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class OrderController extends BaseController
{
    /**
     * Halaman utama Operator Toko: antrean pemenuhan & pengiriman pesanan (FR-016).
     */
    public function index(): string
    {
        $status     = trim((string) $this->request->getGet('status'));
        $orderModel = new OrderModel();

        $allOrders = $orderModel->getAllOrdersForStaff($status !== '' ? $status : null);

        // Operator berfokus pada pesanan yang sudah terverifikasi (processing, shipped, completed)
        $fulfillmentOrders = array_values(array_filter(
            $allOrders,
            static fn (array $o): bool => in_array($o['order_status'], ['processing', 'shipped', 'completed'], true)
        ));

        $db              = \Config\Database::connect();
        $countProcessing = $db->table('orders')->where('order_status', 'processing')->countAllResults();
        $countShipped    = $db->table('orders')->where('order_status', 'shipped')->countAllResults();
        $countCompleted  = $db->table('orders')->where('order_status', 'completed')->countAllResults();

        return view('operator/orders', [
            'title'           => 'Panel Operator – Pemenuhan & Pengiriman ShopEase',
            'orders'          => $fulfillmentOrders,
            'selectedStatus'  => $status,
            'countProcessing' => $countProcessing,
            'countShipped'    => $countShipped,
            'countCompleted'  => $countCompleted,
        ]);
    }

    /**
     * Detail pesanan untuk disiapkan & dikirim oleh Operator.
     */
    public function detail(string $orderNumber): string
    {
        $orderModel = new OrderModel();
        $order      = $orderModel->getOrderDetailForStaff($orderNumber);

        if ($order === null) {
            throw PageNotFoundException::forPageNotFound('Pesanan tidak ditemukan.');
        }

        return view('operator/order_detail', [
            'title' => 'Pemenuhan Pesanan ' . $order['order_number'] . ' – Operator ShopEase',
            'order' => $order,
        ]);
    }

    /**
     * Memperbarui kurir, nomor resi, dan status pengiriman (FR-016, AC-12).
     */
    public function updateShipment(string $orderNumber): RedirectResponse
    {
        $orderModel = new OrderModel();
        $order      = $orderModel->getOrderDetailForStaff($orderNumber);

        if ($order === null) {
            return redirect()->to(site_url('operator'))
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        if (! in_array($order['order_status'], ['processing', 'shipped'], true)) {
            return redirect()->back()
                ->with('error', 'Pesanan belum diverifikasi pembayarannya atau sudah selesai.');
        }

        $action         = (string) $this->request->getPost('action');
        $courier        = trim((string) $this->request->getPost('courier'));
        $trackingNumber = trim((string) $this->request->getPost('tracking_number'));

        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        if ($action === 'ship') {
            if ($courier === '' || $trackingNumber === '') {
                return redirect()->back()->withInput()
                    ->with('error', 'Kurir dan nomor resi wajib diisi saat mengirim pesanan.');
            }

            $db->table('shipments')
                ->where('order_id', $order['id'])
                ->update([
                    'courier'         => $courier,
                    'tracking_number' => $trackingNumber,
                    'shipment_status' => 'shipped',
                    'shipped_at'      => $now,
                ]);

            $orderModel->update($order['id'], [
                'order_status' => 'shipped',
            ]);

            return redirect()->to(site_url('operator/pesanan/' . $orderNumber))
                ->with('success', 'Pesanan telah dikirim via ' . $courier . ' (Resi: ' . $trackingNumber . ').');
        }

        if ($action === 'complete') {
            $db->table('shipments')
                ->where('order_id', $order['id'])
                ->update([
                    'shipment_status' => 'delivered',
                    'delivered_at'    => $now,
                ]);

            $orderModel->update($order['id'], [
                'order_status' => 'completed',
            ]);

            return redirect()->to(site_url('operator/pesanan/' . $orderNumber))
                ->with('success', 'Pesanan ditandai selesai (completed).');
        }

        return redirect()->back()->with('error', 'Aksi pembaruan tidak valid.');
    }
}
