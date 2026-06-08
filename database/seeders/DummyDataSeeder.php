<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\DetailMember;
use App\Models\Lapangan;
use App\Models\Notifikasi;
use App\Models\RatingLapangan;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $memberRole = Role::where('slug', 'member')->first();
        $lapangans = Lapangan::where('status', 'aktif')->get();

        // Dummy member names (Indonesian)
        $members = [
            ['name' => 'Andi Pratama', 'email' => 'andi.pratama@gmail.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@gmail.com'],
            ['name' => 'Cahya Ramadhan', 'email' => 'cahya.ramadhan@gmail.com'],
            ['name' => 'Deni Saputra', 'email' => 'deni.saputra@gmail.com'],
            ['name' => 'Eko Wijaya', 'email' => 'eko.wijaya@gmail.com'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar.nugroho@gmail.com'],
            ['name' => 'Gilang Permana', 'email' => 'gilang.permana@gmail.com'],
            ['name' => 'Hendra Kurniawan', 'email' => 'hendra.kurniawan@gmail.com'],
            ['name' => 'Irfan Hakim', 'email' => 'irfan.hakim@gmail.com'],
            ['name' => 'Joko Susilo', 'email' => 'joko.susilo@gmail.com'],
            ['name' => 'Kevin Anggara', 'email' => 'kevin.anggara@gmail.com'],
            ['name' => 'Lukman Hidayat', 'email' => 'lukman.hidayat@gmail.com'],
            ['name' => 'Muhammad Rizky', 'email' => 'muhammad.rizky@gmail.com'],
            ['name' => 'Naufal Abidin', 'email' => 'naufal.abidin@gmail.com'],
            ['name' => 'Oscar Pratama', 'email' => 'oscar.pratama@gmail.com'],
        ];

        // Create member users
        $createdUsers = [];
        foreach ($members as $m) {
            $user = User::firstOrCreate(
                ['email' => $m['email']],
                [
                    'name' => $m['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $memberRole->id,
                    'phone' => '0822' . rand(10000000, 99999999),
                    'email_verified_at' => now(),
                ]
            );
            $user->detailMember()->firstOrCreate([], [
                'alamat' => 'Jakarta',
                'tanggal_lahir' => Carbon::create(rand(1995, 2003), rand(1, 12), rand(1, 28)),
            ]);
            $createdUsers[] = $user;
        }

        // Good reviews for each lapangan
        $ulasanBagus = [
            'Lapangannya mantap banget! Rumput sintetisnya empuk, nyaman buat sliding tackle. Pasti balik lagi! ⚽🔥',
            'Booking cepat, lapangan bersih, fasilitas lengkap. Top markotop!',
            'Baru pertama kali main di sini dan langsung jatuh cinta. Pencahayaannya terang, lantainya oke banget.',
            'Sering booking di sini buat futsal bareng kantor. Gak pernah kecewa, selalu clean dan ready.',
            'Worth it harganya! Parkirnya luas, lapangannya bagus, ruang ganti juga bersih.',
            'Udah jadi langganan, setiap minggu booking di sini. Pelayanan ramah dan cepat.',
            'Tempatnya strategis, dekat dari kantor. Kualitas lapangan juara! Recommended banget.',
            'AC-nya adem, lapangannya bagus, sound system juga ada. Pengalaman futsal terbaik!',
            'Main futsal di sini bikin nagih. Lapangannya standar FIFA, mantap jiwa!',
            'Booking online-nya gampang banget, tinggal klik-klik langsung dapet jadwal. 10/10!',
            'Lapangannya selalu terawat dengan baik. Toilet bersih, mushala juga ada. Lengkap!',
            'Harga terjangkau tapi kualitasnya premium. Best futsal venue in town! 🏆',
            'Sudah coba hampir semua lapangan di kota ini, yang paling oke tetep di sini.',
            'Anak-anak komunitas futsal kami selalu booking di sini. Tempatnya paling recommended.',
            'Gak nyangka bisa dapet lapangan sebagus ini. Fasilitasnya luar biasa, puas banget!',
            'Mainnya jadi makin seru karena lapangannya top. Tribun penontonnya juga keren!',
            'Setelah renovasi makin bagus aja. Pencahayaan LED-nya bikin malem serasa siang.',
            'Tim kami juara turnamen berkat latihan rutin di sini. Lapangan paling mantap! 🏅',
            'Sistem booking online-nya sangat memudahkan. Gak perlu antri lagi. Modern banget!',
            'Overall experience 10/10. Mulai dari booking, main, sampai pulang semuanya oke.',
        ];

        $bookingCount = 0;

        foreach ($lapangans as $lapangan) {
            // Create 8-12 completed bookings per lapangan
            $numBookings = rand(8, 12);

            for ($i = 0; $i < $numBookings; $i++) {
                $user = $createdUsers[array_rand($createdUsers)];
                $daysAgo = rand(3, 60);
                $tanggal = Carbon::today()->subDays($daysAgo);
                $jamMulai = rand(8, 20);

                $booking = Booking::create([
                    'kode_booking' => 'BK-' . strtoupper(Str::random(8)),
                    'user_id' => $user->id,
                    'lapangan_id' => $lapangan->id,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'jam_mulai' => sprintf('%02d:00:00', $jamMulai),
                    'jam_selesai' => sprintf('%02d:00:00', $jamMulai + 1),
                    'durasi_jam' => 1,
                    'total_harga' => $lapangan->harga_per_jam,
                    'status' => 'completed',
                    'metode_pembayaran' => 'manual',
                    'idempotency_key' => Str::uuid()->toString(),
                    'confirmed_at' => $tanggal->copy()->subHours(rand(1, 12)),
                    'completed_at' => $tanggal->copy()->addHours($jamMulai + 1),
                    'created_at' => $tanggal->copy()->subDays(rand(1, 3)),
                    'updated_at' => $tanggal->copy()->addHours($jamMulai + 1),
                ]);

                // Rating: 4 or 5 (heavily weighted to 5 to get avg >= 4.8)
                $rating = (rand(1, 10) <= 8) ? 5 : 4;

                RatingLapangan::create([
                    'lapangan_id' => $lapangan->id,
                    'booking_id' => $booking->id,
                    'user_id' => $user->id,
                    'rating' => $rating,
                    'ulasan' => $ulasanBagus[array_rand($ulasanBagus)],
                    'created_at' => $booking->completed_at->addHours(rand(1, 24)),
                    'updated_at' => $booking->completed_at->addHours(rand(1, 24)),
                ]);

                $bookingCount++;
            }
        }

        $this->command->info("✅ Created " . count($createdUsers) . " member accounts");
        $this->command->info("✅ Created {$bookingCount} completed bookings with ratings (avg >= 4.8)");
    }
}
