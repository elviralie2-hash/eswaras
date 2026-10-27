<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_eswaras_fix";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>