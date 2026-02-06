<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    background: #0a0e27;
    color: #fff;
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
  }

  /* Navbar Styling */
  .navbar-neon {
    background: linear-gradient(135deg, #1a1f3a 0%, #0f1428 100%);
    border-bottom: 3px solid #ff0055;
    box-shadow: 0 0 20px rgba(255, 0, 85, 0.5);
    animation: navGlow 3s ease-in-out infinite;
  }

  @keyframes navGlow {
    0%, 100% { box-shadow: 0 0 20px rgba(255, 0, 85, 0.5); }
    50% { box-shadow: 0 0 40px rgba(255, 0, 85, 0.8); }
  }

  .navbar-brand {
    font-size: 1.8rem;
    font-weight: bold;
    color: #ff0055 !important;
    text-shadow: 0 0 10px rgba(255, 0, 85, 0.8);
    letter-spacing: 2px;
    animation: brandPulse 2s ease-in-out infinite;
  }

  @keyframes brandPulse {
    0%, 100% { 
      text-shadow: 0 0 10px rgba(255, 0, 85, 0.8), 0 0 20px rgba(255, 0, 85, 0.4);
      transform: scale(1);
    }
    50% { 
      text-shadow: 0 0 20px rgba(255, 0, 85, 1), 0 0 40px rgba(255, 0, 85, 0.6);
      transform: scale(1.05);
    }
  }

  .nav-link {
    color: #00ffff !important;
    font-weight: 500;
    margin: 0 10px;
    position: relative;
    transition: all 0.3s ease;
    text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
  }

  .nav-link::before {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #ff0055, #00ffff);
    transition: width 0.3s ease;
  }

  .nav-link:hover {
    color: #ff0055 !important;
    text-shadow: 0 0 15px rgba(255, 0, 85, 1);
    transform: translateY(-2px);
  }

  .nav-link:hover::before {
    width: 100%;
  }

  /* Global Animations */
  @keyframes fadeInDown {
    from {
      opacity: 0;
      transform: translateY(-30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes slideInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes glowPulse {
    0%, 100% { box-shadow: 0 0 10px rgba(255, 0, 85, 0.6); }
    50% { box-shadow: 0 0 25px rgba(255, 0, 85, 1); }
  }

  @keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
  }

  .container {
    animation: fadeInDown 0.6s ease-out;
  }

  h1, h2, h3, h4, h5 {
    animation: fadeInDown 0.8s ease-out;
  }

  /* Card Styling */
  .card {
    background: linear-gradient(135deg, #1a1f3a 0%, #2a1a3a 100%);
    border: 2px solid #ff0055;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(255, 0, 85, 0.4);
    transition: all 0.3s ease;
    animation: slideInUp 0.6s ease-out;
  }

  .card:hover {
    transform: translateY(-10px);
    box-shadow: 0 0 40px rgba(255, 0, 85, 0.8), inset 0 0 20px rgba(255, 0, 85, 0.1);
    border-color: #00ffff;
  }

  .card-header {
    background: linear-gradient(90deg, #ff0055 0%, #ff3377 100%);
    color: #fff;
    font-weight: bold;
    text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    border-radius: 13px 13px 0 0;
  }

  /* Button Styling */
  .btn-primary {
    background: linear-gradient(90deg, #ff0055, #ff3377);
    border: 2px solid #ff0055;
    color: white;
    font-weight: bold;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
  }

  .btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.3s ease;
  }

  .btn-primary:hover {
    background: linear-gradient(90deg, #ff3377, #ff0055);
    box-shadow: 0 0 20px rgba(255, 0, 85, 1);
    transform: scale(1.05);
    border-color: #00ffff;
  }

  .btn-primary:hover::before {
    left: 100%;
  }

  .btn-success {
    background: linear-gradient(90deg, #00ff88, #00ffaa);
    border: 2px solid #00ff88;
    color: #000;
    font-weight: bold;
    transition: all 0.3s ease;
  }

  .btn-success:hover {
    box-shadow: 0 0 20px rgba(0, 255, 136, 0.8);
    transform: scale(1.05);
  }

  .btn-warning {
    background: linear-gradient(90deg, #ffaa00, #ffcc00);
    border: 2px solid #ffaa00;
    color: #000;
    font-weight: bold;
    transition: all 0.3s ease;
  }

  .btn-warning:hover {
    box-shadow: 0 0 20px rgba(255, 170, 0, 0.8);
    transform: scale(1.05);
  }

  .btn-danger {
    background: linear-gradient(90deg, #ff0055, #cc0055);
    border: 2px solid #ff0055;
    color: white;
    font-weight: bold;
    transition: all 0.3s ease;
  }

  .btn-danger:hover {
    box-shadow: 0 0 20px rgba(255, 0, 85, 1);
    transform: scale(1.05);
  }

  .btn-secondary {
    background: linear-gradient(90deg, #444, #666);
    border: 2px solid #00ffff;
    color: #00ffff;
    font-weight: bold;
    transition: all 0.3s ease;
  }

  .btn-secondary:hover {
    box-shadow: 0 0 20px rgba(0, 255, 255, 0.8);
    transform: scale(1.05);
  }

  /* Table Styling */
  .table {
    animation: slideInUp 0.8s ease-out;
  }

  .table-light {
    background: linear-gradient(90deg, #2a1f3a, #1a2a4a);
    color: #00ffff;
    border-color: #ff0055;
  }

  .table-light th {
    background: linear-gradient(90deg, #ff0055, #ff3377);
    color: #000;
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    border-color: #ff0055;
    font-weight: bold;
  }

  .table-bordered {
    border-color: #ff0055;
  }

  .table-bordered td, .table-bordered th {
    border-color: #ff0055;
  }

  .table tbody tr {
    transition: all 0.3s ease;
    animation: slideInUp 0.6s ease-out;
  }

  .table tbody tr:hover {
    background: rgba(255, 0, 85, 0.15) !important;
    box-shadow: inset 0 0 15px rgba(255, 0, 85, 0.2);
    transform: scale(1.02);
  }

  .table tbody td {
    color: #00ffff;
    border-color: #ff0055;
  }

  .table-dark {
    background: linear-gradient(90deg, #1a1f3a, #2a1a3a);
    border-color: #ff0055;
  }

  .table-dark th {
    background: linear-gradient(90deg, #ff0055, #ff3377);
    color: white;
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    border-color: #ff0055;
  }

  .table-striped tbody tr {
    background: rgba(255, 0, 85, 0.05);
  }

  .table-striped tbody tr:nth-child(odd) {
    background: rgba(0, 255, 255, 0.03);
  }

  .table-striped tbody tr:hover {
    background: rgba(255, 0, 85, 0.2) !important;
  }

  /* Form Control Styling */
  .form-control, .form-select {
    background: rgba(0, 255, 255, 0.05);
    color: #00ffff;
    border: 2px solid #ff0055;
    border-radius: 8px;
    transition: all 0.3s ease;
  }

  .form-control::placeholder {
    color: rgba(0, 255, 255, 0.5);
  }

  .form-control:focus, .form-select:focus {
    background: rgba(0, 255, 255, 0.1);
    border-color: #00ffff;
    box-shadow: 0 0 15px rgba(0, 255, 255, 0.5);
    color: #00ffff;
  }

  /* Alert Styling */
  .alert {
    border-radius: 10px;
    animation: slideInUp 0.6s ease-out;
  }

  .alert-info {
    background: linear-gradient(135deg, rgba(0, 255, 255, 0.1), rgba(255, 0, 85, 0.1));
    border: 2px solid #00ffff;
    color: #00ffff;
    text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
  }

  .alert-danger {
    background: linear-gradient(135deg, rgba(255, 0, 85, 0.15), rgba(255, 0, 85, 0.05));
    border: 2px solid #ff0055;
    color: #ff6699;
    text-shadow: 0 0 5px rgba(255, 0, 85, 0.5);
  }

  /* Badge Styling */
  .badge {
    border-radius: 20px;
    font-weight: bold;
    padding: 5px 12px;
    text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
  }

  .bg-danger {
    background: linear-gradient(90deg, #ff0055, #ff3377) !important;
    box-shadow: 0 0 10px rgba(255, 0, 85, 0.6);
  }

  .bg-success {
    background: linear-gradient(90deg, #00ff88, #00ffaa) !important;
    box-shadow: 0 0 10px rgba(0, 255, 136, 0.6);
    color: #000 !important;
  }

  /* Text Effects */
  .text-success { color: #00ff88 !important; }
  .text-danger { color: #ff0055 !important; }
  .text-warning { color: #ffaa00 !important; }

  .fw-bold {
    text-shadow: 0 0 5px currentColor;
  }

  /* Pulse Animation */
  @keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
  }

  .pulse {
    animation: pulse 2s ease-in-out infinite;
  }

  /* Input Group */
  .input-group .btn {
    border: 2px solid #ff0055;
  }

  .input-group .form-control {
    border: 2px solid #ff0055;
  }

  /* Transition for all interactive elements */
  button, a, input, select, textarea {
    transition: all 0.3s ease;
  }

  /* Logout Button Styling */
  .btn-logout {
    background: linear-gradient(90deg, #ff6b6b, #ff0055);
    border: 2px solid #ff0055;
    color: white;
    font-weight: bold;
    padding: 8px 15px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-logout:hover {
    background: linear-gradient(90deg, #ff0055, #cc0055);
    box-shadow: 0 0 20px rgba(255, 0, 85, 1);
    transform: scale(1.05);
    color: white;
  }

  .admin-info {
    color: #00ffff;
    font-weight: 600;
    margin-right: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
    text-shadow: 0 0 5px rgba(0, 255, 255, 0.5);
  }
</style>

<?php
// Hanya start session jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-neon mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">⚡ Bengkel Fadhil ⚡</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">🏠 Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="servis.php">🔧 Data Servis</a></li>
        <li class="nav-item"><a class="nav-link" href="barang.php">📦 Stok Barang</a></li>
        <li class="nav-item"><a class="nav-link" href="keuangan.php">💰 Keuangan</a></li>
        <li class="nav-item">
          <?php if (isset($_SESSION['admin_id'])): ?>
            <span class="admin-info">👤 <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            <a href="logout.php" class="btn-logout">🚪 Logout</a>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>