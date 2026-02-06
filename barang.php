<?php 
session_start();

// Jika belum login, redirect ke login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php'; 

// Proses Tambah Barang
if(isset($_POST['tambah_barang'])){
    $nama = trim($_POST['nama']);
    $stok = (int)$_POST['stok'];
    $beli = (int)$_POST['beli'];
    $jual = (int)$_POST['jual'];
    
    // Validasi input
    if (empty($nama)) {
        $error = "Nama barang wajib diisi!";
    } elseif ($stok < 0 || $beli < 0 || $jual < 0) {
        $error = "Nilai tidak boleh negatif!";
    } else {
        // Gunakan prepared statement
        $stmt = $koneksi->prepare("INSERT INTO barang (nama_barang, stok, harga_beli, harga_jual) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            // Pastikan urutan: nama_barang (string), stok (int), harga_beli (int), harga_jual (int)
            $stmt->bind_param("siii", $nama, $stok, $beli, $jual);
            
            if ($stmt->execute()) {
                echo "<script>alert('Barang ditambahkan'); window.location='barang.php';</script>";
                exit;
            } else {
                $error = "Gagal menambahkan barang: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Gagal menyiapkan query: " . $koneksi->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            color: #ff0055;
            font-weight: bold;
            text-shadow: 0 0 15px rgba(255, 0, 85, 0.8);
            font-size: 2rem;
            margin-bottom: 20px;
            animation: slideDown 0.6s ease-out;
        }

        .form-card {
            background: linear-gradient(135deg, #1a1f3a 0%, #2a1a3a 100%);
            border: 2px solid #00ffff;
            border-radius: 15px;
            animation: slideInUp 0.6s ease-out;
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.3);
        }

        .form-card .form-control {
            transition: all 0.3s ease;
        }

        .form-card .form-control:focus {
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.6);
        }

        .btn-submit {
            background: linear-gradient(90deg, #00ff88, #00ffaa);
            border: none;
            color: #000;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.5);
        }

        .btn-submit:hover {
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.9);
            transform: scale(1.05);
        }

        .table-wrapper {
            animation: slideInUp 0.8s ease-out 0.2s both;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(255, 0, 85, 0.3);
        }

        @keyframes rowFade {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .table tbody {
            background: #1a1f3a;
        }

        .table tbody tr {
            animation: rowFade 0.5s ease-out;
            animation-fill-mode: both;
            background: #1a1f3a;
        }

        .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.15s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(n+4) { animation-delay: 0.25s; }

        .table tbody td {
            background: #1a1f3a;
            border-color: #ff0055;
        }

        .badge-warning {
            background: linear-gradient(90deg, #ff6b6b, #ff0055) !important;
            color: white;
            box-shadow: 0 0 15px rgba(255, 0, 85, 0.6);
        }

        .stock-status {
            font-weight: bold;
        }

        .profit-cell {
            color: #00ff88 !important;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(0, 255, 136, 0.5);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h3 class="page-title">📦 Stok Sparepart & Barang</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" style="animation: slideInUp 0.6s ease-out;">❌ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="form-card p-4 mb-4">
            <h5 style="color: #00ffff; text-shadow: 0 0 10px rgba(0, 255, 255, 0.6); margin-bottom: 20px;">➕ Tambah Barang Baru</h5>
            <form method="POST" class="row g-3">
                <div class="col-md-4"><input type="text" name="nama" class="form-control" placeholder="Nama Barang" value="<?php echo isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : ''; ?>" required></div>
                <div class="col-md-2"><input type="number" name="stok" class="form-control" placeholder="Stok" value="<?php echo isset($_POST['stok']) ? (int)$_POST['stok'] : ''; ?>" min="0" required></div>
                <div class="col-md-2"><input type="number" name="beli" class="form-control" placeholder="Harga Beli" value="<?php echo isset($_POST['beli']) ? (int)$_POST['beli'] : ''; ?>" min="0" required></div>
                <div class="col-md-2"><input type="number" name="jual" class="form-control" placeholder="Harga Jual" value="<?php echo isset($_POST['jual']) ? (int)$_POST['jual'] : ''; ?>" min="0" required></div>
                <div class="col-md-2"><button type="submit" name="tambah_barang" class="btn btn-submit w-100">💾 Simpan</button></div>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Profit/Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY stok ASC");
                    if(mysqli_num_rows($query) > 0) {
                        while($b = mysqli_fetch_array($query)){
                            $profit = $b['harga_jual'] - $b['harga_beli'];
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($b['nama_barang']); ?></td>
                        <td class="stock-status">
                            <?php 
                            if($b['stok'] <= 5) { 
                                echo "<span class='badge badge-warning'>⚠️ ".(int)$b['stok']." (Hampir Habis)</span>"; 
                            } else { 
                                echo "<span style='color: #00ff88;'>".(int)$b['stok']."</span>";
                            }
                            ?>
                        </td>
                        <td style="color: #ffaa00; font-weight: bold;">Rp <?php echo number_format((int)$b['harga_beli']); ?></td>
                        <td style="color: #00ffff; font-weight: bold;">Rp <?php echo number_format((int)$b['harga_jual']); ?></td>
                        <td class="profit-cell">Rp <?php echo number_format($profit); ?></td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px; color: #00ffff;">Belum ada barang. Mulai tambah barang!</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>