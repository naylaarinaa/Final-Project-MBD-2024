<?php
session_start();
$host = 'localhost'; 
$username = 'root'; 
$password = ''; 
$database =  'mbd';

$koneksi = new mysqli($host, $username, $password, $database);

if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

$email_user = $_SESSION['email_user']; 

$query = "SELECT * FROM View_Appointment_History WHERE email_user = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $email_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $posts = $result->fetch_all(MYSQLI_ASSOC);
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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  
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

<!-- Testimonials Section -->
<section id="testimonials" class="testimonials mt-4">
  <div class="section-title mt-4">
  <div class="mt-4"><h2>My Appointment</h2></div>
            <p>Appointment History</p>
          </div>
    <div class="row">
      <div class="col-md-12 mt-3">
        <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">
            <?php if (!empty($posts)) : ?>
                <?php foreach ($posts as $post) : ?>
                <div class="swiper-slide">
                  <div class="testimonial-wrap">
                    <div class="testimonial-item">
                      <img src="assets/img/testimonials/note.jpg" class="testimonial-img icon" alt="">
                      <h3>APPOINTMENT TICKET</h3>
                      <h4>ID Reservasi: <?php echo htmlspecialchars($post['ID_reservasi']); ?></h4>
                      <h4>Dibuat: <?php echo htmlspecialchars($post['waktu_reservasi']); ?></h4>
                      <p>
                      <i class="bx"></i><br>
                      Nama Pasien&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo htmlspecialchars($post['nama_pasien']); ?> <br>
                      Jadwal Reservasi&nbsp;: <?php echo htmlspecialchars($post['jadwal_reservasi']); ?> <br>
                      Spesialis Tujuan&nbsp;: <?php echo htmlspecialchars($post['spesialisasi_dokter']); ?> <br>
                      Nama Dokter&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo htmlspecialchars($post['nama_dokter']); ?> <br>
                      Keluhan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo htmlspecialchars($post['keluhan_reservasi']); ?> <br>
                      <b>Biaya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp <?php echo htmlspecialchars($post['biaya_reservasi']); ?></b> <br>
                      <b>Metode Pembayaran: <?php echo htmlspecialchars($post['metode_pembayaran']); ?></b> <br> <!-- Added line for payment method -->
                      <i class="bx"></i>
                    </p>
                    </div>
                  </div>
                </div><!-- End testimonial item -->
                <?php endforeach; ?>
            <?php else : ?>
                <p>Tidak ada postingan yang tersedia.</p>
            <?php endif; ?>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
</section><!-- End Testimonials Section -->


  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
