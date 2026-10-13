
<?php
session_start();

// Hapus semua data sesi login
$_SESSION = array();
session_destroy();

// Kembali ke halaman login
header("Location: login.php");
exit;
?>
