<?php
session_start();
session_destroy(); // Hancurkan tiket login kasir
header("Location: index.php"); // Tendang balik ke halaman login depan
exit;
?>