<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $petugasRole = Role::where('slug', 'petugas')->first();
        $memberRole = Role::where('slug', 'member')->first();

        // Admin
        User::firstOrCreate(
            ['email' => 'admin@lapsal.com'],
            [
                'name' => 'Admin Lapsal',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'phone' => '081234567890',
                'email_verified_at' => now(),
            ]
        );

        // Petugas
        User::firstOrCreate(
            ['email' => 'petugas@lapsal.com'],
            [
                'name' => 'Petugas Lapsal',
                'password' => Hash::make('password'),
                'role_id' => $petugasRole->id,
                'phone' => '081234567891',
                'email_verified_at' => now(),
            ]
        );

        // Member Demo
        $member = User::firstOrCreate(
            ['email' => 'member@lapsal.com'],
            [
                'name' => 'Member Demo',
                'password' => Hash::make('password'),
                'role_id' => $memberRole->id,
                'phone' => '081234567892',
                'email_verified_at' => now(),
            ]
        );

        // Create detail member
        $member->detailMember()->firstOrCreate([], [
            'alamat' => 'Jl. Contoh No. 123, Jakarta',
            'tanggal_lahir' => '1998-05-15',
        ]);
    }
}
