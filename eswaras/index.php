<?php 
include './config/koneksi.php'; 

// Filter Kategori
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'Semua';
if ($kategori == 'Semua') {
    $query = "SELECT * FROM tb_menu";
} else {
    $query = "SELECT * FROM tb_menu WHERE kategori LIKE '%$kategori%'";
}
$result = mysqli_query($koneksi, $query);

// Tangkap id_menu yang dipilih jika pelanggan klik pesan
$id_dipilih = isset($_GET['pesan_id']) ? $_GET['pesan_id'] : null;
$menu_dipilih = null;
if ($id_dipilih) {
    $q_detail = mysqli_query($koneksi, "SELECT * FROM tb_menu WHERE id_menu = '$id_dipilih'");
    $menu_dipilih = mysqli_fetch_assoc($q_detail);
}

// Proses Simpan Pesanan (Langkah Checkout)
$step_qris = false;
$data_pesanan = null;

if (isset($_POST['proses_qris'])) {
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $no_wa  = mysqli_real_escape_string($koneksi, $_POST['no_wa']);
    $id_m   = $_POST['id_menu'];
    $harga  = $_POST['harga'];

    $q_m = mysqli_query($koneksi, "SELECT * FROM tb_menu WHERE id_menu = '$id_m'");
    $data_m = mysqli_fetch_assoc($q_m);
    $nama_m = $data_m['nama_menu'];

    $query_insert = "INSERT INTO tb_pesanan (nama_pembeli, menu_dipilih, total_harga, status_bayar) 
                     VALUES ('$nama', '$nama_m', '$harga', 'Belum Bayar')";
    
    $ins = mysqli_query($koneksi, $query_insert);
    
    if ($ins) {
        $step_qris = true;
        $data_pesanan = [
            'nama' => $nama,
            'no_wa' => $no_wa,
            'nama_menu' => $nama_m,
            'harga' => $harga
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Es Waras - Platform Pelanggan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #c8e6c9; color: #1b5e20; min-height: 100vh; }
        header {
            background-color: #69f0ae;
            padding: 12px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #00c853;
        }
        .brand { font-weight: 800; font-size: 20px; color: #1b5e20; display: flex; align-items: center; gap: 8px; }
        .nav-btn { display: flex; gap: 10px; }
        .nav-btn a {
            text-decoration: none;
            background: #a7f3d0;
            color: #1b5e20;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #10b981;
        }
        .nav-btn a.active { background: #10b981; color: white; }
        .main-container { max-width: 950px; margin: 15px auto; padding: 10px; }
        .hero-banner-full {
            background: linear-gradient(rgba(0, 0, 0, 0.35), rgba(0, 0, 0, 0.35)), 
                        url('assets/banner_minuman.jpeg') no-repeat center center;
            background-size: cover;
            border-radius: 12px;
            padding: 55px 40px;
            margin-bottom: 20px;
            border: 2px solid #a7f3d0;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }
        .hero-banner-full h2 { 
            font-size: 32px; font-weight: 800; color: #ffffff; line-height: 1.3; text-shadow: 0 3px 8px rgba(0,0,0,0.7);
        }
        .katalog-wrapper {
            background-color: #c8e6c9;
            background-image: radial-gradient(#81c784 20%, transparent 20%);
            background-size: 30px 30px;
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #81c784;
        }
        .katalog-header { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
        .katalog-header h3 { font-size: 16px; font-weight: 700; }
        .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; }
        .pill {
            text-decoration: none; padding: 5px 15px; background: #69f0ae; color: #1b5e20; border-radius: 15px; font-size: 12px; font-weight: 700; border: 1px solid #00c853;
        }
        .pill.aktif { background: #00c853; color: white; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 15px; }
        .menu-card {
            background: #ffffff; border-radius: 10px; padding: 10px; text-align: center; border: 1px solid #ccc; box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .menu-card img { width: 100%; height: 110px; object-fit: cover; border-radius: 8px; margin-bottom: 8px; }
        .menu-card h4 { font-size: 13px; margin-bottom: 4px; color: #000; text-transform: capitalize; }
        .menu-card p { font-size: 12px; font-weight: 700; color: #1b5e20; margin-bottom: 8px; }
        .btn-pilih { display: block; text-decoration: none; background: #00c853; color: white; font-size: 12px; font-weight: 700; padding: 6px; border-radius: 6px; }
        .box-checkout { background: #b2f5ea; border-radius: 12px; padding: 30px; max-width: 500px; margin: 20px auto; text-align: center; border: 2px solid #319795; }
        .card-inner { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .form-group { text-align: left; margin-bottom: 15px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; background: #fef08a; border: 1px solid #eab308; border-radius: 6px; font-size: 13px; }
        .btn-proses { background: #69f0ae; color: #1b5e20; border: 1px solid #00c853; width: 100%; padding: 10px; border-radius: 20px; font-weight: 800; cursor: pointer; font-size: 13px; }
        .qris-container { display: flex; gap: 20px; align-items: center; justify-content: center; text-align: left; margin-top: 15px; }
        .qris-box { width: 140px; height: 140px; border: 2px dashed #333; display: flex; align-items: center; justify-content: center; font-weight: 800; background: #f5f5f5; font-size: 12px; }
        .qris-detail { font-size: 13px; }
        .qris-detail b { color: #000; }
    </style>
</head>
<body>

    <header>
 <div class="brand" style="display: flex; align-items: center; gap: 8px;">
    <img src="assets/logo_eswaras.jpeg" alt="Logo Es Waras" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
    <span>ES WARAS</span>
</div>
        <div class="nav-btn">
            <a href="index.php" class="active">Beranda</a>
            <a href="index.php">Katalog Menu</a>
            <a href="login.php">Admin Login</a>
        </div>
    </header>

    <div class="main-container">
        <?php if (!$menu_dipilih && !$step_qris) : ?>
            <div class="hero-banner-full">
                <h2>Segernya Es Waras,<br>Bikin Hari Kamu<br>Makin Seger</h2>
            </div>

            <div class="katalog-wrapper">
                <div class="katalog-header">
                    <h3>Katalog Menu:</h3>
                    <div class="filter-pills">
                        <a href="index.php?kategori=Semua" class="pill <?= ($kategori=='Semua')?'aktif':'' ?>">Semua</a>
                        <a href="index.php?kategori=Alpukat" class="pill <?= ($kategori=='Alpukat')?'aktif':'' ?>">Alpukat</a>
                        <a href="index.php?kategori=Pokamilk" class="pill <?= ($kategori=='Pokamilk')?'aktif':'' ?>">Pokamilk</a>
                        <a href="index.php?kategori=Cincau" class="pill <?= ($kategori=='Cincau')?'aktif':'' ?>">Cincau</a>
                    </div>
                </div>

                <div class="menu-grid">
                    <?php while($m = mysqli_fetch_assoc($result)) : ?>
                        <div class="menu-card">
                            <img src="assets/<?= $m['foto']; ?>" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500'">
                            <h4><?= $m['nama_menu']; ?></h4>
                            <p>Rp <?= number_format($m['harga'], 0, ',', '.'); ?></p>
                            <a href="index.php?pesan_id=<?= $m['id_menu']; ?>" class="btn-pilih">Pesan Sekarang</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

        <?php elseif ($menu_dipilih && !$step_qris) : ?>
            <div class="box-checkout">
                <div class="card-inner">
                    <h3 style="margin-bottom: 15px; font-size: 14px; text-transform: uppercase;">FORM DATA PELANGGAN</h3>
                    <form action="" method="POST">
                        <input type="hidden" name="id_menu" value="<?= $menu_dipilih['id_menu']; ?>">
                        <input type="hidden" name="harga" value="<?= $menu_dipilih['harga']; ?>">
                        
                        <div class="form-group">
                            <label>Nama Lengkap :</label>
                            <input type="text" name="nama_pelanggan" placeholder="Masukkan nama Anda" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor WhatsApp :</label>
                            <input type="text" name="no_wa" placeholder="Masukkan nomor WA" required>
                        </div>
                        <button type="submit" name="proses_qris" class="btn-proses">PROSES & QRIS</button>
                    </form>
                </div>
            </div>

        <?php elseif ($step_qris) : ?>
            <div class="box-checkout" style="max-width: 600px;">
                <h3 style="font-size: 14px; margin-bottom: 15px; font-weight: 800;">SILAKAN LAKUKAN PEMBAYARAN VIA QRIS</h3>
                <div class="card-inner">
                    <div class="qris-container">
                        <div class="qris-box">[ KODE QRIS ]</div>
                        <div class="qris-detail">
                            <p><b>Ringkasan Pesanan :</b></p>
                            <p>• <?= $data_pesanan['nama_menu']; ?> (1x)</p>
                            <hr style="margin: 8px 0; border: 0.5px dashed #ccc;">
                            <p><b>Total : Rp <?= number_format($data_pesanan['harga'], 0, ',', '.'); ?></b></p>
                        </div>
                    </div>
                </div>
                <p style="font-size: 11px; font-weight: 700; margin-top: 15px;">*Tunjukkan bukti transfer ke kasir kedai Es Waras</p>
                <a href="index.php" style="display: inline-block; margin-top: 15px; font-size: 12px; color: #1b5e20; font-weight: 700;">← Kembali ke Beranda</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>