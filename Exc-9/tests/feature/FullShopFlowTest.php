<?php

use App\Database\Seeds\ShopSeeder;
use App\Database\Seeds\UserSeeder;
use App\Models\OrderModel;
use App\Models\ProductModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class FullShopFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate   = true;
    protected $namespace = 'App';
    protected $seed      = [UserSeeder::class, ShopSeeder::class];

    public function testHomeLandingAndProductDetailRender(): void
    {
        $this->get('/')->assertOK();
        $this->get('produk/headphone-wireless-studio-pro')->assertOK();
    }

    public function testEndToEndStages2Through6Flow(): void
    {
        $customer = $this->db->table('users')->where('email', 'customer@shopease.com')->get()->getRowArray();
        $admin    = $this->db->table('users')->where('email', 'admin@shopease.com')->get()->getRowArray();
        $operator = $this->db->table('users')->where('email', 'operator@shopease.com')->get()->getRowArray();
        $address  = $this->db->table('addresses')->where('user_id', $customer['id_user'])->get()->getRowArray();

        $customerSession = [
            'isLoggedIn' => true,
            'user_id'    => (int) $customer['id_user'],
            'name'       => $customer['name'],
            'email'      => $customer['email'],
            'role'       => 'customer',
        ];

        // Tahap 2: Dashboard Customer
        $dashboard = $this->withSession($customerSession)->get('akun');
        $dashboard->assertOK();
        $dashboard->assertSee('Dashboard Customer');

        // Tahap 4 & 5: Shopping Cart & Checkout
        $productModel = new ProductModel();
        $product      = $productModel->findActiveBySlug('headphone-wireless-studio-pro');
        $this->assertNotNull($product);

        $this->withSession($customerSession)->post('keranjang/tambah', [
            'product_id' => $product['id'],
            'quantity'   => 1,
        ])->assertRedirect();

        $this->withSession($customerSession)->post('checkout', [
            'address_mode'   => 'saved',
            'address_id'     => $address['id'],
            'payment_method' => 'transfer_bank',
        ])->assertRedirect();

        $orderModel = new OrderModel();
        $orders     = $orderModel->getOrdersByUser((int) $customer['id_user']);
        $this->assertCount(1, $orders);
        $order = $orders[0];

        // Simulasikan bukti pembayaran diunggah
        $this->db->table('payments')->where('order_id', $order['id'])->update([
            'proof_file'     => 'uploads/payments/sample.jpg',
            'payment_status' => 'submitted',
        ]);
        $this->db->table('orders')->where('id', $order['id'])->update([
            'order_status' => 'waiting_verification',
        ]);

        // Tahap 6: Admin verifikasi -> Operator kirim & selesaikan -> Customer lihat status
        $adminSession = [
            'isLoggedIn' => true,
            'user_id'    => (int) $admin['id_user'],
            'name'       => $admin['name'],
            'email'      => $admin['email'],
            'role'       => 'admin',
        ];

        $this->withSession($adminSession)
            ->post('admin/pesanan/' . $order['order_number'] . '/verifikasi', ['decision' => 'verify'])
            ->assertRedirect();

        $operatorSession = [
            'isLoggedIn' => true,
            'user_id'    => (int) $operator['id_user'],
            'name'       => $operator['name'],
            'email'      => $operator['email'],
            'role'       => 'operator',
        ];

        $this->withSession($operatorSession)
            ->post('operator/pesanan/' . $order['order_number'] . '/kirim', [
                'action'          => 'ship',
                'courier'         => 'JNE',
                'tracking_number' => 'JNE123456',
            ])
            ->assertRedirect();

        $this->withSession($operatorSession)
            ->post('operator/pesanan/' . $order['order_number'] . '/kirim', ['action' => 'complete'])
            ->assertRedirect();

        $customerDetail = $this->withSession($customerSession)->get('pesanan/' . $order['order_number']);
        $customerDetail->assertOK();
        $customerDetail->assertSee('completed');
        $customerDetail->assertSee('JNE123456');
    }
}
