<?php
include "../../koneksi.php";

$id = intval($_GET['id']);

$query = $conn->query("SELECT * FROM pemesanan WHERE id=$id");
$data = $query->fetch_assoc();

echo json_encode([
    "status"=>"success",
    "data"=>$data
]);