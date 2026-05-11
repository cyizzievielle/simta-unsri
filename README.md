# SIMTA MI UNSRI

SIMTA MI UNSRI adalah sistem informasi tugas akhir untuk membantu alur administrasi, pengajuan, review, dan arsip tugas akhir mahasiswa Manajemen Informatika Universitas Sriwijaya.

Live production: https://project-simta.my.id

> Situs di atas adalah server live/production, bukan halaman demo.

## Ringkasan

Aplikasi ini dibangun dengan CodeIgniter 4 dan mendukung tiga peran utama: admin, mahasiswa, dan dosen. Fokus sistem adalah memindahkan proses tugas akhir ke satu tempat yang rapi, mulai dari pengajuan pembimbing, pengajuan judul, upload proposal, review dosen, penerbitan surat keputusan, sampai laporan dan audit aktivitas.

## Fitur Utama

- Autentikasi login, logout, forgot password, dan reset password.
- Dashboard berbasis peran untuk admin, mahasiswa, dan dosen.
- Manajemen user admin, mahasiswa, dan dosen.
- Pengelolaan program studi dan periode akademik.
- Pengajuan dan persetujuan pembimbing tugas akhir.
- Pengajuan judul dengan review, revisi, riwayat, dan pengecekan similarity.
- Upload proposal tugas akhir, review dosen, revisi, dan riwayat review.
- Chat internal antar pengguna dengan dukungan lampiran.
- Notifikasi dan audit log aktivitas sistem.
- Penerbitan serta arsip Surat Keputusan.
- Laporan dan ekspor PDF untuk rekap semester, judul, proposal, dan SK.

## Teknologi

- PHP 8.2+
- CodeIgniter 4.7+
- MySQL/MariaDB
- Composer
- Dompdf untuk ekspor PDF

## Struktur Singkat

```text
app/
  Config/          Konfigurasi aplikasi, route, filter, dan service
  Controllers/     Logika fitur dan endpoint
  Database/        Migration dan seed database
  Filters/         Middleware/filter request
  Helpers/         Helper aplikasi
  Models/          Model database
  Views/           Template halaman dashboard, auth, PDF, dan chat
public/
  assets/          CSS, gambar, dan aset publik
  uploads/         File upload dari fitur proposal, chat, profil, dan SK
writable/          Cache, log, session, dan file runtime CodeIgniter
```

## Instalasi Lokal

1. Clone repository.

```bash
git clone https://github.com/cyizzievielle/simta-unsri.git
cd simta-unsri
```

2. Install dependency PHP.

```bash
composer install
```

3. Buat file `.env` dari template environment CodeIgniter, lalu sesuaikan konfigurasi aplikasi dan database.

```bash
cp env .env
```

Konfigurasi penting:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/sistem_ta_mi_unsri/public/'

database.default.hostname = localhost
database.default.database = nama_database
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

4. Jalankan migration.

```bash
php spark migrate
```

5. Jalankan server lokal.

```bash
php spark serve
```

Default server lokal CodeIgniter akan berjalan di `http://localhost:8080`.

## Deployment

Untuk server production, arahkan document root web server ke folder `public/`. Pastikan `.env` production sudah memakai konfigurasi aman:

```ini
CI_ENVIRONMENT = production
app.baseURL = 'https://project-simta.my.id/'
```

Pastikan juga permission folder `writable/` dan folder upload di `public/uploads/` dapat ditulis oleh web server.

## Perintah Berguna

```bash
composer install
php spark migrate
php spark serve
vendor/bin/phpunit
```

## Catatan Keamanan

- Jangan commit file `.env`, kredensial database, atau file konfigurasi rahasia.
- Validasi file upload tetap harus dijaga di sisi server.
- Backup database production sebelum menjalankan migration atau perubahan besar.
- Server live berada di `https://project-simta.my.id` dan digunakan sebagai production.

## Lisensi

Proyek ini mengikuti lisensi yang tersedia di file `LICENSE`.
