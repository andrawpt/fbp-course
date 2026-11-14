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
final class ShoppingFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate   = true;
    protected $namespace = 'App';
    protected $seed      = [UserSeeder::class, ShopSeeder::class];

    public function testProductDetailPageRenders(): void
    {
        $result = $this->get('produk/headphone-wireless-studio-pro');

        $result->assertOK();
        $result->assertSee('Headphone Wireless Studio Pro');
        $result->assertSee('Tambah ke Keranjang');
    }

    public function testGuestIsRedirectedToLoginWhenAddingToCart(): void
    {
        $result = $this->post('keranjang/tambah', [
            'product_id' => 1,
            'quantity'   => 1,
        ]);

        $result->assertRedirectTo(site_url('login'));
    }

    public function testFullCustomerShoppingAndCheckoutFlow(): void
    {
        $user    = $this->db->table('users')->where('email', 'customer@shopease.local')->get()->getRowArray();
        $address = $this->db->table('addresses')->where('user_id', $user['id'])->get()->getRowArray();

        $sessionData = [
            'user_id'      => (int) $user['id'],
            'user_name'    => $user['name'],
            'user_email'   => $user['email'],
            'user_role'    => 'customer',
            'is_logged_in' => true,
        ];

        $productModel = new ProductModel();
        $product      = $productModel->findActiveBySlug('headphone-wireless-studio-pro');
        $this->assertNotNull($product);
        $initialStock = (int) $product['stock'];

        // 2. Tambah 2 unit produk ke keranjang
        $this->withSession($sessionData)->post('keranjang/tambah', [
            'product_id' => $product['id'],
            'quantity'   => 2,
        ])->assertRedirect();

        // 3. Cek halaman keranjang
        $cartPage = $this->withSession($sessionData)->get('keranjang');
        $cartPage->assertOK();
        $cartPage->assertSee('Headphone Wireless Studio Pro');

        // 4. Checkout dengan alamat tersimpan
        $checkoutResult = $this->withSession($sessionData)->post('checkout', [
            'address_mode'   => 'saved',
            'address_id'     => $address['id'],
            'payment_method' => 'transfer_bank',
            'notes'          => 'Tolong kirim segera',
        ]);

        $checkoutResult->assertRedirect();

        // 5. Pastikan order tersimpan, stok berkurang, dan keranjang kosong
        $orderModel = new OrderModel();
        $orders     = $orderModel->getOrdersByUser((int) $user['id']);
        $this->assertCount(1, $orders);

        $order = $orders[0];
        $this->assertSame('pending_payment', $order['order_status']);
        $this->assertEquals(485000 * 2 + 15000, (float) $order['total']);

        $updatedProduct = $productModel->find($product['id']);
        $this->assertSame($initialStock - 2, (int) $updatedProduct['stock']);

        // 6. Halaman riwayat & detail pesanan dapat diakses
        $ordersPage = $this->withSession($sessionData)->get('pesanan');
        $ordersPage->assertOK();
        $ordersPage->assertSee($order['order_number']);

        $detailPage = $this->withSession($sessionData)->get('pesanan/' . $order['order_number']);
        $detailPage->assertOK();
        $detailPage->assertSee('Headphone Wireless Studio Pro');
    }
}
