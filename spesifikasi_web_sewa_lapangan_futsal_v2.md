# Spesifikasi Web Sewa Lapangan Futsal (v2)

## 1. Gambaran Umum

Website ini adalah sistem reservasi lapangan futsal berbasis Laravel dan MySQL yang mendukung alur pemesanan, pembayaran, verifikasi, pembatalan, pengelolaan lapangan, statistik gol, leaderboard, dan laporan. Sistem dibuat agar siap digunakan secara nyata, bukan sekadar demo statis.

Tujuan utamanya:
- memudahkan pengunjung melihat informasi lapangan dan jadwal kosong
- membuat member bisa booking dan bayar secara online
- memberi admin/petugas dashboard operasional yang rapi
- mengganti proses manual di buku catatan menjadi sistem digital yang terstruktur

---

## 2. Ruang Lingkup

### 2.1 Fitur Utama

- Landing page dinamis dan informatif
- Register, login, logout
- Login dengan Google
- Role-based access control pakai middleware
- Daftar lapangan + detail lapangan
- Kalender ketersediaan slot real-time
- Booking lapangan dengan slot locking + countdown timer
- Pembayaran via Midtrans dengan idempotency key
- Upload bukti bayar sebagai opsi cadangan/manual dengan alur verifikasi jelas
- Konfirmasi / penolakan booking oleh admin
- Pembatalan booking oleh member
- Riwayat booking
- QR Code konfirmasi booking
- Pengelolaan data lapangan
- Pencatatan statistik gol
- Leaderboard publik
- Laporan penyewaan dan pendapatan
- Notifikasi email / in-app
- Dashboard admin/petugas
- Rating lapangan post-sesi oleh member
- PWA support (installable di HP)

### 2.2 Batasan Pengguna

Sistem punya 3 peran utama:

1. **Guest / Pengunjung**
   - cuma bisa melihat landing page, daftar lapangan, detail lapangan, kalender ketersediaan, dan leaderboard publik
   - tidak bisa transaksi

2. **Member**
   - bisa daftar, login, login Google
   - bisa booking, bayar, upload bukti bayar bila diperlukan
   - bisa batalkan booking sesuai aturan
   - bisa lihat riwayat booking dan profil
   - bisa kasih rating lapangan setelah sesi selesai
   - bisa share detail booking ke WhatsApp

3. **Admin/Petugas**
   - bisa mengelola lapangan, slot, booking, pembayaran, statistik, leaderboard, dan laporan
   - bisa verifikasi pembayaran manual (dalam SLA yang ditentukan)
   - bisa konfirmasi, atau tolak booking
   - bisa scan QR Code member di lokasi
   - bisa lihat seluruh data operasional
   - bisa mengelola konten landing page jika dibutuhkan

---

## 3. Tujuan Desain

Tampilan web harus terasa:
- modern
- premium
- bersih
- responsif
- cepat
- enak dilihat di HP

Arah visual yang disarankan:
- layout airy, banyak ruang kosong yang sehat
- hero section kuat dengan CTA jelas
- komponen card yang rapi
- animasi halus, tidak berlebihan
- warna konsisten, jangan rame sendiri
- kesan "aplikasi siap pakai", bukan template asal jadi

Inspirasi gaya:
- tampilan premium seperti brand besar
- section landing page yang terstruktur
- ada highlight harga, fasilitas, dan slot tersedia secara real-time
- ada trust section seperti testimoni, statistik penggunaan, dan rating

### 3.1 Standar Empty State & Error State

Setiap halaman yang menampilkan list atau data harus punya empty state yang dirancang dengan benar, bukan sekadar halaman kosong:
- **Leaderboard kosong** → ilustrasi + teks "Belum ada data pertandingan. Jadilah yang pertama!"
- **Booking history kosong** → ilustrasi + CTA "Yuk booking lapangan sekarang"
- **Slot habis semua** → ilustrasi + teks "Semua slot penuh hari ini" + tombol pindah ke tanggal lain
- **Pencarian tidak ketemu** → ilustrasi + teks deskriptif + saran pencarian lain
- **Error 500 / network error** → halaman error custom dengan tombol retry
- **Halaman 404** → custom, ada navigasi balik ke home

Setiap kondisi error dari API atau server harus tampil sebagai pesan yang ramah, bukan raw error message.

### 3.2 Skeleton Loading

Skeleton loading wajib ada di semua komponen list dan data, bukan cuma di satu halaman:
- Daftar lapangan
- Kalender slot ketersediaan
- Leaderboard
- Booking history
- Dashboard statistik admin
- Riwayat transaksi

Skeleton harus proporsional dengan layout konten aslinya.

---

## 4. Alur Sistem

### 4.1 Alur Guest
- membuka landing page
- melihat daftar lapangan
- membuka detail lapangan
- melihat jadwal tersedia
- melihat leaderboard publik
- diarahkan untuk login atau daftar saat ingin booking

### 4.2 Alur Member
- registrasi atau login
- login Google juga tersedia
- pilih lapangan dan slot waktu
- sistem cek ketersediaan
- **sistem lock slot sementara (10 menit) + tampilkan countdown timer**
- member membuat booking
- pembayaran dilakukan lewat Midtrans
- status booking berubah sesuai hasil pembayaran
- **member menerima QR Code konfirmasi booking**
- member bisa lihat riwayat booking
- member bisa membatalkan booking sesuai aturan
- **setelah sesi selesai, member bisa kasih rating lapangan**
- **member bisa share detail booking ke WhatsApp**

### 4.3 Alur Admin/Petugas
- login ke halaman admin
- melihat booking baru dan status pembayaran
- **verifikasi pembayaran manual (jika ada) dalam SLA yang ditentukan**
- **scan QR Code member di lokasi untuk validasi kehadiran**
- mengonfirmasi atau menolak booking
- mengelola lapangan dan slot
- mencatat statistik gol
- melihat leaderboard
- membuat laporan penyewaan dan pendapatan

### 4.4 Alur Slot Locking

Slot locking adalah mekanisme kritis untuk mencegah double booking:

1. User pilih slot → sistem lock slot di database dengan timestamp + user_id
2. Status slot berubah dari `available` → `reserved`
3. Countdown timer 10 menit tampil di halaman booking/payment
4. Jika pembayaran selesai sebelum timer habis → slot berubah `booked`
5. Jika timer habis sebelum pembayaran → slot dibuka kembali ke `available` secara otomatis via scheduled job
6. Jika user lain coba ambil slot yang sedang di-lock → tampilkan notifikasi "Slot sedang dipesan orang lain, coba slot lain atau tunggu beberapa menit"

### 4.5 Alur Pembayaran Manual (Fallback)

Alur pembayaran manual harus didefinisikan eksplisit untuk menghindari ambiguitas implementasi:

1. Member memilih metode upload bukti transfer (bukan Midtrans)
2. Member upload foto/screenshot bukti transfer
3. Status booking berubah ke `under_review`
4. Admin mendapat notifikasi ada pembayaran manual yang perlu diverifikasi
5. Admin memiliki **SLA verifikasi maksimal 2×24 jam** (bisa dikonfigurasi)
6. Admin verifikasi → booking dikonfirmasi atau ditolak dengan alasan
7. Jika tidak diverifikasi dalam SLA, sistem kirim reminder ke admin
8. Member mendapat notifikasi hasil verifikasi via email + in-app

Catatan: Pembayaran Midtrans dan upload manual tidak bisa dipakai bersamaan untuk satu booking. User harus pilih salah satu metode di awal checkout.

---

## 5. Kebutuhan Fungsional

### 5.1 Autentikasi dan Akun
- registrasi dengan nama, email, nomor HP, password
- verifikasi email
- login manual
- login Google
- reset password via email
- update profil
- logout

### 5.2 Daftar Lapangan
- tampilkan semua lapangan aktif
- cari lapangan berdasarkan nama
- filter berdasarkan harga, status, atau fasilitas
- detail lapangan berisi foto (multi-image), harga per jam, fasilitas, status aktif, dan **rating rata-rata dari member**
- kalender jadwal tersedia per tanggal

### 5.3 Booking
- pilih lapangan
- pilih tanggal
- pilih slot waktu
- **sistem lock slot sementara 10 menit + countdown timer tampil di UI**
- sistem mencegah double booking
- sistem menghitung total harga otomatis
- sistem simpan booking ke database
- status awal booking: `pending_payment`
- **generate QR Code saat booking confirmed**

### 5.4 Pembayaran
- integrasi Midtrans untuk pembayaran online
- mendukung payment gateway yang valid dan otomatis
- callback/webhook untuk update status pembayaran
- **implementasi idempotency key di setiap request ke Midtrans** untuk mencegah transaksi ganda meskipun user klik berulang kali atau webhook datang duplikat
- jika dibutuhkan, member bisa upload bukti transfer sebagai fallback manual
- admin bisa memvalidasi bukti bayar dalam SLA yang ditentukan

### 5.5 Pembatalan
- member dapat membatalkan booking sesuai aturan
- slot yang dibatalkan dibuka kembali ke `available`
- status booking berubah menjadi `cancelled`
- sistem simpan riwayat pembatalan

### 5.6 QR Code Konfirmasi
- sistem generate QR Code unik setiap booking yang sudah dikonfirmasi
- QR Code berisi booking ID yang terenkripsi
- petugas bisa scan QR Code di lokasi untuk validasi kehadiran
- tampilkan QR Code di halaman detail booking dan kirim via email

### 5.7 Rating Lapangan
- setelah status booking berubah ke `completed`, member bisa kasih rating (1–5 bintang) + ulasan singkat
- rating hanya bisa diberikan satu kali per booking
- rating tampil di halaman detail lapangan
- rata-rata rating dihitung otomatis dan ditampilkan di daftar lapangan + landing page

### 5.8 Statistik Gol dan Leaderboard
- petugas/admin input hasil pertandingan
- simpan jumlah gol per pemain
- hitung total gol dan total sesi
- sistem update ranking leaderboard otomatis
- leaderboard bisa dilihat publik

### 5.9 Laporan
- laporan penyewaan
- laporan pendapatan
- filter berdasarkan tanggal
- export PDF
- export Excel
- tampilkan ringkasan dan grafik

### 5.10 Notifikasi
- email notifikasi registrasi
- email notifikasi booking
- email notifikasi pembayaran
- email notifikasi konfirmasi atau penolakan
- email notifikasi pembatalan booking
- **email notifikasi berisi QR Code booking saat status confirmed**
- **notifikasi in-app untuk admin saat ada pembayaran manual yang perlu diverifikasi**

### 5.11 Share & Integrasi Sosial
- tombol share booking ke WhatsApp (deep link dengan detail lapangan, tanggal, slot, dan nomor booking)
- link booking bisa dibuka di browser untuk verifikasi

---

## 6. Kebutuhan Nonfungsional

- responsif di desktop, tablet, dan mobile
- aman dari akses antar role yang salah
- validasi input di frontend dan backend
- **query database efisien dengan indexing strategy yang tepat**
- **caching slot availability dengan Redis/cache (TTL 30–60 detik)**
- upload file aman
- logging untuk aktivitas penting
- mudah di-deploy ke server produksi
- struktur kode rapi dan modular
- siap maintenance jangka panjang
- **error monitoring via Sentry atau Flare dari hari pertama deploy**
- **PWA support: web manifest + service worker untuk pengalaman app-like di HP**

---

## 7. Teknologi yang Dipakai

### 7.1 Backend
- Laravel
- MySQL
- Redis untuk caching dan queue
- Laravel Middleware untuk pembatasan akses
- Laravel Socialite untuk Google Login
- Laravel Mail untuk email notifikasi
- Laravel Queue / Job untuk proses notifikasi, webhook, dan slot expiry
- Laravel Scheduler untuk membuka kembali slot yang lock-nya expired

### 7.2 Pembayaran
- Midtrans Payment Gateway
- Snap / Core API sesuai kebutuhan implementasi
- webhook/callback untuk update status otomatis
- **idempotency key untuk setiap transaksi**

### 7.3 Frontend
- Blade untuk server-rendered pages
- Tailwind CSS untuk styling
- Alpine.js atau JavaScript ringan untuk interaksi
- chart library untuk grafik laporan
- kalender booking interaktif
- **PWA: web manifest + service worker**

### 7.4 Utility
- Dompdf atau Snappy untuk PDF
- Laravel Excel untuk export Excel
- Storage Laravel untuk upload file
- **QR Code library (misal: `simplesoftwareio/simple-qrcode`) untuk generate QR**
- **Sentry atau Flare untuk error monitoring**

---

## 8. Desain Role dan Middleware

### 8.1 Middleware yang Disarankan
- `auth`
- `guest`
- `role:member`
- `role:admin`
- `role:petugas`
- `verified`
- `throttle`
- middleware custom untuk cek status booking, cek hak batal, atau cek slot

### 8.2 Aturan Akses
- guest: lihat data publik saja
- member: transaksi dan profil
- admin/petugas: panel operasional penuh

---

## 9. Struktur Halaman

### 9.1 Public Pages
- Home / landing page
- Daftar lapangan
- Detail lapangan (termasuk rating)
- Kalender ketersediaan
- Leaderboard
- Login
- Register
- Reset password
- FAQ / cara booking
- Kontak

### 9.2 Member Pages
- Dashboard member
- Booking saya (termasuk QR Code per booking)
- Riwayat transaksi
- Detail pembayaran
- Profil saya
- Notifikasi
- Pembatalan booking
- Rating lapangan (form post-sesi)

### 9.3 Admin/Petugas Pages
- Dashboard admin
- Manajemen lapangan
- Manajemen slot waktu
- Manajemen booking
- Verifikasi pembayaran (termasuk antrian pembayaran manual + SLA indicator)
- Scan QR Code (halaman khusus untuk validasi kehadiran)
- Statistik gol
- Leaderboard management
- Laporan penyewaan
- Laporan pendapatan
- Manajemen user
- Pengaturan website

---

## 10. Rancangan Landing Page Dinamis

Landing page sebaiknya punya section berikut:

1. **Hero section**
   - headline kuat
   - subheadline singkat
   - tombol CTA: "Pesan Sekarang" dan "Lihat Jadwal"
   - highlight real-time availability
   - **badge "slot tersisa hari ini" dengan update berkala (polling / websocket)**

2. **Highlight lapangan**
   - kartu lapangan unggulan
   - foto berkualitas
   - harga mulai dari
   - fasilitas utama
   - **rating rata-rata dari member**

3. **Ketersediaan slot**
   - kalender jadwal aktif
   - slot kosong ditandai jelas
   - memberi rasa real-time

4. **Alur booking**
   - pilih lapangan
   - pilih slot
   - bayar
   - main

5. **Keunggulan tempat**
   - lapangan terawat
   - pencahayaan
   - parkir
   - toilet
   - musala
   - keamanan

6. **Statistik platform**
   - jumlah total booking
   - jumlah member aktif
   - rata-rata rating keseluruhan

7. **Testimoni & Rating**
   - rating bintang
   - ulasan singkat dari member
   - trust builder

8. **Leaderboard publik**
   - buat pengunjung punya alasan balik lagi

9. **CTA final**
   - booking sekarang
   - login / daftar

### Ide biar landing page kelihatan mahal:
- pakai foto asli lapangan
- tambahkan section statistik: jumlah booking, member aktif, rating
- tampilkan badge "slot tersisa hari ini"
- tambahkan animasi kecil saat hover
- gunakan komponen card modern
- jangan pakai terlalu banyak warna
- fokus ke visual yang clean dan meyakinkan

---

## 11. Modul Database

Entitas yang relevan:
- roles
- users
- sesi_login
- detail_admin
- detail_petugas
- detail_member
- lapangan
- rating_lapangan *(baru)*
- slot_waktu
- slot_lock *(baru — untuk slot locking mechanism)*
- booking
- bukti_pembayaran
- riwayat_booking
- statistik_gol
- leaderboard

### Relasi inti
- role punya banyak user
- user bisa punya detail sesuai peran
- lapangan punya banyak slot waktu
- lapangan punya banyak rating dari member
- member membuat banyak booking
- booking punya bukti pembayaran
- booking punya riwayat perubahan status
- booking bisa terhubung ke statistik gol
- booking punya QR Code unik saat confirmed
- slot bisa punya slot_lock aktif saat sedang di-reserved
- leaderboard dihasilkan dari statistik gol

### Indexing Strategy yang Disarankan

Index database wajib dibuat di kolom-kolom yang sering dipakai untuk filter dan join:

| Tabel | Kolom | Alasan |
|---|---|---|
| bookings | status | filter by status sering |
| bookings | lapangan_id | join ke lapangan |
| bookings | member_id | riwayat booking per member |
| bookings | created_at | sorting dan laporan |
| slot_waktu | lapangan_id + tanggal | cek ketersediaan |
| slot_lock | slot_id + expired_at | cek lock aktif |
| statistik_gol | booking_id | join ke leaderboard |
| leaderboard | total_gol | sorting ranking |

---

## 12. Aturan Status yang Disarankan

### Booking
- `draft`
- `pending_payment`
- `under_review` *(menunggu verifikasi pembayaran manual)*
- `waiting_confirmation`
- `confirmed`
- `rejected`
- `cancelled`
- `expired`
- `completed`

### Pembayaran
- `pending`
- `paid`
- `failed`
- `expired`
- `under_review` *(khusus pembayaran manual yang belum diverifikasi)*

### Slot
- `available`
- `reserved` *(sedang di-lock, belum tentu jadi booking)*
- `booked`
- `blocked`

---

## 13. Alur Data yang Disarankan

### Registrasi
- user isi form
- validasi
- simpan ke users
- kirim verifikasi email
- aktifkan akun setelah verifikasi

### Booking + Slot Locking
- user pilih lapangan dan slot
- sistem cek konflik jadwal
- **sistem buat slot_lock record dengan expired_at = sekarang + 10 menit**
- **status slot berubah `available` → `reserved`**
- **countdown timer tampil di UI**
- simpan booking dengan status `pending_payment`
- hitung total pembayaran
- kirim invoice / instruksi bayar
- **jika timer habis sebelum bayar → slot_lock dihapus → slot kembali `available` via scheduled job**

### Pembayaran Midtrans
- user checkout
- **generate idempotency key unik per transaksi**
- midtrans proses pembayaran
- callback update status booking
- jika sukses → status booking `confirmed`, slot `booked`, **generate & kirim QR Code via email**
- jika gagal → status tetap `pending_payment` atau `expired`, slot lock dihapus

### Pembayaran Manual
- user upload bukti transfer
- status booking → `under_review`
- admin dapat notifikasi
- admin verifikasi dalam SLA 2×24 jam
- jika valid → `confirmed` + QR Code dikirim
- jika tidak valid → `rejected` + alasan penolakan dikirim ke member
- jika SLA terlewat → sistem kirim reminder ke admin

### Pembatalan
- user ajukan pembatalan
- sistem cek aturan waktu
- jika valid → status `cancelled`, slot dibuka kembali ke `available`
- log riwayat disimpan

### Post-Sesi
- sistem/admin ubah status booking ke `completed`
- member dapat notifikasi untuk memberikan rating
- member isi rating + ulasan
- rata-rata rating lapangan diupdate

---

## 14. Strategi Caching

Untuk menjaga performa di saat traffic tinggi, implementasi caching di titik-titik yang paling sering diakses:

| Data | Strategi Cache | TTL |
|---|---|---|
| Slot ketersediaan per lapangan per tanggal | Redis | 30–60 detik |
| Daftar lapangan aktif | Redis | 5 menit |
| Leaderboard | Redis | 1 menit |
| Statistik landing page (jumlah booking, member) | Redis | 10 menit |
| Rating rata-rata per lapangan | Redis | 5 menit |

Cache harus di-invalidate saat ada perubahan data relevan (booking baru, rating baru, dll).

---

## 15. PWA (Progressive Web App)

Web ini harus bisa di-"install" di HP seperti aplikasi native melalui PWA:

- Tambah `manifest.json` dengan nama app, ikon, warna tema
- Implementasi service worker untuk caching aset statis
- Halaman utama harus bisa diakses saat offline (menampilkan pesan offline yang rapi, bukan blank)
- Notifikasi push opsional untuk pengingat booking

Implementasi di Laravel bisa pakai package `silber/bouncer` untuk role, dan service worker di-handle lewat Vite.

---

## 16. Error Monitoring

Integrasikan error monitoring dari hari pertama deploy, bukan setelah ada bug:

- Gunakan **Sentry** (gratis tier tersedia) atau **Flare** (khusus Laravel, lebih detail untuk stack trace Laravel)
- Setiap exception di production harus tercatat otomatis
- Setup alert via email/Slack jika error rate melonjak
- Jangan pernah expose raw error message ke user di production — selalu tampilkan pesan ramah

---

## 17. Ide Tambahan Biar Web Terasa Siap Deploy

- dashboard ringkas dengan statistik utama
- notifikasi realtime di panel admin
- kalender booking interaktif per lapangan
- search bar global
- filter slot per jam dan per tanggal
- invoice page rapi
- halaman 404 custom
- skeleton loading di semua list view
- dark mode opsional
- audit trail aktivitas admin
- backup database rutin
- rate limit login untuk keamanan
- upload foto lapangan multi-image
- SEO basic untuk landing page
- breadcrumb di detail lapangan
- tombol WhatsApp admin untuk bantuan cepat
- **recurring booking untuk member langganan mingguan**
- **QR Code scanner di halaman petugas (pakai kamera HP)**
- **badge "slot tersisa hari ini" dengan update berkala**
- **share booking ke WhatsApp dengan satu klik**

---

## 18. Catatan Implementasi Laravel

Struktur module yang disarankan:
- `Auth`
- `Dashboard`
- `Booking`
- `Payment`
- `Field`
- `Slot`
- `Report`
- `Leaderboard`
- `Rating`
- `QrCode`
- `Admin`

Saran praktik bagus:
- pakai Form Request untuk validasi
- pakai Policy/Gate untuk akses data sensitif
- pakai Service class untuk Midtrans (termasuk idempotency key)
- pakai Observer atau Event untuk log booking
- pakai Transaction database saat booking dan pembayaran
- pakai Queue untuk email/notifikasi dan slot expiry job
- simpan file bukti bayar di storage aman
- **pakai Redis untuk caching dan queue driver**
- **pakai Scheduled Command untuk bersihkan slot_lock yang expired setiap menit**
- **integrasikan Sentry/Flare di AppServiceProvider dari awal**

---

## 19. Ringkasan Final

Sistem ini adalah website sewa lapangan futsal yang lengkap, modern, dan siap berkembang ke produksi. Fokus utamanya ada pada:

- transaksi booking yang aman dengan slot locking mechanism
- pembayaran online via Midtrans + idempotency key
- pembayaran manual dengan alur verifikasi dan SLA yang jelas
- kontrol akses berdasarkan role
- tampilan landing page yang dinamis dengan empty state & skeleton loading yang proper
- QR Code untuk validasi kehadiran di lokasi
- rating lapangan dari member sebagai trust builder
- dashboard admin/petugas yang serius
- laporan dan leaderboard yang bikin sistem terasa hidup
- caching strategy untuk performa saat traffic tinggi
- error monitoring dari hari pertama
- PWA support untuk pengalaman app-like di HP

Kalau dibangun sesuai spesifikasi ini, hasilnya bakal kerasa seperti produk nyata yang siap scale, bukan cuma tugas kampus yang asal jalan.
