<?php

namespace Tests\Feature;

use App\Database\Seeds\ShopSeeder;
use App\Database\Seeds\UserSeeder;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

class CrudShopEaseTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(ShopSeeder::class);
    }

    private function sessionForRole(string $role): array
    {
        $emailMap = [
            'customer' => 'customer@shopease.com',
            'admin'    => 'admin@shopease.com',
            'operator' => 'operator@shopease.com',
        ];

        $user = $this->db->table('users')->where('email', $emailMap[$role])->get()->getRowArray();

        return [
            'user_id'    => (int) $user['id_user'],
            'name'       => (string) $user['name'],
            'email'      => (string) $user['email'],
            'role'       => (string) $user['role'],
            'isLoggedIn' => true,
        ];
    }

    public function testRoleAccessControlForCrudModules(): void
    {
        // Customer ditolak ke panel admin
        $this->withSession($this->sessionForRole('customer'))
            ->get('/admin/categories')
            ->assertRedirectTo('/');

        // Operator boleh akses categories, products, inventory, tapi ditolak di karyawan
        $this->withSession($this->sessionForRole('operator'))
            ->get('/admin/categories')
            ->assertOK();

        $this->withSession($this->sessionForRole('operator'))
            ->get('/admin/products')
            ->assertOK();

        $this->withSession($this->sessionForRole('operator'))
            ->get('/admin/inventory')
            ->assertOK();

        $this->withSession($this->sessionForRole('operator'))
            ->get('/admin/karyawan')
            ->assertRedirectTo('/');

        // Admin boleh akses karyawan
        $this->withSession($this->sessionForRole('admin'))
            ->get('/admin/karyawan')
            ->assertOK();
    }

    public function testCategoryCrudAndDeleteConstraint(): void
    {
        $admin = $this->sessionForRole('admin');

        // Create
        $this->withSession($admin)->post('/admin/categories/store', [
            csrf_token()  => csrf_hash(),
            'name'        => 'Kategori Kosong Baru',
            'description' => 'Untuk pengujian hapus',
        ])->assertRedirect();

        $newCat = $this->db->table('categories')->where('name', 'Kategori Kosong Baru')->get()->getRowArray();
        $this->assertNotNull($newCat);

        // Update
        $this->withSession($admin)->post('/admin/categories/update/' . $newCat['id'], [
            csrf_token()  => csrf_hash(),
            'name'        => 'Kategori Kosong Updated',
            'description' => 'Deskripsi baru',
        ])->assertRedirect();

        $updated = $this->db->table('categories')->where('id', $newCat['id'])->get()->getRowArray();
        $this->assertSame('Kategori Kosong Updated', $updated['name']);

        // Delete unused category succeeds
        $this->withSession($admin)->post('/admin/categories/delete/' . $newCat['id'], [
            csrf_token() => csrf_hash(),
        ])->assertRedirect();

        $this->assertNull($this->db->table('categories')->where('id', $newCat['id'])->get()->getRowArray());

        // Delete used category (Elektronik) is rejected
        $usedCat = $this->db->table('categories')->where('slug', 'elektronik')->get()->getRowArray();
        $this->withSession($admin)->post('/admin/categories/delete/' . $usedCat['id'], [
            csrf_token() => csrf_hash(),
        ])->assertRedirect();

        $this->assertNotNull($this->db->table('categories')->where('id', $usedCat['id'])->get()->getRowArray());
    }

    public function testProductZeroStockForcesInactiveAndCartBlocksDelete(): void
    {
        $admin = $this->sessionForRole('admin');
        $cat   = $this->db->table('categories')->where('slug', 'elektronik')->get()->getRowArray();

        // Create product with stock 0 even though status='tersedia' -> stored as inactive
        $this->withSession($admin)->post('/admin/products/store', [
            csrf_token()  => csrf_hash(),
            'name'        => 'Produk Stok Nol',
            'category_id' => $cat['id'],
            'price'       => 50000,
            'stock'       => 0,
            'status'      => 'tersedia',
            'description' => 'Testing stok 0',
        ])->assertRedirect();

        $prod = $this->db->table('products')->where('name', 'Produk Stok Nol')->get()->getRowArray();
        $this->assertNotNull($prod);
        $this->assertSame('inactive', $prod['status']);

        // Create active product and put in customer cart -> delete rejected
        $this->withSession($admin)->post('/admin/products/store', [
            csrf_token()  => csrf_hash(),
            'name'        => 'Produk Di Keranjang',
            'category_id' => $cat['id'],
            'price'       => 75000,
            'stock'       => 10,
            'status'      => 'tersedia',
        ])->assertRedirect();

        $cartProd = $this->db->table('products')->where('name', 'Produk Di Keranjang')->get()->getRowArray();
        $customer = $this->sessionForRole('customer');

        $this->withSession($customer)->post('/api/cart', [
            csrf_token() => csrf_hash(),
            'product_id' => $cartProd['id'],
            'quantity'   => 2,
        ])->assertStatus(200);

        $this->withSession($admin)->post('/admin/products/delete/' . $cartProd['id'], [
            csrf_token() => csrf_hash(),
        ])->assertRedirect();

        // Still exists because it is in cart
        $this->assertNotNull($this->db->table('products')->where('id', $cartProd['id'])->get()->getRowArray());

        // Set cart quantity to 0 -> removes item from cart -> physical delete succeeds
        $this->withSession($customer)->post('/api/cart', [
            csrf_token() => csrf_hash(),
            'product_id' => $cartProd['id'],
            'quantity'   => 0,
        ])->assertStatus(200);

        $this->withSession($admin)->post('/admin/products/delete/' . $cartProd['id'], [
            csrf_token() => csrf_hash(),
        ])->assertRedirect();

        $this->assertNull($this->db->table('products')->where('id', $cartProd['id'])->get()->getRowArray());
    }

    public function testKaryawanCrudDoesNotCreateUserAccount(): void
    {
        $admin      = $this->sessionForRole('admin');
        $usersCount = $this->db->table('users')->countAllResults();

        $this->withSession($admin)->post('/admin/karyawan/store', [
            csrf_token() => csrf_hash(),
            'nip'        => '20269999',
            'name'       => 'Andi Wijaya',
            'position'   => 'Kurir',
            'email'      => 'andi.wijaya@shopease.id',
            'phone'      => '081299998888',
            'hire_date'  => '2026-11-01',
            'status'     => 'aktif',
        ])->assertRedirect();

        $kar = $this->db->table('karyawan')->where('nip', '20269999')->get()->getRowArray();
        $this->assertNotNull($kar);
        $this->assertSame($usersCount, $this->db->table('users')->countAllResults());

        // Update
        $this->withSession($admin)->post('/admin/karyawan/update/' . $kar['id'], [
            csrf_token() => csrf_hash(),
            'nip'        => '20269999',
            'name'       => 'Andi Wijaya Updated',
            'position'   => 'Kasir',
            'email'      => 'andi.wijaya@shopease.id',
            'phone'      => '081299998888',
            'hire_date'  => '2026-11-01',
            'status'     => 'nonaktif',
        ])->assertRedirect();

        $updated = $this->db->table('karyawan')->where('id', $kar['id'])->get()->getRowArray();
        $this->assertSame('Kasir', $updated['position']);

        // Delete
        $this->withSession($admin)->post('/admin/karyawan/delete/' . $kar['id'], [
            csrf_token() => csrf_hash(),
        ])->assertRedirect();

        $this->assertNull($this->db->table('karyawan')->where('id', $kar['id'])->get()->getRowArray());
    }

    public function testCustomerAddressApiCrudAndOrderLock(): void
    {
        $customer = $this->sessionForRole('customer');

        // Create address
        $res = $this->withSession($customer)->post('/api/addresses', [
            csrf_token()     => csrf_hash(),
            'recipient_name' => 'Budi Santoso',
            'phone'          => '081234567890',
            'address'        => 'Jl. Diponegoro No. 10, Bandung',
            'city'           => 'Bandung',
            'province'       => 'Jawa Barat',
            'postal_code'    => '40115',
        ]);
        $res->assertStatus(201);

        $addr = $this->db->table('addresses')
            ->where('address', 'Jl. Diponegoro No. 10, Bandung')
            ->get()
            ->getRowArray();
        $this->assertNotNull($addr);

        // Update address before order
        $this->withSession($customer)->post('/api/addresses/' . $addr['id'] . '/update', [
            csrf_token()     => csrf_hash(),
            'recipient_name' => 'Budi Updated',
            'phone'          => '081234567890',
            'address'        => 'Jl. Diponegoro No. 12, Bandung',
            'city'           => 'Bandung',
            'province'       => 'Jawa Barat',
            'postal_code'    => '40115',
        ])->assertStatus(200);

        // Add item to cart and checkout using this address
        $prod = $this->db->table('products')->where('status', 'active')->get()->getRowArray();
        $this->withSession($customer)->post('/api/cart', [
            csrf_token() => csrf_hash(),
            'product_id' => $prod['id'],
            'quantity'   => 1,
        ])->assertStatus(200);

        $this->withSession($customer)->post('/api/checkout', [
            csrf_token() => csrf_hash(),
            'address_id' => $addr['id'],
        ])->assertStatus(201);

        // Address now used in order -> update & delete must be rejected (409)
        $this->withSession($customer)->post('/api/addresses/' . $addr['id'] . '/update', [
            csrf_token()     => csrf_hash(),
            'recipient_name' => 'Should Fail',
            'phone'          => '081234567890',
            'address'        => 'Jl. Diponegoro No. 99, Bandung',
            'city'           => 'Bandung',
            'province'       => 'Jawa Barat',
            'postal_code'    => '40115',
        ])->assertStatus(409);

        $this->withSession($customer)->post('/api/addresses/' . $addr['id'] . '/delete', [
            csrf_token() => csrf_hash(),
        ])->assertStatus(409);
    }
}
