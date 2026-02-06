<?php
session_start();

// Jika belum login, redirect ke login page
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Gunakan prepared statement untuk keamanan
    $stmt = $koneksi->prepare("DELETE FROM servis WHERE id=?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . addslashes($stmt->error) . "'); window.location='index.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>window.location='index.php';</script>";
}
?>