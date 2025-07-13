<?php
session_start();
include 'db.php'; // Updated path to db.php

// Check if user is logged in as student, teacher, or advisor
if (isset($_SESSION['student_id'])) {
    $user_type = "student";
    $user_id = $_SESSION['student_id'];
} elseif (isset($_SESSION['teacher_id'])) {
    $user_type = "teacher";
    $user_id = $_SESSION['teacher_id'];
} elseif (isset($_SESSION['advisor_id'])) {
    $user_type = "advisor";
    $user_id = $_SESSION['advisor_id'];
} else {
    header("Location:login.php"); // Updated path
    exit();
}

// Fetch user details based on user type
if ($user_type === "student") {
    $sql_user = "SELECT name, father_name, email, ag_number, department, semester FROM students WHERE id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user = $result_user->fetch_assoc();
} elseif ($user_type === "teacher" || $user_type === "advisor") {
    $sql_user = "SELECT name FROM " . ($user_type === "teacher" ? "teachers" : "advisors") . " WHERE id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $user = $result_user->fetch_assoc() ?: ['name' => 'N/A', 'father_name' => 'N/A', 'email' => 'N/A', 'ag_number' => 'N/A', 'department' => 'N/A', 'semester' => 'N/A'];
}

// Fetch application based on user type
if ($user_type === "student") {
    $query_application = "SELECT id, serial_number, date, result_image, status FROM applications WHERE student_id = ?";
} elseif ($user_type === "teacher") {
    $query_application = "SELECT applications.id, applications.serial_number, applications.date, applications.result_image, applications.status 
                          FROM applications 
                          INNER JOIN assigned_applications ON applications.id = assigned_applications.application_id
                          WHERE assigned_applications.teacher_id = ?";
} elseif ($user_type === "advisor") {
    $query_application = "SELECT applications.id, applications.serial_number, applications.date, applications.result_image, applications.status 
                          FROM applications 
                          INNER JOIN students ON applications.student_id = students.id
                          WHERE students.department = (SELECT department FROM advisors WHERE id = ?)";
}

$stmt_application = $conn->prepare($query_application);
$stmt_application->bind_param("i", $user_id);
$stmt_application->execute();
$result_application = $stmt_application->get_result();

if ($result_application->num_rows > 0) {
    $application = $result_application->fetch_assoc();
    $application_id = $application['id'];
    $serial_number = $application['serial_number'];
    $date = $application['date'];
    $result_image = $application['result_image'];
    $status = $application['status'];

    // Fetch selected subjects in ascending order
    $query_subjects = "SELECT subject_name, semester FROM application_subjects WHERE application_id = ? ORDER BY semester ASC, subject_name ASC";
    $stmt_subjects = $conn->prepare($query_subjects);
    $stmt_subjects->bind_param("i", $application_id);
    $stmt_subjects->execute();
    $result_subjects = $stmt_subjects->get_result();
} else {
    $application_id = null;
    $serial_number = "N/A";
    $date = "N/A";
    $result_image = null;
    $status = "No application found.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Adjusted path -->
    <title>Application Status</title>
    <link rel="stylesheet" href="../css/application_status.css ?v=<?php echo time(); ?>"> <!-- Added CSS file -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var status = "<?= htmlspecialchars($status); ?>";
            var statusElement = document.querySelector(".status");
            var printButton = document.getElementById("printBtn");
            if (status === "Approved") {
                statusElement.textContent = "Approved";
                statusElement.style.color = "green";
                printButton.style.display = "inline-block";
            } else {
                statusElement.textContent = status;
                printButton.style.display = "none";
            }
        });

        function printApplication() {
            window.print();
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="../images/favicon.jpg" alt="University Logo"> <!-- Adjusted path -->
            <div>
                <h2>University of Agriculture Faisalabad</h2>
                <h4>Sub Campus Depalpur Okara</h4>
            </div>
        </div>
        <p><strong>Serial Number:</strong> <?= htmlspecialchars($serial_number); ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($date); ?></p>
        <p><strong>SUBJECT:</strong> Application For Re-Enrollment</p>
        <h4 class="sirmam">Respected Sir/Mam,</h4>
        <p class="application-para">I, <strong class="underline"><?= htmlspecialchars($user['name']); ?></strong>,
            son/daughter of <strong class="underline"><?= htmlspecialchars($user['father_name']); ?></strong>,
            with AG Number <strong class="underline"><?= htmlspecialchars($user['ag_number']); ?></strong>,
            wish to re-enroll in the following subjects:</p>
        <h3 class="subjects-header">Selected Subjects</h3>
        <?php
        if ($result_subjects && $result_subjects->num_rows > 0) {
            while ($subject = $result_subjects->fetch_assoc()) : ?>
                <p><?= htmlspecialchars($subject['subject_name']); ?> - Semester <?= htmlspecialchars($subject['semester']); ?></p>
            <?php endwhile;
        } else { ?>
            <p style="color: red;">No subjects selected for re-enrollment.</p>
        <?php } ?>
        <button id="printBtn" onclick="printApplication()" class="btn" style="display: none;">Print Application</button>
        <a href="php/<?= $user_type ?>_dashboard.php" class="btn">Go Back to Dashboard</a> <!-- Dynamic path based on user type -->
    </div>
</body>

</html>