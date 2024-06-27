<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbd";

if (!isset($_SESSION['ID_pasien'])) {
    header("Location: pasien.php");
    exit();
}

$id_pasien = $_SESSION['ID_pasien'];
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_doctor = $conn->real_escape_string($_POST['doctor']);
    $date = $conn->real_escape_string($_POST['date']);
    $slot = $conn->real_escape_string($_POST['slot']);
    $reason = $conn->real_escape_string($_POST['reason']);

    $query_doctor = "SELECT ID_dokter FROM Dokter WHERE nama_dokter = ?";
    $stmt_doctor = $conn->prepare($query_doctor);
    $stmt_doctor->bind_param("s", $selected_doctor);
    $stmt_doctor->execute();
    $result_doctor = $stmt_doctor->get_result();

    if ($result_doctor->num_rows > 0) {
        $row = $result_doctor->fetch_assoc();
        $doctor_id = $row['ID_dokter'];
        $hari_reservasi = date('l', strtotime($date));
        $jadwal_reservasi = $date . ' ' . $slot;

        $query_insert = "INSERT INTO Reservasi (waktu_reservasi, hari_reservasi, jadwal_reservasi, keluhan_reservasi, Dokter_ID_dokter, Pasien_ID_pasien, Status_pembayaran)
                         VALUES (CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, false)";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param("sssii", $hari_reservasi, $jadwal_reservasi, $reason, $doctor_id, $id_pasien);

        try {
            $stmt_insert->execute();
            $id_reservasi_baru = $conn->insert_id;
            header("Location: payment.php?ID_reservasi=" . $id_reservasi_baru);
            exit();
        } catch (mysqli_sql_exception $e) {
            $error_message = $e->getMessage();
        }
    }

    $stmt_doctor->close();
}

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
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway|Poppins" rel="stylesheet">
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

    <section id="appointment" class="appointment section-bg mt-4" style="padding-top: 80px;">
        <div class="container mt-2">
            <div class="section-title">
                <h2>Make an Appointment</h2>
                <p>Buat janji temu dokter jadi lebih mudah bersama MedHub! Pilih slot waktu yang sesuai dan isi detail identitas Anda secara online.</p>
                <p>Tidak perlu lagi antri panjang, nikmati kenyamanan dari mana saja!</p>
            </div>
            <div id="alertModal" class=" align-items-center justify-content-center <?= $error_message ? '' : 'hidden' ?>" style="display: inline-flex; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 9999;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p><?= htmlspecialchars($error_message); ?></p>
                <div class="text-center mt-4">
                    <button id="closeModal" class="btn btn-primary">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

            <form id="post-form" action="" method="POST" class="php-email-form mt-3">
                <div class="row">
                    <div class="col-md-6 form-group mt-4">
                        <select name="specialization" id="specialization" class="form-select">
                            <option value="">Pilih Spesialisasi</option>
                            <?php foreach ($specializations as $spec): ?>
                                <option value="<?php echo htmlspecialchars($spec); ?>">
                                    <?php echo htmlspecialchars($spec); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group mt-4">
                        <select name="doctor" id="doctor" class="form-select">
                            <option value="">Pilih Dokter</option>
                            <?php foreach ($doctors as $doctor): ?>
                                <option value="<?php echo htmlspecialchars($doctor['doctor_name']); ?>">
                                    <?php echo htmlspecialchars($doctor['doctor_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group mt-4">
                        <select name="schedule" id="schedule" class="form-select">
                            <option value="">Pilih Jadwal</option>
                            <?php foreach ($schedules as $schedule): ?>
                                <option value="<?php echo htmlspecialchars($schedule); ?>">
                                    <?php echo htmlspecialchars($schedule); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group mt-4">
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>
                    <div class="col-md-6 form-group mt-4">
                        <input type="time" class="form-control" name="slot" id="slot" required>
                    </div>
                    <div class="col-md-12 form-group mt-4">
                        <textarea class="form-control" name="reason" rows="4"
                            placeholder="Masukan alasan kunjungan"></textarea>
                    </div>
                    <div class="text-center mt-3"><button type="submit">Make an Appointment</button></div>
                </div>
            </form>
        </div>
    </section>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>

    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
document.getElementById('specialization').addEventListener('change', function() {
    var specialization = this.value;
    console.log('Selected specialization: ' + specialization);

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log('Response received: ' + xhr.responseText);
                var doctors = JSON.parse(xhr.responseText);
                populateDoctorsDropdown(doctors);
            } else {
                console.error('Request failed: ' + xhr.status);
            }
        }
    };

    xhr.open('POST', 'get_doctors.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('specialization=' + encodeURIComponent(specialization));
});
document.getElementById('specialization').addEventListener('change', function () {
            var specialization = this.value;
            console.log('Selected specialization: ' + specialization);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log('Response received: ' + xhr.responseText);
                        var doctors = JSON.parse(xhr.responseText);
                        populateDoctorsDropdown(doctors);
                    } else {
                        console.error('Request failed: ' + xhr.status);
                    }
                }
            };

            xhr.open('POST', 'get_doctors.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('specialization=' + encodeURIComponent(specialization));
        });

        function populateDoctorsDropdown(doctors) {
            var doctorDropdown = document.getElementById('doctor');
            doctorDropdown.innerHTML = '';

            var option = document.createElement('option');
            option.value = '';
            option.textContent = 'Pilih Dokter';
            doctorDropdown.appendChild(option);

            doctors.forEach(function (doctor) {
                var option = document.createElement('option');
                option.value = doctor.doctor_name;
                option.textContent = doctor.doctor_name;
                doctorDropdown.appendChild(option);
            });
        }

        document.getElementById('doctor').addEventListener('change', function () {
            var doctorName = this.value;
            console.log('Selected doctor name: ' + doctorName);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log('Response received: ' + xhr.responseText);
                        var schedules = JSON.parse(xhr.responseText);
                        populateSchedulesDropdown(schedules);
                    } else {
                        console.error('Request failed: ' + xhr.status);
                    }
                }
            };

            xhr.open('POST', 'get_schedules.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('doctor_name=' + encodeURIComponent(doctorName));
        });

        function populateSchedulesDropdown(schedules) {
            var scheduleDropdown = document.getElementById('schedule');
            scheduleDropdown.innerHTML = '';

            var option = document.createElement('option');
            option.value = '';
            option.textContent = 'Pilih Jadwal';
            scheduleDropdown.appendChild(option);

            schedules.forEach(function (schedule) {
                var option = document.createElement('option');
                option.value = schedule;
                option.textContent = schedule;
                scheduleDropdown.appendChild(option);
            });
        }
        document.addEventListener('DOMContentLoaded', (event) => {
            const modal = document.getElementById('alertModal');
            const closeModal = document.getElementById('closeModal');
            closeModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>

</body>
</html>

