<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

$check_table_query = "SHOW TABLES LIKE 'logins'";
$check_table_result = $conn->query($check_table_query);
if ($check_table_result && $check_table_result->num_rows > 0) {
    $applications_query = "
        SELECT applications.id, students.name AS student_name, students.department, applications.status 
        FROM applications
        JOIN students ON applications.student_id = students.id
    ";
    $applications_result = $conn->query($applications_query);
} else {
    $applications_result = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications</title>
    <link rel="stylesheet" href="../css/style_dashboard.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/gif" href="../images/logo1.gif" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        .edit-form {
            display: none;
            margin-top: 10px;
        }

        .edit-form.active {
            display: block;
        }

        .btn {
            padding: 5px 10px;
            margin: 2px;
        }
    </style>
</head>

<body>
    <button class="menu-toggle" aria-label="Toggle Sidebar">☰</button>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="applications.php">Applications</a></li>
            <li><a href="advisors.php">Advisors</a></li>
            <li><a href="teachers.php">Teachers</a></li>
            <li><a href="add_advisor_teacher.php">Add Advisor/Teacher</a></li>
            <li><a href="admin_logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header1">
            <h2>University of Agriculture Faisalabad</h2>
            <p>Welcome, <?php echo htmlspecialchars($admin_name); ?></p>
        </div>
        <div class="section">
            <h2>All Applications</h2>
            <?php if ($applications_result && $applications_result->num_rows > 0) : ?>
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $applications_result->fetch_assoc()) : ?>
                        <tr>
                            <td data-label="Application ID"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td data-label="Student Name"><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                            <td data-label="Status">
                                <span class="status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td data-label="Actions">
                                <a href="preview_applicationA.php?id=<?php echo $row['id']; ?>" class="btn btn-review">View Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else : ?>
                <p class="no-applications">No applications found.</p>
            <?php endif; ?>
        </div>
        <br>
        <a href="admin_dashboard.php" class="btn back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.remove('active');
            });
        });
    </script>
</body>

</html>
<?php $conn->close(); ?>