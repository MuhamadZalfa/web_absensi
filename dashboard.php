<?php
session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background:linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('bcg2.jpg');
        }
        .navbar {
            background: #421010ff;
            color: white;
            padding: 15px 30px;
            font-size: 22px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(209, 34, 34, 0.81);
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background:  #1b0101d1;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(207, 42, 42, 0.86);
        }
        h2 {
            margin-top: 0;
            color: #ffffffff;
            font-size: 28px;
        }
        .menu {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        .menu li {
            margin-bottom: 15px;
        }
        .menu a {
            display: block;
            padding: 14px 20px;
            background: #421010ff;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 18px;
            transition: 0.2s;
        }
        .menu a:hover {
            background: #2e0f0fff;
        }
        .logout {
            display: inline-block;
            margin-top: 25px;
            background:  #421010ff;
            padding: 12px 18px;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }
        .logout:hover {
            background: #401010ff;
        }
    </style>
</head>
<body>

<div class="navbar">Dashboard Admin</div>

<div class="container">
    <h2>Selamat Datang, Admin</h2>

    <ul class="menu">
        <li><a href="data-karyawan.php">📌 Data Karyawan</a></li>
        <li><a href="rekap.php">📊 Rekap Absensi</a></li>
    </ul>

    <a class="logout" href="../logout.php">Logout</a>
</div>

</body>
</html>
