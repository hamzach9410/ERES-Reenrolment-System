<?php
session_start();
include 'db.php'; // Updated path to db.php

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Updated path
    exit();
}

$student_id = $_SESSION['student_id'];

$conn->query("ALTER TABLE applications ADD COLUMN IF NOT EXISTS result_image VARCHAR(255) NOT NULL");

$query_last_serial = "SELECT MAX(serial_number) AS last_serial FROM applications";
$result_last_serial = $conn->query($query_last_serial);

if (!$result_last_serial) {
    die("Error fetching last serial: " . $conn->error);
}

$row_last_serial = $result_last_serial->fetch_assoc();
$new_serial_number = ($row_last_serial['last_serial'] !== null) ? $row_last_serial['last_serial'] + 1 : 1;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['selected_subjects'])) {
        echo "<script>alert('Please select at least one subject.'); window.history.back();</script>";
        exit();
    }

    $selected_subjects = $_POST['selected_subjects'];

    $upload_dir = "../uploads/"; // Updated path
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = $_FILES['result_image']['name'];
    $file_tmp = $_FILES['result_image']['tmp_name'];
    $file_size = $_FILES['result_image']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpeg', 'jpg', 'png'];
    $file_path = $upload_dir . uniqid() . "." . $file_ext;

    if (!in_array($file_ext, $allowed_ext)) {
        echo "<script>alert('Only JPEG and PNG images are allowed.'); window.history.back();</script>";
        exit();
    }

    if ($file_size > 2 * 1024 * 1024) {
        echo "<script>alert('File size must be less than 2MB.'); window.history.back();</script>";
        exit();
    }

    if (!move_uploaded_file($file_tmp, $file_path)) {
        echo "<script>alert('Error uploading result image.'); window.history.back();</script>";
        exit();
    }

    $check_application = "SELECT id FROM applications WHERE student_id = ?";
    $stmt = $conn->prepare($check_application);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $application_id = $row['id'];

        $update_application = "UPDATE applications SET result_image = ? WHERE id = ?";
        $stmt = $conn->prepare($update_application);
        $stmt->bind_param("si", $file_path, $application_id);
        $stmt->execute();
    } else {
        $insert_application = "INSERT INTO applications (student_id, serial_number, result_image, status) VALUES (?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($insert_application);
        $stmt->bind_param("iis", $student_id, $new_serial_number, $file_path);
        $stmt->execute();
        $application_id = $conn->insert_id;
    }

    $existing_subjects = [];
    $query_check = "SELECT subject_name FROM application_subjects WHERE application_id = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("i", $application_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    while ($row_check = $result_check->fetch_assoc()) {
        $existing_subjects[] = $row_check['subject_name'];
    }

    $sql_insert_subject = "INSERT INTO application_subjects (application_id, student_id, subject_name, semester) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert_subject);

    foreach ($selected_subjects as $subject_id) {
        $sql_fetch_subject = "SELECT subject_name, semester FROM subjects WHERE id = ?";
        $stmt_fetch = $conn->prepare($sql_fetch_subject);
        $stmt_fetch->bind_param("i", $subject_id);
        $stmt_fetch->execute();
        $subject_result = $stmt_fetch->get_result();

        if ($subject_result->num_rows > 0) {
            $subject_row = $subject_result->fetch_assoc();
            $subject_name = $subject_row['subject_name'];
            $semester = $subject_row['semester'];

            if (!in_array($subject_name, $existing_subjects)) {
                $stmt_insert->bind_param("iisi", $application_id, $student_id, $subject_name, $semester);
                $stmt_insert->execute();
            }
        }
    }

    echo "<script>alert('Application submitted successfully with Serial Number: $new_serial_number'); window.location.href='application_status.php';</script>"; // Updated path
    exit();
}
