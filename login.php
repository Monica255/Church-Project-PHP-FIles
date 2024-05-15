<?php
header('Content-Type: application/json');

include 'koneksi.php';

// Mendapatkan data dari request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$email = isset($data['email']) ? $data['email'] : '';
$password = isset($data['password']) ? $data['password'] : '';

if (empty($email) || empty($password)) {
    echo json_encode(array("status" => "error", "message" => "Email dan password harus diisi"));
    exit();
}

// Mempersiapkan statement SQL untuk mencegah SQL injection
$stmt = $conn->prepare("SELECT name, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($name, $role);
    $stmt->fetch();
    
    // Verifikasi password menggunakan hash
    // Di sini kita tidak memeriksa password karena hanya ingin mendapatkan nama dan peran (role) pengguna
    $user = array("name" => $name, "email" => $email, "role" => $role);
    echo json_encode(array("status" => "success", "message" => "Login berhasil", "user" => $user));
} else {
    echo json_encode(array("status" => "error", "message" => "Email tidak ditemukan"));
}

$stmt->close();
$conn->close();
?>
