<?php
header('Content-Type: application/json');

include 'koneksi.php';

// Mendapatkan data dari request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$email = isset($data['email']) ? $data['email'] : '';
$password = isset($data['password']) ? $data['password'] : '';
$name = isset($data['name']) ? $data['name'] : '';

if (empty($email) || empty($password) || empty($name)) {
    echo json_encode(array("status" => "error", "message" => "Semua field harus diisi"));
    exit();
}

// Memeriksa apakah email sudah digunakan
$stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(array("status" => "error", "message" => "Email sudah terdaftar"));
    exit();
}
$stmt->close();

// Menentukan peran berdasarkan email
$role = (strpos($email, '@admin') !== false) ? 'admin' : 'jemaat';

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $email, $hashed_password, $name, $role);

if ($stmt->execute()) {
    echo json_encode(array("status" => "success", "message" => "Pendaftaran berhasil", "user" => array("name" => $name, "email" => $email, "role" => $role)));
} else {
    echo json_encode(array("status" => "error", "message" => "Terjadi kesalahan, silakan coba lagi"));
}

$stmt->close();
$conn->close();
?>
