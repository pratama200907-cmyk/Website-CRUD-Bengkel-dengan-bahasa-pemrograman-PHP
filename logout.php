<?php
session_start();

// Hapus semua session
session_destroy();

// Redirect ke login page
header("Location: login.php");
exit();
?>
