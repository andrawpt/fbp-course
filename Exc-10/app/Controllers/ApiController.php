<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Services\ShopService;
use CodeIgniter\HTTP\ResponseInterface;

class ApiController extends BaseController
{
    private ShopService $shopService;

    public function __construct()
    {
        $this->shopService = new ShopService();
    }

    public function session(): ResponseInterface
    {
        $session = session();

        return $this->response->setJSON([
            'authenticated' => (bool) $session->get('isLoggedIn'),
            'user'          => $session->get('isLoggedIn') ? [
                'id'    => (int) $session->get('user_id'),
                'name'  => $session->get('name'),
                'email' => $session->get('email'),
                'role'  => $session->get('role'),
            ] : null,
            'csrf_token'    => csrf_hash(),
            'csrf_header'   => csrf_header(),
        ]);
    }

    public function catalog(): ResponseInterface
    {
        $keyword      = trim((string) $this->request->getGet('q'));
        $categorySlug = trim((string) $this->request->getGet('category'));

        return $this->response->setJSON([
            'categories' => (new CategoryModel())->getActiveWithProductCount(),
            'products'   => (new ProductModel())->getActiveCatalog($keyword, $categorySlug),
        ]);
    }

    public function addresses(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');

        return $this->response->setJSON([
            'addresses' => $this->shopService->listAddresses($userId),
        ]);
    }

    public function createAddress(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $result = $this->shopService->createAddress($userId, $this->getRequestPayload());

        return $this->response->setStatusCode($result['status'])->setJSON($result);
    }

    public function updateAddress(int $id): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $result = $this->shopService->updateAddress($userId, $id, $this->getRequestPayload());

        return $this->response->setStatusCode($result['status'])->setJSON($result);
    }

    public function deleteAddress(int $id): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $result = $this->shopService->deleteAddress($userId, $id);

        return $this->response->setStatusCode($result['status'])->setJSON($result);
    }

    public function cart(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');

        return $this->response->setJSON($this->shopService->getCart($userId));
    }

    public function upsertCart(): ResponseInterface
    {
        $userId    = (int) session()->get('user_id');
        $payload   = $this->getRequestPayload();
        $productId = (int) ($payload['product_id'] ?? 0);
        $quantity  = (int) ($payload['quantity'] ?? 0);
        $mode      = (string) ($payload['mode'] ?? 'set');

        $result = $this->shopService->upsertCartItem($userId, $productId, $quantity, $mode);

        return $this->response->setStatusCode($result['status'])->setJSON($result);
    }

    public function profile(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $result = $this->shopService->updateProfile($userId, $this->getRequestPayload());

        return $this->response->setStatusCode($result['status'])->setJSON($result);
    }

    public function checkout(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $result = $this->shopService->checkout($userId, $this->getRequestPayload());

        return $this->response->setStatusCode($result['status'])->setJSON($result);
    }

    public function orders(): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $orders = (new OrderModel())->getOrdersByUser($userId);

        return $this->response->setJSON(['orders' => $orders]);
    }

    public function orderDetail(string $orderNumber): ResponseInterface
    {
        $userId = (int) session()->get('user_id');
        $order  = (new OrderModel())->getOrderDetail($orderNumber, $userId);

        if ($order === null) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'message' => 'Pesanan tidak ditemukan.']);
        }

        return $this->response->setJSON(['order' => $order]);
    }

    public function orderAction(): ResponseInterface
    {
        $payload     = $this->getRequestPayload();
        $orderNumber = trim((string) ($payload['order_number'] ?? ''));
        $action      = trim((string) ($payload['action'] ?? ''));
        $role        = (string) session()->get('role');
        $userId      = (int) session()->get('user_id');

        $orderModel = new OrderModel();
        $order      = $orderModel->where('order_number', $orderNumber)->first();

        if (! is_array($order)) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'message' => 'Pesanan tidak ditemukan.']);
        }

        if ($action === 'complete' && $role === 'customer' && (int) $order['user_id'] === $userId && $order['order_status'] === 'shipped') {
            $orderModel->update($order['id'], ['order_status' => 'completed']);
            \Config\Database::connect()->table('shipments')
                ->where('order_id', $order['id'])
                ->update(['shipment_status' => 'delivered', 'delivered_at' => date('Y-m-d H:i:s')]);

            return $this->response->setJSON(['ok' => true, 'message' => 'Pesanan diselesaikan.']);
        }

        return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'Aksi pesanan tidak valid untuk peran atau status saat ini.']);
    }

    /**
     * Legacy Admin API untuk kategori.
     */
    public function adminCategory(): ResponseInterface
    {
        $payload = $this->getRequestPayload();
        $action  = (string) ($payload['action'] ?? 'create');
        $model   = new CategoryModel();

        if ($action === 'create') {
            $name = trim((string) ($payload['name'] ?? ''));
            if (mb_strlen($name) < 3) {
                return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'Nama kategori minimal 3 karakter.']);
            }
            $id = $model->insert([
                'name'        => $name,
                'slug'        => url_title($name, '-', true) . '-' . bin2hex(random_bytes(2)),
                'description' => trim((string) ($payload['description'] ?? '')) ?: null,
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
            ], true);

            return $this->response->setStatusCode(201)->setJSON(['ok' => true, 'id' => $id]);
        }

        return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'Aksi tidak didukung.']);
    }

    /**
     * Legacy Admin API untuk produk (aksi delete mengubah status menjadi inactive).
     */
    public function adminProduct(): ResponseInterface
    {
        $payload = $this->getRequestPayload();
        $action  = (string) ($payload['action'] ?? '');
        $id      = (int) ($payload['id'] ?? 0);
        $model   = new ProductModel();

        if ($action === 'delete' && $id > 0) {
            $product = $model->find($id);
            if (! is_array($product)) {
                return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'message' => 'Produk tidak ditemukan.']);
            }

            $model->update($id, ['status' => 'inactive']);

            return $this->response->setJSON(['ok' => true, 'message' => 'Produk dinonaktifkan.', 'status' => 'inactive']);
        }

        return $this->response->setStatusCode(422)->setJSON(['ok' => false, 'message' => 'Aksi tidak didukung.']);
    }

    /**
     * @return array<string, mixed>
     */
    private function getRequestPayload(): array
    {
        $json = $this->request->getJSON(true);
        if (is_array($json)) {
            return $json;
        }

        $post = $this->request->getPost();

        return is_array($post) ? $post : [];
    }
}
