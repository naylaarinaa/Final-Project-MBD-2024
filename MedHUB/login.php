<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbd";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$login_failed = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_user = trim($_POST['email']);
    $passwords = trim($_POST['password']);

    if (filter_var($email_user, FILTER_VALIDATE_EMAIL) && !empty($passwords)) {
        // Prepare SQL statement to fetch user data
        $stmt = $conn->prepare("SELECT ID_user, passwords FROM Users WHERE email_user = ?");
        $stmt->bind_param("s", $email_user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Bind result variables
            $stmt->bind_result($user_id, $stored_password);

            // Fetch the user ID and password
            $stmt->fetch();

            // Verify password
            if ($passwords === $stored_password) {
                // Store user ID, email, and password in session
                $_SESSION['ID_user'] = $user_id;
                $_SESSION['email_user'] = $email_user;
                $_SESSION['passwords'] = $passwords;

                // Redirect to index.php upon successful login
                header("Location: index.php");
                exit();
            } else {
                $login_failed = true;
            }
        } else {
            $login_failed = true;
        }
        $stmt->close();
    } else {
        $login_failed = true;
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
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">
        <h1 class="logo me-auto"><a href="index.php">MedHub</a></h1>
        <a href="register.php" class="appointment-btn scrollto"><span class="d-none d-md-inline">Daftar</span></a>
    </div>
</header><!-- End Header -->

<section id="appointment" class="appointment section-bg mt-4">
    <div class="container">
        <div class="section-title mt-4">
            <h2>Masuk</h2>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && $login_failed) {
            echo '<div class="alert alert-danger" role="alert">Login gagal. Periksa kembali email dan password Anda.</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" role="form" class="php-email-form">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-md-4 form-group mt-3 mt-md-0">
                    <p>Email</p>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan Email" data-rule="email" data-msg="Please enter a valid email" required>
                    <div class="validate"></div>
                    <p>Kata Sandi</p>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan Kata Sandi" data-rule="minlen:4" data-msg="Please enter at least 4 chars" required>
                    <div class="validate"></div>
                </div>
            </div>
            <div class="mb-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your appointment request has been sent successfully. Thank you!</div>
            </div>
            <div class="text-center"><button type="submit">Masuk</button></div>
        </form>
    </div>
</section><!-- End Appointment Section -->

<div id="preloader"></div>
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
