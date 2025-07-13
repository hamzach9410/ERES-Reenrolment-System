<?php
session_start();
include 'db.php'; // Updated path to db.php

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Updated path
    exit();
}

$student_id = $_SESSION['student_id'];
// Check if the student has already submitted an application
$query_check = "SELECT COUNT(*) as app_count FROM applications WHERE student_id = ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param("i", $student_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row = $result_check->fetch_assoc();

if ($row['app_count'] > 0) {
    header("Location: student_dashboard.php?message=You have already submitted an application."); // Updated path
    exit();
} else if ($row['app_count'] == 0) {
    header("Location: dashboard.php?message=Please submit an application."); // Updated path
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serial_number = $_POST['serial_number'] ?? 'N/A';
    $date = date('Y-m-d');
    $result_image = $_POST['result_image'] ?? null;
    $status = 'Pending';
    $query_insert = "INSERT INTO applications (student_id, serial_number, date, result_image, status) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("issss", $student_id, $serial_number, $date, $result_image, $status);
    if ($stmt_insert->execute()) {
        $application_id = $conn->insert_id; // Get the last inserted application ID
        // Fetch student's department
        $student_dept_query = "SELECT department FROM users WHERE id = ? AND role = 'student'";
        $stmt_dept = $conn->prepare($student_dept_query);
        $stmt_dept->bind_param("i", $student_id);
        $stmt_dept->execute();
        $dept_result = $stmt_dept->get_result();
        $student_department = $dept_result->fetch_assoc()['department'];
        $stmt_dept->close();

        // Fetch the advisor's user_id from users table
        $advisor_query = "SELECT id FROM users WHERE department = ? AND role = 'advisor' LIMIT 1";
        $advisor_stmt = $conn->prepare($advisor_query);
        $advisor_stmt->bind_param("s", $student_department);
        $advisor_stmt->execute();
        $advisor_result = $advisor_stmt->get_result();
        $advisor = $advisor_result->fetch_assoc();

        if ($advisor) {
            $advisor_id = $advisor['id'];
            $message = "New application (ID: $application_id) submitted by student ID $student_id from $student_department";
            $insert_notification = "INSERT INTO notifications (user_id, application_id, message) VALUES (?, ?, ?)";
            $stmt_notification = $conn->prepare($insert_notification);
            $stmt_notification->bind_param("iis", $advisor_id, $application_id, $message);
            $stmt_notification->execute();
            $stmt_notification->close();
        }

        $advisor_stmt->close();
        header("Location: student_dashboard.php?message=Application submitted successfully."); // Updated path
        exit();
    } else {
        $error = "Error submitting application: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Re-enrollment</title>
    <link rel="stylesheet" href="../css/apply_reenrollment.css ?v=<?php echo time(); ?>"> <!-- Adjusted path -->
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Adjusted path -->
</head>

<body>
    <div class="container">
        <h2>Apply for Re-enrollment</h2>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="serial_number">Serial Number:</label>
            <input type="text" id="serial_number" name="serial_number" required>
            <label for="result_image">Result Image URL (optional):</label>
            <input type="text" id="result_image" name="result_image">
            <button type="submit">Submit Application</button>
        </form>
        <p><a href="student_dashboard.php">Back to Dashboard</a></p> <!-- Updated path -->
    </div>
</body>

</html>