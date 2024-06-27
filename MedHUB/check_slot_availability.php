<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbd";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$doctor = $_GET['doctor'];
$date = $_GET['date'];
$slot = $_GET['slot'];

// Get doctor ID from selected doctor name
$query_doctor_id = "SELECT ID_dokter FROM Dokter WHERE nama_dokter = ?";
$stmt_doctor_id = $conn->prepare($query_doctor_id);
$stmt_doctor_id->bind_param("s", $doctor);
$stmt_doctor_id->execute();
$result_doctor_id = $stmt_doctor_id->get_result();

if ($result_doctor_id->num_rows > 0) {
    $row = $result_doctor_id->fetch_assoc();
    $doctor_id = $row['ID_dokter'];

    // Combine date and slot for jadwal_reservasi
    $jadwal_reservasi = $date . ' ' . $slot;

    // Attempt to insert a dummy appointment to check availability
    $query_insert = "INSERT INTO Reservasi (Dokter_ID_dokter, jadwal_reservasi, Pasien_ID_pasien) VALUES (?, ?, 0)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("iss", $doctor_id, $jadwal_reservasi, $jadwal_reservasi);

    try {
        $stmt_insert->execute();
        // If insertion is successful, delete the dummy appointment
        $stmt_delete = $conn->prepare("DELETE FROM Reservasi WHERE Dokter_ID_dokter = ? AND jadwal_reservasi = ? AND Pasien_ID_pasien = 0");
        $stmt_delete->bind_param("is", $doctor_id, $jadwal_reservasi);
        $stmt_delete->execute();
        echo json_encode(['available' => true]);
    } catch (mysqli_sql_exception $e) {
        // Handle the error raised by the trigger
        echo json_encode(['available' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['available' => false, 'error' => 'Doctor not found']);
}

$stmt_doctor_id->close();
$conn->close();
?>
