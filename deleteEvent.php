<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Mendapatkan data dari query parameter
$id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id)) {
    echo json_encode(array("status" => "error", "message" => "ID harus diisi"));
    $conn->close();
    exit();
}

// Mempersiapkan statement SQL untuk menghapus data attendance
$stmt = $conn->prepare("DELETE FROM kegiatan WHERE id_event = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(array("status" => "success", "message" => "Data acara berhasil dihapus"));
    } else {
        echo json_encode(array("status" => "error", "message" => "ID tidak ditemukan"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Terjadi kesalahan, silakan coba lagi"));
}

$stmt->close();
$conn->close();
?>
