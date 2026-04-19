<?php
include "../koneksi.php";

$id = intval($_GET['id']);

// hapus denda
$conn->query("DELETE FROM denda WHERE pemesanan_id = $id");

// update status
$conn->query("UPDATE pemesanan SET status='selesai' WHERE id=$id");

echo "ok";