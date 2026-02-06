<?php
session_start();

// Jika belum login, redirect ke login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';
$id = (int)$_GET['id'];

// Fetch data dengan prepared statement
$stmt = $koneksi->prepare("SELECT * FROM servis WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $nama   = trim($_POST['nama']);
    $nopol  = trim($_POST['nopol']);
    $servis = trim($_POST['servis']);
    $biaya  = (int)$_POST['biaya'];
    $tgl    = $_POST['tanggal'];
    
    // Validasi input
    if (empty($nama) || empty($nopol) || empty($servis) || empty($tgl)) {
        $error = "Semua field wajib diisi!";
    } elseif ($biaya < 0) {
        $error = "Biaya tidak boleh negatif!";
    } else {
        // Gunakan prepared statement untuk keamanan
        $stmt = $koneksi->prepare("UPDATE servis SET nama_pelanggan=?, nopol=?, jenis_servis=?, biaya=?, tanggal=? WHERE id=?");
        $stmt->bind_param("ssssii", $nama, $nopol, $servis, $biaya, $tgl, $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diupdate!'); window.location='index.php';</script>";
            exit;
        } else {
            $error = "Gagal update data: " . $stmt->error;
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
    <title>Edit Data Servis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shine {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .form-container {
            animation: fadeIn 0.5s ease-out;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-card {
            background: linear-gradient(135deg, #1a1f3a 0%, #2a1a3a 100%);
            border: 3px solid #ffaa00;
            border-radius: 20px;
            box-shadow: 0 0 40px rgba(255, 170, 0, 0.5);
            width: 100%;
            max-width: 600px;
            animation: slideUp 0.6s ease-out;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shine 3s infinite;
        }

        .card-header {
            background: linear-gradient(90deg, #ffaa00, #ffcc00);
            color: #000;
            font-size: 1.5rem;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            border: none;
        }

        .card-body {
            position: relative;
            z-index: 1;
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            animation: slideUp 0.6s ease-out;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.15s; }
        .form-group:nth-child(3) { animation-delay: 0.2s; }
        .form-group:nth-child(4) { animation-delay: 0.25s; }
        .form-group:nth-child(5) { animation-delay: 0.3s; }

        label {
            color: #00ffff;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
            margin-bottom: 8px;
            display: block;
        }

        .form-control, .form-select {
            transition: all 0.3s ease;
            padding: 12px 15px;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.8), inset 0 0 10px rgba(0, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            animation: slideUp 0.6s ease-out 0.35s both;
        }

        .btn-update {
            background: linear-gradient(90deg, #ffaa00, #ffcc00);
            border: none;
            color: #000;
            font-weight: bold;
            padding: 12px 30px;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 0 20px rgba(255, 170, 0, 0.5);
            flex: 1;
        }

        .btn-update:hover {
            background: linear-gradient(90deg, #ffcc00, #ffaa00);
            box-shadow: 0 0 40px rgba(255, 170, 0, 0.9);
            transform: scale(1.05);
        }

        .btn-kembali {
            background: linear-gradient(90deg, #444, #666);
            border: 2px solid #00ffff;
            color: #00ffff;
            font-weight: bold;
            padding: 10px 25px;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-kembali:hover {
            background: linear-gradient(90deg, #555, #777);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.8);
            transform: scale(1.05);
            color: #00ffff;
        }

        .alert-danger {
            animation: slideUp 0.6s ease-out;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="form-container">
        <div class="form-card">
            <div class="card-header">✏️ Edit Data Servis</div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">❌ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>👤 Nama Pelanggan</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama_pelanggan']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>🔢 No. Polisi</label>
                        <input type="text" name="nopol" class="form-control" value="<?php echo htmlspecialchars($data['nopol']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>🔧 Jenis Servis</label>
                        <textarea name="servis" class="form-control" required><?php echo htmlspecialchars($data['jenis_servis']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>💵 Biaya (Rp)</label>
                        <input type="number" name="biaya" class="form-control" value="<?php echo (int)$data['biaya']; ?>" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>📅 Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?php echo $data['tanggal']; ?>" required>
                    </div>
                    <div class="btn-group">
                        <button type="submit" name="update" class="btn-update">✓ Update</button>
                        <a href="index.php" class="btn-kembali">← Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>