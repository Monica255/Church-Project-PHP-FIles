<?php
header('Content-Type: application/json');
include 'koneksi.php';

// Mendapatkan data dari request body
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$email_user = isset($data['email_user']) ? $data['email_user'] : '';
$id_event = isset($data['id_event']) ? $data['id_event'] : '';

if (empty($email_user) || empty($id_event)) {
    echo json_encode(array("status" => "error", "message" => "Email user dan ID event harus diisi"));
    $conn->close();
    exit();
}

// Memeriksa apakah email_user tersedia di tabel users
$stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
$stmt->bind_param("s", $email_user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo json_encode(array("status" => "error", "message" => "Email user tidak ditemukan"));
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// Memeriksa apakah id_event tersedia di tabel kegiatan
$stmt = $conn->prepare("SELECT name_event, date, start_time, end_time FROM kegiatan WHERE id_event = ?");
$stmt->bind_param("i", $id_event);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo json_encode(array("status" => "error", "message" => "ID event tidak ditemukan"));
    $stmt->close();
    $conn->close();
    exit();
}

$stmt->bind_result($name_event, $date, $start_time, $end_time);
$stmt->fetch();
$stmt->close();

// Memeriksa apakah email_user sudah memiliki kehadiran dalam id_event tertentu
$stmt = $conn->prepare("SELECT id FROM attendance WHERE email_user = ? AND id_event = ?");
$stmt->bind_param("si", $email_user, $id_event);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(array("status" => "error", "message" => "Email user sudah memiliki kehadiran dalam ID event ini"));
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// Konversi string date, start_time, and end_time to timestamps
$event_start_timestamp = strtotime("$date $start_time");
$event_end_timestamp = strtotime("$date $end_time");
$current_timestamp = time(); 
// $berlin_timestamp = time(); 

// $jakarta_offset = 5 * 3600; 

// $current_timestamp = $berlin_timestamp + $jakarta_offset;

// Cek apakah waktu saat ini ada dalam rentan waktu acara
if ($current_timestamp >= $event_start_timestamp && $current_timestamp <= $event_end_timestamp) {
    // Menyisipkan data ke tabel attendance
    $stmt = $conn->prepare("INSERT INTO attendance (id_event, name_event, email_user) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_event, $name_event, $email_user);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Data kehadiran berhasil disimpan"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Terjadi kesalahan, silakan coba lagi"));
    }

    $stmt->close();
} else {
    // Mengembalikan pesan error
    echo json_encode(array("status" => "error", "message" => "Acara sedang tidak berlangsung"));
}

$conn->close();
?>
