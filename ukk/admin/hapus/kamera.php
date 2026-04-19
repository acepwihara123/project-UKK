<?php
include "../../koneksi.php";

$id = $_GET['id'];
$conn->query("DELETE FROM kamera WHERE id=$id");

header("Location:../list/kamera.php");
?>
