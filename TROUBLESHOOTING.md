## TROUBLESHOOTING - Error "Username atau password salah!"

### 🔍 Diagnosis Cepat

Jika Anda mengalami error "Username atau password salah!" saat login dengan username dan password yang benar, ikuti langkah-langkah berikut:

---

## Solusi Step-by-Step

### **STEP 1: Gunakan Password Helper**

1. Akses: `http://localhost/bengkel_crud/password_helper.php`
2. Buka tab **"Verify Password"**
3. Masukkan username dan password yang ingin Anda test
4. Klik tombol "Test Password"
5. Baca hasil outputnya:
   - Jika menunjukkan **"Password COCOK"** ✓ → Masalah bukan di password hash
   - Jika menunjukkan **"Password TIDAK COCOK"** ✗ → Lanjut ke STEP 2

---

### **STEP 2: Jika Password Tidak Cocok - Reset Password**

#### Cara 1: Menggunakan Password Helper (Paling Mudah)
1. Buka `password_helper.php`
2. Buka tab **"Generate Hash"**
3. Masukkan password baru yang diinginkan (contoh: `admin123`)
4. Klik **"Generate Hash"**
5. Copy hash yang dihasilkan
6. Buka phpMyAdmin → Database `bengkel_db` → Tabel `admin`
7. Jalankan query UPDATE dengan hash yang sudah dicopy:
   ```sql
   UPDATE admin SET password = '[hash_yang_sudah_dicopy]' WHERE username = 'admin';
   ```
8. Klik Execute
9. Test login lagi

#### Cara 2: Menggunakan Terminal (Alternatif)
1. Buka Command Prompt atau PowerShell
2. Jalankan command:
   ```bash
   php -r "echo password_hash('admin123', PASSWORD_BCRYPT);"
   ```
   (Ganti `admin123` dengan password yang diinginkan)
3. Copy hasil hash
4. Lakukan step 6-9 seperti di atas

---

### **STEP 3: Jika Database Kosong - Setup Admin Baru**

Jika tabel `admin` kosong atau belum ada:

#### Cara 1: Menggunakan Password Helper
1. Buka `password_helper.php`
2. Buka tab **"Setup Admin"**
3. Ikuti semua langkah yang tertera
4. Atau langsung ke Cara 2

#### Cara 2: Langsung ke phpMyAdmin
1. Buka phpMyAdmin
2. Pilih database `bengkel_db`
3. Jalankan query berikut untuk membuat tabel:
   ```sql
   CREATE TABLE IF NOT EXISTS admin (
       id INT PRIMARY KEY AUTO_INCREMENT,
       username VARCHAR(100) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );
   ```
4. Buka tab **"Generate Hash"** di `password_helper.php`
5. Generate hash password (contoh: `admin123`)
6. Copy hash yang dihasilkan
7. Jalankan query INSERT:
   ```sql
   INSERT INTO admin (username, password) VALUES ('admin', '[hash_yang_sudah_dicopy]');
   ```
8. Klik Execute
9. Test login dengan username `admin` dan password `admin123`

---

## 🔧 Kemungkinan Penyebab dan Solusi

| Masalah | Gejala | Solusi |
|---------|--------|--------|
| **Password hash tidak valid** | Error "Username atau password salah!" | Generate hash baru di password_helper.php |
| **Password belum di-hash** | Masih error meski password benar | Hash password menggunakan bcrypt |
| **Spasi di awal/akhir password** | Hanya error untuk password tertentu | Cek spasi, gunakan trim() |
| **Database kosong** | Username tidak ditemukan | Insert data admin ke database |
| **Encoding character salah** | Error random | Pastikan tabel charset UTF-8 |
| **PHP version lama** | `password_verify()` tidak tersedia | Update PHP ke versi >= 5.5 |

---

## 📋 Checklist Verifikasi

Pastikan semua ini benar sebelum troubleshoot lebih lanjut:

- [ ] Tabel `admin` sudah ada di database `bengkel_db`
- [ ] Ada minimal 1 record di tabel `admin`
- [ ] Password di-hash menggunakan `password_hash()` dengan `PASSWORD_BCRYPT`
- [ ] Username di database sama persis dengan yang di-input (case-sensitive untuk beberapa database)
- [ ] File `koneksi.php` terhubung ke database yang benar
- [ ] PHP version >= 5.5 (untuk fungsi `password_verify()`)
- [ ] Browser cookies enabled

---

## 🧪 Testing & Debugging

### Test 1: Cek Connection Database
```php
<?php
include 'koneksi.php';

// Test connection
if ($koneksi) {
    echo "✓ Koneksi database berhasil!";
} else {
    echo "✗ Koneksi database gagal: " . mysqli_connect_error();
}

// Test tabel admin
$result = $koneksi->query("SELECT * FROM admin");
if ($result) {
    echo "<br>✓ Tabel admin ada";
    echo "<br>Total record: " . $result->num_rows;
    while ($row = $result->fetch_assoc()) {
        echo "<br>Username: " . $row['username'];
    }
} else {
    echo "<br>✗ Tabel admin tidak ada atau error";
}
?>
```

### Test 2: Cek Password Hash
Buka file `password_helper.php` → Tab "Verify Password" → Masukkan kredensial

### Test 3: Manual Test di PHP
```php
<?php
$password = "admin123";
$hash = '$2y$10$YIjlrBLq8cQFXDpBXmKLkODUpMqLmTdTkZH0DaRH1QV9Z5ZG5VFHK';

if (password_verify($password, $hash)) {
    echo "✓ Password cocok!";
} else {
    echo "✗ Password tidak cocok!";
}
?>
```

---

## 📞 Tips & Trik

### Tips 1: Simpan Password Hash Default
Jika ingin reset ke password default `admin123`, gunakan hash ini:
```
$2y$10$YIjlrBLq8cQFXDpBXmKLkODUpMqLmTdTkZH0DaRH1QV9Z5ZG5VFHK
```

Query:
```sql
UPDATE admin SET password = '$2y$10$YIjlrBLq8cQFXDpBXmKLkODUpMqLmTdTkZH0DaRH1QV9Z5ZG5VFHK' WHERE username = 'admin';
```

### Tips 2: Buat Admin Account Baru
```sql
INSERT INTO admin (username, password) VALUES ('new_user', '[hash_password]');
```

### Tips 3: Lihat Semua Admin
```sql
SELECT id, username, created_at FROM admin;
```

### Tips 4: Delete Admin
```sql
DELETE FROM admin WHERE username = 'username_yang_dihapus';
```

---

## ❓ Masih Error?

Jika sudah mencoba semua cara di atas tetap error:

1. **Cek Error Log PHP**
   - Buka file `php_errors.log` di folder XAMPP
   - Cari error berkaitan dengan login

2. **Buka Browser Console**
   - Tekan F12 di halaman login
   - Tab "Console"
   - Lihat ada error JavaScript atau tidak

3. **Cek Koneksi Database**
   - Pastikan MySQL/MariaDB XAMPP sudah running
   - Buka phpMyAdmin dan verifikasi database

4. **Test Simple PHP**
   - Buat file test.php:
   ```php
   <?php
   include 'koneksi.php';
   $query = $koneksi->query("SELECT VERSION()");
   $result = $query->fetch_assoc();
   echo "MySQL Version: " . $result['VERSION()'];
   ?>
   ```
   - Akses file tersebut dan lihat hasilnya

5. **Contact/Ask for Help**
   - Sertakan error message lengkap
   - Sertakan output dari password_helper.php
   - Sertakan screenshot phpMyAdmin (data di tabel admin)

---

## 🔒 Security Note

File `password_helper.php` digunakan untuk debugging. Setelah setup selesai, Anda bisa:
- Menghapus file ini
- Atau membatasi akses dengan password
- Atau memindahkan ke folder yang tidak dapat diakses publik

Jangan biarkan file ini dapat diakses di production!
