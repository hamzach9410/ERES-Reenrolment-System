<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$title = "Admin Dashboard";
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="../css/style_dashboard.css?v=<?= time(); ?>" />
    <link rel="stylesheet" href="../css/admin_dashboard.css?v=<?= time(); ?>" />
    <link rel="icon" type="image/gif" href="../images/logo1.gif" />
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
            <h2>Menu</h2>
            <p>Manage applications, advisors, teachers, and more from the sections below.</p>

            <div class="card-grid">
                <a href="applications.php" class="card-link">
                    <div class="card">
                        <h3>Applications</h3>
                        <p>View and manage student applications.</p>
                    </div>
                </a>

                <a href="advisors.php" class="card-link">
                    <div class="card">
                        <h3>Advisors</h3>
                        <p>Manage advisor profiles and details.</p>
                    </div>
                </a>

                <a href="teachers.php" class="card-link">
                    <div class="card">
                        <h3>Teachers</h3>
                        <p>Manage teacher profiles and details.</p>
                    </div>
                </a>

                <a href="add_advisor_teacher.php" class="card-link">
                    <div class="card">
                        <h3>Add Advisor/Teacher</h3>
                        <p>Add a new advisor or teacher to the system.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="../js/admin_dashboard.js?v=<?= time(); ?>"></script>
</body>

</html>