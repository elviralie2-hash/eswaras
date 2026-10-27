<?php 
session_start();

// Cek apakah admin sudah login. Jika belum, lempar ke login.php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'config/koneksi.php'; 

// Proses Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.php");
    exit;
}
 
// Proses Tambah Menu Baru
if (isset($_POST['tambah_menu'])) {
    $nama     = $_POST['nama_menu'];
    $kategori = $_POST['kategori'];
    $harga    = $_POST['harga'];
    $foto     = $_POST['foto'];

    $query = "INSERT INTO tb_menu (nama_menu, kategori, harga, foto) VALUES ('$nama', '$kategori', '$harga', '$foto')";
    mysqli_query($koneksi, $query);
    header("Location: admin.php");
}

// Proses Hapus Menu
if (isset($_GET['hapus_id'])) {
    $id_hapus = $_GET['hapus_id'];
    mysqli_query($koneksi, "DELETE FROM tb_menu WHERE id_menu = '$id_hapus'");
    header("Location: admin.php");
}

// Ambil Data Menu
$query_menu = "SELECT * FROM tb_menu ORDER BY id_menu DESC";
$res_menu = mysqli_query($koneksi, $query_menu);

// Ambil Data Pesanan / Rekap Pelanggan dari tb_pesanan
$query_pesanan = "SELECT * FROM tb_pesanan ORDER BY id_pesanan DESC";
$res_pesanan = mysqli_query($koneksi, $query_pesanan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Es Waras</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f4f7f4; padding: 30px; color: #2c3e50; }
        .container { max-width: 1050px; margin: 0 auto; }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        h1 { color: #2e7d32; font-size: 24px; }
        .btn-logout { background: #d32f2f; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; }
        
        .card-box { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 30px; }
        h2 { font-size: 18px; color: #1b5e20; margin-bottom: 15px; border-bottom: 2px solid #a7f3d0; padding-bottom: 8px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; font-size: 13px; }
        th { background-color: #e8f5e9; color: #2e7d32; font-weight: 700; }
        
        .badge-status { background: #c8e6c9; color: #1b5e20; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 11px; }

        .form-group { margin-bottom: 12px; }
        label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 5px; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; }
        .btn-submit { background: #2e7d32; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .btn-submit:hover { background: #1b5e20; }
        .nav-back { display: inline-block; margin-bottom: 15px; color: #2e7d32; text-decoration: none; font-weight: 600; font-size: 14px; }
        .btn-hapus { color: #d32f2f; text-decoration: none; font-weight: bold; background: #ffebee; padding: 5px 10px; border-radius: 6px; font-size: 12px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-top">
        <a href="index.php" class="nav-back">← Kembali ke Katalog Utama</a>
        <a href="admin.php?action=logout" class="btn-logout" onclick="return confirm('Keluar dari Admin?')">Logout</a>
    </div>
    
    <h1>⚙️ ES WARAS ADMIN</h1>

    <!-- TABEL 1: REKAPITULASI DATA PELANGGAN (SESUAI FIGMA) -->
    <div class="card-box">
        <h2>📋 REKAPITULASI DATA PELANGGAN</h2>
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA PELANGGAN</th>
                    <th>MENU DIPILIH</th>
                    <th>TOTAL HARGA</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while($p = mysqli_fetch_assoc($res_pesanan)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><b><?= $p['nama_pembeli']; ?></b></td>
                    <td><?= $p['menu_dipilih']; ?></td>
                    <td>Rp <?= number_format($p['total_harga'], 0, ',', '.'); ?></td>
                    <td><span class="badge-status"><?= $p['status_bayar']; ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- TABEL 2: FORM TAMBAH MENU BARU -->
    <div class="card-box">
        <h2>➕ Tambah Menu Baru</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" placeholder="Contoh: Es Cokelat Waras" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori">
                    <option value="Alpukat">Alpukat</option>
                    <option value="Pokamilk">Pokamilk</option>
                    <option value="Dragomilk">Dragomilk</option>
                    <option value="Cincau">Cincau</option>
                    <option value="Buah">Buah</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" placeholder="15000" required>
            </div>
            <div class="form-group">
                <label>Nama File Foto (di folder assets/)</label>
                <input type="text" name="foto" placeholder="contoh: alpukat_kocok.jpg" required>
            </div>
            <button type="submit" name="tambah_menu" class="btn-submit">Simpan Menu Baru</button>
        </form>
    </div>

    <!-- TABEL 3: DAFTAR MENU & HAPUS -->
    <div class="card-box">
        <h2>🍹 Daftar Menu Saat Ini</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>File Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($m = mysqli_fetch_assoc($res_menu)) : ?>
                <tr>
                    <td><?= $m['id_menu']; ?></td>
                    <td><b><?= $m['nama_menu']; ?></b></td>
                    <td><?= $m['kategori']; ?></td>
                    <td>Rp <?= number_format($m['harga'], 0, ',', '.'); ?></td>
                    <td><code>assets/<?= $m['foto']; ?></code></td>
                    <td>
                        <a href="admin.php?hapus_id=<?= $m['id_menu']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus menu ini?')">🗑 Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>