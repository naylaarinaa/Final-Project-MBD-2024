<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbd";

// Establish database connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Get doctor's schedule
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doctor_name'])) {
    $doctor_name = $_POST['doctor_name'];

    $query = "SELECT hari_jadwal, jam_mulai_jadwal, jam_selesai_jadwal 
              FROM Jadwal_Dokter 
              WHERE ID_jadwal IN (
                  SELECT Jadwal_Dokter_ID_jadwal 
                  FROM Dokter_Jadwal_Dokter 
                  WHERE Dokter_ID_dokter = (
                      SELECT ID_dokter 
                      FROM Dokter 
                      WHERE nama_dokter = ?)
              )";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $doctor_name);
    $stmt->execute();
    $result = $stmt->get_result();

    $slots = [];
    while ($row = $result->fetch_assoc()) {
        $start_time = new DateTime($row['jam_mulai_jadwal']);
        $end_time = new DateTime($row['jam_selesai_jadwal']);
        $interval = new DateInterval('PT30M'); // 30 minutes interval

        // Generate time slots between start_time and end_time
        for ($time = clone $start_time; $time < $end_time; $time->add($interval)) {
            $slots[] = [
                'hari_jadwal' => $row['hari_jadwal'],
                'slot' => $time->format('H:i')
            ];
        }
    }

    $stmt->close();
    echo json_encode($slots);
}

$conn->close();
?>
