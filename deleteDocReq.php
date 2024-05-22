<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Get the id from the query parameters
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo json_encode(array("status" => "error", "message" => "ID tidak valid"));
    exit();
}

// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM doc_req WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(array("status" => "success", "message" => "Data berhasil dihapus"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Data tidak ditemukan"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Terjadi kesalahan, silakan coba lagi"));
}

$stmt->close();
$conn->close();
?>
