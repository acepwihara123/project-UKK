<?php
session_start();
include "../../koneksi.php";

if(isset($_POST['id'])){

    $id = intval($_POST['id']);

    $stmt = mysqli_prepare($conn,
        "DELETE FROM pemesanan WHERE id=?"
    );

    mysqli_stmt_bind_param($stmt,"i",$id);
    mysqli_stmt_execute($stmt);
}

header("Location: pemesanan.php");
exit;