<?php
// Pastikan ada session_start() jika diperlukan

// Ambil data input dari POST
$p_doctor_name = $_POST['doctor_name'];

// Lakukan koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbd";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Panggil stored procedure untuk mendapatkan jadwal dokter berdasarkan nama dokter
$query = "CALL GetSchedulesByDoctorName(?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $p_doctor_name);

$stmt->execute();
$result = $stmt->get_result();

$schedules = [];
while ($row = $result->fetch_assoc()) {
    $schedule = $row['hari_jadwal'] . " " . $row['jam_mulai_jadwal'] . " - " . $row['jam_selesai_jadwal'];
    $schedules[] = $schedule;
}

$stmt->close();
$conn->close();

// Mengembalikan data jadwal dalam format JSON
echo json_encode($schedules);
?>
