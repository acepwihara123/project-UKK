<?php
function hitungDendaDanUpdate($conn) {
    $today = date('Y-m-d');

    $query = $conn->query("SELECT * FROM pemesanan WHERE status != 'selesai'");

    while ($data = $query->fetch_assoc()) {
        $id = $data['id'];
        $tanggal_kembali = $data['tanggal_kembali'];

        if ($today > $tanggal_kembali) {
            $selisih = (strtotime($today) - strtotime($tanggal_kembali)) / (60 * 60 * 24);
            $hari = floor($selisih);
            $denda = $hari * 50000;

            // update ke database
            $conn->query("
                UPDATE pemesanan 
                SET denda = $denda, 
                    terlambat = $hari,
                    status = 'terlambat'
                WHERE id = $id
            ");
        }
    }
}
?>