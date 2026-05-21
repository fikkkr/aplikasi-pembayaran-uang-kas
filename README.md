Markdown
# 💰 Sistem Kas Kelas XI PPLG 1
> **Developed by GCT A (Grub Coding Terbaikk AAMIIN)** 🚀

Sistem Manajemen Kas Kelas berbasis Web yang dibangun khusus untuk kebutuhan transparansi keuangan kelas **XI PPLG 1**. Aplikasi ini mempermudah bendahara dalam mengelola iuran murid, melacak pengeluaran kelas secara otomatis, serta menyediakan laporan siap cetak yang presisi.

---

## 🚀 Fitur Utama
* **📊 Smart Dashboard & Summary**: Visualisasi saldo total, akumulasi pemasukan, dan total pengeluaran secara *real-time* dengan efek animasi interaksi mikro (`hover-card`).
* **🚥 Auto-Status Indicator**: Sistem cerdas yang mendeteksi status bayar murid secara otomatis per periode minggu:
    * `Lunas Semua` (Semua murid di periode tersebut sudah membayar)
    * `Belum Bayar` / `Tunggakan` (Menampilkan jumlah murid yang belum menyelesaikan kewajibannya)
* **📅 Dynamic Monthly Accordion**: Laporan bulanan otomatis yang dikelompokkan secara rapi dalam komponen Accordion interaktif untuk menghemat ruang baca dan mempermudah pencarian rekam historis.
* **🖨️ PDF & Print Optimizer**: Fitur cetak instan halaman penuh via `window.print()` yang otomatis menyembunyikan elemen navigasi (*sidebar*, *navbar*, tombol aksi) menggunakan CSS `@media print` sehingga menghasilkan dokumen fisik laporan kas yang bersih.
* **🛡️ Anti-Bonos Validation**: Sistem otomatis menolak input pengeluaran jika nominal melebihi sisa saldo kas yang tersedia.
* **⚡ Modern Security & Quick Logout**: Manajemen sesi yang aman antar-role (Admin/Bendahara) dengan integrasi bypass token CSRF di core-system, memastikan proses logout 100% lancar tanpa gangguan *error 419 | Page Expired*.
* **🕒 WIB Synchronized**: Semua waktu transaksi menggunakan Zona Waktu Asia/Jakarta (WIB) yang disinkronkan langsung via pustaka Carbon.

---

## 🛠️ Tech Stack
* **Core Framework**: [Laravel 12](https://laravel.com) (PHP v8.2+)
* **UI Engine**: Bootstrap 5 & Argon Dashboard 2
* **Database**: MySQL (Relational DB)
* **Time Management**: Carbon Library (Local ID Support)

---

## 🗃️ Skema Database
Sistem menggunakan konsep **Eloquent Relationships (One-to-Many)**:
- **Tabel `murids`**: Identitas utama siswa (Primary Key: `id_murid`).
- **Tabel `pembayarans` / `laporans`**: Jalur riwayat transaksi baik uang masuk (iuran) maupun uang keluar (belanja kelas).
- **Data Grouping**: Pengelompokan dinamis berbasis koleksi data bulan (`$laporanGrouped`) sebagai data induk, dan data minggu sebagai anak relasinya.

---

## 🛠️ Cara Install (Local Development)

### 1. Clone & Dependencies
```bash
git clone [https://github.com/USERNAME_LO/pembayaran_kas.git](https://github.com/USERNAME_LO/pembayaran_kas.git)
cd pembayaran_kas
composer install
```

### 2. Environment Setup
Copy file .env.example menjadi .env, lalu buat database bernama pembayaran_kas di phpMyAdmin.

```
Bash
cp .env.example .env
```
Sesuaikan konfigurasi database di file .env:

Ini, TOML
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pembayaran_kas
DB_USERNAME=root
DB_PASSWORD=tanya ke ketua GCT A awokawok
```

### 3. Database Migration & Configuration Clear
Eksekusi perintah di terminal untuk membangun struktur tabel dan membersihkan sisa cache session:

```
Bash
php artisan key:generate
php artisan migrate
php artisan config:clear
php artisan route:clear
```

### 4. Running App
```
Bash
php artisan serve
```
Akses aplikasi di: ```http://127.0.0.1:8000```


#### 🤝 Kontributor (GCT A Team)
Dibuat dengan ❤️ dan kurang tidur oleh tim (ketuanya apalagi) GCT A:

Fullstack Lead & Debugger: fikrimeren (https://github.com/fikkkr)

Database Architect: GCT A Team

UI/UX Research & Print Optimizer: GCT A Team

"Coding sampai tipes, revisi sampai lulus (amit-amit).

" — GCT A Team, 2026 😹🙏
