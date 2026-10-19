# Perpustakaan Mini 📚

Aplikasi web sistem informasi peminjaman buku perpustakaan modern berbasis **Laravel 11**, **Laravel Breeze (Blade)**, **Tailwind CSS**, **Alpine.js**, dan **MySQL**.

---

## 🚀 Fitur Utama

### 1. Role & Autentikasi
- Multi-role: **Admin** dan **Anggota**.
- Middleware proteksi peran (`role:admin`, `role:anggota`).
- Pengalihan otomatis ke dashboard sesuai peran setelah login.
- Tampilan autentikasi responsif dengan layout *split-screen*.

### 2. Admin
- **Dashboard Statistik**:
  - Kartu metrik: Total Judul Buku, Total Eksemplar, Total Anggota, Peminjaman Aktif, Peminjaman Terlambat, dan Total Denda Terkumpul.
  - Grafik interaktif tren peminjaman 6 bulan terakhir (**Chart.js**).
  - Peringatan stok buku menipis (<= 2).
  - Tabel transaksi peminjaman terbaru.
- **Manajemen Kategori**: CRUD lengkap dengan validasi keunikan dan pencegahan hapus kategori yang masih memiliki buku.
- **Manajemen Buku**: CRUD buku lengkap (Judul, Penulis, Penerbit, Tahun, Stok, dan upload Cover) dengan preview gambar langsung (Alpine.js) dan pencegahan hapus buku yang sedang dipinjam.
- **Kelola Anggota**: CRUD data anggota, ringkasan riwayat pinjaman per anggota, dan proteksi hapus anggota yang memiliki pinjaman aktif.
- **Sirkulasi Buku (Peminjaman & Pengembalian)**:
  - Pencatatan peminjaman dengan multi-buku (1-3 buku).
  - Transaksi database aman (`DB::transaction` & `lockForUpdate`).
  - Pengurangan stok otomatis saat dipinjam dan penambahan kembali saat dikembalikan.
  - Validasi batas maksimal 3 buku aktif per anggota.
  - Pengembalian buku dengan kalkulasi denda otomatis (**Rp1.000 / hari keterlambatan**).
- **Laporan & Ekspor PDF**:
  - Filter rentang tanggal peminjaman dan status (`dipinjam`, `dikembalikan`, `terlambat`).
  - Ringkasan total transaksi dan denda.
  - Ekspor dokumen resmi format landscape (**Barryvdh DomPDF**).

### 3. Anggota
- **Dashboard Anggota**: Indikator kuota sisa pinjaman (maks 3 buku), peringatan peminjaman terlambat, daftar buku sedang dipinjam dengan hitungan hari sisa / denda berjalan, dan rekomendasi buku siap pinjam.
- **Katalog Buku**: Grid kartu modern dengan cover, judul, penulis, badge kategori, ketersediaan stok, pencarian judul/penulis/penerbit, dan filter kategori.
- **Peminjaman Mandiri**: Pinjam buku langsung dari katalog (durasi default 7 hari) dengan validasi kuota dan stok.
- **Riwayat & Denda**: Daftar seluruh riwayat transaksi serta halaman khusus informasi rincian denda keterlambatan berjalan.

### 4. Tampilan & UI/UX
- Desain minimalis dan bersih dengan font **Inter**, aksen warna **Indigo**, sudut membulat (`rounded-xl`), dan bayangan halus.
- Sidebar dapat di-collapse di desktop dan bertransformasi menjadi drawer di mobile.
- **Dark Mode toggle** dengan penyimpanan preferensi di `localStorage`.
- Komponen Blade reusable (`card`, `badge`, `modal`, `button`, `toast`).

---

## 🛠️ Persyaratan Sistem

- **PHP**: >= 8.2 (dengan ekstensi `pdo_mysql`, `gd` / `imagick`, `mbstring`, `fileinfo`)
- **Composer**: >= 2.0
- **Node.js**: >= 18.x & **NPM**
- **Database**: MySQL >= 8.0 atau MariaDB >= 10.4

---

## ⚙️ Panduan Instalasi

1. **Clone repository & masuk ke direktori proyek**:
   ```bash
   git clone https://github.com/rawplane/mini-library.git
   cd mini-library
   ```

2. **Install dependensi PHP & JavaScript**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   Salin file konfigurasi `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Sesuaikan koneksi database MySQL pada `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mini_library
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan Migrasi & Seeder Database**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Buat Symlink Storage untuk Cover Buku**:
   ```bash
   php artisan storage:link
   ```

6. **Kompilasi Asset Frontend**:
   ```bash
   npm run build
   # Atau untuk mode development:
   # npm run dev
   ```

7. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Akses aplikasi di peramban: `http://localhost:8000`.

---

## 🔑 Akun Pengguna Bawaan (Seeder)

| Peran | Email | Password | Keterangan |
|---|---|---|---|
| **Admin** | `admin@perpus.test` | `password` | Akses penuh dashboard admin, master data, dan sirkulasi |
| **Anggota** | `anggota@perpus.test` | `password` | Anggota utama dengan sampel riwayat transaksi & denda |
| **Anggota Dummy** | `anggota1@perpus.test` s/d `anggota9@perpus.test` | `password` | Akun anggota tambahan |

---

## 🧪 Pengujian (Automated Tests)

Aplikasi dilengkapi dengan rangkaian pengujian fitur (**PHPUnit / Pest**) mencakup autentikasi, middleware role, relasi database, kalkulasi denda, kendali stok, dan ekspor laporan:

```bash
php artisan test
```

---

## 📁 Struktur Direktori Penting

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Dashboard, Category, Book, Member, Loan, Report
│   │   ├── Anggota/        # Dashboard, Catalog, LoanHistory
│   │   └── Auth/           # AuthenticatedSession, RegisteredUser
│   ├── Middleware/         # RoleMiddleware
│   └── Requests/           # CategoryRequest, BookRequest, MemberRequest, LoanRequest, BorrowRequest
└── Models/                 # User, Category, Book, Loan, LoanDetail
database/
├── factories/              # User, Category, Book, Loan Factory
├── migrations/             # Struktur tabel relasional
└── seeders/                # Category, Book, User, Loan Seeder
resources/
└── views/
    ├── admin/              # Dashboard, buku, kategori, anggota, peminjaman, laporan, PDF
    ├── anggota/            # Dashboard, katalog, riwayat peminjaman, info denda
    ├── components/         # Blade reusable components (card, badge, modal, button, toast)
    └── layouts/            # app.blade.php (sidebar/topbar/darkmode), guest.blade.php
```

---

## 📜 Lisensi
Aplikasi ini dilisensikan di bawah lisensi terbuka [MIT](LICENSE).
