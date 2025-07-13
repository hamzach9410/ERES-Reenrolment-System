<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

$departments = [
    'math',
    'Chemistry',
    'computerscience',
    'Botany',
    'Zoology',
    'Agriculture',
    'foodscience'
];

$error = '';
$success = '';
function log_message($message)
{
    file_put_contents('debug.log', date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['action']) && !isset($_POST['edit_id'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $raw_password = $_POST['password'];
    $password = password_hash($raw_password, PASSWORD_DEFAULT);
    $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
    $role = $_POST['role'];

    log_message("Attempting to add $role: Name=$name, Email=$email, Department=$department");


    if (!$conn) {
        $error = "Database connection failed: " . mysqli_connect_error();
        log_message($error);
    } else {

        $check_query = "SELECT email FROM advisors WHERE email = ? UNION SELECT email FROM teachers WHERE email = ?";
        $check_stmt = $conn->prepare($check_query);
        if ($check_stmt === false) {
            $error = "Prepare failed for email check: " . $conn->error;
            log_message($error);
        } else {
            $check_stmt->bind_param("ss", $email, $email);
            if (!$check_stmt->execute()) {
                $error = "Execute failed for email check: " . $check_stmt->error;
                log_message($error);
            } else {
                $check_result = $check_stmt->get_result();
                if ($check_result->num_rows > 0) {
                    $error = "Email already exists.";
                    log_message($error);
                } else {
                    $table = $role === 'advisor' ? 'advisors' : 'teachers';
                    $query = "INSERT INTO $table (name, email, password, department) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    if ($stmt === false) {
                        $error = "Prepare failed for insert: " . $conn->error;
                        log_message($error);
                    } else {
                        $stmt->bind_param("ssss", $name, $email, $password, $department);
                        if ($stmt->execute()) {
                            $success = ucfirst($role) . " added successfully. Email: $email, Password: $raw_password";
                            log_message("Success: " . $success);

                            header("Location: " . ($role === 'advisor' ? 'advisors.php' : 'teachers.php') . "?success=" . urlencode($success));
                            exit();
                        } else {
                            $error = "Error adding $role: " . $stmt->error;
                            log_message($error);
                        }
                        $stmt->close();
                    }
                }
            }
            $check_stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Add Advisor/Teacher'; ?></title>
    <link rel="stylesheet" href="../css/style_dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../css/admin_dashboard.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/gif" href="../images/logo1.gif" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

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
            <h2>Add New Advisor/Teacher</h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <?php if (!empty($_GET['success'])) echo "<p class='success'>" . htmlspecialchars($_GET['success']) . "</p>"; ?>
            <form method="post">
                <label>Name: <input type="text" name="name" required></label>
                <label>Email: <input type="email" name="email" required></label>
                <label>Password: <input type="password" name="password" required></label>
                <label>Department:
                    <select name="department" required>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo htmlspecialchars($dept); ?>">
                                <?php echo htmlspecialchars($dept); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Role:
                    <select name="role" required>
                        <option value="advisor">Advisor</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </label>
                <button type="submit" class="btn btn-approve">Add</button>
            </form>
            <br>
            <a href="admin_dashboard.php" class="btn back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>
    <script src="../js/add-advisor-teacher.js"></script>
</body>

</html>
<?php $conn->close(); ?>