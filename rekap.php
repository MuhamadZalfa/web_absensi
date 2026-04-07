<?php
include("../config/koneksi.php");

// Handle filter parameters
$filter_nama = isset($_GET['filter_nama']) ? trim($_GET['filter_nama']) : '';
$filter_tanggal = isset($_GET['filter_tanggal']) ? $_GET['filter_tanggal'] : '';

// Query to get all employees for the dropdown first
$employee_sql = "SELECT id_karyawan, nama FROM karyawan ORDER BY nama ASC";
$employee_result = mysqli_query($koneksi, $employee_sql);
$employees = array();
while($row = mysqli_fetch_assoc($employee_result)) {
    $employees[] = $row;
}

// Query join tabel karyawan dan absensi
$sql = "SELECT
            a.id_karyawan,
            k.nama,
            a.tanggal,
            a.jam_masuk,
            a.jam_pulang
        FROM absensi a
        INNER JOIN karyawan k ON a.id_karyawan = k.id_karyawan";

$whereAdded = false;

// Add name filter if selected
if (!empty($filter_nama)) {
    $sql .= " WHERE k.nama LIKE '%" . mysqli_real_escape_string($koneksi, $filter_nama) . "%'";
    $whereAdded = true;
}

// Add date filter if selected
if (!empty($filter_tanggal)) {
    if ($whereAdded) {
        $sql .= " AND a.tanggal = '" . mysqli_real_escape_string($koneksi, $filter_tanggal) . "'";
    } else {
        $sql .= " WHERE a.tanggal = '" . mysqli_real_escape_string($koneksi, $filter_tanggal) . "'";
        $whereAdded = true;
    }
}

$sql .= " ORDER BY a.tanggal DESC";

$result = mysqli_query($koneksi, $sql);

// Handle alerts
$alert_message = '';
$alert_type = ''; // 'success' or 'error'

if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
    $alert_message = $_SESSION['success'];
    $alert_type = 'success';
    unset($_SESSION['success']);
} elseif (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    $alert_message = $_SESSION['error'];
    $alert_type = 'error';
    unset($_SESSION['error']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.03), rgba(0, 0, 0, 0.09)), url('bcg2.jpg');
        }

        .navbar {
            background: #00000067;
            color: #ffffffff;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 6px rgba(255, 17, 17, 0.81);
        }

        .btn-back {
            padding: 8px 15px;
            background: #d82020ac;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-back:hover {
            background: #4b1111ff;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #ffffff80;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(254, 0, 0, 0.7);
        }

        h2 {
            text-align: center;
            margin-top: 0;
            color: #000000ff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #4b1111ff;
            color: #ffffffff;
        }

        th, td {
            border: 1px solid #000000ff;
            padding: 12px;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #ffffff53;
        }

        tr:hover {
            background: #8f8f8f72;
        }

        .btn-filter {
            padding: 8px 15px;
            background: #4b1111ff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-filter:hover {
            background: #d82020ac;
        }

        /* Responsive design for mobile devices */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                min-height: 60px;
            }

            .btn-settings {
                font-size: 1.2em;
                padding: 5px;
                align-self: center;
            }

            .container {
                margin: 15px;
                padding: 15px;
            }

            .modal-content {
                width: 95%;
                margin: 10px;
                padding: 15px;
            }

            table {
                font-size: 0.9em;
            }

            th, td {
                padding: 8px;
            }

            form {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .delete-option {
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            .navbar {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                position: relative;
            }

            .btn-settings {
                align-self: flex-end;
                position: absolute;
                top: 15px;
                right: 25px;
            }

            .container {
                margin: 10px 5px;
                padding: 10px;
            }

            .modal-content {
                width: 98%;
                margin: 5px;
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .navbar h3 {
                font-size: 1.2em;
            }

            .btn-settings {
                font-size: 1.1em;
            }

            .container {
                margin: 10px;
                padding: 10px;
            }

            table {
                font-size: 0.8em;
            }

            th, td {
                padding: 5px;
                font-size: 0.8em;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <button class="btn-back" onclick="window.location.href='dashboard.php'">← Kembali</button>
    <h3 style="margin:0;">Rekap Absensi</h3>
     <button class="btn-settings" onclick="openSettingsModal()" style="margin-left: auto; background:
     transparent; border: none; font-size: 1.5em; cursor: pointer; color: #ffffff;">⚙️ Pengaturan</
     button>
</div>

<!-- Alert messages -->
<?php if ($alert_message): ?>
<div class="alert <?php echo $alert_type; ?>" style="max-width: 900px; margin: 20px auto; padding: 15px; border-radius: 6px; <?php echo $alert_type === 'success' ? 'background: #d4edda; color: #155724; border: 1px solid #c3e6cb;' : 'background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'; ?> ">
    <?php echo htmlspecialchars($alert_message); ?>
</div>
<?php endif; ?>

<!-- Modal for settings -->
<div id="settingsModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: #ffffff80; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(254, 0, 0, 0.7); width: 90%; max-width: 500px; position: relative;">
        <span class="close" onclick="closeSettingsModal()" style="position: absolute; top: 10px; right: 15px; font-size: 24px; font-weight: bold; cursor: pointer;">✖</span>
        <h3>Pengaturan Rekap Absensi</h3>

        <!-- Delete by specific date -->
        <div class="delete-option" style="margin-bottom: 20px; padding: 15px; background: #f0f0f080; border-radius: 8px;">
            <h4>Hapus Berdasarkan Tanggal Tertentu</h4>
            <form action="delete_by_date.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi untuk tanggal ini?');">
                <input type="date" name="date" required style="margin-right: 10px; padding: 5px;">
                <button type="submit" class="btn-delete" style="padding: 8px 15px; background: #d82020ac; color: white; border: none; border-radius: 6px; cursor: pointer;">Hapus Berdasarkan Tanggal</button>
            </form>
        </div>

        <!-- Delete by date range -->
        <div class="delete-option" style="margin-bottom: 20px; padding: 15px; background: #f0f0f080; border-radius: 8px;">
            <h4>Hapus Berdasarkan Rentang Tanggal</h4>
            <form action="delete_by_range.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi dalam rentang tanggal ini?');">
                <label>Dari:</label>
                <input type="date" name="start_date" required style="margin-right: 10px; padding: 5px;">
                <label>Sampai:</label>
                <input type="date" name="end_date" required style="margin-right: 10px; padding: 5px;">
                <button type="submit" class="btn-delete" style="padding: 8px 15px; background: #d82020ac; color: white; border: none; border-radius: 6px; cursor: pointer; Margin-Top: 15px">Hapus Berdasarkan Rentang</button>
            </form>
        </div>

        <!-- Delete by month -->
        <div class="delete-option" style="margin-bottom: 20px; padding: 15px; background: #f0f0f080; border-radius: 8px;">
            <h4>Hapus Berdasarkan Bulan</h4>
            <form action="delete_by_month.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi untuk bulan ini?');">
                <select name="month" required style="margin-right: 10px; padding: 5px;">
                    <option value="">Pilih Bulan</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
                <input type="number" name="year" min="2000" max="2100" placeholder="Tahun" required style="margin-right: 10px; padding: 5px;">
                <button type="submit" class="btn-delete" style="padding: 8px 15px; background: #d82020ac; color: white; border: none; border-radius: 6px; cursor: pointer;">Hapus Berdasarkan Bulan</button>
            </form>
        </div>

        <!-- Delete by year -->
        <div class="delete-option" style="margin-bottom: 20px; padding: 15px; background: #f0f0f080; border-radius: 8px;">
            <h4>Hapus Berdasarkan Tahun</h4>
            <form action="delete_by_year.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data absensi untuk tahun ini?');">
                <input type="number" name="year" min="2000" max="2100" placeholder="Tahun" required style="margin-right: 10px; padding: 5px;">
                <button type="submit" class="btn-delete" style="padding: 8px 15px; background: #d82020ac; color: white; border: none; border-radius: 6px; cursor: pointer;">Hapus Berdasarkan Tahun</button>
            </form>
        </div>

        <!-- Delete all data -->
        <div class="delete-option" style="margin-bottom: 20px; padding: 15px; background: #f0f0f080; border-radius: 8px;">
            <h4>Hapus Semua Data Rekap</h4>
            <form id="deleteAllForm" action="delete_all.php" method="POST">
                <p>Ketik "HAPUS" untuk mengkonfirmasi penghapusan semua data:</p>
                <input type="text" id="confirmText" name="confirm_text" placeholder="Ketik HAPUS" style="margin-right: 10px; padding: 5px;">
                <button type="button" class="btn-delete-all" onclick="confirmDeleteAll()" style="padding: 10px 20px; background: #ff0000; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">Hapus Semua Data Rekap</button>
            </form>
        </div>
    </div>
</div>

<script>
let scrollPosition = 0;

function isMobileDevice() {
    return window.innerWidth <= 768;
}

function openSettingsModal() {
    if (isMobileDevice()) {
        // Store current scroll position only on mobile
        scrollPosition = window.pageYOffset || document.body.scrollTop;
        // Prevent background scrolling on mobile devices
        document.body.style.overflow = 'hidden';
        document.body.style.position = 'fixed';
        document.body.style.top = -scrollPosition + 'px';
        document.body.style.width = '100%';
    }
    document.getElementById('settingsModal').style.display = 'flex';
}

function closeSettingsModal() {
    document.getElementById('settingsModal').style.display = 'none';
    if (isMobileDevice()) {
        // Restore scrolling and position only on mobile devices
        document.body.style.overflow = 'auto';
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        // Restore scroll position
        window.scrollTo(0, scrollPosition);
    }
}

function confirmDeleteAll() {
    const confirmText = document.getElementById('confirmText').value;

    if(confirmText !== 'HAPUS') {
        alert('Harap ketik "HAPUS" untuk mengkonfirmasi penghapusan semua data.');
        return;
    }

    if(confirm('Apakah Anda YAKIN ingin menghapus SEMUA data rekap absensi? Tindakan ini tidak bisa dibatalkan.')) {
        document.getElementById('deleteAllForm').submit();
    }
}

// Close modal when clicking outside the content
window.onclick = function(event) {
    const modal = document.getElementById('settingsModal');
    if (event.target === modal) {
        closeSettingsModal();
    }
}
</script>

<div class="container">
    <h2>Rekap Absensi Karyawan</h2>

    <!-- Form Filter -->
    <form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <label for="filter_nama">Nama Karyawan:</label>
        <select id="filter_nama" name="filter_nama" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
            <option value="">Semua Karyawan</option>
            <?php
            foreach($employees as $employee) {
                $selected = ($employee['nama'] == $filter_nama) ? 'selected' : '';
                echo "<option value='".$employee['nama']."' $selected>".$employee['nama']."</option>";
            }
            ?>
        </select>

        <label for="filter_tanggal">Tanggal:</label>
        <input type="date" id="filter_tanggal" name="filter_tanggal" value="<?php echo $filter_tanggal; ?>">

        <button type="submit" class="btn-filter">Filter</button>
          <a href="rekap.php" 
       style="padding: 5px 30px; background: #4CAF50; color: white; text-decoration: none; border-radius: 20px;">
       Reset
    </a>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Karyawan</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
            <th>Status</th>
        </tr>

        <?php
        $no = 1;
        while($row = mysqli_fetch_assoc($result)) {
            // Determine status based on jam_masuk
            if (empty($row['jam_masuk']) || $row['jam_masuk'] === null) {
                $status = '-';
            } else {
                $jam_masuk_time = strtotime($row['jam_masuk']);
                $batas_waktu = strtotime("08:00:00");

                if ($jam_masuk_time > $batas_waktu) {
                    $status = "Telat";
                } else {
                    $status = "Hadir";
                }
            }

            echo "<tr>";
            echo "<td>".$no++."</td>";
            echo "<td>".$row['nama']."</td>";
            echo "<td>".$row['tanggal']."</td>";
            echo "<td>".($row['jam_masuk'] ?? '-')."</td>";
            echo "<td>".($row['jam_pulang'] ?? '-')."</td>";
            echo "<td>".$status."</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<!-- Script to auto-hide alerts after 5 seconds -->
<?php if ($alert_message): ?>
<script>
setTimeout(function() {
    const alertDiv = document.querySelector('.alert');
    if(alertDiv) {
        alertDiv.style.display = 'none';
    }
}, 5000); // Hide after 5 seconds
</script>
<?php endif; ?>

</body>
</html>
