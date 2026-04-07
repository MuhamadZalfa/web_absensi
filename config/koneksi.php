<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_absensi");

if(!$koneksi){
    echo "Koneksi gagal: " . mysqli_connect_error();
}
?>
