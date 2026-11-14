<?php

use App\Database\Seeds\ShopSeeder;
use App\Database\Seeds\UserSeeder;
use App\Models\OrderModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class RoleAndVerificationFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate   = true;
    protected $namespace = 'App';
    protected $seed      = [UserSeeder::class, ShopSeeder::class];

    public function testLoginRedirectsBasedOnRole(): void
    {
        $adminLogin = $this->post('login', [
            'email'    => 'admin@shopease.local',
            'password' => 'Admin123!',
        ]);
        $adminLogin->assertRedirectTo(site_url('admin'));

        $operatorLogin = $this->post('login', [
            'email'    => 'operator@shopease.local',
            'password' => 'Operator123!',
        ]);
        $operatorLogin->assertRedirectTo(site_url('operator'));
    }

    public function testRoleAccessIsolation(): void
    {
        $customerSession = [
            'user_id'      => 1,
            'user_name'    => 'Budi Santoso',
            'user_email'   => 'customer@shopease.local',
            'user_role'    => 'customer',
            'is_logged_in' => true,
        ];

        $this->withSession($customerSession)->get('admin')->assertRedirectTo(site_url('/'));
        $this->withSession($customerSession)->get('operator')->assertRedirectTo(site_url('/'));

        $operatorSession = [
            'user_id'      => 3,
            'user_name'    => 'Operator Gudang',
            'user_email'   => 'operator@shopease.local',
            'user_role'    => 'operator',
            'is_logged_in' => true,
        ];

        $this->withSession($operatorSession)->get('admin')->assertRedirectTo(site_url('/'));
        $operatorPage = $this->withSession($operatorSession)->get('operator');
        $operatorPage->assertOK();
        $operatorPage->assertSee('OPERATOR TOKO');
    }

    public function testConnectedFlowFromOrderToAdminVerificationToOperatorShipment(): void
    {
        $customer = $this->db->table('users')->where('email', 'customer@shopease.local')->get()->getRowArray();
        $admin    = $this->db->table('users')->where('email', 'admin@shopease.local')->get()->getRowArray();
        $operator = $this->db->table('users')->where('email', 'operator@shopease.local')->get()->getRowArray();
        $address  = $this->db->table('addresses')->where('user_id', $customer['id'])->get()->getRowArray();

        $customerSession = [
            'user_id'      => (int) $customer['id'],
            'user_name'    => $customer['name'],
            'user_email'   => $customer['email'],
            'user_role'    => 'customer',
            'is_logged_in' => true,
        ];

        // 1. Customer tambah ke keranjang & checkout
        $this->withSession($customerSession)->post('keranjang/tambah', [
            'product_id' => 1,
            'quantity'   => 1,
        ])->assertRedirect();

        $this->withSession($customerSession)->post('checkout', [
            'address_mode'   => 'saved',
            'address_id'     => $address['id'],
            'payment_method' => 'transfer_bank',
        ])->assertRedirect();

        $orderModel = new OrderModel();
        $orders     = $orderModel->getOrdersByUser((int) $customer['id']);
        $order      = $orders[0];

        // Simulasikan customer sudah mengirim bukti bayar (status submitted / waiting_verification)
        $this->db->table('payments')->where('order_id', $order['id'])->update([
            'proof_file'     => 'uploads/payments/sample.jpg',
            'payment_status' => 'submitted',
        ]);
        $this->db->table('orders')->where('id', $order['id'])->update([
            'order_status' => 'waiting_verification',
        ]);

        // 2. Admin memverifikasi pembayaran
        $adminSession = [
            'user_id'      => (int) $admin['id'],
            'user_name'    => $admin['name'],
            'user_email'   => $admin['email'],
            'user_role'    => 'admin',
            'is_logged_in' => true,
        ];

        $this->withSession($adminSession)
            ->post('admin/pesanan/' . $order['order_number'] . '/verifikasi', ['decision' => 'verify'])
            ->assertRedirect();

        $verifiedOrder = $orderModel->getOrderDetailForStaff($order['order_number']);
        $this->assertSame('processing', $verifiedOrder['order_status']);
        $this->assertSame('verified', $verifiedOrder['payment']['payment_status']);

        // 3. Operator melihat pesanan di antrean, input kurir & resi, lalu selesaikan
        $operatorSession = [
            'user_id'      => (int) $operator['id'],
            'user_name'    => $operator['name'],
            'user_email'   => $operator['email'],
            'user_role'    => 'operator',
            'is_logged_in' => true,
        ];

        $operatorQueue = $this->withSession($operatorSession)->get('operator');
        $operatorQueue->assertOK();
        $operatorQueue->assertSee($order['order_number']);

        $this->withSession($operatorSession)
            ->post('operator/pesanan/' . $order['order_number'] . '/kirim', [
                'action'          => 'ship',
                'courier'         => 'JNE',
                'tracking_number' => 'JNE998877',
            ])
            ->assertRedirect();

        $shippedOrder = $orderModel->getOrderDetailForStaff($order['order_number']);
        $this->assertSame('shipped', $shippedOrder['order_status']);
        $this->assertSame('JNE998877', $shippedOrder['shipment']['tracking_number']);

        $this->withSession($operatorSession)
            ->post('operator/pesanan/' . $order['order_number'] . '/kirim', ['action' => 'complete'])
            ->assertRedirect();

        // 4. Customer melihat status selesai & nomor resi di riwayat pesanannya
        $customerDetail = $this->withSession($customerSession)->get('pesanan/' . $order['order_number']);
        $customerDetail->assertOK();
        $customerDetail->assertSee('completed');
        $customerDetail->assertSee('JNE998877');
    }
}
