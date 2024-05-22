<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Check if 'email' query parameter is set
$email = isset($_GET['email']) ? $_GET['email'] : '';

// Prepare SQL query based on the value of 'email' parameter
if ($email == 'all') {
    $query = "SELECT * FROM doc_req";
} else {
    $query = "SELECT * FROM doc_req WHERE user_email = ?";
}

if ($email == 'all') {
    $stmt = $conn->prepare($query);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $prayers = array();
    while ($row = $result->fetch_assoc()) {
        $prayers[] = $row;
    }
    echo json_encode(array("status" => "success", "message" => "Berhasil mengambil data", "data" => $prayers));
} else {
    echo json_encode(array("status" => "error", "message" => "Tidak ada data ditemukan"));
}

$stmt->close();
$conn->close();
?>
