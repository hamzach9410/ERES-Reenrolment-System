<?php
ob_start();
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM teachers WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Database error: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $storedPassword = $row['password'];

        // Detect bcrypt format (starts with $2y$ or $2a$)
        if (strpos($storedPassword, '$2y$') === 0 || strpos($storedPassword, '$2a$') === 0) {
            if (password_verify($password, $storedPassword)) {
                loginSuccess($row);
            } else {
                echo "Invalid email or password!";
            }

            // SHA-256 hash (64 hex characters)
        } elseif (strlen($storedPassword) === 64 && ctype_xdigit($storedPassword)) {
            if (hash('sha256', $password) === $storedPassword) {
                loginSuccess($row);
            } else {
                echo "Invalid email or password!";
            }

            // Fallback: plaintext match (not secure)
        } else {
            if ($password === $storedPassword) {
                loginSuccess($row);
            } else {
                echo "Invalid email or password!";
            }
        }
    } else {
        echo "No teacher found with this email.";
    }
    $stmt->close();
}

function loginSuccess($row)
{
    $_SESSION['teacher_id'] = $row['id'];
    $_SESSION['teacher_name'] = $row['name'];
    $_SESSION['teacher_department'] = $row['department'];
    ob_end_flush();
    header("Location: teacher_dashboard.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="../css/login_section.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/gif" href="../images/logo1.gif">
</head>

<body>
    <div class="main">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="contain">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav m-auto">
                            <li class="nav-item"><a class="nav-link" href="http://127.0.0.1:5500/#section1">Home</a></li> <!-- Updated to relative path -->
                            <li class="nav-item"><a class="nav-link" href="discipline.php">Discipline</a></li>
                            <li class="nav-item"><a class="nav-link" href="institute.php">Institutes</a></li>
                            <li class="nav-item"><a class="nav-link" href="faq.php">Faqs</a></li>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="login-sec">
                <img src="../images/favicon.jpg" alt="University Logo" class="logo">
                <p class="campus-name">University of Agriculture Faisalabad<br>Sub Campus Depalpur Okara</p>
                <h2>Teacher Login</h2>
                <form method="POST" action="">
                    <input type="email" name="email" placeholder="Enter Email" required>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Enter Password" required>
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </div>
                    <button type="submit">Login</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/passwordtoggle.js"></script>
</body>

</html>