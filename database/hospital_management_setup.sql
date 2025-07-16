-- =============================================================================
-- HOSPITAL MANAGEMENT SYSTEM DATABASE SETUP
-- =============================================================================
-- Database: hospital_management_db
-- Version: 1.0
-- Created: July 16, 2025
-- Description: Complete database setup for Hospital Management System
-- =============================================================================

-- Create database
CREATE DATABASE IF NOT EXISTS hospital_management_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Use the database
USE hospital_management_db;

-- =============================================================================
-- 1. USERS TABLE
-- =============================================================================
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =============================================================================
-- 2. DOCTORS TABLE
-- =============================================================================
CREATE TABLE doctors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    birth_date DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    address TEXT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =============================================================================
-- 3. PATIENTS TABLE
-- =============================================================================
CREATE TABLE patients (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    patient_number VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    birth_date DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    blood_type VARCHAR(5) NULL,
    allergies TEXT NULL,
    medical_history TEXT NULL,
    emergency_contact_name VARCHAR(255) NOT NULL,
    emergency_contact_phone VARCHAR(20) NOT NULL,
    insurance_number VARCHAR(100) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =============================================================================
-- 4. SCHEDULES TABLE
-- =============================================================================
CREATE TABLE schedules (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    doctor_id BIGINT UNSIGNED NOT NULL,
    day_of_week ENUM('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- =============================================================================
-- 5. APPOINTMENTS TABLE
-- =============================================================================
CREATE TABLE appointments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id BIGINT UNSIGNED NOT NULL,
    doctor_id BIGINT UNSIGNED NOT NULL,
    schedule_id BIGINT UNSIGNED NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('scheduled', 'confirmed', 'completed', 'cancelled') DEFAULT 'scheduled',
    symptoms TEXT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE
);

-- =============================================================================
-- 6. PATIENT_REQUESTS TABLE
-- =============================================================================
CREATE TABLE patient_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    birth_date DATE NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    blood_type VARCHAR(5) NULL,
    emergency_contact_name VARCHAR(255) NOT NULL,
    emergency_contact_phone VARCHAR(20) NOT NULL,
    insurance_number VARCHAR(100) NULL,
    medical_history TEXT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- =============================================================================
-- 7. APPOINTMENT_REQUESTS TABLE
-- =============================================================================
CREATE TABLE appointment_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    doctor_id BIGINT UNSIGNED NOT NULL,
    schedule_id BIGINT UNSIGNED NOT NULL,
    appointment_id BIGINT UNSIGNED NULL,
    patient_name VARCHAR(255) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    symptoms TEXT NULL,
    request_type ENUM('create', 'edit') NOT NULL,
    requested_changes JSON NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- =============================================================================
-- 8. PATIENT_EDIT_REQUESTS TABLE
-- =============================================================================
CREATE TABLE patient_edit_requests (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    patient_id BIGINT UNSIGNED NOT NULL,
    requested_changes JSON NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- =============================================================================
-- 9. ADDITIONAL TABLES FOR LARAVEL
-- =============================================================================

-- Cache table
CREATE TABLE cache (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
);

CREATE TABLE cache_locks (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
);

-- Jobs table (for queues)
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX jobs_queue_index (queue)
);

CREATE TABLE job_batches (
    id VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sessions table
CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    PRIMARY KEY (id),
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
);

-- =============================================================================
-- 10. SAMPLE DATA INSERTION
-- =============================================================================

-- Insert Admin User
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin Hospital', 'admin@hospital.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('User Test', 'user@hospital.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NOW(), NOW());

-- Insert Sample Doctors
INSERT INTO doctors (name, email, phone, specialty, birth_date, gender, address, created_at, updated_at) VALUES
('Dr. Ahmad Hidayat', 'ahmad.hidayat@hospital.com', '08123456789', 'Cardiology', '1975-05-15', 'male', 'Jl. Sudirman No. 123, Jakarta', NOW(), NOW()),
('Dr. Siti Nurhaliza', 'siti.nurhaliza@hospital.com', '08123456790', 'Pediatrics', '1980-08-20', 'female', 'Jl. Thamrin No. 456, Jakarta', NOW(), NOW()),
('Dr. Budi Santoso', 'budi.santoso@hospital.com', '08123456791', 'Orthopedics', '1978-12-10', 'male', 'Jl. Gatot Subroto No. 789, Jakarta', NOW(), NOW()),
('Dr. Rina Wijaya', 'rina.wijaya@hospital.com', '08123456792', 'Dermatology', '1982-03-25', 'female', 'Jl. Kuningan No. 101, Jakarta', NOW(), NOW()),
('Dr. Andi Saputra', 'andi.saputra@hospital.com', '08123456793', 'Internal Medicine', '1976-11-30', 'male', 'Jl. Menteng No. 202, Jakarta', NOW(), NOW());

-- Insert Sample Schedules
INSERT INTO schedules (doctor_id, day_of_week, start_time, end_time, created_at, updated_at) VALUES
(1, 'monday', '08:00:00', '12:00:00', NOW(), NOW()),
(1, 'wednesday', '08:00:00', '12:00:00', NOW(), NOW()),
(1, 'friday', '08:00:00', '12:00:00', NOW(), NOW()),
(2, 'tuesday', '09:00:00', '13:00:00', NOW(), NOW()),
(2, 'thursday', '09:00:00', '13:00:00', NOW(), NOW()),
(2, 'saturday', '08:00:00', '12:00:00', NOW(), NOW()),
(3, 'monday', '13:00:00', '17:00:00', NOW(), NOW()),
(3, 'wednesday', '13:00:00', '17:00:00', NOW(), NOW()),
(3, 'friday', '13:00:00', '17:00:00', NOW(), NOW()),
(4, 'tuesday', '14:00:00', '18:00:00', NOW(), NOW()),
(4, 'thursday', '14:00:00', '18:00:00', NOW(), NOW()),
(5, 'monday', '08:00:00', '16:00:00', NOW(), NOW()),
(5, 'friday', '08:00:00', '16:00:00', NOW(), NOW());

-- Insert Sample Patients
INSERT INTO patients (user_id, patient_number, name, email, phone, address, birth_date, gender, blood_type, emergency_contact_name, emergency_contact_phone, insurance_number, created_at, updated_at) VALUES
(2, 'P001', 'John Doe', 'john.doe@email.com', '08123456794', 'Jl. Kemang No. 123, Jakarta', '1990-01-15', 'male', 'A', 'Jane Doe', '08123456795', 'INS001', NOW(), NOW()),
(NULL, 'P002', 'Maria Santos', 'maria.santos@email.com', '08123456796', 'Jl. Pondok Indah No. 456, Jakarta', '1985-06-20', 'female', 'B', 'Carlos Santos', '08123456797', 'INS002', NOW(), NOW()),
(NULL, 'P003', 'David Wilson', 'david.wilson@email.com', '08123456798', 'Jl. Kelapa Gading No. 789, Jakarta', '1992-11-10', 'male', 'O', 'Sarah Wilson', '08123456799', 'INS003', NOW(), NOW()),
(NULL, 'P004', 'Lisa Johnson', 'lisa.johnson@email.com', '08123456800', 'Jl. Senayan No. 101, Jakarta', '1988-04-25', 'female', 'AB', 'Mike Johnson', '08123456801', 'INS004', NOW(), NOW()),
(NULL, 'P005', 'Robert Brown', 'robert.brown@email.com', '08123456802', 'Jl. Serpong No. 202, Tangerang', '1987-09-18', 'male', 'A', 'Emma Brown', '08123456803', 'INS005', NOW(), NOW());

-- Insert Sample Appointments
INSERT INTO appointments (patient_id, doctor_id, schedule_id, appointment_date, appointment_time, status, symptoms, created_at, updated_at) VALUES
(1, 1, 1, '2025-07-17', '08:30:00', 'scheduled', 'Chest pain and shortness of breath', NOW(), NOW()),
(2, 2, 4, '2025-07-18', '09:30:00', 'confirmed', 'Fever and cough in child', NOW(), NOW()),
(3, 3, 7, '2025-07-17', '14:00:00', 'scheduled', 'Knee pain after exercise', NOW(), NOW()),
(4, 4, 10, '2025-07-18', '15:00:00', 'scheduled', 'Skin rash and itching', NOW(), NOW()),
(5, 5, 12, '2025-07-17', '10:00:00', 'confirmed', 'Regular health checkup', NOW(), NOW());

-- =============================================================================
-- 11. INDEXES FOR PERFORMANCE
-- =============================================================================

-- Create indexes for better performance
CREATE INDEX idx_patients_user_id ON patients(user_id);
CREATE INDEX idx_patients_patient_number ON patients(patient_number);
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_status ON appointments(status);
CREATE INDEX idx_schedules_doctor_day ON schedules(doctor_id, day_of_week);
CREATE INDEX idx_patient_requests_user_id ON patient_requests(user_id);
CREATE INDEX idx_patient_requests_status ON patient_requests(status);
CREATE INDEX idx_appointment_requests_user_id ON appointment_requests(user_id);
CREATE INDEX idx_appointment_requests_status ON appointment_requests(status);

-- =============================================================================
-- 12. VIEWS FOR REPORTING
-- =============================================================================

-- View for appointments with full details
CREATE VIEW appointment_details AS
SELECT 
    a.id,
    a.appointment_date,
    a.appointment_time,
    a.status,
    a.symptoms,
    a.notes,
    p.name as patient_name,
    p.phone as patient_phone,
    d.name as doctor_name,
    d.specialty as doctor_specialty,
    s.day_of_week,
    s.start_time,
    s.end_time
FROM appointments a
JOIN patients p ON a.patient_id = p.id
JOIN doctors d ON a.doctor_id = d.id
JOIN schedules s ON a.schedule_id = s.id;

-- View for patient requests with user details
CREATE VIEW patient_request_details AS
SELECT 
    pr.id,
    pr.name,
    pr.email,
    pr.phone,
    pr.status,
    pr.created_at,
    pr.reviewed_at,
    pr.admin_notes,
    u.name as user_name,
    u.email as user_email,
    reviewer.name as reviewer_name
FROM patient_requests pr
JOIN users u ON pr.user_id = u.id
LEFT JOIN users reviewer ON pr.reviewed_by = reviewer.id;

-- =============================================================================
-- DATABASE SETUP COMPLETE
-- =============================================================================

-- Show database information
SELECT 'Database setup completed successfully!' as message;
SELECT DATABASE() as current_database;
SELECT COUNT(*) as total_tables FROM information_schema.tables WHERE table_schema = DATABASE();

-- Show sample data counts
SELECT 'Sample data inserted:' as message;
SELECT COUNT(*) as users_count FROM users;
SELECT COUNT(*) as doctors_count FROM doctors;
SELECT COUNT(*) as patients_count FROM patients;
SELECT COUNT(*) as schedules_count FROM schedules;
SELECT COUNT(*) as appointments_count FROM appointments;
