<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Mendapatkan data dari request query string
$email = isset($_GET['email']) ? $_GET['email'] : '';

if ($email == 'all') {
    // Query untuk mendapatkan semua data kehadiran
    $query = "SELECT * FROM attendance ORDER BY timestamp DESC";
} else {
    // Query untuk mendapatkan data kehadiran berdasarkan email
    $query = "SELECT * FROM attendance WHERE email_user = ?";
}

// Membuat statement
$stmt = $conn->prepare($query);

if ($email != 'all') {
    // Mengikat parameter email jika tidak "all"
    $stmt->bind_param("s", $email);
}

// Mengeksekusi statement
$stmt->execute();

// Mengambil hasil query
$result = $stmt->get_result();

// Inisialisasi array untuk menyimpan data kehadiran
$attendance_list = array();

// Memasukkan hasil query ke dalam array
while ($row = $result->fetch_assoc()) {
    $attendance_list[] = $row;
}

// Menutup statement
$stmt->close();

// Menutup koneksi
$conn->close();

// Mengembalikan data kehadiran dalam format JSON
echo json_encode($attendance_list);
?>
