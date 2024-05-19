<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Include the database connection file
include 'koneksi.php';

// Get the request body
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

// Check if all required fields are provided
if (isset($data['nama_kegiatan'], $data['tanggal'], $data['jam_mulai'], $data['jam_berakhir'])) {
    $nama_kegiatan = $data['nama_kegiatan'];
    $tanggal = $data['tanggal'];
    $jam_mulai = $data['jam_mulai'];
    $jam_berakhir = $data['jam_berakhir'];

    // Check if the event already exists
    $check_sql = "SELECT * FROM kegiatan WHERE name_event = ? AND date = ? AND start_time = ? AND end_time = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ssss", $nama_kegiatan, $tanggal, $jam_mulai, $jam_berakhir);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Event already exists
        $response = array("status" => "error", "message" => "Kegiatan sudah ada");
    } else {
        // Insert the event
        $insert_sql = "INSERT INTO kegiatan (name_event, date, start_time, end_time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $nama_kegiatan, $tanggal, $jam_mulai, $jam_berakhir);
        if ($stmt->execute()) {
            $response = array("status" => "success", "message" => "Kegiatan berhasil ditambahkan");
        } else {
            $response = array("status" => "error", "message" => "Gagal menambahkan kegiatan: " . $stmt->error);
        }
    }

    // Close the statement
    $stmt->close();
} else {
    $response = array("status" => "error", "message" => "Invalid request data.");
}

// Close the database connection
$conn->close();

// Return the JSON response
echo json_encode($response);
?>
