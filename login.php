<?php
session_start();

// Jika sudah login, redirect ke index
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

include 'koneksi.php';

$error = "";
$debug_mode = false; // Set ke true untuk enable debug mode

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        // Gunakan prepared statement untuk mencegah SQL injection
        $stmt = $koneksi->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            // Verifikasi password
            $password_verify = password_verify($password, $row['password']);
            
            if ($password_verify) {
                // Login berhasil, set session
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_username'] = $row['username'];
                header("Location: index.php");
                exit();
            } else {
                $error = "Username atau password salah!";
            }
        } else {
            $error = "Username atau password salah!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bengkel CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
            font-family: 'Poppins', sans-serif;
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-box {
            background: rgba(20, 30, 60, 0.8);
            border: 2px solid #ff0055;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 0 40px rgba(255, 0, 85, 0.5),
                        inset 0 0 40px rgba(255, 0, 85, 0.1);
            backdrop-filter: blur(10px);
            animation: slideIn 0.6s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: #ff0055;
            text-shadow: 0 0 15px rgba(255, 0, 85, 0.8);
            letter-spacing: 2px;
        }

        .login-subtitle {
            text-align: center;
            color: #00ffff;
            margin-bottom: 30px;
            font-size: 0.9rem;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #00ffff;
            font-weight: 600;
            margin-bottom: 8px;
            text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
        }

        .form-control {
            background: rgba(15, 20, 40, 0.6);
            border: 2px solid #ff0055;
            color: #fff;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            background: rgba(15, 20, 40, 0.9);
            border-color: #00ffff;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
            color: #fff;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #ff0055 0%, #ff3377 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 20px rgba(255, 0, 85, 0.5);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 30px rgba(255, 0, 85, 0.8),
                        0 10px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #ff3377 0%, #ff0055 100%);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .alert {
            background: rgba(255, 0, 0, 0.2);
            border: 2px solid #ff0055;
            color: #ff6b9d;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 12px 15px;
            font-size: 0.9rem;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }

        .footer-text a {
            color: #00ffff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-text a:hover {
            color: #ff0055;
            text-shadow: 0 0 10px rgba(255, 0, 85, 0.8);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1 class="login-title">BENGKEL</h1>
            <p class="login-subtitle">Sistem Manajemen Bengkel</p>

            <?php if ($error): ?>
                <div class="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Masukkan username" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <div class="footer-text">
                Sistem Manajemen Bengkel 2026
                <br>
                <small style="margin-top: 10px; display: block;">
                    <a href="password_helper.php" style="color: #ff0055; text-decoration: none;">🔧 Password Helper (Setup/Debug)</a>
                </small>
            </div>
        </div>
    </div>
</body>
</html>
