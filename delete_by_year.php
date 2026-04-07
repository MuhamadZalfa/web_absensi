<?php
session_start();
include("../config/koneksi.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the year input
    $year = isset($_POST['year']) ? (int)$_POST['year'] : 0;
    
    // Validate year (reasonable range)
    if ($year < 1970 || $year > 2100) {
        $_SESSION['error'] = "Tahun tidak valid.";
        header("Location: rekap.php");
        exit();
    }
    
    // Format the dates for the query (entire year)
    $start_date = $year . '-01-01';
    $end_date = $year . '-12-31';
    
    try {
        // Prepare statement to prevent SQL injection
        $stmt = mysqli_prepare($koneksi, "DELETE FROM absensi WHERE tanggal BETWEEN ? AND ?");
        mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
        
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $_SESSION['success'] = "Berhasil menghapus " . $affected_rows . " data absensi untuk tahun " . $year;
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