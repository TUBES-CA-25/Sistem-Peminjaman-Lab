<?php
echo "✅ PHP BERJALAN!<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'kosong') . "<br>";
echo "Token: " . ($_GET['token'] ?? 'tidak ada') . "<br>";
?>