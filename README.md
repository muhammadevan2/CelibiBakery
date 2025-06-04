<<<<<<< HEAD
# CelibiBakery
=======
# Celibi Bakery Order Website

Website pemesanan roti dan kue untuk Celibi Bakery. Terdiri dari dua bagian:
- **Halaman Pelanggan**: Untuk memesan menu tanpa perlu login.
- **Halaman Kasir**: Untuk mengelola pesanan dan status pembayaran (perlu login).

## Fitur Utama

### Pelanggan
- Melihat daftar menu roti/kue yang tersedia.
- Memesan menu secara langsung (tanpa login).
- Checkout dengan upload bukti pembayaran via QRIS.

### Kasir
- Login untuk akses dashboard.  
- Melihat dan mengelola semua pesanan.
- Mengubah status pesanan (pending, process, done, cancel).
- Menghapus pesanan dengan status **done**.
- Mencetak struk pesanan (PDF).

## Akun Kasir Default
- Email: kasir@example.com  
- Password: kasir

## Instalasi & Penggunaan

### 1. Clone repository

```bash
git clone https://github.com/muhammadevan2/CelibiBakery.git
cd CelibiBakery
````

### 2. Install dependencies

```bash
composer install
npm install
npm run dev
```

### 3. Setup environment

File `.env` sudah disiapkan, tinggal cek dan sesuaikan jika perlu (opsional).

### 4. Import database

Import file `celibi_bakery.sql` yang ada di folder `database` ke MySQL kamu.

Contoh perintah (Linux/macOS):

```bash
mysql -u root -p nama_database < database/celibi_bakery.sql
```

Sesuaikan `nama_database` dengan nama database yang kamu buat.

### 5. Jalankan project

```bash
php artisan serve
```

Lalu buka `http://localhost:8000` di browser kamu.

Akun Login Kasir
Email : kasir@exmaple.com
Pass : kasir
>>>>>>> fb3b2ca (Final Commit)
