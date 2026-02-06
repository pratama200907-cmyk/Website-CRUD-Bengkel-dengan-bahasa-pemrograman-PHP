## SETUP LOGIN & LOGOUT SYSTEM

### 1. Database Setup

Jalankan query SQL berikut di phpMyAdmin untuk membuat tabel admin:

```sql
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2. Insert Data Admin

Insert admin default dengan username `admin` dan password `admin123`:

```sql
INSERT INTO admin (username, password) VALUES 
('admin', '$2y$10$YIjlrBLq8cQFXDpBXmKLkODUpMqLmTdTkZH0DaRH1QV9Z5ZG5VFHK');
```

**Username:** admin
**Password:** admin123

### 3. Cara Membuat Admin Baru

Jika ingin menambah admin baru dengan password yang berbeda, ikuti langkah berikut:

1. Buka terminal/command prompt
2. Jalankan PHP command untuk generate password hash:
   ```php
   php -r "echo password_hash('password_anda', PASSWORD_BCRYPT);"
   ```
3. Copy hasil hash tersebut
4. Insert ke database dengan query:
   ```sql
   INSERT INTO admin (username, password) VALUES ('username_baru', 'hash_yang_sudah_dikopi');
   ```

### 4. Fitur Login/Logout

**File-file yang ditambahkan:**
- `login.php` - Halaman login dengan validasi menggunakan prepared statements
- `logout.php` - Script untuk logout dan destroy session
- `check_session.php` - Include file untuk checking session (opsional)

**File-file yang dimodifikasi:**
- `index.php` - Menambahkan session checking
- `barang.php` - Menambahkan session checking
- `servis.php` - Menambahkan session checking
- `keuangan.php` - Menambahkan session checking
- `tambah.php` - Menambahkan session checking
- `edit.php` - Menambahkan session checking
- `hapus.php` - Menambahkan session checking
- `navbar.php` - Menambahkan logout button dan menampilkan username

### 5. Keamanan

✅ Menggunakan prepared statements untuk mencegah SQL injection
✅ Password di-hash menggunakan bcrypt (PASSWORD_BCRYPT)
✅ Session-based authentication
✅ Redirect otomatis ke login jika belum login
✅ CSRF protection melalui form POST

### 6. Cara Menggunakan

1. Akses aplikasi di `http://localhost/bengkel_crud/`
2. Anda akan diarahkan ke halaman login jika belum login
3. Masukkan username dan password
4. Setelah login berhasil, username akan muncul di navbar
5. Klik tombol "Logout" untuk logout

### 7. Troubleshooting

**Masalah: Redirect terus ke login**
- Pastikan session sudah di-start di semua file
- Cek browser cookies apakah sudah enable

**Masalah: Login tidak bisa dengan password default**
- Pastikan password hash sudah benar di database
- Gunakan password `admin123` (case-sensitive)
- Pastikan username di database adalah `admin`

**Masalah: Tampilan logout button tidak muncul**
- Clear browser cache
- Pastikan navbar.php sudah di-include di setiap halaman
- Cek di browser console untuk error JavaScript
