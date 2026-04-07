<?php
include "config/koneksi.php";
session_start();

if (isset($_POST['daftar'])) {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah username sudah digunakan
    $cek = mysqli_query($koneksi, "SELECT * FROM karyawan WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.location='register.php';</script>";
        exit;
    }

    // Insert data baru
    mysqli_query($koneksi, "INSERT INTO karyawan (nama, username, password, level)
                            VALUES ('$nama', '$username', '$password', 'karyawan')");

    echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='login.php';</script>";
}
?>
