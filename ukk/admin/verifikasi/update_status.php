<?php
include "../../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ambil data
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // validasi sederhana
    if (!$id || !$status) {
        echo "data_kosong";
        exit;
    }

    // update database
    $query = $conn->query("
        UPDATE pemesanan
        SET status = '$status'
        WHERE id = $id
    ");

    if ($query) {
        echo "success";
    } else {
        echo "error";
    }
}