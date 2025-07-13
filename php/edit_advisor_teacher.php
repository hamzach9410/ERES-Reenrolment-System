<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'] ?? null;
$role = $_GET['role'] ?? null;
$error = '';
$success = '';

if (!$id || !$role || !in_array($role, ['advisor', 'teacher'])) {
    $error = "Invalid request.";
} else {
    $table = $role === 'advisor' ? 'advisors' : 'teachers';
    $query = "SELECT id, name, email, department FROM $table WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        $error = "Prepare failed: " . $conn->error;
    } else {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $error = "Record not found.";
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
            $password = trim($_POST['password']); // Trim to handle empty input

            // Update query with conditional password update
            $update_query = "UPDATE $table SET name = ?, email = ?, department = ?";
            $params = [$name, $email, $department];
            $types = "sss";
            $param_refs = [&$name, &$email, &$department];

            if (!empty($password)) {
                $update_query .= ", password = ?";
                $types .= "s";
                $param_refs[] = &$password;
            }
            $update_query .= " WHERE id = ?";
            $types .= "i";
            $param_refs[] = &$id;

            $stmt = $conn->prepare($update_query);
            if ($stmt === false) {
                $error = "Prepare failed: " . $conn->error;
            } else {
                $stmt->bind_param($types, ...$param_refs);
                if ($stmt->execute()) {
                    $success = "Record updated successfully.";
                    // Redirect to dashboard to reflect changes
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $error = "Error updating record: " . $conn->error;
                }
                $stmt->close();
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?php echo ucfirst($role); ?></title>
    <link rel="stylesheet" href="../css/style_dashboard.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body>
    <div class="main-content">
        <h2>Edit <?php echo ucfirst($role); ?></h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if ($user) : ?>
            <form method="POST">
                <label>Name: <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required></label>
                <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required></label>
                <label>Department: <input type="text" name="department" value="<?php echo htmlspecialchars($user['department']); ?>" required></label>
                <label>Password: <input type="password" name="password" placeholder="Leave blank to keep existing"></label>
                <button type="submit" class="btn btn-approve">Update</button>
            </form>
        <?php endif; ?>
        <a href="admin_dashboard.php" class="btn">Back</a>
    </div>
</body>

</html>