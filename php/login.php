<?php
session_start();
include 'db.php'; // Corrected: already in php/ folder

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $student = $result->fetch_assoc();

            if (password_verify($password, $student['password'])) {
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_name'] = $student['name'];
                $_SESSION['student_email'] = $student['email'];
                $_SESSION['student_semester'] = $student['semester'];
                $_SESSION['student_department'] = $student['department'];

                header("Location: student_dashboard.php"); // Fixed: already in php folder
                exit();
            } else {
                $message = "<p class='error-message'>Incorrect password!</p>";
            }
        } else {
            $message = "<p class='error-message'>No student found with this email.</p>";
        }

        $stmt->close();
    } else {
        $message = "<p class='error-message'>Database error. Please try again later.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="../css/login_section.css ?v=<?php echo time(); ?>">
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
                            <li class="nav-item"><a class="nav-link" href="http://127.0.0.1:5500/#section1">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="discipline.php">Discipline</a></li>
                            <li class="nav-item"><a class="nav-link" href="institute.php">Institutes</a></li>
                            <li class="nav-item"><a class="nav-link" href="faq.php">Faqs</a></li>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                            <li class="nav-item"><a class="nav-link" href="admin_login.php">Admin</a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="login-sec">
                <img src="../images/favicon.jpg" alt="University Logo" class="logo">
                <p class="campus-name">University of Agriculture Faisalabad<br>Sub Campus Depalpur Okara</p>
                <h2>Student Login</h2>
                <?= $message; ?>
                <form method="POST" action="">
                    <input type="email" name="email" placeholder="Enter Email" required>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Enter Password" required>
                        <i class="fas fa-eye" id="togglePassword"></i>
                    </div>
                    <button type="submit">Login</button>
                </form>
                <p class="signup-text">Not having an account? <a href="signup.php">Sign up first</a></p>
                <div class="button-row">
                    <a href="advisor_login.php" class="login-button">Advisor Login</a>
                    <a href="teacher_login.php" class="login-button">Teacher Login</a>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/passwordtoggle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>