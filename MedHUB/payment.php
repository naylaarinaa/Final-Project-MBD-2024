<?php
// Sertakan file koneksi
include 'connection.php';

// Ambil ID_reservasi dari parameter URL atau request body
$ID_reservasi = $_GET['ID_reservasi'] ?? null;

if (!$ID_reservasi) {
    die("ID_reservasi tidak diberikan.");
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

$reservasi_id_reservasi = $ID_reservasi;
$id_metode_pembayaran = $_POST['id_metode_pembayaran'] ?? null; // Sesuaikan dengan form input

// Ambil metode pembayaran dari tabel pembayaran
$sql_pembayaran = "SELECT ID_pembayaran, metode_pembayaran FROM pembayaran";
$result_pembayaran = $conn->query($sql_pembayaran);
$metode_pembayaran_options = [];

if ($result_pembayaran->num_rows > 0) {
    while ($row = $result_pembayaran->fetch_assoc()) {
        $metode_pembayaran_options[] = $row;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>MedHub</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link href="assets/css/style_fairna.css" rel="stylesheet">
</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

      <h1 class="logo me-auto"><a href="index.php">MedHub</a></h1>
      <!-- Uncomment below if you prefer to use an image logo -->
      <!-- <a href="index.php" class="logo me-auto"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="my_appointment.php">My Appointment</a></li>
          <li><a class="nav-link scrollto" href="dokter.php">Doctor</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

      <a href="pasien.php" class="appointment-btn scrollto"><span class="d-none d-md-inline">Make an</span> Appointment</a>
      <a href="profile.php" class="appointment-btn scrollto"><span class="d-none d-md-inline icon"><i class="fas fa-hospital-user"></i></a>
    </div>
  </header><!-- End Header -->

    <section id="doctors" class="doctors mt-5">
        <div class="container">
            <form class="custom-form col-lg-6 mt-3 pt-2 mb-lg-0 mt-5" style="margin-left: 325px;" data-aos="zoom-in-up"
                id="search-form" role="search" method="POST" action="payment_service.php">
                <input type="hidden" name="ID_reservasi" value="<?= htmlspecialchars($ID_reservasi) ?>">
                <div class="section-title mt-3">
                    <h2>Payment</h2>
                    <p>ID Reservasi: <?= htmlspecialchars($ID_reservasi) ?></p>
                    <p>Harga Dokter: <?= htmlspecialchars($harga_dokter) ?></p>
                </div>

                <div class="section-title mt-3">
                    <p>Yang Harus Dibayarkan</p>
                    <h5><?php echo number_format($total_cost, 2); ?></h5>
                </div>
                <div class="input-group mt-3">
                    <label class="input-group-text" for="specialization">Metode Pembayaran</label>
                    <select class="form-select" name="id_metode_pembayaran" id="id_metode_pembayaran">
                        <?php foreach ($metode_pembayaran_options as $option): ?>
                            <option value="<?= htmlspecialchars($option['ID_pembayaran']) ?>">
                                <?= htmlspecialchars($option['metode_pembayaran']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>
    </section>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
</body>

</html>
<?php
$conn->close();
?>