# AutoDesk - Sistem POS Showroom Mobil

Sistem informasi manajemen dealer mobil berbasis web yang dibangun dengan **CodeIgniter 3**, **Tailwind CSS**, dan **Framer Motion**.

---

## 🚀 Cara Menjalankan Aplikasi

Aplikasi ini menggunakan **PHP** dan **MySQL**. Anda memiliki dua opsi untuk menjalankannya:

### Opsi 1: Menggunakan PHP Built-in Server (Sangat Mudah)
Jika Anda sudah memiliki PHP yang terinstall di komputer, Anda bisa menjalankannya langsung tanpa perlu memindahkannya ke folder `htdocs`.

1. Pastikan database `pos_showroom` sudah dibuat di **phpMyAdmin** dan Anda sudah mengimport file `database.sql` ke dalamnya.
2. Buka Terminal atau Command Prompt, lalu arahkan ke folder proyek ini (`d:\web_job\showroom_mobil`).
3. Jalankan perintah berikut:
   ```bash
   php -S localhost:8080 router.php
   ```
4. Buka browser dan akses: **[http://localhost:8080](http://localhost:8080)**

---

### Opsi 2: Menggunakan XAMPP / Laragon / WAMP
Jika Anda lebih terbiasa menggunakan XAMPP:

1. Copy/pindahkan seluruh folder `showroom_mobil` ini ke dalam folder `htdocs` di XAMPP Anda (biasanya `C:\xampp\htdocs\showroom_mobil`).
2. Pastikan Apache dan MySQL sudah berjalan (Start) di XAMPP Control Panel.
3. Pastikan database `pos_showroom` sudah dibuat dan file `database.sql` sudah di-import.
4. Karena kita menggunakan `.htaccess` untuk menghapus `index.php`, pastikan modul `rewrite` (mod_rewrite) aktif di Apache Anda.
5. Buka browser dan akses: **[http://localhost/showroom_mobil](http://localhost/showroom_mobil)**

---

## 🔑 Akses Login

Secara bawaan, saya telah menyiapkan akun admin yang bisa Anda gunakan untuk masuk:

- **Username**: `admin`
- **Password**: `admin123`
- **Role**: Admin

Dan juga akun untuk pemilik dealer (Owner):

- **Username**: `owner`
- **Password**: `owner123`
- **Role**: Owner
---

## 🛠 Struktur Modul Saat Ini

Sejauh ini, modul-modul berikut telah selesai dibangun:
- Sistem Autentikasi (Login/Logout)
- Dashboard Utama
- Manajemen Customer (CRUD lengkap)
- Manajemen Supplier (CRUD lengkap)
- Katalog Mobil & Stok (CRUD lengkap)

Selamat mencoba!
