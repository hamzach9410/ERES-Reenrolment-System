<?php
session_start();
include 'db.php';

if (!isset($_SESSION['advisor_id'])) {
    die("Access denied. Please log in.");
}

$advisor_id = $_SESSION['advisor_id'];
$application_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($application_id <= 0) {
    header("Location: advisor_dashboard.php?message=" . urlencode("Invalid application ID."));
    exit();
}

// Update application status to Rejected
$query_update = "UPDATE applications SET status = 'Rejected' WHERE id = ?";
$stmt_update = $conn->prepare($query_update);
$stmt_update->bind_param("i", $application_id);
if ($stmt_update->execute()) {
    // Fetch student ID and department for the application
    $query_details = "SELECT student_id, (SELECT department FROM students WHERE id = applications.student_id) AS department FROM applications WHERE id = ?";
    $stmt_details = $conn->prepare($query_details);
    $stmt_details->bind_param("i", $application_id);
    $stmt_details->execute();
    $result_details = $stmt_details->get_result();
    $details = $result_details->fetch_assoc();
    $stmt_details->close();

    if ($details) {
        $student_id = $details['student_id'];
        $department = $details['department'];
        $current_date = date('Y-m-d H:i:s');

        // Insert notification for student
        $student_message = "Your application (ID: $application_id) has been rejected on $current_date.";
        $stmt_notify_student = $conn->prepare("INSERT INTO notifications (user_id, user_type, message, created_at, is_read) VALUES (?, 'student', ?, NOW(), 0)");
        $stmt_notify_student->bind_param("is", $student_id, $student_message);
        $stmt_notify_student->execute();
        $stmt_notify_student->close();

        // Fetch the teacher for the student's department
        $teacher_query = "SELECT id FROM teachers WHERE department = ?";
        $teacher_stmt = $conn->prepare($teacher_query);
        $teacher_stmt->bind_param("s", $department);
        $teacher_stmt->execute();
        $teacher_result = $teacher_stmt->get_result();
        $teacher = $teacher_result->fetch_assoc();

        if ($teacher) {
            // Insert notification for teacher
            $teacher_id = $teacher['id'];
            $teacher_message = "Application with ID $application_id has been rejected. Assist the student to apply again correctly.";
            $stmt_notify_teacher = $conn->prepare("INSERT INTO notifications (user_id, user_type, message, created_at, is_read) VALUES (?, 'teacher', ?, NOW(), 0)");
            $stmt_notify_teacher->bind_param("is", $teacher_id, $teacher_message);
            $stmt_notify_teacher->execute();
            $stmt_notify_teacher->close();
        }

        $teacher_stmt->close();
    }

    $message = "Application rejected successfully!";
} else {
    $message = "Failed to reject application: " . $stmt_update->error;
}
$stmt_update->close();

header("Location: advisor_dashboard.php?message=" . urlencode($message));
exit();
