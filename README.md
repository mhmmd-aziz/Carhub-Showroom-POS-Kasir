# CarHub - Sistem Manajemen Jual Beli Mobil

Sistem informasi manajemen dealer mobil berbasis web yang dibangun dengan **CodeIgniter 3**, **Tailwind CSS**, dan **Framer Motion**.

---

## 🚀 Cara Menjalankan Aplikasi (Dari Awal Sampai Akhir)

Aplikasi ini membutuhkan **PHP** (direkomendasikan versi 7.4 atau 8.x) dan **MySQL**.

### Langkah 1: Persiapan Database
1. Buka **phpMyAdmin** (biasanya di `http://localhost/phpmyadmin` jika menggunakan XAMPP/Laragon).
2. Buat database baru dengan nama `pos_showroom`.
3. Import file `database.sql` yang ada di dalam folder proyek ini ke dalam database `pos_showroom`.
   > **Catatan:** File `database.sql` ini dapat memuat contoh data (master data, transaksi, dsb.) yang sudah kami siapkan sebelumnya agar Anda bisa langsung melihat bagaimana aplikasi bekerja tanpa harus menginput semuanya dari nol.

### Langkah 2: Menjalankan Aplikasi

Aplikasi ini dapat dijalankan dengan 2 cara. Silakan pilih salah satu yang paling mudah bagi Anda:

**Opsi A: Menggunakan XAMPP / Laragon / WAMP (Jika ingin memakai Apache)**
Jika memilih cara ini, Anda **wajib** memindahkan folder proyek ke dalam server lokal.
1. Copy/pindahkan seluruh folder proyek ini ke dalam folder `htdocs` (jika menggunakan XAMPP, biasanya `C:\xampp\htdocs\showroom_mobil`) atau `www` (Laragon).
2. Pastikan **Apache** dan **MySQL** sudah berjalan (Start) di Control Panel XAMPP Anda.
3. Buka browser dan akses: **[http://localhost/showroom_mobil](http://localhost/showroom_mobil)**
   *(Sesuaikan `showroom_mobil` dengan nama folder Anda).*

**Opsi B: Menggunakan PHP Built-in Server (Tanpa perlu dipindah ke htdocs)**
Cara ini sangat direkomendasikan jika Anda tidak ingin repot memindahkan folder. Folder aplikasi bisa diletakkan di mana saja (misal: di Desktop atau Documents).
1. Pastikan **MySQL** tetap berjalan (Start) di Control Panel XAMPP Anda agar database bisa diakses.
2. Buka Terminal atau Command Prompt, lalu arahkan ke dalam folder proyek ini berada.
3. Jalankan perintah berikut:
   ```bash
   php -S localhost:8080 router.php
   ```
4. Buka browser dan akses: **[http://localhost:8080](http://localhost:8080)**

---

## 🔑 Akses Login

Terdapat dua akun utama yang sudah disiapkan:

1. **Admin (Kasir/Admin)**
   - Username: `admin`
   - Password: `admin123`
   
2. **Owner (Pemilik Showroom)**
   - Username: `owner`
   - Password: `owner123`

---

## 🛠 Panduan Pengujian (Start to Finish)

Untuk menguji fitur secara lengkap, ikuti alur berikut:

1. **Login & Dashboard**
   - Login sebagai `admin` untuk akses operasional harian.
   
2. **Manajemen Master Data**
   - **Customer**: Masuk ke menu "Customer" dan coba tambahkan data pelanggan baru.
   - **Supplier**: Masuk ke menu "Supplier" dan coba tambahkan data supplier.
   - **Mobil (Katalog & Stok)**: Masuk ke menu "Mobil", tambahkan mobil baru dan kaitkan dengan supplier yang dibuat tadi.

3. **Transaksi Pembelian (Dari Supplier)**
   - Masuk ke menu **Pembelian**.
   - Buat transaksi pembelian mobil baru.
   - Lakukan **Pembayaran Pembelian** untuk melunasi transaksi dengan supplier.

4. **Transaksi Pemesanan & Penjualan (Ke Customer)**
   - Masuk ke menu **Pemesanan Mobil**. Buat pesanan baru untuk customer.
   - Masuk ke **Pembayaran**. Lakukan pembayaran secara bertahap mulai dari **Tanda Jadi**, **DP**, hingga **Pelunasan**.
   - Setelah lunas, pesanan akan secara otomatis masuk ke data **Penjualan**.

5. **Penyerahan Unit**
   - Masuk ke menu **Penyerahan Mobil**.
   - Proses penyerahan unit (ambil sendiri atau diantar) beserta dokumen BPKB kepada customer.
   - Anda juga dapat mencetak **Surat Jalan**.

6. **Laporan Keuangan & Operasional**
   - (Bisa login sebagai `owner` atau `admin`)
   - Masuk ke menu **Laporan**.
   - Generate berbagai jenis laporan (Pembelian, Penjualan, atau Stok Mobil) untuk melihat rekapitulasi data berdasarkan periode waktu. Anda juga bisa mencetak Faktur/Kwitansi.

---

## 📦 Struktur Modul
- Sistem Autentikasi (Login/Logout)
- Landing Page
- Dashboard Utama
- Manajemen Master (Customer, Supplier, Mobil)
- Transaksi (Pembelian, Pemesanan, Penjualan)
- Pembayaran (Hutang Supplier & Piutang Customer)
- Penyerahan Unit & Cetak Surat Jalan
- Laporan (Pembelian, Penjualan, Stok, dll) & Cetak PDF/Faktur

Selamat mencoba! Jika ada kendala, pastikan koneksi database sudah sesuai di `application/config/database.php`.
