<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $users = [
            [
                'name'       => 'Budi Santoso',
                'email'      => 'customer@shopease.com',
                'password'   => password_hash('Customer123!', PASSWORD_DEFAULT),
                'role'       => 'customer',
                'phone'      => '081234567890',
                'address'    => 'Jl. Merdeka Belajar No. 42, Bandung',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Admin ShopEase',
                'email'      => 'admin@shopease.com',
                'password'   => password_hash('Admin123!', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'phone'      => '081298765432',
                'address'    => 'Kantor Pusat ShopEase',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Operator Gudang',
                'email'      => 'operator@shopease.com',
                'password'   => password_hash('Operator123!', PASSWORD_DEFAULT),
                'role'       => 'operator',
                'phone'      => '081355566677',
                'address'    => 'Gudang Logistik ShopEase',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $builder = $this->db->table('users');
        foreach ($users as $user) {
            if ($builder->where('email', $user['email'])->countAllResults() === 0) {
                $builder->insert($user);
            }
        }
    }
}
