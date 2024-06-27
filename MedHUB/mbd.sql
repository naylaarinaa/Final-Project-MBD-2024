



-- CREATE DATABASE mbd;
-- Tablmbe: Rumah_Sakit
CREATE TABLE Users (
    ID_user INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    email_user VARCHAR(255) NOT NULL,
    passwords VARCHAR(15) NOT NULL
);

-- Table: Dokter
CREATE TABLE Dokter (
    ID_dokter INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nama_dokter VARCHAR(255) NOT NULL,
    spesialisasi_dokter VARCHAR(255) NOT NULL,
    telepon_dokter VARCHAR(255) NOT NULL,
    harga_dokter FLOAT NOT NULL
);

-- Table: Jadwal_Dokter
CREATE TABLE Jadwal_Dokter (
    ID_jadwal INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    hari_jadwal VARCHAR(255) NOT NULL,
    jam_mulai_jadwal TIME NOT NULL,
    jam_selesai_jadwal TIME NOT NULL
);

CREATE TABLE Dokter_Jadwal_Dokter (
    Dokter_ID_dokter INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Jadwal_Dokter_ID_jadwal INT NOT NULL,
    CONSTRAINT Dokter_Jadwal_Dokter_fk0 FOREIGN KEY (Dokter_ID_dokter) REFERENCES Dokter(ID_dokter) ON DELETE CASCADE,
    CONSTRAINT Dokter_Jadwal_Dokter_fk2 FOREIGN KEY (Jadwal_Dokter_ID_jadwal) REFERENCES Jadwal_Dokter(ID_jadwal) ON DELETE CASCADE
);

-- Table: Pasien
CREATE TABLE Pasien (
    ID_pasien INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nama_pasien VARCHAR(255) NOT NULL,
    tanggal_lahir_pasien DATE NOT NULL,
    alamat_pasien VARCHAR(255) NOT NULL,
    gender_pasien VARCHAR(255) NOT NULL,
    umur_pasien INT NOT NULL,
    telepon_pasien VARCHAR(15) NOT NULL,
    users_id_user INT NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY (users_id_user) REFERENCES Users(ID_user) ON DELETE CASCADE
    
);

-- Table: Pembayaran
CREATE TABLE Pembayaran (
    ID_pembayaran INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    metode_pembayaran VARCHAR(255) NOT NULL
);

CREATE TABLE Reservasi (
    ID_reservasi INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    waktu_reservasi TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    hari_reservasi VARCHAR(20) NOT NULL,
    jadwal_reservasi DATETIME NOT NULL,
    keluhan_reservasi VARCHAR(255) NOT NULL,
    Dokter_ID_dokter INT NOT NULL,
    Pasien_ID_pasien INT NOT NULL,
    Status_pembayaran BOOLEAN,
    CONSTRAINT Reservasi_fk FOREIGN KEY (Dokter_ID_dokter) REFERENCES Dokter(ID_dokter) ON DELETE CASCADE,
    CONSTRAINT Reservasi_fk2 FOREIGN KEY (Pasien_ID_pasien) REFERENCES Pasien(ID_pasien) ON DELETE CASCADE
  
);	

CREATE TABLE payment_conf (
    id_conf INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    reservasi_id_reservasi INT NOT NULL,
    biaya_reservasi FLOAT,
    id_metode_pembayaran INT,
    CONSTRAINT payment_id_reservasi FOREIGN KEY (reservasi_ID_reservasi) REFERENCES reservasi(ID_reservasi) ON DELETE CASCADE
);

-- Menambahkan data ke tabel Dokter
INSERT INTO Dokter (nama_dokter, spesialisasi_dokter, telepon_dokter, harga_dokter)
VALUES
    ('Dr. Ananda', 'Bedah Umum', '081234567890', 500000),
    ('Dr. Budi', 'Dokter Gigi', '081234567891', 450000),
    ('Dr. Candra', 'Kandungan', '081234567892', 550000),
    ('Dr. Dharma', 'Bedah Umum', '081234567893', 600000),
    ('Dr. Eka', 'Anak', '081234567894', 400000),
    ('Dr. Fajar', 'Jantung', '081234567895', 700000),
    ('Dr. Gita', 'Mata', '081234567896', 450000),
    ('Dr. Hadi', 'THT', '081234567897', 550000),
    ('Dr. Indah', 'Kulit', '081234567898', 500000),
    ('Dr. Jaya', 'Bedah Plastik', '081234567899', 800000),
    ('Dr. Kresna', 'Bedah Syaraf', '081234567810', 900000),
    ('Dr. Lina', 'Psikiater', '081234567811', 750000),
    ('Dr. Mira', 'Bedah Tulang', '081234567812', 650000),
    ('Dr. Nanda', 'Mata', '081234567813', 450000),
    ('Dr. Oka', 'Anak', '081234567814', 400000),
    ('Dr. Prima', 'Umum', '081234567815', 350000),
    ('Dr. Qistina', 'Gigi', '081234567816', 450000),
    ('Dr. Rahma', 'Kandungan', '081234567817', 600000),
    ('Dr. Surya', 'Penyakit Dalam', '081234567818', 700000),
    ('Dr. Taufik', 'Anak', '081234567819', 500000),
    ('Dr. Usman', 'Jantung', '081234567820', 800000),
    ('Dr. Vina', 'Mata', '081234567821', 550000),
    ('Dr. Wahyu', 'THT', '081234567822', 600000),
    ('Dr. Xenia', 'Kulit', '081234567823', 500000),
    ('Dr. Yudha', 'Bedah Plastik', '081234567824', 850000),
    ('Dr. Zara', 'Bedah Syaraf', '081234567825', 950000),
    ('Dr. Akbar', 'Psikiater', '081234567826', 750000),
    ('Dr. Bella', 'Bedah Tulang', '081234567827', 650000),
    ('Dr. Cipto', 'Mata', '081234567828', 450000),
    ('Dr. Diana', 'Anak', '081234567829', 500000),
    ('Dr. Erwin', 'Umum', '081234567830', 350000),
    ('Dr. Farah', 'Gigi', '081234567831', 450000),
    ('Dr. Galuh', 'Kandungan', '081234567832', 600000),
    ('Dr. Hendra', 'Penyakit Dalam', '081234567833', 700000),
    ('Dr. Indra', 'Anak', '081234567834', 500000),
    ('Dr. JJoko', 'Jantung', '081234567835', 800000),
    ('Dr. Karina', 'Mata', '081234567836', 550000),
    ('Dr. Lukman', 'THT', '081234567837', 600000),
    ('Dr. Mila', 'Kulit', '081234567838', 500000),
    ('Dr. Naufal', 'Bedah Plastik', '081234567839', 850000),
    ('Dr. Opan', 'Bedah Syaraf', '081234567840', 950000),
    ('Dr. Putri', 'Psikiater', '081234567841', 750000),
    ('Dr. Qais', 'Bedah Tulang', '081234567842', 650000),
    ('Dr. Rafi', 'Mata', '081234567843', 450000),
    ('Dr. Shinta', 'Anak', '081234567844', 500000);

    
 INSERT INTO Jadwal_Dokter (hari_jadwal, jam_mulai_jadwal, jam_selesai_jadwal)
VALUES
    ('Senin', '08:00:00', '16:00:00'),
    ('Selasa', '08:00:00', '16:00:00'),
    ('Rabu', '08:00:00', '16:00:00'),
    ('Kamis', '08:00:00', '16:00:00'),
    ('Jumat', '08:00:00', '16:00:00'),
    ('Sabtu', '08:00:00', '12:00:00'),
    ('Minggu', '08:00:00', '12:00:00'),
    ('Senin', '09:00:00', '17:00:00'),
    ('Selasa', '09:00:00', '17:00:00'),
    ('Rabu', '09:00:00', '17:00:00'),
    ('Kamis', '09:00:00', '17:00:00'),
    ('Jumat', '09:00:00', '17:00:00'),
    ('Sabtu', '09:00:00', '13:00:00'),
    ('Minggu', '09:00:00', '13:00:00'),
    ('Senin', '10:00:00', '18:00:00');
    
INSERT INTO Users (email_user, passwords) VALUES
('alicewonderland@example.com', 'alice123'),
('bobsadino@example.com', 'bobSecure!'),
('gibran@example.com', 'Pass'),
('Jokowigoddess@example.com', 'dave2024'),
('nayla@example.com', 'nay2023');

-- Menambahkan data ke tabel Pasien
INSERT INTO Pasien (nama_pasien, tanggal_lahir_pasien, alamat_pasien, gender_pasien, umur_pasien, telepon_pasien, users_id_user)
VALUES
    ('Andi', '1990-01-01', 'Jl. Raya No. 1', 'Laki-laki', 32, '081234567800', 1),
    ('Budi', '1995-02-02', 'Jl. Baru No. 2', 'Laki-laki', 27, '081234567801', 2),
    ('Citra', '1988-03-03', 'Jl. Maju No. 3', 'Perempuan', 34, '081234567802', 3),
    ('Dewi', '1985-04-04', 'Jl. Damai No. 4', 'Perempuan', 37, '081234567803', 4),
    ('Eko', '1992-05-05', 'Jl. Indah No. 5', 'Laki-laki', 30, '081234567804', 5),
    ('Fita', '1998-06-06', 'Jl. Cemerlang No. 6', 'Perempuan', 24, '081234567805', 1),
    ('Galih', '1991-07-07', 'Jl. Bahagia No. 7', 'Laki-laki', 31, '081234567806', 2),
    ('Hana', '1994-08-08', 'Jl. Sentosa No. 8', 'Perempuan', 28, '081234567807', 3),
    ('Indra', '1989-09-09', 'Jl. Sejahtera No. 9', 'Laki-laki', 33, '081234567808', 4),
    ('Joko', '1996-10-10', 'Jl. Damai No. 10', 'Laki-laki', 26, '081234567809', 5),
    ('Karin', '1993-11-11', 'Jl. Suka No. 11', 'Perempuan', 29, '081234567810', 1),
    ('Lusi', '1987-12-12', 'Jl. Gembira No. 12', 'Perempuan', 35, '081234567811', 2),
    ('Miko', '1997-01-13', 'Jl. Berkat No. 13', 'Laki-laki', 25, '081234567812', 3),
    ('Nina', '1990-02-14', 'Jl. Riang No. 14', 'Perempuan', 32, '081234567813', 4),
    ('Oscar', '1986-03-15', 'Jl. Indah No. 15', 'Laki-laki', 36, '081234567814', 5);
    INSERT INTO Pembayaran (metode_pembayaran)
VALUES
    ('Transfer'),
    ('Kartu Kredit'),
    ('Shopeepay'),
    ('Gopay'),
    ('Asuransi');
INSERT INTO Dokter_Jadwal_Dokter (Dokter_ID_dokter, Jadwal_Dokter_ID_jadwal)
VALUES
    (1, 2),
    (2, 2),
    (3, 3),
    (4, 4),
    (5, 5),
    (6, 6),
    (7, 7),
    (8, 8),
    (9, 9),
    (10, 10),
    (11, 11),
    (12, 12),
    (13, 13),
    (14, 14),
    (15, 15),
    (16, 1),
    (17, 2),
    (18, 3),
    (19, 4),
    (20, 5),
    (21, 6),
    (22, 7),
    (23, 8),
    (24, 9);



-- Menambahkan data ke tabel Reservasi tanpa biaya_reservasi
INSERT INTO Reservasi (waktu_reservasi, hari_reservasi, jadwal_reservasi, keluhan_reservasi, Dokter_ID_dokter, Pasien_ID_pasien, Status_pembayaran)
VALUES
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-06 08:00:00'), '2024-05-06 08:00:00', 'Demam', 1, 1, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-07 08:00:00'), '2024-05-07 08:00:00', 'Sakit Gigi', 2, 2, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-08 08:00:00'), '2024-05-08 08:00:00', 'Kehamilan', 3, 3, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-09 08:00:00'), '2024-05-09 08:00:00', 'Patah Tulang', 4, 4, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-10 08:00:00'), '2024-05-10 08:00:00', 'Demam', 5, 5, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-11 08:00:00'), '2024-05-11 08:00:00', 'Sakit Jantung', 6, 6, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-12 08:00:00'), '2024-05-12 08:00:00', 'Mata Merah', 7, 7, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-13 09:00:00'), '2024-05-13 09:00:00', 'Telinga Berdengung', 8, 8, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-14 09:00:00'), '2024-05-14 09:00:00', 'Jerawat', 9, 9, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-15 09:00:00'), '2024-05-15 09:00:00', 'Bedah Plastik', 10, 10, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-16 09:00:00'), '2024-05-16 09:00:00', 'Saraf Terjepit', 11, 11, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-17 09:00:00'), '2024-05-17 09:00:00', 'Depresi', 12, 12, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-18 09:00:00'), '2024-05-18 09:00:00', 'Patah Tulang', 13, 13, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-19 09:00:00'), '2024-05-19 09:00:00', 'Mata Merah', 14, 14, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-20 10:00:00'), '2024-05-20 10:00:00', 'Demam', 15, 15, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-21 08:00:00'), '2024-05-21 08:00:00', 'Flu', 16, 1, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-22 08:00:00'), '2024-05-22 08:00:00', 'Sakit Gigi', 17, 2, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-23 08:00:00'), '2024-05-23 08:00:00', 'Kehamilan', 18, 3, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-24 08:00:00'), '2024-05-24 08:00:00', 'Gastritis', 19, 4, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-25 08:00:00'), '2024-05-25 08:00:00', 'Demam', 20, 5, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-26 08:00:00'), '2024-05-26 08:00:00', 'Nyeri Dada', 21, 6, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-27 08:00:00'), '2024-05-27 08:00:00', 'Gangguan Penglihatan', 22, 7, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-28 08:00:00'), '2024-05-28 08:00:00', 'Radang Tenggorokan', 23, 8, TRUE),
    (CURRENT_TIMESTAMP, DAYNAME('2024-05-29 08:00:00'), '2024-05-29 08:00:00', 'Alergi Kulit', 24, 9, TRUE);



-- Insert into payment_conf with corresponding Reservasi and Pembayaran
INSERT INTO payment_conf (reservasi_id_reservasi, id_metode_pembayaran) VALUES
    (1, 1),  -- Reservasi 1, Metode Pembayaran Transfer
    (2, 2),  -- Reservasi 2, Metode Pembayaran Kartu Kredit
    (3, 3),  -- Reservasi 3, Metode Pembayaran Shopeepay
    (4, 4),  -- Reservasi 4, Metode Pembayaran Gopay
    (5, 5),  -- Reservasi 5, Metode Pembayaran Asuransi
    (6, 1),  -- Reservasi 6, Metode Pembayaran Transfer
    (7, 2),  -- Reservasi 7, Metode Pembayaran Kartu Kredit
    (8, 3),  -- Reservasi 8, Metode Pembayaran Shopeepay
    (9, 4),  -- Reservasi 9, Metode Pembayaran Gopay
    (10, 5), -- Reservasi 10, Metode Pembayaran Asuransi
    (11, 1), -- Reservasi 11, Metode Pembayaran Transfer
    (12, 2), -- Reservasi 12, Metode Pembayaran Kartu Kredit
    (13, 3), -- Reservasi 13, Metode Pembayaran Shopeepay
    (14, 4), -- Reservasi 14, Metode Pembayaran Gopay
    (15, 5), -- Reservasi 15, Metode Pembayaran Asuransi
    (16, 1), -- Reservasi 16, Metode Pembayaran Transfer
    (17, 2), -- Reservasi 17, Metode Pembayaran Kartu Kredit
    (18, 3), -- Reservasi 18, Metode Pembayaran Shopeepay
    (19, 4), -- Reservasi 19, Metode Pembayaran Gopay
    (20, 5), -- Reservasi 20, Metode Pembayaran Asuransi
    (21, 1), -- Reservasi 21, Metode Pembayaran Transfer
    (22, 2), -- Reservasi 22, Metode Pembayaran Kartu Kredit
    (23, 3), -- Reservasi 23, Metode Pembayaran Shopeepay
    (24, 4); -- Reservasi 24, Metode Pembayaran Gopay

-- Update biaya_reservasi in payment_conf based on dokter's price
UPDATE payment_conf pc
SET biaya_reservasi = (
    SELECT d.harga_dokter * 1.1
    FROM reservasi r
    INNER JOIN dokter d ON r.Dokter_ID_dokter = d.id_dokter
    WHERE r.id_reservasi = pc.reservasi_id_reservasi
);

-- PROCEDURE -- 

DELIMITER //

CREATE PROCEDURE GetSchedulesByDoctor(IN doctor_id INT)
BEGIN
    SELECT jadwal_dokter.hari_jadwal, jadwal_dokter.jam_mulai_jadwal, jadwal_dokter.jam_selesai_jadwal
    FROM jadwal_dokter
    JOIN dokter_jadwal_dokter ON jadwal_dokter.ID_jadwal = dokter_jadwal_dokter.Jadwal_Dokter_ID_jadwal
    WHERE dokter_jadwal_dokter.Dokter_ID_dokter = doctor_id;
END //

DELIMITER ;

DELIMITER //
CREATE PROCEDURE GetDoctorIdByName(IN doctor_name VARCHAR(255), OUT doctor_id INT)
BEGIN
    SELECT ID_dokter INTO doctor_id FROM Dokter WHERE nama_dokter = doctor_name;
END //
DELIMITER ;


DELIMITER $$
DELIMITER //

CREATE PROCEDURE GetDoctorsBySpecialization
    (IN selected_specialization VARCHAR(100))
BEGIN
    SELECT 
        d.nama_dokter AS doctor_name, 
        d.spesialisasi_dokter AS specialization,
        CONCAT(jd.hari_jadwal, ' ', 
               TIME_FORMAT(jd.jam_mulai_jadwal, '%H:%i'), ' - ', 
               TIME_FORMAT(jd.jam_selesai_jadwal, '%H:%i')) AS schedule
    FROM 
        Dokter d
    JOIN 
        Dokter_Jadwal_Dokter djd ON d.ID_dokter = djd.Dokter_ID_dokter
    JOIN 
        Jadwal_Dokter jd ON djd.Jadwal_Dokter_ID_jadwal = jd.ID_jadwal
    WHERE 
        d.spesialisasi_dokter = selected_specialization
    ORDER BY 
        d.nama_dokter, jd.hari_jadwal, TIME_FORMAT(jd.jam_mulai_jadwal, '%H:%i');
END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE GetAllDoctors()
BEGIN
    SELECT 
        d.nama_dokter AS doctor_name, 
        d.spesialisasi_dokter AS specialization,
        CONCAT(jd.hari_jadwal, ' ', 
               TIME_FORMAT(jd.jam_mulai_jadwal, '%H:%i'), ' - ', 
               TIME_FORMAT(jd.jam_selesai_jadwal, '%H:%i')) AS schedule
    FROM 
        Dokter d
    JOIN 
        Dokter_Jadwal_Dokter djd ON d.ID_dokter = djd.Dokter_ID_dokter
    JOIN 
        Jadwal_Dokter jd ON djd.Jadwal_Dokter_ID_jadwal = jd.ID_jadwal
    ORDER BY 
        d.nama_dokter, jd.hari_jadwal, TIME_FORMAT(jd.jam_mulai_jadwal, '%H:%i');
END //

DELIMITER ;


DELIMITER $$

CREATE PROCEDURE GetSchedulesByDoctorName (
    IN p_doctor_name VARCHAR(100)
)
BEGIN
    SELECT j.hari_jadwal, j.jam_mulai_jadwal, j.jam_selesai_jadwal
    FROM Jadwal_Dokter j
    JOIN Dokter_Jadwal_Dokter dj ON j.ID_jadwal = dj.Jadwal_Dokter_ID_jadwal
    JOIN Dokter d ON dj.Dokter_ID_dokter = d.ID_dokter
    WHERE d.nama_dokter = p_doctor_name;
END$$

DELIMITER ;

-- function
DELIMITER //

CREATE FUNCTION DoctorCountBySpecialization(specialization_name VARCHAR(255)) RETURNS INT
BEGIN
    DECLARE doctor_count INT;
    
    SELECT COUNT(DISTINCT djd.Dokter_ID_dokter) INTO doctor_count
    FROM Dokter_Jadwal_Dokter djd
    JOIN Dokter d ON djd.Dokter_ID_dokter = d.ID_dokter
    WHERE d.spesialisasi_dokter = specialization_name;
    
    RETURN doctor_count;
END //

DELIMITER ;

DELIMITER //

CREATE FUNCTION calculate_total_cost(harga_dokter DECIMAL(10,2))
RETURNS DECIMAL(10,2)
BEGIN
    DECLARE total DECIMAL(10,2);
    SET total = harga_dokter * 1.10;
    RETURN total;
END //

DELIMITER ;


DELIMITER $$

CREATE TRIGGER check_slot_availability BEFORE INSERT ON Reservasi
FOR EACH ROW
BEGIN
    DECLARE slot_count INT;

    -- Check if there is already an appointment within 30 minutes before or after
    SELECT COUNT(*) INTO slot_count
    FROM Reservasi
    WHERE Dokter_ID_dokter = NEW.Dokter_ID_dokter
      AND (
          (jadwal_reservasi BETWEEN DATE_SUB(NEW.jadwal_reservasi, INTERVAL 30 MINUTE) AND NEW.jadwal_reservasi)
          OR
          (jadwal_reservasi BETWEEN NEW.jadwal_reservasi AND DATE_ADD(NEW.jadwal_reservasi, INTERVAL 30 MINUTE))
      );

    IF slot_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jadwal sudah terisi, Pilih jadwal lain!';
    END IF;
END$$

DELIMITER ;



DELIMITER //
CREATE TRIGGER update_status_pembayaran
AFTER INSERT ON Payment_conf
FOR EACH ROW
BEGIN
    UPDATE Reservasi
    SET Status_pembayaran = 1
    WHERE id_reservasi = NEW.reservasi_id_reservasi;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER validate_reservation_time
BEFORE INSERT ON Reservasi
FOR EACH ROW
BEGIN
    DECLARE start_time TIME;
    DECLARE end_time TIME;
    
    -- Get the doctor's working hours for the specified doctor
    SELECT jd.jam_mulai_jadwal, jd.jam_selesai_jadwal INTO start_time, end_time
    FROM Jadwal_Dokter jd
    JOIN Dokter_Jadwal_Dokter djd ON jd.ID_jadwal = djd.Jadwal_Dokter_ID_jadwal
    WHERE djd.Dokter_ID_dokter = NEW.Dokter_ID_dokter;

    -- Check if the reservation time is within working hours
    IF start_time IS NULL OR end_time IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Waktu yang dipilih tidak tersedia!';
    ELSEIF TIME(NEW.jadwal_reservasi) < start_time OR TIME(NEW.jadwal_reservasi) > end_time THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Waktu yang dipilih di luar jam praktik dokter!';
    END IF;
END //

DELIMITER ;


CREATE VIEW View_Appointment_History AS
SELECT 
    r.ID_reservasi, r.waktu_reservasi, r.jadwal_reservasi, r.keluhan_reservasi, py.biaya_reservasi,
    p.nama_pasien, 
    d.nama_dokter, d.spesialisasi_dokter, u.email_user, jb.metode_pembayaran
FROM 
    Reservasi r
JOIN 
    Pasien p ON r.Pasien_ID_pasien = p.ID_pasien
JOIN 
    Dokter d ON r.Dokter_ID_dokter = d.ID_dokter
JOIN
    Users u ON p.Users_ID_user = u.ID_user
JOIN 
    payment_conf py ON py.reservasi_id_reservasi = r.ID_reservasi
JOIN 
    Pembayaran jb ON py.id_metode_pembayaran = jb.ID_pembayaran
ORDER BY 
    r.ID_reservasi DESC;


