<?php
// Check if admin is logged in
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

// Get employee ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// If form is submitted
if (isset($_POST['update'])) {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);

    $role = $_POST['role'];
    $password = $_POST['password']; // Password is optional on edit
    
    // Validation: check if all required fields are filled
    if (empty($nama) || empty($username) || empty($role)) {
        $error = "Nama, Username, dan Role wajib diisi!";
    } else {
        // Check if username already exists (excluding current record)
        $cek_username = mysqli_query($koneksi, "SELECT * FROM karyawan WHERE username='$username' AND id_karyawan != $id");
        if (mysqli_num_rows($cek_username) > 0) {
            $error = "Username Sudah Digunakan";
        } else {
            // Prepare the update query
            if (!empty($password)) {
                // If password is provided, hash it
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_query = "UPDATE karyawan SET nama='$nama', username='$username', password='$hashed_password', role='$role' WHERE id_karyawan=$id";
            } else {
                // If password is not provided, don't update it
                $update_query = "UPDATE karyawan SET nama='$nama', username='$username', role='$role' WHERE id_karyawan=$id";
            }
            
            $result = mysqli_query($koneksi, $update_query);
            
            if ($result) {
                $success = "Data karyawan berhasil diperbarui!";
            } else {
                $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
            }
        }
    }
}

// Get current employee data
$result = mysqli_query($koneksi, "SELECT * FROM karyawan WHERE id_karyawan = $id");
$data = mysqli_fetch_assoc($result);

// If no data found, redirect back
if (!$data) {
    header("Location: data_karyawan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan - Admin Panel</title>
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
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #e74c3c;
            box-shadow: 0 0 5px rgba(231, 76, 60, 0.3);
        }

        .btn-update {
            background: #e74c3c;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn-update:hover {
            background: #c0392b;
        }

        .btn-back {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 15px;
            transition: background 0.3s;
        }

        .btn-back:hover {
            background: #2980b9;
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

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .note {
            font-size: 13px;
            color: #666;
            margin-top: -10px;
            margin-bottom: 15px;
        }

        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .container {
                margin: 20px 15px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <span>Admin Panel - Edit Karyawan</span>
        <a href="../logout.php" style="color: white; text-decoration: none;">Logout</a>
    </div>

    <div class="container">
        <h2>Edit Akun Karyawan</h2>
        
        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password (Kosongkan jika tidak ingin diubah)</label>
                <input type="password" id="password" name="password">
                <div class="note" style="margin-top: 1px;">Kosongkan jika tidak ingin mengganti password</div>
            </div>
            
            <div class="form-group">
                
                
            </div>
            
            <div class="form-group">
                <label for="role">Role *</label>
                <select id="role" name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="karyawan" <?php echo ($data['role'] == 'karyawan') ? 'selected' : ''; ?>>Karyawan</option>
                    <option value="admin" <?php echo ($data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <button type="submit" name="update" class="btn-update">Update Akun Karyawan</button>
        </form>
        
        <a href="data-karyawan.php" class="btn-back">Kembali ke Daftar Karyawan</a>
    </div>
</body>
</html>