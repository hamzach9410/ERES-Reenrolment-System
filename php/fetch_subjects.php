<?php
include 'db.php'; // Updated path to db.php

// Ensure the student is logged in
session_start();
if (!isset($_SESSION['student_id'])) {
    die("Error: Student not logged in.");
}

$student_id = $_SESSION['student_id'];

// Fetch student details (department & semester)
$sql_student = "SELECT department, semester FROM students WHERE id = ?";
$stmt_student = $conn->prepare($sql_student);
$stmt_student->bind_param("i", $student_id);
$stmt_student->execute();
$result_student = $stmt_student->get_result();

if ($result_student->num_rows == 0) {
    die("Error: Student not found.");
}

$student = $result_student->fetch_assoc();
$student_department = $student['department'];
$student_semester = $student['semester'];

// Determine semester parity (even or odd)
$parity_value = ($student_semester % 2 == 0) ? 0 : 1;

// Fetch subjects based on department & semester parity
$sql = "SELECT id, subject_name, semester 
        FROM subjects 
        WHERE department = ? 
        AND semester < ? 
        AND (semester % 2 = ?)
        ORDER BY semester ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $student_department, $student_semester, $parity_value);
$stmt->execute();
$result = $stmt->get_result();

// Display subjects
if ($result->num_rows > 0) {
    echo "<option value=''>Select Subjects</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>" . $row['subject_name'] . " (Semester " . $row['semester'] . ")</option>";
    }
} else {
    echo "<option value=''>No subjects available for your department and parity-based previous semesters.</option>";
}

// Close connections
$stmt->close();
$stmt_student->close();
$conn->close();
