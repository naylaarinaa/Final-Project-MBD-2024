<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbd";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_SESSION['email_user']) && isset($_SESSION['passwords'])) {
    $current_email_user = $_SESSION['email_user'];
    $current_password = $_SESSION['passwords'];
} else {
    echo '<div class="alert alert-danger" role="alert"> Anda belum login.</div>';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateButton'])) {
        $newemail_user = $_POST['newemail_user'];
        $new_password = $_POST['passwords'];

        // Check for duplicate email
        $checkDuplicateQuery = "SELECT * FROM Users WHERE email_user = ? AND email_user != ?";
        $stmt = $conn->prepare($checkDuplicateQuery);
        $stmt->bind_param("ss", $newemail_user, $current_email_user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo '<div class="alert alert-danger" role="alert"> Email sudah terdaftar.</div>';
        } else {
            $updateQuery = "UPDATE Users SET email_user = ?, passwords = ? WHERE email_user = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("sss", $newemail_user, $new_password, $current_email_user);

            if ($stmt->execute()) {
                $_SESSION['email_user'] = $newemail_user;
                $_SESSION['passwords'] = $new_password;
                header("Location: profile.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }

    if (isset($_POST['deleteButton'])) {
        $deleteQuery = "DELETE FROM Users WHERE email_user = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("s", $current_email_user);

        if ($stmt->execute()) {
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();
        } else {
            echo "Error saat menghapus akun: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();
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

  <!-- =======================================================
  * Template Name: MedHub
  * Template URL: https://bootstrapmade.com/MedHub-free-medical-bootstrap-theme/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
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
          <li><a class="nav-link scrollto active" href="index.php">Home</a></li>
          <li><a class="nav-link scrollto" href="my_appointment.php">My Appointment</a></li>
          <li><a class="nav-link scrollto" href="dokter.php">Doctor</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

      <a href="#appointment" class="appointment-btn scrollto"><span class="d-none d-md-inline">Make an</span> Appointment</a>
      <a href="profile.php" class="appointment-btn scrollto"><span class="d-none d-md-inline icon"><i class="fas fa-hospital-user"></i></a>
    </div>
  </header><!-- End Header -->

      <!-- ======= Appointment Section ======= -->
      <section id="appointment" class="appointment section-bg mt-4">
        <div class="container">
      
          <div class="section-title mt-4">
            <h2>Profil</h2>
          </div>
          <?php
        if (isset($_SESSION['email_user'])) {
      } else {
          echo '<div class="alert alert-danger" role="alert"> Anda belum login.</div>';
      }
          ?>
          <form id="appointment" action="" method="POST" class="php-email-form">
              <div class="mb-3">
                  <label for="newemail_user" class="form-label">Email</label>
                  <input type="email" class="form-control" id="newemail_user" name="newemail_user" value="<?php echo isset($current_email_user) ? $current_email_user : ''; ?>" required>
              </div>
              <div class="mb-3">
                  <label for="passwords" class="form-label">Kata Sandi</label>
                  <input type="password" class="form-control" id="passwords" name="passwords" value="<?php echo isset($current_password) ? $current_password : ''; ?>" required>
              </div>
              <div class="d-grid text-center" >
                  <button type="submit" class="btn btn-primary btn-block" name="updateButton" >Perbarui</button>
              </div>
          </form>

          <form id="delete-form" action="" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
              <input type="hidden" name="deleteButton">
              <div class="d-grid mt-3">
                  <button type="submit" class="btn btn-danger btn-block rounded-pill" id="deleteButton">Hapus Akun</button>
              </div>
          </form>

          <form id="logout-form" action="login.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar?');" style="display: flex; justify-content: center; margin-top: 20px;">
    <input type="hidden" name="logoutButton">
        <button type="submit" class="btn btn-primary btn-block rounded-pill" style="display: inline-flex; align-items: center; justify-content: center;">
            <i class="fas fa-sign-out-alt" style="font-size: 24px;"></i>   Keluar
        </button>
    </div>
</form>

        </div>
      </section>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
