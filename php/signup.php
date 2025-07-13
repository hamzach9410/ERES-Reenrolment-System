<?php
include 'db.php'; // Updated path to db.php
session_start();

$message = ""; // To store success or error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['name'], $_POST['father_name'], $_POST['ag_number'], $_POST['department'], $_POST['email'], $_POST['semester'], $_POST['password'], $_POST['confirm_password'])) {
        $message = "<p class='error-message'>Please fill in all fields!</p>";
    } else {
        $name = $_POST['name'];
        $father_name = $_POST['father_name'];
        $ag_number = $_POST['ag_number'];
        $department = $_POST['department'];
        $email = $_POST['email'];
        $semester = $_POST['semester'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            $message = "<p class='error-message'>Passwords do not match!</p>";
        } else {
            $checkEmail = $conn->prepare("SELECT email FROM students WHERE email = ?");
            $checkEmail->bind_param("s", $email);
            $checkEmail->execute();
            $result = $checkEmail->get_result();

            if ($result->num_rows > 0) {
                $message = "<p class='error-message'>Email already exists! Try another.</p>";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO students (name, father_name, ag_number, department, email, semester, password) VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    $message = "<p class='error-message'>Database error: " . $conn->error . "</p>";
                } else {
                    $stmt->bind_param("sssssss", $name, $father_name, $ag_number, $department, $email, $semester, $hashed_password);

                    if ($stmt->execute()) {
                        $message = "<p class='success-message'>Registration successful! You can now log in.</p>";
                    } else {
                        $message = "<p class='error-message'>Error: " . $stmt->error . "</p>";
                    }

                    $stmt->close();
                }
            }

            $checkEmail->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Sign-Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Eye Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/signup.css"> <!-- Updated path -->
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Updated path -->
</head>

<body>
    <!-- Navigation Header -->
    <div class="main">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="contain">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav m-auto">
                            <li class="nav-item"><a class="nav-link" href=http://127.0.0.1:5500/#section1>Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="discipline.php">Discipline</a></li> <!-- Updated path -->
                            <li class="nav-item"><a class="nav-link" href="institute.php">Institutes</a></li> <!-- Updated path -->
                            <li class="nav-item"><a class="nav-link" href="faq.php">Faqs</a></li> <!-- Updated path -->
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li> <!-- Updated path -->
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Sign-Up Section -->
            <div class="login-sec">
                <!-- University Logo -->
                <img src="../images/favicon.jpg" alt="University Logo" class="logo"> <!-- Updated path -->

                <!-- Campus Name -->
                <p class="campus-name">University of Agriculture Faisalabad<br>Sub Campus Depalpur Okara</p>

                <h2>Student Sign-Up</h2>
                <?= $message; ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Enter Full Name" required>
                        <input type="text" name="father_name" placeholder="Enter Father's Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="ag_number" placeholder="Enter AG Number" required>
                        <input type="email" name="email" placeholder="Enter Email" required>
                    </div>
                    <div class="form-group">
                        <select name="department" required>
                            <option value="">Select Department</option>
                            <option value="math">Mathematics</option>
                            <option value="chemistry">chemistry</option>
                            <option value="computerscience">Computer Science</option>
                            <option value="botany">Botany</option>
                            <option value="zoology">Zoology</option>
                            <option value="agriculture">Agriculture</option>
                            <option value="foodscience">Food Sciences</option>
                        </select>
                        <select name="semester" required>
                            <option value="">Select Semester</option>
                            <option value="1">1st</option>
                            <option value="2">2nd</option>
                            <option value="3">3rd</option>
                            <option value="4">4th</option>
                            <option value="5">5th</option>
                            <option value="6">6th</option>
                            <option value="7">7th</option>
                            <option value="8">8th</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="password-container">
                            <input type="password" name="password" id="password" placeholder="Enter Password" required>
                            <i class="fa fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                        <div class="password-container">
                            <input type="password" name="confirm_password" id="confirmPassword" placeholder="Confirm Password" required>
                            <i class="fa fa-eye password-toggle" id="toggleConfirmPassword"></i>
                        </div>
                    </div>
                    <button type="submit">Sign Up</button>
                </form>
                <p class="signup-text">Already have an account? <a href="login.php">Login here</a></p> <!-- Updated path -->
            </div>
        </div>
    </div>
    <script src="../js/signup.js"></script> <!-- Updated path -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>