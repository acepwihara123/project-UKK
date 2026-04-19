<?php
include "../../koneksi.php";

$id = intval($_GET['id']);

// hapus denda (atau set 0)
$conn->query("DELETE FROM denda WHERE pemesanan_id = $id");

// update status jadi selesai
$conn->query("
    UPDATE pemesanan 
    SET status='selesai' 
    WHERE id=$id
");

echo "success";