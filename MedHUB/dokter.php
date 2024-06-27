<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbd";

// Establish database connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$doctor_count_by_specialization = 0; // Initialize doctor count by specialization

// Handle form submission to filter doctors based on selected specialization
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_specialization = $_POST['specialization'];

    // Call stored procedure to retrieve doctors based on selected specialization
    if (!empty($selected_specialization)) {
        $query = "CALL GetDoctorsBySpecialization(?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $selected_specialization);
        $stmt->execute();
        $result = $stmt->get_result();

        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        $stmt->close();
        
        // Call the function to get doctor count by specialization
        $query = "SELECT DoctorCountBySpecialization(?) AS doctor_count";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $selected_specialization);
        $stmt->execute();
        $stmt->bind_result($doctor_count_by_specialization);
        $stmt->fetch();
        $stmt->close();
    } else {
        // Call stored procedure to retrieve all doctors
        $query = "CALL GetAllDoctors()";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        $stmt->close();
    }
}

// Query to get list of specializations from the database
$query_specializations = "SELECT DISTINCT spesialisasi_dokter FROM Dokter";
$result_specializations = $conn->query($query_specializations);

$specializations = [];
if ($result_specializations) {
    while ($row = $result_specializations->fetch_assoc()) {
        $specializations[] = $row['spesialisasi_dokter'];
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
    <link href="assets/css/style_fairna.css" rel="stylesheet">

</head>

<body>

    <!-- ======= Top Bar ======= -->
    <div id="topbar" class="d-flex align-items-center fixed-top">
        <div class="container d-flex justify-content-between">
        </div>
    </div>

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

            <a href="pasien.php" class="appointment-btn scrollto"><span class="d-none d-md-inline">Make an</span>
                Appointment</a>
            <a href="profile.php" class="appointment-btn scrollto"><span class="d-none d-md-inline icon"><i
                        class="fas fa-hospital-user"></i></a>
        </div>
    </header><!-- End Header -->

    <!-- ======= Doctors Section ======= -->
    <section id="doctors" class="doctors mt-2">
        <div class="container">
            <form class="custom-form col-lg-6 mt-3 pt-2 mb-lg-0 mt-5" style="margin-left: 325px;" data-aos="zoom-in-up" id="search-form" role="search" method="POST">
                <div class="section-title mt-3">
                    <h2>Dokter</h2>
                </div>
                <h6 class="mt-3 text-center">Temukan dokter yang sesuai dengan kebutuhan Anda</h6>
                <div class="input-group mt-3">
                    <label class="input-group-text" for="specialization">Cari dokter spesialis:</label>
                    <select class="form-select" id="specialization" name="specialization">
                        <option selected>Pilih spesialisasi...</option>
                        <option value="">Semua Spesialisasi</option>
                        <?php foreach ($specializations as $spec) : ?>
                            <option value="<?php echo htmlspecialchars($spec); ?>"><?php echo htmlspecialchars($spec); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </form>
        </div>

        <div class="container mt-4">
            <p class="text-center"><?php echo $doctor_count_by_specialization; ?> pilihan dokter spesialis ditemukan</p>
        </div>

        <div class="row" id="doctors-list" >
            <?php if (!empty($doctors)) : ?>
                <?php foreach ($doctors as $doctor) : ?>
                    <div class="col-lg-6 mt-4">
                        <div class="member d-flex align-items-start">
                            <div class="pic">
                            <img src="assets\img\file.png" class="img-fluid" alt="Doctor Image">
                            </div>
                            <div class="member-info">
                                <h4><?php echo htmlspecialchars($doctor['doctor_name']); ?></h4>
                                <span><?php echo htmlspecialchars($doctor['specialization']); ?></span>
                                <div class="form-group mt-3" style="width: fit-content;">
                                    <select name="schedule" id="schedule" class="form-select form-select-sm" style="width: 150px;">
                                        <option value=""><?php echo htmlspecialchars($doctor['schedule']); ?></option>
                                    </select>
                                    <div class="validate"></div>
                                </div>
                                <a href="pasien.php" class="btn btn-primary mt-2">Reservasi</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-lg-12 mt-4">
                    <p class="text-center">Tidak ada dokter yang sesuai dengan pilihan Anda.</p>
                </div>
            <?php endif; ?>
        </div>
    </section><!-- End Doctors Section -->
    <!-- End Doctors Section --><!-- End Doctors Section -->
    <!-- End Doctors Section -->

   

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