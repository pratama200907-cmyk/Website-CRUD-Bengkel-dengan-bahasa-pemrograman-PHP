<?php
/**
 * Password Helper - Untuk generate dan verify password hash
 * Gunakan script ini untuk membuat hash password yang benar
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($action === 'generate') {
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $result = [
                'success' => true,
                'password' => $password,
                'hash' => $hash,
                'message' => 'Hash berhasil di-generate'
            ];
        } else {
            $result = ['success' => false, 'message' => 'Password tidak boleh kosong'];
        }
    } elseif ($action === 'verify') {
        include 'koneksi.php';
        
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        if (empty($username) || empty($password)) {
            $result = ['success' => false, 'message' => 'Username dan password harus diisi'];
        } else {
            $stmt = $koneksi->prepare("SELECT id, username, password FROM admin WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $query_result = $stmt->get_result();
            
            if ($query_result->num_rows === 1) {
                $row = $query_result->fetch_assoc();
                $password_hash = $row['password'];
                
                // Debug info
                $verify = password_verify($password, $password_hash);
                
                $result = [
                    'success' => true,
                    'username' => $username,
                    'password' => $password,
                    'hash_in_db' => $password_hash,
                    'verify_result' => $verify,
                    'verify_result_text' => $verify ? 'COCOK ✓' : 'TIDAK COCOK ✗',
                    'message' => $verify ? 'Password benar!' : 'Password salah!'
                ];
            } else {
                $result = ['success' => false, 'message' => 'Username tidak ditemukan di database'];
            }
            $stmt->close();
        }
    }
}

$generated_result = isset($result) ? $result : null;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Helper</title>
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
            max-width: 900px;
            margin-top: 30px;
        }
        
        .card {
            background: rgba(20, 30, 60, 0.8);
            border: 2px solid #ff0055;
            border-radius: 15px;
            box-shadow: 0 0 40px rgba(255, 0, 85, 0.3);
        }
        
        .card-header {
            background: linear-gradient(135deg, #ff0055 0%, #ff3377 100%);
            color: white;
            border-radius: 13px 13px 0 0;
            font-weight: bold;
            padding: 15px 20px;
        }
        
        .form-label {
            color: #00ffff;
            font-weight: 600;
        }
        
        .form-control {
            background: rgba(15, 20, 40, 0.6);
            border: 2px solid #ff0055;
            color: #fff;
            border-radius: 8px;
        }
        
        .form-control:focus {
            background: rgba(15, 20, 40, 0.9);
            border-color: #00ffff;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
            color: #fff;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff0055 0%, #ff3377 100%);
            border: none;
            font-weight: bold;
            margin-top: 20px;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #ff3377 0%, #ff0055 100%);
            box-shadow: 0 0 20px rgba(255, 0, 85, 0.8);
        }
        
        .alert {
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .alert-success {
            background: rgba(0, 255, 136, 0.15);
            border: 2px solid #00ff88;
            color: #00ff88;
        }
        
        .alert-danger {
            background: rgba(255, 0, 85, 0.15);
            border: 2px solid #ff0055;
            color: #ff6b9d;
        }
        
        .code-block {
            background: rgba(0, 0, 0, 0.4);
            border: 2px solid #00ffff;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            font-family: 'Courier New', monospace;
            color: #00ffff;
            word-break: break-all;
            font-size: 0.85rem;
        }
        
        .info-text {
            color: #00ffff;
            margin-top: 15px;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        
        .tabs {
            margin-bottom: 20px;
        }
        
        .nav-tabs {
            border-bottom: 2px solid #ff0055;
        }
        
        .nav-tabs .nav-link {
            color: #00ffff;
            border: none;
            border-bottom: 3px solid transparent;
        }
        
        .nav-tabs .nav-link.active {
            background: transparent;
            color: #fff;
            border-bottom-color: #ff0055;
        }
        
        .tab-content {
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                🔐 Password Helper - Generate & Verify Hash
            </div>
            
            <div class="card-body">
                <ul class="nav nav-tabs tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#generate">Generate Hash</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#verify">Verify Password</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#setup">Setup Admin</a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <!-- TAB 1: GENERATE HASH -->
                    <div id="generate" class="tab-pane fade show active">
                        <h5 class="mt-4 text-warning">Generate Password Hash</h5>
                        <p class="info-text">Masukkan password yang ingin di-hash. Hash ini akan digunakan untuk INSERT ke database.</p>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="generate">
                            
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" name="password" 
                                       placeholder="Contoh: admin123" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Generate Hash</button>
                        </form>
                        
                        <?php if ($generated_result && isset($generated_result['hash'])): ?>
                            <div class="alert alert-success">
                                <strong>✓ Hash berhasil di-generate!</strong>
                                <p class="mt-2">Password: <strong><?= htmlspecialchars($generated_result['password']) ?></strong></p>
                                <p>Hash yang dapat dicopy:</p>
                                <div class="code-block">
                                    <?= htmlspecialchars($generated_result['hash']) ?>
                                </div>
                                <p class="mt-3 info-text">
                                    <strong>Gunakan SQL Query:</strong><br>
                                    INSERT INTO admin (username, password) VALUES ('admin', '<?= htmlspecialchars($generated_result['hash']) ?>');
                                </p>
                            </div>
                        <?php elseif ($generated_result && !$generated_result['success']): ?>
                            <div class="alert alert-danger">
                                ✗ <?= htmlspecialchars($generated_result['message']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- TAB 2: VERIFY PASSWORD -->
                    <div id="verify" class="tab-pane fade">
                        <h5 class="mt-4 text-warning">Verify Password dari Database</h5>
                        <p class="info-text">Test apakah password cocok dengan yang ada di database.</p>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="verify">
                            
                            <div class="form-group">
                                <label for="verify_username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="verify_username" name="username" 
                                       placeholder="Contoh: admin" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="verify_password" class="form-label">Password</label>
                                <input type="text" class="form-control" id="verify_password" name="password" 
                                       placeholder="Contoh: admin123" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Test Password</button>
                        </form>
                        
                        <?php if ($generated_result && isset($generated_result['verify_result'])): ?>
                            <?php if ($generated_result['verify_result']): ?>
                                <div class="alert alert-success">
                                    <strong>✓ Password COCOK!</strong>
                                    <p class="mt-2">Username: <strong><?= htmlspecialchars($generated_result['username']) ?></strong></p>
                                    <p>Password: <strong><?= htmlspecialchars($generated_result['password']) ?></strong></p>
                                    <p class="mt-2">Hash di database cocok dengan password yang diberikan. Login seharusnya berhasil.</p>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <strong>✗ Password TIDAK COCOK!</strong>
                                    <p class="mt-2">Username: <strong><?= htmlspecialchars($generated_result['username']) ?></strong></p>
                                    <p>Password yang diinput: <strong><?= htmlspecialchars($generated_result['password']) ?></strong></p>
                                    <p>Hash di database: <code style="color: #ff0055;"><?= substr(htmlspecialchars($generated_result['hash_in_db']), 0, 40) ?>...</code></p>
                                    <p class="mt-3 info-text">
                                        <strong>Solusi:</strong><br>
                                        1. Generate hash baru dengan password yang benar<br>
                                        2. Update database dengan SQL: UPDATE admin SET password = 'hash_baru' WHERE username = '<?= htmlspecialchars($generated_result['username']) ?>';
                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php elseif ($generated_result && !$generated_result['success']): ?>
                            <div class="alert alert-danger">
                                ✗ <?= htmlspecialchars($generated_result['message']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- TAB 3: SETUP ADMIN -->
                    <div id="setup" class="tab-pane fade">
                        <h5 class="mt-4 text-warning">Setup Admin Account</h5>
                        <p class="info-text">Petunjuk lengkap untuk setup admin account baru di database.</p>
                        
                        <div class="card border border-warning mt-4">
                            <div class="card-body" style="background: rgba(255, 170, 0, 0.05);">
                                <h6 class="text-warning">Langkah 1: Generate Hash Password</h6>
                                <ol class="info-text">
                                    <li>Buka tab <strong>"Generate Hash"</strong> di atas</li>
                                    <li>Masukkan password yang diinginkan (contoh: admin123)</li>
                                    <li>Klik tombol "Generate Hash"</li>
                                    <li>Copy hash yang dihasilkan</li>
                                </ol>
                            </div>
                        </div>
                        
                        <div class="card border border-success mt-3">
                            <div class="card-body" style="background: rgba(0, 255, 136, 0.05);">
                                <h6 class="text-success">Langkah 2: Insert ke Database</h6>
                                <ol class="info-text">
                                    <li>Buka phpMyAdmin</li>
                                    <li>Pilih database <strong>bengkel_db</strong></li>
                                    <li>Buka tab <strong>"SQL"</strong></li>
                                    <li>Jalankan salah satu query berikut:
                                        <div class="code-block mt-2">
                                            <!-- Create table -->
                                            CREATE TABLE IF NOT EXISTS admin (<br>
                                            &nbsp;&nbsp;id INT PRIMARY KEY AUTO_INCREMENT,<br>
                                            &nbsp;&nbsp;username VARCHAR(100) NOT NULL UNIQUE,<br>
                                            &nbsp;&nbsp;password VARCHAR(255) NOT NULL,<br>
                                            &nbsp;&nbsp;created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP<br>
                                            );
                                        </div>
                                        <div class="code-block mt-2">
                                            <!-- Insert data (ganti hash dengan yang sudah di-generate) -->
                                            INSERT INTO admin (username, password) VALUES ('admin', '$2y$10$...hash_yang_sudah_dikopi...');
                                        </div>
                                        <div class="code-block mt-2">
                                            <!-- Update jika sudah ada -->
                                            UPDATE admin SET password = '$2y$10$...hash_yang_sudah_dikopi...' WHERE username = 'admin';
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        
                        <div class="card border border-info mt-3">
                            <div class="card-body" style="background: rgba(0, 255, 255, 0.05);">
                                <h6 class="text-info">Langkah 3: Test Login</h6>
                                <ol class="info-text">
                                    <li>Buka tab <strong>"Verify Password"</strong> di atas untuk test</li>
                                    <li>Atau coba langsung di halaman login (<a href="login.php" style="color: #ff0055;">login.php</a>)</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert alert-info mt-4">
            <strong>ℹ️ Catatan Penting:</strong>
            <ul class="mt-2 mb-0 info-text">
                <li>Tool ini hanya untuk development dan debugging</li>
                <li>Jangan bagikan link ini ke orang lain</li>
                <li>Hapus file ini setelah selesai setup (atau set permission untuk restricted access)</li>
                <li>Password di-hash menggunakan algoritma BCrypt yang aman</li>
            </ul>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
