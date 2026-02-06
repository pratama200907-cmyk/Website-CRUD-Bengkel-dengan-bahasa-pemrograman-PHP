<?php
/**
 * SETUP DATABASE ADMIN TABLE
 * 
 * Jalankan query berikut di phpMyAdmin untuk membuat tabel admin:
 */

/*
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert data admin (password: admin123 - sudah di-hash dengan password_hash)
INSERT INTO admin (username, password) VALUES 
('admin', '$2y$10$YIjlrBLq8cQFXDpBXmKLkODUpMqLmTdTkZH0DaRH1QV9Z5ZG5VFHK');

-- Username: admin
-- Password: admin123

-- Jika ingin menambah admin baru, gunakan password_hash di PHP:
-- password_hash('password_anda', PASSWORD_BCRYPT)
*/

// Script untuk membuat hash password
// Uncomment untuk mendapatkan hash password baru

/*
$password = 'admin123'; // Ganti dengan password yang diinginkan
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Password hash: " . $hash;
*/

?>
