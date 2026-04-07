<?php
session_start();
include("../config/koneksi.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $month = isset($_POST['month']) ? (int)$_POST['month'] : 0;
    $year = isset($_POST['year']) ? (int)$_POST['year'] : 0;
    
    // Validate month (1-12) and year (reasonable range)
    if ($month < 1 || $month > 12 || $year < 1970 || $year > 2100) {
        $_SESSION['error'] = "Bulan atau tahun tidak valid.";
        header("Location: rekap.php");
        exit();
    }
    
    // Format the dates for the query
    $start_date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
    $end_date = date('Y-m-t', strtotime($start_date)); // Last day of the month
    
    try {
        // Prepare statement to prevent SQL injection
        $stmt = mysqli_prepare($koneksi, "DELETE FROM absensi WHERE tanggal BETWEEN ? AND ?");
        mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
        
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $month_name = date('F', mktime(0, 0, 0, $month, 10)); // Get month name
            $_SESSION['success'] = "Berhasil menghapus " . $affected_rows . " data absensi untuk bulan " . $month_name . " " . $year;
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