# InventarisIT
Sistem Manajemen Inventaris IT komprehensif yang dibangun menggunakan Laravel.

## Fitur Utama
- Manajemen Aset Hardware & Software
- Manajemen Penugasan Aset ke Pegawai
- Permintaan Peminjaman & Pengadaan Baru
- Pemeliharaan & Pemusnahan Aset
- Laporan & Audit
- Cetak & Scan Label Barcode
- Role & Permission Management (Spatie)

## Panduan Pengembangan & Skalabilitas (Scalable Development)
Untuk memastikan aplikasi tetap bersih, terstruktur, dan mudah ditambahkan fitur di masa depan, tim pengembang diharapkan mengikuti standar berikut:

### 1. Struktur Branching (Git Flow)
Gunakan Git Flow atau GitHub Flow untuk mencegah bentrokan kode (conflict) dengan anggota tim lain:
- **`main`**: Branch utama yang berisi kode stabil dan siap digunakan (Production).
- **`staging`**: Branch untuk testing menyeluruh *(Quality Assurance)* sebelum menggabungkannya ke `main`.
- **`develop`**: Branch utama untuk pengembangan harian.
- **`feature/*`**: Digunakan untuk membuat fitur baru. Buat branch dari `develop`. (Contoh: `feature/export-excel`).
- **`bugfix/*`** / **`hotfix/*`**: Branch khusus untuk memperbaiki bugs. (Contoh: `bugfix/fix-login-error`).

### 2. Standar Penulisan Kode (Clean Code)
Agar sistem bisa diskalakan (scalable) dengan mudah:
- **Fat Model, Skinny Controller**: Pindahkan logika / proses validasi kompleks ke layer yang terpisah seperti **Service Classes**, **Action Classes**, atau **Form Requests**. Biarkan Controller hanya mengelola Request & Response.
- **Gunakan Interface & Repository**: Jika aplikasi semakin besar dan koneksi database berubah-ubah formatnya, gunakan Repository Pattern.
- **DRY (Don't Repeat Yourself)**: Hindari duplikasi kode. Jadikan fungsi yang sering dipanggil sebagai Trait, Helpers, atau Base Component.
- **Reusable Blade Components**: Pisahkan UI komponen yang berulang (misal Modals, Alerts, Buttons) menjadi file Blade Component tersendiri.

### 3. CI/CD Pipeline (Rekomendasi)
Kedepannya sangat disarankan menggunakan fitur seperti **GitHub Actions** untuk menjalankan otomasi:
- **Automated Testing** (Unit & Feature Test) setiap ada Pull Request.
- **Linting & Code Style Checks** memastikan semua kode sesuai standar sebelum dimerge.
- **Automated Deployment** jika fitur di branch `main` sudah diverifikasi stabil.

## Cara Instalasi / Menjalankan Project (Setup)

1. Clone repository ini:
   ```bash
   git clone <url-repo-github-anda>
   ```
2. Copy file konfigurasi environment dan sesuaikan `DB_DATABASE`, `DB_USERNAME`, dll:
   ```bash
   cp .env.example .env
   ```
3. Install semua dependensi backend & frontend:
   ```bash
   composer install
   npm install && npm run build
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Jalankan migrasi dan seeder database untuk data awal (*dummy*):
   ```bash
   php artisan migrate --seed
   ```
6. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
