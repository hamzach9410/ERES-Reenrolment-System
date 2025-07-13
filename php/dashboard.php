<?php
session_start();
include 'db.php'; // Updated path to db.php

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Updated path
    exit();
}

// Fetch student details
$student_id = $_SESSION['student_id'];
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// Determine semester parity
$semester = (int)$student['semester'];
$parity = ($semester % 2 == 0) ? "Even" : "Odd";
$department = $student['department']; // Use original department value

// Fetch previous semester subjects based on parity
$previous_semesters = [];
$start = ($semester % 2 == 0) ? 2 : 1;
for ($i = $start; $i < $semester; $i += 2) {
    $previous_semesters[] = $i;
}

$prev_subjects = [];
if (!empty($previous_semesters)) {
    $placeholders = implode(",", array_fill(0, count($previous_semesters), "?"));
    $sql = "SELECT id, subject_name, semester FROM subjects WHERE department = ? AND semester IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $types = "s" . str_repeat("i", count($previous_semesters));
        $params = array_merge([$department], $previous_semesters);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $prev_subjects = $stmt->get_result();
    } else {
        die("SQL Preparation Error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>"> <!-- Adjusted path -->
    <link rel="stylesheet" href="../css/style_dashboard.css?v=<?php echo time(); ?>"> <!-- Adjusted path -->
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Adjusted path -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" aria-label="Toggle Sidebar">☰</button>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Student Dashboard</h2>
        <ul>
            <li><a href="student_dashboard.php" class="dash">Dashboard</a></li> <!-- Updated path -->
            <li><a href="apply_reenrollment.php">Apply for Re-enrollment</a></li> <!-- Updated path -->
            <li><a href="logout.php" class="logout-btn">Logout</a></li> <!-- Updated path -->
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header1">
            <div>
                <h2>University of Agriculture Faisalabad</h2>
                <p>Welcome, <?php echo htmlspecialchars($student['name']); ?> (Department: <?php echo htmlspecialchars($student['department']); ?>)</p>
            </div>
        </div>

        <div class="section">
            <h2>Select Subjects for Re-enrollment</h2>
            <p class="email-section"><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            <p><strong>Current Semester:</strong> <?php echo $semester . " (" . $parity . ")"; ?></p>
            <form action="submit_application.php" method="POST" enctype="multipart/form-data"> <!-- Updated path -->
                <table>
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Subject Name</th>
                            <th>Semester</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($prev_subjects && $prev_subjects->num_rows > 0) {
                            $sr_no = 1;
                            while ($row = $prev_subjects->fetch_assoc()) {
                                echo "<tr>
                                    <td data-label='Sr. No'>$sr_no</td>
                                    <td data-label='Subject Name'>" . htmlspecialchars($row['subject_name']) . "</td>
                                    <td data-label='Semester'>" . htmlspecialchars($row['semester']) . "</td>
                                    <td data-label='Select'><input type='checkbox' name='selected_subjects[]' value='" . $row['id'] . "'></td>
                                  </tr>";
                                $sr_no++;
                            }
                        } else {
                            echo "<tr><td colspan='4' class='no-applications'>No subjects available for your department and previous semesters.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div class="upload-section">
                    <h3>Upload Result Picture (JPEG, PNG only)</h3>
                    <input type="file" name="result_image" accept="image/png, image/jpeg" required>
                </div>

                <button type="submit" class="submit-btn">Submit Application</button>
            </form>
        </div>
    </div>

    <script>
        // Mobile Menu Toggle
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Close Sidebar on Link Click (Mobile)
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.remove('active');
            });
        });
    </script>
</body>

</html>

<?php
if (isset($stmt)) $stmt->close();
$conn->close();
?>