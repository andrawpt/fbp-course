<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCrudPertemuan12Tables extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('product_images')) {
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
        }

        if (! $this->db->tableExists('karyawan')) {
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
    }

    public function down(): void
    {
        // Dibiarkan aman agar rollback 000003 tidak menghapus tabel yang dibuat oleh 000002
    }
}
