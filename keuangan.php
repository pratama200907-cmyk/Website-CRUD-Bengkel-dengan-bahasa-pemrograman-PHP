<?php 
session_start();

// Jika belum login, redirect ke login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php'; 

if(isset($_POST['simpan_keuangan'])){
    $tgl   = $_POST['tanggal'];
    $jenis = $_POST['jenis'];
    $ket   = trim($_POST['ket']);
    $jml   = (int)$_POST['jumlah'];
    
    // Validasi input
    if (empty($tgl) || empty($ket)) {
        $error = "Semua field wajib diisi!";
    } elseif ($jml < 0) {
        $error = "Jumlah tidak boleh negatif!";
    } else {
        // Gunakan prepared statement
        $stmt = $koneksi->prepare("INSERT INTO keuangan (tanggal, jenis, keterangan, jumlah) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $tgl, $jenis, $ket, $jml);
        
        if ($stmt->execute()) {
            echo "<script>window.location='keuangan.php';</script>";
            exit;
        } else {
            $error = "Gagal menyimpan transaksi: " . $stmt->error;
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
    <title>Keuangan Bengkel</title>
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

        .input-card {
            background: linear-gradient(135deg, #1a1f3a 0%, #2a1a3a 100%);
            border: 2px solid #ff0055;
            border-radius: 15px;
            animation: slideInUp 0.6s ease-out;
            box-shadow: 0 0 25px rgba(255, 0, 85, 0.3);
        }

        .input-card h5 {
            color: #00ffff;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.6);
            font-weight: bold;
        }

        .input-card .form-control,
        .input-card .form-select {
            transition: all 0.3s ease;
        }

        .input-card .form-control:focus,
        .input-card .form-select:focus {
            box-shadow: 0 0 15px rgba(0, 255, 255, 0.6);
        }

        .btn-add {
            background: linear-gradient(90deg, #ff0055, #ff3377);
            border: none;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(255, 0, 85, 0.5);
        }

        .btn-add:hover {
            box-shadow: 0 0 30px rgba(255, 0, 85, 0.9);
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
            transition: all 0.3s ease;
            background: #1a1f3a;
        }

        .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.15s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(n+4) { animation-delay: 0.25s; }

        .table tbody tr:hover {
            background: rgba(255, 0, 85, 0.25) !important;
        }

        .table tbody td {
            color: #00ffff;
            background: #1a1f3a;
            border-color: #ff0055;
        }

        .badge-in {
            background: linear-gradient(90deg, #00ff88, #00ffaa) !important;
            color: #000;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.6);
            font-weight: bold;
        }

        .badge-out {
            background: linear-gradient(90deg, #ff0055, #ff3377) !important;
            color: white;
            box-shadow: 0 0 15px rgba(255, 0, 85, 0.6);
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h3 class="page-title">💰 Laporan Keuangan</h3>
        
        <div class="input-card p-4 mb-4">
            <h5>➕ Catat Transaksi Baru</h5>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" style="animation: slideInUp 0.6s ease-out;">❌ <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" class="row g-2 mt-3">
                <div class="col-md-2">
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <select name="jenis" class="form-select">
                        <option value="Pemasukan">➕ Pemasukan</option>
                        <option value="Pengeluaran">➖ Pengeluaran</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <input type="text" name="ket" class="form-control" placeholder="Keterangan (misal: Bayar Listrik)" value="<?php echo isset($_POST['ket']) ? htmlspecialchars($_POST['ket']) : ''; ?>" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="jumlah" class="form-control" placeholder="Nominal" value="<?php echo isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : ''; ?>" min="0" required>
                </div>
                <div class="col-md-1">
                    <button type="submit" name="simpan_keuangan" class="btn btn-add w-100">✓</button>
                </div>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>📅 Tanggal</th>
                        <th>📝 Keterangan</th>
                        <th>📊 Jenis</th>
                        <th>💵 Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM keuangan ORDER BY tanggal DESC");
                    if(mysqli_num_rows($query) > 0) {
                        while($k = mysqli_fetch_array($query)){
                            $warna = ($k['jenis'] == 'Pemasukan') ? 'text-success' : 'text-danger';
                            $tanda = ($k['jenis'] == 'Pemasukan') ? '✓ +' : '✗ -';
                            $badge_class = ($k['jenis'] == 'Pemasukan') ? 'badge-in' : 'badge-out';
                    ?>
                    <tr>
                        <td style="color: #00ffff; font-weight: bold;">📅 <?php echo htmlspecialchars($k['tanggal']); ?></td>
                        <td style="color: #00ffff;"><?php echo htmlspecialchars($k['keterangan']); ?></td>
                        <td><span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($k['jenis']); ?></span></td>
                        <td class="<?php echo $warna; ?> fw-bold" style="font-size: 1.1rem; text-shadow: 0 0 5px currentColor;">
                            <?php echo $tanda; ?> Rp <?php echo number_format((int)$k['jumlah']); ?>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 30px; color: #00ffff;">Belum ada transaksi</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>