<?php
// get_doctors.php

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['specialization'])) {
    $selected_specialization = $_POST['specialization'];

    // Query database to get doctors based on specialization
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mbd";

    // Establish database connection using mysqli
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute query
    $query = "CALL GetDoctorsBySpecialization(?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selected_specialization);
    $stmt->execute();
    $result = $stmt->get_result();

    $doctors = array();
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }

    $stmt->close();
    $conn->close();

    // Return doctors data as JSON
    header('Content-Type: application/json');
    echo json_encode($doctors);
    exit;
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(array('message' => 'Invalid request'));
    exit;
}
?>
