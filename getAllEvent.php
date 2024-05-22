<?php
header('Content-Type: application/json');

include 'koneksi.php';

// Query untuk membaca semua data kegiatan
$sql = "SELECT * FROM kegiatan";
$result = $conn->query($sql);

// Inisialisasi array untuk menyimpan data kegiatan
$activities = array();


// Loop melalui setiap baris hasil query
while ($row = $result->fetch_assoc()) {
    // Tambahkan data kegiatan ke dalam array
    $activity = array(
        "id_kegiatan" => $row["id_event"],
        "nama_kegiatan" => $row["name_event"],
        "tanggal" => $row["date"],
        "jam_mulai" => $row["start_time"],
        "jam_berakhir" => $row["end_time"]
    );
    array_push($activities, $activity);
}
// Mengembalikan data kegiatan dalam format JSON
echo json_encode($activities);


// Menutup koneksi database
$conn->close();
?>
