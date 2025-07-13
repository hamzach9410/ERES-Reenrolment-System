<?php
session_start();
include 'db.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// Check if an application ID is provided in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing application ID.");
}
$application_id = (int)$_GET['id'];

// Fetch the specific application
$query_application = "SELECT applications.id, applications.serial_number, applications.date, 
                            applications.result_image, applications.status, applications.student_id
                     FROM applications 
                     INNER JOIN students ON applications.student_id = students.id
                     WHERE applications.id = ?";
$stmt_application = $conn->prepare($query_application);
$stmt_application->bind_param("i", $application_id);
$stmt_application->execute();
$result_application = $stmt_application->get_result();

if ($result_application->num_rows > 0) {
    $application = $result_application->fetch_assoc();
    $application_id = $application['id'];
    $serial_number = $application['serial_number'];
    $date = $application['date'];
    $result_image = $application['result_image'];
    $status = $application['status'];
    $student_id = $application['student_id'];

    // Fetch student details
    $sql_student = "SELECT name, father_name, email, ag_number, department, semester 
                    FROM students WHERE id = ?";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("i", $student_id);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();
    $student = $result_student->fetch_assoc() ?: [
        'name' => 'N/A',
        'father_name' => 'N/A',
        'email' => 'N/A',
        'ag_number' => 'N/A',
        'department' => 'N/A',
        'semester' => 'N/A'
    ];

    // Fetch selected subjects in ascending order
    $query_subjects = "SELECT subject_name, semester FROM application_subjects 
                       WHERE application_id = ? ORDER BY semester ASC, subject_name ASC";
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
    $student = [
        'name' => 'N/A',
        'father_name' => 'N/A',
        'email' => 'N/A',
        'ag_number' => 'N/A',
        'department' => 'N/A',
        'semester' => 'N/A'
    ];
    $result_subjects = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Application Preview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/application_prev.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/gif" href="../images/logo1.gif">
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="../images/logo1.gif" alt="University Logo">
            <div>
                <h2>University of Agriculture Faisalabad</h2>
                <h4>Sub Campus Depalpur Okara</h4>
            </div>
        </div>

        <p><strong>Serial Number:</strong> <?= htmlspecialchars($serial_number); ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($date); ?></p>
        <p><strong>SUBJECT:</strong> Application For Re-Enrollment</p>

        <h4 class="sirmam">Respected Sir/Madam,</h4>
        <p class="application-para">
            I, <strong class="underline"><?= htmlspecialchars($student['name']); ?></strong>,
            son/daughter of <strong class="underline"><?= htmlspecialchars($student['father_name']); ?></strong>,
            with AG Number <strong class="underline"><?= htmlspecialchars($student['ag_number']); ?></strong>,
            enrolled in the <strong class="underline"><?= htmlspecialchars($student['department']); ?></strong> department,
            currently in semester <strong class="underline"><?= htmlspecialchars($student['semester']); ?></strong>,
            wish to re-enroll in the following subjects:
        </p>

        <h3 class="subjects-header">Selected Subjects</h3>
        <?php if ($result_subjects && $result_subjects->num_rows > 0) : ?>
            <table>
                <tr>
                    <th>Sr#</th>
                    <th>Subject Name</th>
                    <th>Semester</th>
                </tr>
                <?php
                $sr_no = 1;
                while ($subject = $result_subjects->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $sr_no++; ?></td>
                        <td><?= htmlspecialchars($subject['subject_name']); ?></td>
                        <td><?= htmlspecialchars($subject['semester']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p style="color: red;">No subjects selected for re-enrollment.</p>
        <?php endif; ?>

        <p><strong>Kindly grant me the permission to re-enroll in these subjects.</strong></p>

        <div class="application">
            <p><strong>Application Status:</strong></p>
            <span class="status"><?= htmlspecialchars($status); ?></span>
        </div>

        <h3 class="uploaded-result">Uploaded Result</h3>
        <?php if (!empty($result_image)) : ?>
            <img src="<?= htmlspecialchars($result_image); ?>" alt="Result Image" class="result-image">
        <?php else : ?>
            <p style="color: red;">No result image uploaded.</p>
        <?php endif; ?>

        <br>
        <a href="applications.php" class="btn back-btn"><i class="fas fa-arrow-left"></i> Back to Applications</a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var status = "<?= htmlspecialchars($status); ?>";
            var statusElement = document.querySelector(".status");

            if (status === "Approved") {
                statusElement.textContent = "Approved";
                statusElement.style.color = "green";
            } else if (status === "No application found.") {
                statusElement.textContent = status;
                statusElement.style.color = "red";
            } else {
                statusElement.textContent = status;
                statusElement.style.color = "orange";
            }
        });
    </script>
</body>

</html>
<?php
// Close all statements
$stmt_application->close();
if (isset($stmt_student)) $stmt_student->close();
if (isset($stmt_subjects)) $stmt_subjects->close();
$conn->close();
?>