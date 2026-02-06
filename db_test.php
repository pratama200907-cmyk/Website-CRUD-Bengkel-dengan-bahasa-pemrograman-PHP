<?php
/**
 * Database Test Script
 * Untuk debug koneksi database dan data admin
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        
        body {
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            font-family: 'Poppins', sans-serif;
            color: #fff;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin-top: 30px;
        }
        
        .card {
            background: rgba(20, 30, 60, 0.8);
            border: 2px solid #ff0055;
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(255, 0, 85, 0.3);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #ff0055 0%, #ff3377 100%);
            color: white;
            border-radius: 13px 13px 0 0;
            font-weight: bold;
            padding: 15px 20px;
        }
        
        .code-block {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid #00ffff;
            border-radius: 8px;
            padding: 12px;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
            color: #00ffff;
            word-break: break-all;
            font-size: 0.85rem;
        }
        
        .badge-success {
            background: linear-gradient(90deg, #00ff88, #00ffaa);
            color: #000;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .badge-danger {
            background: linear-gradient(90deg, #ff6b6b, #ff0055);
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .info-text {
            color: #00ffff;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-top: 10px;
        }
        
        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ff0055;
        }
        
        table th {
            background: linear-gradient(90deg, #ff0055, #ff3377);
            color: white;
            font-weight: bold;
        }
        
        table tbody tr:hover {
            background: rgba(255, 0, 85, 0.1);
        }
        
        .section-title {
            color: #ff0055;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="color: #ff0055; text-align: center; margin-bottom: 30px;">🔧 Database Test & Diagnosis</h1>
        
        <!-- CONNECTION TEST -->
        <div class="card">
            <div class="card-header">🔌 Test Koneksi Database</div>
            <div class="card-body">
                <?php if ($koneksi): ?>
                    <div style="color: #00ff88; font-weight: bold;">✓ Koneksi Database BERHASIL</div>
                    <table>
                        <tr>
                            <td style="width: 40%;">Host:</td>
                            <td><?= htmlspecialchars($koneksi->get_connection_stats()['pid'] ? 'Connected' : 'Unknown') ?></td>
                        </tr>
                        <tr>
                            <td>Database:</td>
                            <td>bengkel_db</td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td><span class="badge-success">CONNECTED</span></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <div style="color: #ff6b9d; font-weight: bold;">✗ Koneksi Database GAGAL</div>
                    <div class="code-block">Error: <?= mysqli_connect_error() ?></div>
                    <div class="info-text">
                        <strong>Solusi:</strong><br>
                        1. Pastikan MySQL/MariaDB XAMPP sudah running<br>
                        2. Cek file koneksi.php di folder project<br>
                        3. Verifikasi nama database, user, dan password
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- TABLE CHECK -->
        <div class="card">
            <div class="card-header">📋 Cek Tabel Admin</div>
            <div class="card-body">
                <?php
                $table_exists = $koneksi->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'bengkel_db' AND table_name = 'admin'");
                
                if ($table_exists && $table_exists->num_rows > 0):
                ?>
                    <div style="color: #00ff88; font-weight: bold;">✓ Tabel Admin DITEMUKAN</div>
                    
                    <?php
                    $result = $koneksi->query("SELECT id, username, created_at FROM admin");
                    $row_count = $result->num_rows;
                    ?>
                    
                    <table>
                        <tr>
                            <td style="width: 40%;">Total Records:</td>
                            <td style="color: #00ffff; font-weight: bold;"><?= $row_count ?> record(s)</td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td>
                                <?php if ($row_count > 0): ?>
                                    <span class="badge-success">DATA ADA ✓</span>
                                <?php else: ?>
                                    <span class="badge-danger">TABEL KOSONG ✗</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                    
                    <?php if ($row_count > 0): ?>
                        <div style="margin-top: 20px;">
                            <strong style="color: #00ffff;">Data Admin di Database:</strong>
                            <table style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $koneksi->query("SELECT id, username, created_at FROM admin");
                                    while ($row = $result->fetch_assoc()):
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['id']) ?></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                                            <td>
                                                <a href="password_helper.php" style="color: #ff0055; text-decoration: none;">
                                                    Verify Password →
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="info-text" style="margin-top: 20px; background: rgba(0, 255, 255, 0.05); padding: 10px; border-radius: 5px;">
                            <strong>Next Step:</strong><br>
                            1. Buka <a href="password_helper.php" style="color: #ff0055;">password_helper.php</a><br>
                            2. Tab "Verify Password"<br>
                            3. Test dengan username dan password yang ingin dicoba<br>
                            4. Ikuti instruksi untuk reset password jika diperlukan
                        </div>
                    <?php else: ?>
                        <div class="info-text" style="margin-top: 20px; background: rgba(255, 0, 85, 0.1); padding: 10px; border-radius: 5px;">
                            <strong>⚠️ Tabel Admin Kosong!</strong><br>
                            Belum ada data admin di database. Ikuti langkah:<br>
                            1. Buka <a href="password_helper.php" style="color: #ff0055;">password_helper.php</a><br>
                            2. Tab "Setup Admin"<br>
                            3. Ikuti semua langkah untuk insert data admin
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="color: #ff6b9d; font-weight: bold;">✗ Tabel Admin TIDAK DITEMUKAN</div>
                    <div class="info-text" style="margin-top: 10px;">
                        <strong>Solusi:</strong><br>
                        1. Buka phpMyAdmin<br>
                        2. Pilih database bengkel_db<br>
                        3. Tab "SQL"<br>
                        4. Jalankan query berikut:<br>
                    </div>
                    <div class="code-block">
                        CREATE TABLE admin (<br>
                        &nbsp;&nbsp;id INT PRIMARY KEY AUTO_INCREMENT,<br>
                        &nbsp;&nbsp;username VARCHAR(100) NOT NULL UNIQUE,<br>
                        &nbsp;&nbsp;password VARCHAR(255) NOT NULL,<br>
                        &nbsp;&nbsp;created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP<br>
                        );
                    </div>
                    <div class="info-text" style="margin-top: 10px;">
                        5. Klik Execute<br>
                        6. Refresh halaman ini
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- QUICK LINKS -->
        <div class="card">
            <div class="card-header">🔗 Quick Links</div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <a href="login.php" class="btn btn-primary w-100">🔐 Go to Login</a>
                    <a href="password_helper.php" class="btn btn-primary w-100">🔧 Password Helper</a>
                    <a href="javascript:location.reload()" class="btn btn-primary w-100">🔄 Refresh</a>
                    <a href="http://localhost/phpmyadmin" class="btn btn-primary w-100" target="_blank">💾 phpMyAdmin</a>
                </div>
            </div>
        </div>
        
        <!-- INFO -->
        <div class="alert alert-info" style="background: rgba(0, 255, 255, 0.1); border: 2px solid #00ffff; border-radius: 8px; color: #00ffff;">
            <strong>ℹ️ Informasi:</strong><br>
            Halaman ini hanya untuk debugging. Gunakan untuk mengecek status database sebelum melakukan login.
        </div>
    </div>
</body>
</html>
