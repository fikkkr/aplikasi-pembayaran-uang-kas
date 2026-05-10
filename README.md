# 💰 Sistem Kas Kelas XI PPLG 1
> **Developed by GCT A (Grub Coding Terbaikk AAMIIN)** 🚀

Sistem Manajemen Kas Kelas berbasis Web yang dibangun khusus untuk kebutuhan transparansi keuangan kelas **XI PPLG 1**. Aplikasi ini mempermudah bendahara dalam mengelola iuran murid dan pengeluaran kelas secara otomatis dan presisi.

---

## 🚀 Fitur Utama
* **📊 Smart Dashboard**: Visualisasi saldo total, akumulasi pemasukan, dan total pengeluaran secara real-time.
* **🚥 Auto-Status Indicator**: Sistem cerdas yang mendeteksi status bayar murid secara otomatis:
    * `Lunas` (Target Rp 5.000)
    * `Belum Lunas` (Mencicil)
    * `Belum Bayar` (Tanpa riwayat transaksi)
* **🛡️ Anti-Bonos Validation**: Sistem akan menolak input pengeluaran jika nominal melebihi sisa saldo kas yang tersedia.
* **🕒 WIB Synchronized**: Semua waktu transaksi menggunakan Zona Waktu Asia/Jakarta (WIB).
* **📱 Modern UI**: Interface responsif menggunakan Argon Dashboard 2 dengan navigasi yang intuitif.

## 🛠️ Tech Stack
* **Core**: [Laravel 12](https://laravel.com)
* **UI Engine**: Bootstrap 5 & Argon Dashboard 2
* **Database**: MySQL (Relational DB)
* **Time Management**: Carbon Library (Local ID Support)

## 🗃️ Skema Database
Sistem menggunakan **Eloquent Relationships (One-to-Many)**:
-   **Tabel `murids`**: Identitas utama siswa (Primary Key: `id_murid`).
-   **Tabel `pembayarans`**: Riwayat transaksi baik uang masuk (iuran) maupun uang keluar (belanja kelas).

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

```Bash
cp .env.example .env
```
Sesuaikan konfigurasi database di .env:

```Code snippet
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pembayaran_kas
DB_USERNAME=root
DB_PASSWORD=tanya ke ketua GCT A awokawok
```

### 3. Database Migration
Eksekusi migrasi untuk membangun struktur tabel:

```Bash
php artisan key:generate
php artisan migrate
```

### 4. Running App
```Bash
php artisan serve
Akses aplikasi di: http://127.0.0.1:8000
``` 

## 🤝 Kontributor (GCT A Team)
Dibuat dengan ❤️ dan kurang tidur oleh tim (ketuanya apalagi) GCT A:

#### Fullstack Lead: [fikrimeren--https://github.com/fikkkr]

#### Database Architect: GCT A Team

#### UI/UX Research: GCT A Team

"Coding sampai tipes, revisi sampai mampus." — GCT A Team, 2026 😹🙏