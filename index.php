<?php
session_start();

// Jika belum login, redirect ke login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Hitung Total Servis dengan prepared statement
$stmt1 = $koneksi->prepare("SELECT COUNT(*) as total FROM servis");
$stmt1->execute();
$d1 = $stmt1->get_result()->fetch_assoc();
$stmt1->close();

// Hitung Total Barang dengan prepared statement
$stmt2 = $koneksi->prepare("SELECT COUNT(*) as total FROM barang");
$stmt2->execute();
$d2 = $stmt2->get_result()->fetch_assoc();
$stmt2->close();

// Hitung Saldo (Pemasukan - Pengeluaran)
$jenis_in = 'Pemasukan';
$stmt3 = $koneksi->prepare("SELECT COALESCE(SUM(jumlah), 0) as total FROM keuangan WHERE jenis=?");
$stmt3->bind_param("s", $jenis_in);
$stmt3->execute();
$in = $stmt3->get_result()->fetch_assoc()['total'];
$stmt3->close();

$jenis_out = 'Pengeluaran';
$stmt4 = $koneksi->prepare("SELECT COALESCE(SUM(jumlah), 0) as total FROM keuangan WHERE jenis=?");
$stmt4->bind_param("s", $jenis_out);
$stmt4->execute();
$out = $stmt4->get_result()->fetch_assoc()['total'];
$stmt4->close();

$saldo = $in - $out;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .stat-card {
            animation: float 3s ease-in-out infinite, slideInUp 0.6s ease-out;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: spin 4s infinite;
        }

        .stat-card.stat-servis {
            background: linear-gradient(-45deg, rgba(0, 150, 255, 0.2), rgba(0, 255, 255, 0.2));
            border: 2px solid #00ffff;
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.5);
        }

        .stat-card.stat-servis:hover {
            box-shadow: 0 0 50px rgba(0, 255, 255, 0.9), inset 0 0 20px rgba(0, 255, 255, 0.2);
            transform: scale(1.08);
        }

        .stat-card.stat-barang {
            background: linear-gradient(-45deg, rgba(255, 170, 0, 0.2), rgba(255, 200, 0, 0.2));
            border: 2px solid #ffaa00;
            box-shadow: 0 0 25px rgba(255, 170, 0, 0.5);
        }

        .stat-card.stat-barang:hover {
            box-shadow: 0 0 50px rgba(255, 170, 0, 0.9), inset 0 0 20px rgba(255, 170, 0, 0.2);
            transform: scale(1.08);
        }

        .stat-card.stat-saldo {
            background: linear-gradient(-45deg, rgba(0, 255, 136, 0.2), rgba(0, 255, 180, 0.2));
            border: 2px solid #00ff88;
            box-shadow: 0 0 25px rgba(0, 255, 136, 0.5);
        }

        .stat-card.stat-saldo:hover {
            box-shadow: 0 0 50px rgba(0, 255, 136, 0.9), inset 0 0 20px rgba(0, 255, 136, 0.2);
            transform: scale(1.08);
        }

        .stat-icon {
            font-size: 3rem;
            animation: bounce 2s ease-in-out infinite;
            filter: drop-shadow(0 0 10px currentColor);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 0 0 15px currentColor;
            animation: pulse 2s ease-in-out infinite;
        }

        .stat-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .stat-link {
            display: inline-block;
            margin-top: 15px;
            transition: all 0.3s ease;
            font-weight: bold;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
        }

        .stat-card.stat-servis .stat-link:hover {
            background: rgba(0, 255, 255, 0.3);
            transform: translateX(5px);
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.8);
        }

        .stat-card.stat-barang .stat-link:hover {
            background: rgba(255, 170, 0, 0.3);
            transform: translateX(5px);
            text-shadow: 0 0 10px rgba(255, 170, 0, 0.8);
        }

        .stat-card.stat-saldo .stat-link:hover {
            background: rgba(0, 255, 136, 0.3);
            transform: translateX(5px);
            text-shadow: 0 0 10px rgba(0, 255, 136, 0.8);
        }

        .welcome-alert {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.15), rgba(255, 0, 85, 0.15));
            border: 2px solid #00ffff;
            border-radius: 12px;
            color: #00ffff;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.5);
            font-weight: bold;
            letter-spacing: 1px;
            animation: slideInUp 0.6s ease-out, pulse 3s ease-in-out infinite;
        }

        .card-body {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="welcome-alert p-3 mb-4">
            Selamat Datang di Sistem Manajemen Bengkel
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card stat-card stat-servis">
                    <div class="card-body text-center">
                        <div class="stat-icon">🚗&🏍️</div>
                        <div class="stat-label mt-3">Total Servis Selesai</div>
                        <div class="stat-number"><?php echo $d1['total']; ?></div>
                        <div style="font-size: 0.85rem; color: #00ffff;">Kendaraan</div>
                        <a href="servis.php" class="stat-link" style="color: #00ffff;">Lihat Detail →</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card stat-card stat-barang">
                    <div class="card-body text-center">
                        <div class="stat-icon">📦</div>
                        <div class="stat-label mt-3">Total Barang</div>
                        <div class="stat-number"><?php echo $d2['total']; ?></div>
                        <div style="font-size: 0.85rem; color: #ffaa00;">Item</div>
                        <a href="barang.php" class="stat-link" style="color: #ffaa00;">Cek Gudang →</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card stat-card stat-saldo">
                    <div class="card-body text-center">
                        <div class="stat-icon">💰</div>
                        <div class="stat-label mt-3">Saldo Kas</div>
                        <div class="stat-number" style="color: #00ff88; font-size: 1.8rem;">Rp <?php echo number_format($saldo); ?></div>
                        <div style="font-size: 0.75rem; color: #00ff88; margin-top: 10px;">
                            <div>✓ Masuk: Rp <?php echo number_format($in); ?></div>
                            <div>✗ Keluar: Rp <?php echo number_format($out); ?></div>
                        </div>
                        <a href="keuangan.php" class="stat-link" style="color: #00ff88;">Detail Keuangan →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>