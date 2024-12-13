<?php
$servername = "localhost"; // Ganti dengan server Anda
$username = "root";        // Ganti dengan username MySQL Anda
$password = "your_new_password";            // Ganti dengan password MySQL Anda
$dbname = "penjahat_db"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
