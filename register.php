<?php
include "config/koneksi.php";
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Absensi</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 35px;
            width: 380px;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #1d3557;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #1d3557;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0f2542;
        }
        a {
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #457b9d;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Daftar Akun</h2>
    <form action="proses_register.php" method="POST">
        <input type="text" name="nama" placeholder="Nama lengkap" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="daftar">Daftar</button>
    </form>

    <a href="login.php">Sudah punya akun? Login</a>
</div>

</body>
</html>
