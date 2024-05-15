<?php
header('Content-Type: application/json');

include 'koneksi.php';

$email = isset($_GET['email']) ? $_GET['email'] : '';
 
if (empty($email)) {
    
    echo json_encode(array("status" => "error", "message" => "Email harus diisi"));
    exit();
}

// Mempersiapkan statement SQL untuk mencegah SQL injection
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($name, $email, $role);
    $stmt->fetch();
    
    $user = array("name" => $name, "email" => $email, "role" => $role);
    echo json_encode(array("status" => "success", "user" => $user));
} else {
    echo json_encode(array("status" => "error", "message" => "Email tidak ditemukan"));
}

$stmt->close();
$conn->close();
?>
