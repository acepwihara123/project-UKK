<?php
include "../../koneksi.php";

$tarif = 50000;

$q = $conn->query("SELECT * FROM pemesanan");

while($p = $q->fetch_assoc()){

    if($p['status'] != 'selesai'){

        $tgl_kembali = strtotime($p['tanggal_kembali']);
        $hari_ini = strtotime(date('Y-m-d'));

        if($hari_ini > $tgl_kembali){

            $hari_telat = floor(($hari_ini - $tgl_kembali) / 86400);
            $total = $hari_telat * $tarif;

            $cek = $conn->query("SELECT * FROM denda WHERE pemesanan_id=".$p['id']);

            if($cek->num_rows > 0){
                $conn->query("UPDATE denda SET hari_terlambat=$hari_telat,total_denda=$total WHERE pemesanan_id=".$p['id']);
            } else {
                $conn->query("INSERT INTO denda(pemesanan_id,hari_terlambat,total_denda) VALUES(".$p['id'].",$hari_telat,$total)");
            }

        }

    }

}