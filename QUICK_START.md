## 🚀 QUICK START - Debug Login Error

Jika mengalami error "Username atau password salah!", ikuti ini:

### **Langkah 1: Diagnosis (2 menit)**
Buka: http://localhost/bengkel_crud/db_test.php

Ini akan menunjukkan:
- ✓ Status koneksi database
- ✓ Apakah tabel admin ada
- ✓ Data admin apa saja di database

### **Langkah 2: Test Password (3 menit)**
Buka: http://localhost/bengkel_crud/password_helper.php

Tab "Verify Password" → masukkan username & password → klik "Test Password"

Hasil:
- **Password COCOK ✓** = Masalah di setting lain, bukan password
- **Password TIDAK COCOK ✗** = Lanjut ke Langkah 3

### **Langkah 3: Reset Password (3 menit)**
Di `password_helper.php`:

1. Tab "Generate Hash"
2. Masukkan password baru (contoh: `admin123`)
3. Klik "Generate Hash"
4. Copy hash yang muncul
5. Buka phpMyAdmin → bengkel_db → tab SQL
6. Jalankan query:
   ```sql
   UPDATE admin SET password = '[PASTE_HASH_DI_SINI]' WHERE username = 'admin';
   ```
7. Klik Execute
8. Test login lagi

### **Langkah 4: Jika Masih Error**
Buka: http://localhost/bengkel_crud/TROUBLESHOOTING.md

File ini berisi troubleshooting lengkap untuk semua kasus.

---

## 📊 Ringkas Tools yang Tersedia:

| Tool | URL | Fungsi |
|------|-----|--------|
| **DB Test** | /db_test.php | Cek status database & data |
| **Password Helper** | /password_helper.php | Generate hash & verify password |
| **Login** | /login.php | Halaman login utama |
| **Troubleshooting** | /TROUBLESHOOTING.md | Panduan lengkap semua masalah |

---

## 💡 Tips

- **Password default**: `admin123` (hash sudah ada di database)
- **Username default**: `admin`
- Semua password di-hash dengan bcrypt
- Gunakan password_helper.php untuk buat user baru

---

**Butuh bantuan?** Buka TROUBLESHOOTING.md untuk dokumentasi lengkap.
