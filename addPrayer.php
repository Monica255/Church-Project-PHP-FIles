<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Get the input from the request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$user_email = isset($data['user_email']) ? $data['user_email'] : '';
$prayer = isset($data['prayer']) ? $data['prayer'] : '';
$status = isset($data['status']) ? $data['status'] : 0;

if (empty($user_email) || empty($prayer)) {
    echo json_encode(array("status" => "error", "message" => "User email dan prayer harus diisi"));
    exit();
}

// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO prayer (user_email, prayer, timestamp,status) VALUES (?, ?, NOW(),?)");
$stmt->bind_param("sss", $user_email, $prayer, $status);

if ($stmt->execute()) {
    echo json_encode(array("status" => "success", "message" => "Permintaan berhasil dikirim"));
} else {
    echo json_encode(array("status" => "error", "message" => "Terjadi kesalahan, silakan coba lagi"));
}

$stmt->close();
$conn->close();
?>
