# Panduan Instalasi & Menjalankan Aplikasi CarHub Showroom

Panduan ini menjelaskan langkah-langkah untuk menjalankan aplikasi sistem informasi jual beli mobil (CarHub) di komputer lokal Anda. Aplikasi ini dibangun menggunakan PHP (CodeIgniter 3) dan database MySQL.

---

## 1. Persiapan Awal (Mendapatkan Source Code)

Anda bisa mendapatkan source code aplikasi dengan dua cara:

### Opsi A: Download ZIP
1. Download file ZIP dari repository project.
2. Ekstrak file ZIP tersebut.
3. Ubah nama folder hasil ekstraksi menjadi `showroom_mobil` (agar sesuai dengan konfigurasi bawaan).
4. Pindahkan folder `showroom_mobil` ke dalam direktori web server Anda:
   - Jika menggunakan **XAMPP**: pindahkan ke folder `C:\xampp\htdocs\`
   - Jika menggunakan **Laragon**: pindahkan ke folder `C:\laragon\www\`

### Opsi B: Clone via Git (Untuk Developer)
1. Buka Terminal / Command Prompt di folder `htdocs` (XAMPP) atau `www` (Laragon).
2. Jalankan perintah berikut:
   ```bash
   git clone <url-repository-anda> showroom_mobil
   ```

---

## 2. Persiapan Database MySQL

Aplikasi ini membutuhkan database MySQL untuk menyimpan data.

1. Pastikan modul **Apache** dan **MySQL** di aplikasi XAMPP/Laragon Anda sudah dalam keadaan **Start/Running**.
2. Buka browser dan akses phpMyAdmin melalui link berikut: 
   👉 `http://localhost/phpmyadmin`
3. Di panel sebelah kiri, klik menu **New** untuk membuat database baru.
4. Masukkan nama database: **`pos_showroom`**
5. Klik tombol **Create** (Buat).

---

## 3. Import File Database

Project ini sudah dilengkapi dengan file database yang siap digunakan (beserta data dummy contoh).

1. Pastikan Anda masih berada di dalam database `pos_showroom` di phpMyAdmin.
2. Klik tab **Import** di bagian atas.
3. Klik tombol **Choose File** (Pilih File).
4. Cari dan pilih file `pos_showroom.sql` yang berada di dalam folder project aplikasi (`htdocs/showroom_mobil/pos_showroom.sql`).
5. Scroll ke paling bawah dan klik tombol **Go** (Kirim) / **Import**.
6. Tunggu proses import selesai hingga muncul notifikasi sukses berwarna hijau.

> **Penting:** File `pos_showroom.sql` sudah berisi data contoh untuk memudahkan Anda mencoba fitur aplikasi langsung (seperti data supplier, mobil, customer, dan transaksi penjualan).

---

## 4. Menjalankan Aplikasi

Setelah source code dan database siap, Anda bisa langsung membuka aplikasinya:

1. Buka browser Anda (Google Chrome / Firefox / Edge).
2. Akses URL berikut:
   👉 **`http://localhost/showroom_mobil`**
   *(Catatan: URL ini menyesuaikan dengan nama folder yang Anda letakkan di dalam htdocs).*

**Alternatif (Menggunakan PHP Built-in Server):**
Jika Anda terbiasa dengan terminal, buka terminal di dalam folder project dan jalankan:
```bash
php -S localhost:8080 router.php
```
Lalu akses `http://localhost:8080` di browser.

---

## 5. Akun Login Sistem

Setelah halaman login terbuka, Anda dapat menggunakan akun berikut untuk masuk ke dalam sistem:

**Akses Administrator (Kasir / Admin):**
- Username : `admin`
- Password : `admin123`

**Akses Owner (Pemilik Showroom):**
- Username : `owner`
- Password : `owner123`

---

## 6. Selesai! 🎉

Aplikasi sudah siap digunakan. Anda dapat mulai mencoba melakukan transaksi pembelian mobil (dari menu Pembelian) atau membuat pemesanan dari pelanggan (dari menu Pemesanan).
