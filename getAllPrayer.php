<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Prepare SQL query to fetch all records ordered by timestamp (oldest to newest)
$query = "SELECT * FROM prayer ORDER BY timestamp ASC";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $prayers = array();
    while($row = $result->fetch_assoc()) {
        $prayers[] = $row;
    }
    echo json_encode(array("status" => "success", "message" => "Berhasil mengambil data","data" => $prayers));
} else {
    echo json_encode(array("status" => "error", "message" => "Tidak ada data ditemukan"));
}

$conn->close();
?>
