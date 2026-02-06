<?php 
session_start();

// Jika belum login, redirect ke login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Servis</title>
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

        .page-header {
            animation: slideDown 0.6s ease-out;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title {
            color: #ff0055;
            font-weight: bold;
            text-shadow: 0 0 15px rgba(255, 0, 85, 0.8);
            font-size: 2rem;
            margin-bottom: 0;
        }

        .btn-add {
            background: linear-gradient(90deg, #00ff88, #00ffaa);
            color: #000;
            border: none;
            font-weight: bold;
            padding: 10px 25px;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 255, 136, 0.5);
            animation: slideDown 0.6s ease-out 0.1s both;
        }

        .btn-add:hover {
            box-shadow: 0 0 30px rgba(0, 255, 136, 0.9);
            transform: scale(1.08) translateY(-3px);
            color: #000;
        }

        .search-form {
            animation: slideDown 0.6s ease-out 0.2s both;
            margin-bottom: 20px;
        }

        .search-form .input-group {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
        }

        .search-form .form-control {
            background: rgba(0, 255, 255, 0.08);
            border: 2px solid #ff0055;
            color: #00ffff;
            padding: 12px 15px;
        }

        .search-form .form-control::placeholder {
            color: rgba(0, 255, 255, 0.6);
        }

        .search-form .form-control:focus {
            background: rgba(0, 255, 255, 0.12);
            border-color: #00ffff;
            box-shadow: inset 0 0 10px rgba(0, 255, 255, 0.2);
            color: #00ffff;
        }

        .search-form .btn-outline-secondary {
            background: linear-gradient(90deg, #ff0055, #ff3377);
            color: white;
            border: 2px solid #ff0055;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .search-form .btn-outline-secondary:hover {
            box-shadow: 0 0 20px rgba(255, 0, 85, 0.8);
            transform: scale(1.05);
        }

        .table-wrapper {
            animation: slideInUp 0.6s ease-out 0.3s both;
            overflow: hidden;
            border-radius: 12px;
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
        .table tbody tr:nth-child(4) { animation-delay: 0.25s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.3s; }
        .table tbody tr:nth-child(n+6) { animation-delay: 0.35s; }

        .table tbody td {
            color: #00ffff;
            border-color: #ff0055;
            background: #1a1f3a;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            padding: 5px 12px;
            font-size: 0.85rem;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #00ffff;
            font-size: 1.1rem;
            animation: slideInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="page-header mb-4">
            <h3 class="page-title">🔧 Data Servis Kendaraan</h3>
            <a href="tambah.php" class="btn btn-add">+ Servis Baru</a>
        </div>

        <form action="" method="GET" class="search-form">
            <div class="input-group">
                <input type="text" name="cari" class="form-control" placeholder="🔍 Cari nama pelanggan atau nopol..." value="<?php echo isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : ''; ?>">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
        </form>

        <div class="table-wrapper">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Pelanggan</th>
                        <th>Nopol</th>
                        <th>Servis</th>
                        <th>Biaya</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Logika Pencarian
                    if(isset($_GET['cari'])){
                        $cari = $_GET['cari'];
                        $query = mysqli_query($koneksi, "SELECT * FROM servis WHERE nama_pelanggan LIKE '%$cari%' OR nopol LIKE '%$cari%'");
                    } else {
                        $query = mysqli_query($koneksi, "SELECT * FROM servis ORDER BY id DESC");
                    }
                    
                    if(mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($data['nama_pelanggan']); ?></td>
                        <td><strong><?php echo htmlspecialchars($data['nopol']); ?></strong></td>
                        <td><?php echo htmlspecialchars($data['jenis_servis']); ?></td>
                        <td style="color: #00ff88; font-weight: bold;">Rp <?php echo number_format((int)$data['biaya']); ?></td>
                        <td><?php echo htmlspecialchars($data['tanggal']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?php echo (int)$data['id']; ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
                                <a href="hapus.php?id=<?php echo (int)$data['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">🗑️ Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                    ?>
                    <tr>
                        <td colspan="7" class="no-data">Tidak ada data servis. Mulai tambah servis baru!</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>