<?php
include 'connection.php';

// Ambil reservation_id dari parameter GET
$reservation_id = isset($_GET['reservation_id']) ? intval($_GET['reservation_id']) : 2;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debugging: Cek nilai yang dikirim dari form
    echo "Specialization value from form: " . $_POST['specialization'];

    // Validasi input
    if (isset($_POST['specialization']) && is_numeric($_POST['specialization'])) {
        $selected_metode_pembayaran = intval($_POST['specialization']);

        // Update metode pembayaran di tabel reservasi
        $update_sql = "UPDATE `reservasi` SET `Pembayaran_ID_metode` = ? WHERE `ID_reservasi` = ?";

        // Prepare statement
        $stmt = $conn->prepare($update_sql);
        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("ii", $selected_metode_pembayaran, $reservation_id);

            // Execute statement
            if ($stmt->execute()) {
                echo "Metode pembayaran berhasil diperbarui.";

                // Commit transaksi
                $conn->commit();
            } else {
                echo "Error updating record: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Invalid input specialization.";
    }
}

$conn->close();
?>