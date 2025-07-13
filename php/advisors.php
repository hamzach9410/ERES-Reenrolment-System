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

$advisors_query = "SELECT id, name, email, department FROM advisors";
$advisors_result = $conn->query($advisors_query);

// Handle drop action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'drop') {
    $id = (int)$_POST['id'];
    $query = "DELETE FROM advisors WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: advisors.php");
        exit();
    } else {
        $error = "Error deleting advisor: " . $conn->error;
    }
    $stmt->close();
}

// Handle edit action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
    $id = (int)$_POST['edit_id'];
    $name = filter_var($_POST['edit_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['edit_email'], FILTER_SANITIZE_EMAIL);
    $department = filter_var($_POST['edit_department'], FILTER_SANITIZE_STRING);
    $password = trim($_POST['edit_password']);

    // Validate department
    if (!in_array($department, $departments)) {
        $error = "Invalid department selected.";
    } else {
        $update_query = "UPDATE advisors SET name = ?, email = ?, department = ?";
        $params = [$name, $email, $department];
        $types = "sss";
        $param_refs = [&$name, &$email, &$department];

        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
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
                $success = "Advisor updated successfully.";
            } else {
                $error = "Error updating advisor: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    // Refresh advisors list after update
    $advisors_result = $conn->query($advisors_query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisors</title>
    <link rel="stylesheet" href="../css/style_dashboard.css?v=<?php echo time(); ?>">
    <link rel="icon" type="image/gif" href="../images/logo1.gif" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        .edit-form {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .edit-form.active {
            display: block;
        }

        .edit-form label {
            display: block;
            margin-bottom: 5px;
        }

        .edit-form input,
        .edit-form select {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .btn {
            padding: 5px 10px;
            margin: 2px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            transition: opacity 1s ease-in-out;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
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
            <h2>Advisors</h2>
            <?php if ($advisors_result && $advisors_result->num_rows > 0) : ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $advisors_result->fetch_assoc()) : ?>
                        <tr>
                            <td data-label="ID"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                            <td data-label="Actions">
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to drop this advisor?');">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <input type="hidden" name="action" value="drop">
                                    <button type="submit" class="btn btn-danger">Drop</button>
                                </form>
                                <button class="btn btn-primary edit-btn" data-id="<?php echo htmlspecialchars($row['id']); ?>">Edit</button>
                                <div class="edit-form" id="edit-form-<?php echo htmlspecialchars($row['id']); ?>">
                                    <form method="POST">
                                        <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                        <label>Name: <input type="text" name="edit_name" value="<?php echo htmlspecialchars($row['name']); ?>" required></label>
                                        <label>Email: <input type="email" name="edit_email" value="<?php echo htmlspecialchars($row['email']); ?>" required></label>
                                        <label>Department:
                                            <select name="edit_department" required>
                                                <?php foreach ($departments as $dept): ?>
                                                    <option value="<?php echo htmlspecialchars($dept); ?>" <?php echo $row['department'] === $dept ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($dept); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </label>
                                        <label>Password: <input type="password" name="edit_password" placeholder="Leave blank to keep existing"></label>
                                        <button type="submit" class="btn btn-approve">Save</button>
                                        <button type="button" class="btn btn-cancel cancel-edit-btn" data-id="<?php echo htmlspecialchars($row['id']); ?>">Cancel</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else : ?>
                <p class="no-applications">No advisors found.</p>
            <?php endif; ?>
            <?php if (isset($error)) : ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if (isset($success)) : ?>
                <p class="success" id="success-message"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
        </div>
        <br>
        <a href="admin_dashboard.php" class="btn back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <script>
        // Fade out success message after 5 seconds
        setTimeout(function() {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.opacity = '0';
            }
        }, 5000); // 5 seconds

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

        // Handle Edit Button Clicks
        const editButtons = document.querySelectorAll('.edit-btn');
        const cancelButtons = document.querySelectorAll('.cancel-edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Hide all edit forms first
                document.querySelectorAll('.edit-form').forEach(form => {
                    form.classList.remove('active');
                });
                // Show the clicked edit form
                const id = this.getAttribute('data-id');
                const editForm = document.getElementById(`edit-form-${id}`);
                if (editForm) {
                    editForm.classList.add('active');
                }
            });
        });

        // Handle Cancel Button Clicks
        cancelButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const editForm = document.getElementById(`edit-form-${id}`);
                if (editForm) {
                    editForm.classList.remove('active');
                }
            });
        });

        // Close Edit Form when clicking outside
        document.addEventListener('click', function(event) {
            const editForms = document.querySelectorAll('.edit-form.active');
            const editButtons = document.querySelectorAll('.edit-btn');
            let isClickInsideFormOrButton = false;

            // Check if click is inside any edit form or edit button
            editForms.forEach(form => {
                if (form.contains(event.target)) {
                    isClickInsideFormOrButton = true;
                }
            });
            editButtons.forEach(button => {
                if (button.contains(event.target)) {
                    isClickInsideFormOrButton = true;
                }
            });

            if (!isClickInsideFormOrButton) {
                editForms.forEach(form => {
                    form.classList.remove('active');
                });
            }
        });
    </script>
</body>

</html>
<?php $conn->close(); ?>