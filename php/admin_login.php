<?php
session_start();
include 'db.php';

if (!isset($_SESSION)) {
    $message = "<p class='error-message'>Session failed to start.</p>";
} else {
    $message = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$conn) {
        $message = "<p class='error-message'>Database connection failed: " . mysqli_connect_error() . "</p>";
    } else {
        $sql = "SELECT id, name, password FROM admins WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            $message = "<p class='error-message'>Prepare failed: " . $conn->error . "</p>";
        } else {
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                $message = "<p class='error-message'>Execute failed: " . $stmt->error . "</p>";
            } else {
                $result = $stmt->get_result();
                $admin = $result->fetch_assoc();

                if ($admin && $password === $admin['password']) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_name'] = $admin['name'];
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $message = "<p class='error-message'>Invalid email or password!</p>";
                }
            }
            $stmt->close();
        }
        $conn->close();
    }
}

$title = "Admin Login";
$heading = "Admin Login";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $title; ?></title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="../css/login_section.css" />
    <link rel="stylesheet" href="../css/passwordtoggle.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="icon" type="image/gif" href="../images/logo1.gif" />
</head>

<body>
    <div class="main">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="contain">
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav m-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="http://127.0.0.1:5500/#section1">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="http://localhost/university_db/php/discipline.php">Discipline</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="http://localhost/university_db/php/institute.php">Institutes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="http://localhost/university_db/php/faq.php">Faqs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="http://localhost/university_db/php/login.php">Login</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="login-sec">
                <img src="../images/favicon.jpg" alt="University Logo" class="logo" />
                <p class="campus-name">
                    University of Agriculture Faisalabad<br />Sub Campus Depalpur Okara
                </p>
                <h2><?php echo $heading; ?></h2>
                <?php echo $message; ?>
                <form method="POST" action="admin_login.php">
                    <input
                        type="email"
                        name="email"
                        placeholder="Enter Email"
                        required />
                    <div class="password-container">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Enter Password"
                            required />
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