<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Get prayer ID and new status from the query string
$doc_id = isset($_GET['doc_id']) ? (int) $_GET['doc_id'] : 0; // Cast to integer for security
$new_status = isset($_GET['new_status']) ? (int) $_GET['new_status'] : 0; // Cast to integer

if (empty($doc_id)) {
    echo json_encode(array("status" => "error", "message" => "Document ID harus diisi"));
    exit();
}

// Prepare SQL statement with parameter binding to prevent SQL injection
try {
  $stmt = $conn->prepare("UPDATE doc_req SET status = ? WHERE id = ?");
  $stmt->bind_param("ii", $new_status, $doc_id);
  $stmt->execute();
  
  if ($stmt->affected_rows === 1) {
    $response = array("status" => "success", "message" => "Status dokumen berhasil diperbarui");
  } else {
    $response = array("status" => "error", "message" => "Dokumen ID tidak ditemukan atau gagal update");
  }
} catch(mysqli_sql_exception $e) {
  $response = array("status" => "error", "message" => "Terjadi kesalahan: " . $e->getMessage());
} finally {
  // Always close resources regardless of success or failure
  if (isset($stmt)) {
    $stmt->close();
  }
  $conn->close();
}

echo json_encode($response);
?>
