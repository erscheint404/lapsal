<?php

namespace Database\Seeders;

use App\Models\Lapangan;
use App\Models\SlotWaktu;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LapanganSeeder extends Seeder
{
    public function run(): void
    {
        $lapanganData = [
            [
                'nama' => 'Lapangan A - Premium',
                'deskripsi' => 'Lapangan futsal premium dengan rumput sintetis kualitas FIFA. Dilengkapi pencahayaan LED dan tribun penonton.',
                'harga_per_jam' => 150000,
                'fasilitas' => ['Rumput Sintetis', 'Pencahayaan LED', 'Tribun Penonton', 'Ruang Ganti', 'Kamar Mandi', 'Parkir Luas'],
                'tipe' => 'rumput_sintetis',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Lapangan B - Standard',
                'deskripsi' => 'Lapangan futsal standar dengan lantai vinyl berkualitas tinggi. Cocok untuk latihan rutin dan pertandingan santai.',
                'harga_per_jam' => 100000,
                'fasilitas' => ['Lantai Vinyl', 'Pencahayaan Standar', 'Ruang Ganti', 'Kamar Mandi', 'Parkir'],
                'tipe' => 'vinyl',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Lapangan C - Indoor',
                'deskripsi' => 'Lapangan futsal indoor dengan AC dan lantai parquette. Pengalaman bermain paling nyaman di kota.',
                'harga_per_jam' => 200000,
                'fasilitas' => ['Parquette', 'AC Central', 'Pencahayaan LED', 'Sound System', 'Tribun VIP', 'Ruang Ganti Premium', 'Locker', 'Kantin'],
                'tipe' => 'parquette',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Lapangan D - Outdoor',
                'deskripsi' => 'Lapangan outdoor dengan rumput sintetis dan suasana terbuka. Harga terjangkau untuk semua kalangan.',
                'harga_per_jam' => 80000,
                'fasilitas' => ['Rumput Sintetis', 'Pencahayaan Malam', 'Parkir', 'Toilet'],
                'tipe' => 'rumput_sintetis',
                'status' => 'aktif',
            ],
        ];

        foreach ($lapanganData as $data) {
            $lapangan = Lapangan::firstOrCreate(
                ['nama' => $data['nama']],
                $data
            );

            // Generate slots for the next 14 days
            $this->generateSlots($lapangan);
        }

        // Seed pengaturan
        $settings = [
            ['key' => 'nama_website', 'value' => 'Lapsal Futsal', 'group' => 'general'],
            ['key' => 'alamat', 'value' => 'Jl. Olahraga No. 10, Jakarta Selatan', 'group' => 'general'],
            ['key' => 'telepon', 'value' => '021-12345678', 'group' => 'general'],
            ['key' => 'whatsapp', 'value' => '6281234567890', 'group' => 'general'],
            ['key' => 'email', 'value' => 'info@lapsal-futsal.com', 'group' => 'general'],
            ['key' => 'jam_buka', 'value' => '08:00', 'group' => 'operasional'],
            ['key' => 'jam_tutup', 'value' => '23:00', 'group' => 'operasional'],
            ['key' => 'durasi_slot_menit', 'value' => '60', 'group' => 'operasional'],
            ['key' => 'slot_lock_menit', 'value' => '10', 'group' => 'operasional'],
            ['key' => 'sla_verifikasi_jam', 'value' => '48', 'group' => 'operasional'],
            ['key' => 'batas_batal_jam', 'value' => '3', 'group' => 'operasional'],
            ['key' => 'instagram', 'value' => 'https://instagram.com/lapsalfutsal', 'group' => 'sosmed'],
            ['key' => 'facebook', 'value' => 'https://facebook.com/lapsalfutsal', 'group' => 'sosmed'],
            ['key' => 'maps_embed', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.2!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            Pengaturan::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }

    private function generateSlots(Lapangan $lapangan): void
    {
        $jamBuka = 8;
        $jamTutup = 23;

        for ($day = 0; $day < 14; $day++) {
            $tanggal = Carbon::today()->addDays($day);

            for ($jam = $jamBuka; $jam < $jamTutup; $jam++) {
                SlotWaktu::firstOrCreate(
                    [
                        'lapangan_id' => $lapangan->id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'jam_mulai' => sprintf('%02d:00:00', $jam),
                    ],
                    [
                        'jam_selesai' => sprintf('%02d:00:00', $jam + 1),
                        'status' => 'available',
                    ]
                );
            }
        }
    }
}
