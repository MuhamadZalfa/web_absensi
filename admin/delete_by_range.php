<?php
session_start();
include("../config/koneksi.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the date inputs
    $start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';
    
    // Validate date formats (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
        $_SESSION['error'] = "Format tanggal tidak valid.";
        header("Location: rekap.php");
        exit();
    }
    
    // Validate that start date is not after end date
    if (strtotime($start_date) > strtotime($end_date)) {
        $_SESSION['error'] = "Tanggal awal tidak boleh lebih besar dari tanggal akhir.";
        header("Location: rekap.php");
        exit();
    }
    
    try {
        // Prepare statement to prevent SQL injection
        $stmt = mysqli_prepare($koneksi, "DELETE FROM absensi WHERE tanggal BETWEEN ? AND ?");
        mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
        
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $_SESSION['success'] = "Berhasil menghapus " . $affected_rows . " data absensi dalam rentang tanggal " . $start_date . " hingga " . $end_date;
        } else {
            $_SESSION['error'] = "Gagal menghapus data absensi: " . mysqli_error($koneksi);
        }
        
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        $_SESSION['error'] = "Terjadi kesalahan saat menghapus data: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Metode request tidak valid.";
}

header("Location: rekap.php");
exit();
?>