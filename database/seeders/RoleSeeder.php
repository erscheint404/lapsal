<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nama' => 'Admin', 'slug' => 'admin'],
            ['nama' => 'Petugas', 'slug' => 'petugas'],
            ['nama' => 'Member', 'slug' => 'member'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
