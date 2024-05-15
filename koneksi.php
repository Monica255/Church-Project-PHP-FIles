<?php
$servername = "localhost";  // Nama host MySQL Anda
$username = "root";    // Username MySQL Anda
$password = "";    // Password MySQL Anda
$dbname = "church"; // Nama database yang ingin Anda hubungkan

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
// echo "Koneksi berhasil";
?>
