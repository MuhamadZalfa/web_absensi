<?php
include "../config/koneksi.php";

date_default_timezone_set('Asia/Jakarta');
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['id_karyawan'])) {
    header("Location: ../login.php");
    exit;
}

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   if (isset($_POST['masuk'])) {
    $id_karyawan = $_SESSION['id_karyawan'];
    $tanggal = date("Y-m-d");
    $jam = date("H:i:s");

    mysqli_query($koneksi, "INSERT INTO absensi (id_karyawan, tanggal, jam_masuk)
                            VALUES ('$id_karyawan', '$tanggal', '$jam')");

    $pesan = "Absen masuk berhasil dicatat pada $jam";
}

}
if (isset($_POST['pulang'])) {
    $id_karyawan = $_SESSION['id_karyawan'];
    $tanggal = date("Y-m-d");
    $jam = date("H:i:s");

    // Periksa apakah sudah ada record absensi untuk hari ini
    $cek_absensi = mysqli_query($koneksi, "SELECT * FROM absensi WHERE id_karyawan = '$id_karyawan' AND tanggal = '$tanggal'");

    if(mysqli_num_rows($cek_absensi) > 0) {
        // Jika sudah ada record, lakukan UPDATE
        mysqli_query($koneksi, "UPDATE absensi SET jam_pulang = '$jam' WHERE id_karyawan = '$id_karyawan' AND tanggal = '$tanggal'");
        $pesan = "Absen pulang berhasil dicatat pada $jam";
    } else {
        // Jika belum ada record, buat record baru dengan INSERT
        mysqli_query($koneksi, "INSERT INTO absensi (id_karyawan, tanggal, jam_pulang) VALUES ('$id_karyawan', '$tanggal', '$jam')");
        $pesan = "Absen pulang berhasil dicatat pada $jam (tanpa absen masuk)";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absen Karyawan</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('bcg2.jpg');
        }
        .navbar {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('dip11.png');
            color: white;
            padding: 15px 30px;
            font-size: 22px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(255, 0, 0, 0.84);
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: rgba(0, 0, 0, 0.69);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(255, 0, 0, 0.91);
        }
        h2, h3 {
            color: #ffffffff;
            margin-bottom: 10px;
        }
        .pesan {
    background: #e8f0fe;
    padding: 10px 14px;
    border-radius: 8px;
    margin: 25px 0 20px 0; /* lebih ke bawah */
    font-weight: 500;       /* lebih tipis */
    font-size: 14px;        /* lebih kecil */
}

        .btn {
            display: inline-block;
            padding: 14px 26px;
            margin-right: 10px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s;
            color: white;
        }
        .btn-masuk { background: #1530b8ff; }
        .btn-masuk:hover { background: #0a2243ff; }
        .btn-pulang { background: #e21526ff; }
        .btn-pulang:hover { background: #581010ff; }
        .logout {
            display: inline-block;
            margin-top: 25px;
            background: #6c757d;
            padding: 12px 18px;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
        }
        .logout:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>

<div class="navbar">Presensi Karyawan Mersonlikeit</div>

<div class="container">
    <h2>Halo, <?php echo $_SESSION['nama']; ?></h2>
    <h3>Absen Kehadiran</h3>

    <?php if(isset($pesan)) echo "<div class='pesan'>$pesan</div>"; ?>

    <form method="POST">
        <button class="btn btn-masuk" name="masuk">Absen Masuk</button>
        <button class="btn btn-pulang" name="pulang">Absen Pulang</button>
    </form>

    <a class="logout" href="../logout.php">Logout</a>
</div>
<script>
    // Menghilangkan pesan setelah 3 detik
    setTimeout(function() {
        let pesan = document.querySelector('.pesan');
        if (pesan) {
            pesan.style.opacity = '0';
            pesan.style.transition = '0.5s';
            setTimeout(() => pesan.remove(), 500);
        }
    }, 3000);
</script>

</body>
</html>
