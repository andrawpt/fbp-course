<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShopTables extends Migration
{
    public function up(): void
    {
        // 1. categories
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 120],
            'icon'        => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => 'bi-tag'],
            'description' => ['type' => 'TEXT', 'null' => true],
            'is_active'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('categories', true);

        // 2. products
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 180],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 200],
            'description' => ['type' => 'TEXT', 'null' => true],
            'price'       => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'stock'       => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'image'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'icon'        => ['type' => 'VARCHAR', 'constraint' => 60, 'default' => 'bi-box'],
            'badge'       => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'Produk'],
            'status'      => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'active'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('category_id');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('products', true);

        // 3. product_images (Galeri Produk)
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'image_path' => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_primary' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_images', true);

        // 4. carts (FK ke users.id_user)
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('carts', true);

        // 5. cart_items
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'cart_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity'   => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('cart_id');
        $this->forge->addKey('product_id');
        $this->forge->addForeignKey('cart_id', 'carts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cart_items', true);

        // 6. addresses (FK ke users.id_user)
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'recipient_name' => ['type' => 'VARCHAR', 'constraint' => 120],
            'phone'          => ['type' => 'VARCHAR', 'constraint' => 30],
            'address'        => ['type' => 'TEXT'],
            'city'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'province'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'postal_code'    => ['type' => 'VARCHAR', 'constraint' => 20],
            'is_default'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('addresses', true);

        // 7. orders (FK ke users.id_user)
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'address_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'order_number'  => ['type' => 'VARCHAR', 'constraint' => 40],
            'order_date'    => ['type' => 'DATETIME'],
            'subtotal'      => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'shipping_cost' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'total'         => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'order_status'  => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending_payment'],
            'notes'         => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('order_number');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id_user', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('address_id', 'addresses', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('orders', true);

        // 8. order_items (product_id nullable & ON DELETE SET NULL agar riwayat pesanan tetap aman)
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'order_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'product_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'product_name' => ['type' => 'VARCHAR', 'constraint' => 180],
            'price'        => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'quantity'     => ['type' => 'INT', 'constraint' => 11],
            'subtotal'     => ['type' => 'DECIMAL', 'constraint' => '12,2'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('order_items', true);

        // 9. payments
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'order_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'method'         => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'transfer_bank'],
            'amount'         => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'proof_file'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'payment_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'unpaid'],
            'verified_by'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'verified_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments', true);

        // 10. shipments
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'order_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'courier'         => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'tracking_number' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'shipment_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
            'shipped_at'      => ['type' => 'DATETIME', 'null' => true],
            'delivered_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('order_id');
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('shipments', true);

        // 11. karyawan (tabel staf berdiri sendiri)
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nip'        => ['type' => 'VARCHAR', 'constraint' => 20],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'position'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone'      => ['type' => 'VARCHAR', 'constraint' => 20],
            'hire_date'  => ['type' => 'DATE'],
            'status'     => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'aktif'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nip');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('karyawan', true);
    }

    public function down(): void
    {
        $this->forge->dropTable('karyawan', true);
        $this->forge->dropTable('shipments', true);
        $this->forge->dropTable('payments', true);
        $this->forge->dropTable('order_items', true);
        $this->forge->dropTable('orders', true);
        $this->forge->dropTable('addresses', true);
        $this->forge->dropTable('cart_items', true);
        $this->forge->dropTable('carts', true);
        $this->forge->dropTable('product_images', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('categories', true);
    }
}
