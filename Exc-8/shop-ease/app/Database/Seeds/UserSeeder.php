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
                'name'          => 'Budi Santoso',
                'email'         => 'customer@shopease.local',
                'password_hash' => password_hash('Customer123!', PASSWORD_DEFAULT),
                'role'          => 'customer',
                'phone'         => '081234567890',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Admin ShopEase',
                'email'         => 'admin@shopease.local',
                'password_hash' => password_hash('Admin123!', PASSWORD_DEFAULT),
                'role'          => 'admin',
                'phone'         => '081298765432',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'name'          => 'Operator Gudang',
                'email'         => 'operator@shopease.local',
                'password_hash' => password_hash('Operator123!', PASSWORD_DEFAULT),
                'role'          => 'operator',
                'phone'         => '081355566677',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];

        $builder = $this->db->table('users');

        foreach ($users as $user) {
            $exists = $builder->where('email', $user['email'])->countAllResults();
            if ($exists === 0) {
                $builder->insert($user);
            }
        }
    }
}
