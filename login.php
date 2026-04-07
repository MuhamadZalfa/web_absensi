<?php
include "config/koneksi.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = ""; // inisialisasi

if (isset($_POST['login'])) {

    $user = $_POST['username'];
    $pass = $_POST['password'];
    $role = $_POST['jabatan'];

    // Cek username + role
    $cek = mysqli_query($koneksi, "SELECT * FROM karyawan WHERE username='$user' AND role='$role'");
    $data = mysqli_fetch_assoc($cek);

    if ($data) {

        // Cocokkan password HASH
        if (password_verify($pass, $data['password'])) {

            // Set session
            $_SESSION['id_karyawan'] = $data['id_karyawan'];
            $_SESSION['nama']        = $data['nama'];
            $_SESSION['role']        = $data['role'];

            // Redirect sesuai role
            if ($data['role'] == "admin") {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: karyawan/absen.php");
            }
            exit;

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Username atau Jabatan tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Absensi</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('bcg2.jpg');

            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            width: 380px;
            background: #1a1818cc;
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(223, 19, 19, 1);
            text-align: center;
        }

        h2 {
            margin-bottom: 15px;
            color: #ffffffff;
            font-size: 26px;
            font-weight: 700;
        }

        p.subtitle {
            margin-top: -5px;
            margin-bottom: 20px;
            color: #656565ff;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            border-radius: 10px;
            border: 1px solid #ffffffa6;
            font-size: 15px;
            outline: none;
            transition: 0.2s;
        }

        input:focus, select:focus {
            border-color: #ff009dff;
            box-shadow: 0 0 5px rgba(41, 185, 79, 1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: #b12323ff;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer; 
            transition: 0.3s;
        }

        button:hover {
            background: #320f0fff;
        }

        .footer-text {
            margin-top: 15px;
            font-size: 12px;
            color: #656565ff;;
        }
        
    </style>
</head>
<body>

    <div class="card">
        <h2>Presensi Karyawan Mersonlikeit</h2>
        <p class="subtitle">Silahkan login untuk melanjutkan</p>

        <form method="POST">
            
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <select name="jabatan" required>
                <option value="">-- Pilih Jabatan --</option>
                <option value="admin">Admin</option>
                <option value="karyawan">Karyawan</option>
            </select>

            <button type="submit" name="login">Login</button>
            
             <!-- Error message muncul di sini -->
    <?php if (!empty($error)): ?>
        <p id="errorMsg" style="color: red; font-weight: 200; font-weight: normal; margin-top: 10px;">
            <?= $error ?>
        </p>

        <script>
            setTimeout(() => {
                document.getElementById("errorMsg").style.display = "none";
            }, 3000);
        </script>
    <?php endif; ?>
        </form>
        


        <p class="footer-text">© 2025 Sistem Presensi | Exclusive & Formal Design By Zalfa & Zaki</p>
    </div>

</body>
</html>

