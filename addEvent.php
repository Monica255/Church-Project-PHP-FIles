<?php
// Set the content type to JSON
header('Content-Type: application/json');

// Include the database connection file
include 'koneksi.php';

// Get the request body
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

// Check for null id_kegiatan and required fields
$id_kegiatan = isset($data['id_kegiatan']) ? (int) $data['id_kegiatan'] : null; // Cast to integer for security
if (empty($id_kegiatan) && !(isset($data['nama_kegiatan'], $data['tanggal'], $data['jam_mulai'], $data['jam_berakhir']))) {
  $response = array("status" => "error", "message" => "Invalid request data. Either id_kegiatan or required fields for new activity are missing.");
  echo json_encode($response);
  exit();
}

// Prepare variables based on the presence of id_kegiatan
if ($id_kegiatan) {
  // Update scenario
  $nama_kegiatan = $data['nama_kegiatan'];
  $tanggal = $data['tanggal'];
  $jam_mulai = $data['jam_mulai'];
  $jam_berakhir = $data['jam_berakhir'];
  
  // Update SQL statement with parameter binding
  $update_sql = "UPDATE kegiatan SET name_event = ?, date = ?, start_time = ?, end_time = ? WHERE id_event = ?";
  $stmt = $conn->prepare($update_sql);
  $stmt->bind_param("sssss", $nama_kegiatan, $tanggal, $jam_mulai, $jam_berakhir, $id_kegiatan);
  
  $message = "Kegiatan berhasil diubah";
} else {
  // Insert scenario (same as before)
  $nama_kegiatan = $data['nama_kegiatan'];
  $tanggal = $data['tanggal'];
  $jam_mulai = $data['jam_mulai'];
  $jam_berakhir = $data['jam_berakhir'];
  
  $insert_sql = "INSERT INTO kegiatan (name_event, date, start_time, end_time) VALUES (?, ?, ?, ?)";
  $stmt = $conn->prepare($insert_sql);
  $stmt->bind_param("ssss", $nama_kegiatan, $tanggal, $jam_mulai, $jam_berakhir);
  
  $message = "Kegiatan berhasil ditambahkan";
}

// Execute the prepared statement based on update or insert
if ($stmt->execute()) {
  $response = array("status" => "success", "message" => $message);
} else {
  $response = array("status" => "error", "message" => "Gagal " . ($id_kegiatan ? "mengubah" : "menambahkan") . " kegiatan: " . $stmt->error);
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();

// Return the JSON response
echo json_encode($response);
?>
