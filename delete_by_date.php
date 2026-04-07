<?php
session_start();
include("../config/koneksi.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the date input
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    
    // Validate date format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $_SESSION['error'] = "Format tanggal tidak valid.";
        header("Location: rekap.php");
        exit();
    }
    
    try {
        // Prepare statement to prevent SQL injection
        $stmt = mysqli_prepare($koneksi, "DELETE FROM absensi WHERE tanggal = ?");
        mysqli_stmt_bind_param($stmt, "s", $date);
        
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $_SESSION['success'] = "Berhasil menghapus " . $affected_rows . " data absensi untuk tanggal " . $date;
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