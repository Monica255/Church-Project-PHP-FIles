<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Get the input from the request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$user_email = isset($data['user_email']) ? $data['user_email'] : '';
$date = isset($data['max_date']) ? $data['max_date'] : '';
$letter = isset($data['doc_type']) ? $data['doc_type'] : '';
$status = isset($data['status']) ? $data['status'] : 0;

if (empty($user_email) || empty($date)|| empty($letter)) {
    echo json_encode(array("status" => "error", "message" => "Data harus diisi"));
    exit();
}

// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO doc_req (user_email, doc_type, max_date, status) VALUES (?, ?, ?,?)");
$stmt->bind_param("sssi", $user_email, $letter, $date, $status);

if ($stmt->execute()) {
    echo json_encode(array("status" => "success", "message" => "Permintaan berhasil dikirim"));
} else {
    echo json_encode(array("status" => "error", "message" => "Terjadi kesalahan, silakan coba lagi"));
}

$stmt->close();
$conn->close();
?>
