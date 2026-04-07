<?php
session_start();
include("../config/koneksi.php");

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for double confirmation
    $confirm_text = isset($_POST['confirm_text']) ? trim($_POST['confirm_text']) : '';
    
    if ($confirm_text !== 'HAPUS') {
        $_SESSION['error'] = "Konfirmasi tidak valid. Harap ketik 'HAPUS' untuk mengkonfirmasi penghapusan semua data.";
        header("Location: rekap.php");
        exit();
    }
    
    try {
        // Prepare statement to prevent SQL injection
        $stmt = mysqli_prepare($koneksi, "DELETE FROM absensi");
        
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            $_SESSION['success'] = "Berhasil menghapus semua (" . $affected_rows . ") data absensi.";
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