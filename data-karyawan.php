<?php
// Check if admin is logged in
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

// Handle messages from delete operations
$message = "";
$alert_type = "";
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $alert_type = isset($_GET['type']) ? $_GET['type'] : 'info';
}

// Ambil data karyawan dari database
//$sql = "SELECT id_karyawan, nama, username, jabatan, role FROM karyawan ORDER BY id_karyawan ASC";
//$karyawan = mysqli_query($koneksi, $sql);
// Jalankan query untuk ambil data karyawan
$sql = "SELECT id_karyawan, nama, username, role, informasi_kontak FROM karyawan";
$karyawan = mysqli_query($koneksi, $sql);

if($karyawan === false){
    $dbErr = mysqli_error($koneksi);
    die("Query gagal: " . htmlspecialchars($dbErr));
}

$total = mysqli_num_rows($karyawan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan - Admin Panel</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../bcg2.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: #421010ff;
            color: white;
            padding: 15px 30px;
            font-size: 22px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(209, 34, 34, 0.81);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            background: #ffffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(255, 8, 8, 0.81);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        h2 {
            margin: 0;
            color: #421010ff;
            font-size: 28px;
        }

        .btn-tambah {
            background: #421010ff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-tambah:hover {
            background: #7d281fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ffffffff;
        }

        th {
            background: #421010ff;
            color: white;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #86858562;
        }

        tr:hover {
            background-color: #35333362;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-edit, .btn-hapus {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-edit {
            background: #1e13e8ff;
            color: white;
        }

        .btn-edit:hover {
            background: #131464ff;
        }

        .btn-hapus {
            background: #e74c3c;
            color: white;
        }

        .btn-hapus:hover {
            background: #c0392b;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
        }

        .error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .btn-back {
            display: inline-block;
            background: #421010ff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn-back:hover {
            background: #270b0bff;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .container {
                margin: 20px 15px;
                padding: 15px;
            }
            
            table {
                overflow-x: auto;
                display: block;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <span>Admin Panel - Data Karyawan</span>
        <a href="../logout.php" style="color: white; text-decoration: none;">Logout</a>
    </div>

    <div class="container">
        <div class="header">
            <h2>Daftar Karyawan</h2>
            <a href="tambah_karyawan.php" class="btn-tambah">+ Tambah Karyawan</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo $alert_type == 'success' ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if($total === 0): ?>
            <div class="no-data">Belum ada data karyawan.</div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($d = mysqli_fetch_assoc($karyawan)): 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($d['nama']); ?></td>
                    <td><?php echo htmlspecialchars($d['username']); ?></td>
                   
                    <td><?php echo htmlspecialchars($d['role']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit_karyawan.php?id=<?php echo $d['id_karyawan']; ?>" class="btn-edit">Edit</a>
                            <a href="hapus_karyawan.php?id=<?php echo $d['id_karyawan']; ?>" class="btn-hapus" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus karyawan <?php echo htmlspecialchars($d['nama']); ?>?')">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <a href="dashboard.php" class="btn-back">Kembali ke Dashboard</a>
    </div>
</body>
</html>