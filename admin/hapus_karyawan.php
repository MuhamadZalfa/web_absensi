<?php
// Check if admin is logged in
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

// Get employee ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Delete the employee record
    $delete = mysqli_query($koneksi, "DELETE FROM karyawan WHERE id_karyawan = $id");
    
    if ($delete) {
        $message = "Data karyawan berhasil dihapus!";
        $alert_type = "success";
    } else {
        $message = "Gagal menghapus data karyawan: " . mysqli_error($koneksi);
        $alert_type = "error";
    }
} else {
    $message = "ID karyawan tidak valid!";
    $alert_type = "error";
}

// Redirect back to data_karyawan.php with message
header("Location: data-karyawan.php?message=" . urlencode($message) . "&type=" . $alert_type);
exit;
?>