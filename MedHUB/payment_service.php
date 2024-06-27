<?php
// Sertakan file koneksi
include 'connection.php';

// Ambil data dari form
$ID_reservasi = $_POST['ID_reservasi'] ?? null;
$id_metode_pembayaran = $_POST['id_metode_pembayaran'] ?? null;

if (!$ID_reservasi || !$id_metode_pembayaran) {
    die("ID_reservasi atau metode pembayaran tidak diberikan.");
}

// Query untuk mengambil harga_dokter dari tabel dokter berdasarkan ID_reservasi
$sql = "SELECT d.harga_dokter 
        FROM dokter d
        JOIN reservasi r ON d.ID_dokter = r.Dokter_ID_dokter
        WHERE r.ID_reservasi = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ID_reservasi);
$stmt->execute();
$stmt->bind_result($harga_dokter);
$stmt->fetch();
$stmt->close();

if (!$harga_dokter) {
    die("Harga dokter tidak ditemukan untuk ID_reservasi: $ID_reservasi");
}

// Query untuk memanggil fungsi SQL calculate_total_cost
$sql_total_cost = "SELECT calculate_total_cost(?) AS total_cost";
$stmt_total_cost = $conn->prepare($sql_total_cost);
$stmt_total_cost->bind_param("d", $harga_dokter);
$stmt_total_cost->execute();
$stmt_total_cost->bind_result($total_cost);
$stmt_total_cost->fetch();
$stmt_total_cost->close();

if (!$total_cost) {
    die("Gagal menghitung total biaya.");
}

// Query untuk memasukkan data ke tabel payment_conf
$sql_insert = "INSERT INTO payment_conf (reservasi_id_reservasi, biaya_reservasi, id_metode_pembayaran) VALUES (?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("idi", $ID_reservasi, $total_cost, $id_metode_pembayaran);

if ($stmt_insert->execute()) {
    echo "Data berhasil dimasukkan ke payment_conf.";
} else {
    echo "Gagal memasukkan data: " . $stmt_insert->error;
}

$stmt_insert->close();
$conn->close();
header("location:my_appointment.php?ID_reservasi=$ID_reservasi");
?>
