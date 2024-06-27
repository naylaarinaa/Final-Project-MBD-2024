<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbd";

// Establish database connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$register_failed = false;

// Handle form submission for appointment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];

    // Retrieve user ID from session
    $user_id = $_SESSION['ID_user'];

    // Prepare and bind the SQL statement for inserting patient data
    $stmt = $conn->prepare("INSERT INTO Pasien (nama_pasien, tanggal_lahir_pasien, alamat_pasien, gender_pasien, umur_pasien, telepon_pasien, users_id_user) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisi", $name, $birthdate, $address, $gender, $age, $phone, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        $new_patient_id = $conn->insert_id;

        // Simpan ID pasien ke dalam session
        $_SESSION['ID_pasien'] = $new_patient_id;
        // Redirect to appointment.php upon successful insertion
        $stmt->close();
        $conn->close();
        header("Location: appointment.php");
        exit();
    } else {
        $register_failed = true;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>MedHub</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div id="topbar" class="d-flex align-items-center fixed-top">
        <div class="container d-flex justify-content-between"></div>
    </div>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto"><a href="index.php">MedHub</a></h1>
            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
                    <li><a class="nav-link scrollto" href="my_appointment.php">My Appointment</a></li>
                    <li><a class="nav-link scrollto" href="dokter.php">Doctor</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
            <a href="pasien.php" class="appointment-btn scrollto"><span class="d-none d-md-inline">Make an</span> Appointment</a>
            <a href="profile.php" class="appointment-btn scrollto"><span class="d-none d-md-inline icon"><i class="fas fa-hospital-user"></i></span></a>
        </div>
    </header><!-- End Header -->

    <section id="appointment" class="appointment section-bg mt-4" style="padding-top: 80px;">
        <div class="container mt-4">
            <div class="section-title">
                <h2>Make an Appointment</h2>
                <p>Masukkan data pasien dengan benar.</p>
            </div>

            <form id="post-form" action="" method="POST" class="php-email-form mt-4">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="row">
                            <div class="col-md-6 form-group mt-4">
                                <input type="text" name="name" class="form-control" id="name" placeholder="Masukan nama pasien" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required>
                                <div class="validate"></div>
                            </div>
                            <div class="col-md-6 form-group mt-4">
                                <input type="tel" class="form-control" name="phone" id="phone" placeholder="Masukan nomor telepon pasien" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required>
                                <div class="validate"></div>
                            </div>
                            <div class="col-md-6 form-group mt-4">
                                <input type="date" name="birthdate" class="form-control" id="birthdate" placeholder="Tanggal Lahir" required>
                            </div>
                            <div class="col-md-6 form-group mt-4">
                                <input type="number" class="form-control" name="age" id="age" placeholder="Masukan umur pasien" min="1" max="120" required>
                                <div class="validate"></div>
                            </div>
                            <div class="col-md-6 form-group mt-4">
                                <select name="gender" id="gender" class="form-select" required>
                                    <option value="">Pilih Gender</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <div class="validate"></div>
                            </div>
                            <div class="col-md-12 form-group mt-4">
                                <textarea class="form-control" name="address" rows="4" placeholder="Masukan alamat pasien" required></textarea>
                                <div class="validate"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3"><button type="submit">Submit</button></div>
            </form>
            <?php if ($register_failed): ?>
                <div class="alert alert-danger" role="alert">
                    Gagal mendaftarkan pasien. Silakan coba lagi.
                </div>
            <?php endif; ?>
        </div>
    </section>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>

    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>

