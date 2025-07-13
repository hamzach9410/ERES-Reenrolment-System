<?php
session_start();
include 'db.php'; // Updated path to db.php

if (!isset($_SESSION['teacher_id'])) {
    die("Access denied. Please log in.");
}

$teacher_id = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];
$teacher_department = isset($_SESSION['teacher_department']) ? $_SESSION['teacher_department'] : 'Unknown Department';

// Initialize session array to track sent reminders/suggestions if not already set
if (!isset($_SESSION['sent_assists'])) {
    $_SESSION['sent_assists'] = [];
}

// Handle AJAX request to mark notifications as read
if (isset($_GET['action']) && $_GET['action'] === 'mark_read') {
    $update_query = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND user_type = 'teacher'";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $teacher_id);
    $update_stmt->execute();
    $update_stmt->close();
    echo json_encode(['status' => 'success']);
    exit();
}

// Handle Assist form submission for Approved applications (send exam date reminder)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_exam_reminder') {
    $application_id = (int)$_POST['application_id'];
    $student_id = (int)$_POST['student_id'];
    $exam_date = $_POST['exam_date'];
    $message = "Reminder: Your approved application (ID: $application_id) has an exam scheduled on $exam_date. Please prepare accordingly.";

    $stmt_notify = $conn->prepare("INSERT INTO notifications (user_id, message, user_type) VALUES (?, ?, 'student')");
    $stmt_notify->bind_param("is", $student_id, $message);
    if ($stmt_notify->execute()) {
        $success_message = "Reminder sent successfully!";
        // Mark this application as having a reminder sent
        $_SESSION['sent_assists'][$application_id] = 'approved';
    } else {
        $error_message = "Failed to send reminder: " . $stmt_notify->error;
    }
    $stmt_notify->close();
}

// Handle Assist form submission for Rejected applications (send suggestions)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_reapply_suggestions') {
    $application_id = (int)$_POST['application_id'];
    $student_id = (int)$_POST['student_id'];
    $suggestions = $_POST['suggestions'];
    $message = "Your application (ID: $application_id) was rejected. Please reapply with the following suggestions: $suggestions";

    $stmt_notify = $conn->prepare("INSERT INTO notifications (user_id, message, user_type) VALUES (?, ?, 'student')");
    $stmt_notify->bind_param("is", $student_id, $message);
    if ($stmt_notify->execute()) {
        $success_message = "Suggestions sent successfully!";
        // Mark this application as having suggestions sent
        $_SESSION['sent_assists'][$application_id] = 'rejected';
    } else {
        $error_message = "Failed to send suggestions: " . $stmt_notify->error;
    }
    $stmt_notify->close();
}

// Fetch Approved Applications
$queryApproved = "
    SELECT applications.id, students.name AS student_name, students.department, applications.status, applications.student_id 
    FROM applications
    JOIN students ON applications.student_id = students.id
    WHERE students.department = ? AND applications.status = 'Approved'
";
$stmt = $conn->prepare($queryApproved);
if (!$stmt) {
    $resultApproved = new mysqli_result($conn); // Empty result if query fails
} else {
    $stmt->bind_param("s", $teacher_department); // Filter by teacher's department
    $stmt->execute();
    $resultApproved = $stmt->get_result();
}

// Fetch Rejected Applications
$queryRejected = "
    SELECT applications.id, students.name AS student_name, students.department, applications.status, applications.student_id 
    FROM applications
    JOIN students ON applications.student_id = students.id
    WHERE students.department = ? AND applications.status = 'Rejected'
";
$stmt = $conn->prepare($queryRejected);
if (!$stmt) {
    $resultRejected = new mysqli_result($conn); // Empty result if query fails
} else {
    $stmt->bind_param("s", $teacher_department); // Filter by teacher's department
    $stmt->execute();
    $resultRejected = $stmt->get_result();
}

// Fetch all notifications for the panel (only from advisor actions on approve/reject)
$query_notifications_all = "
    SELECT message, created_at, is_read 
    FROM notifications 
    WHERE user_id = ? AND user_type = 'teacher' 
    ORDER BY created_at DESC
";
$stmt_notifications_all = $conn->prepare($query_notifications_all);
$stmt_notifications_all->bind_param("i", $teacher_id);
$stmt_notifications_all->execute();
$notifications_result_all = $stmt_notifications_all->get_result();

// Count unread notifications for the badge
$query_unread = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND user_type = 'teacher' AND is_read = 0";
$stmt_unread = $conn->prepare($query_unread);
$stmt_unread->bind_param("i", $teacher_id);
$stmt_unread->execute();
$unread_result = $stmt_unread->get_result();
$unread_count = $unread_result->fetch_assoc()['unread_count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../css/style_dashboard.css?v=<?php echo time(); ?>"> <!-- Updated path -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Updated path -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for bell icon -->
    <style>
        .notification-section {
            margin-top: 20px;
            padding: 10px;
            background: #012048;
            border-radius: 5px;
        }

        .notification-section h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
            display: none;
            /* Hide the Notifications header */
        }

        .notification-button {
            position: relative;
            display: inline-flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
            color: #fff;
            font-size: 1.5em;
        }

        .notification-button .badge {
            position: absolute;
            top: 0;
            right: 0;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.8em;
        }

        .notification-button .button-content {
            display: flex;
            align-items: center;
        }

        .notification-button .button-content button {
            margin-left: 10px;
            padding: 5px 10px;
            background-color: #012048;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8em;
        }

        .notification-button .button-content button:hover {
            border: 1px solid #ffffff;
        }

        .notification-panel {
            display: none;
            position: fixed;
            top: 60px;
            right: 20px;
            width: 300px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-panel.active {
            display: block;
        }

        .notification-panel .notification {
            background: #f9f9f9;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
            font-size: 0.9em;
        }

        .notification-panel .notification.unread {
            background: #e6f3ff;
        }

        /* Styles for Assist Form */
        .assist-form-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 300px;
        }

        .assist-form-container.active {
            display: block;
        }

        .assist-form-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .assist-form-container input,
        .assist-form-container textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .assist-form-container textarea {
            height: 100px;
            resize: vertical;
        }

        .assist-form-container button {
            padding: 5px 10px;
            background-color: #012048;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-right: 10px;
        }

        .assist-form-container .close-btn {
            background-color: #012048;
            color: white;
        }

        .assist-form-container .close-btn:hover {
            background-color: #012048;
        }

        .assist-form-container button:hover {
            background-color: #023a75;
        }

        .btn-assist {
            background-color: #012048;
            color: white;
            padding: 5px 10px;
            border-radius: 8px;
            text-decoration: none;
        }

        .btn-assist:hover {
            background-color: #012048;
        }

        .success-message,
        .error-message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            transition: opacity 1s ease-in-out;
        }

        .success-message {
            background-color: #d4edda;
            color: #012048;
        }

        .error-message {
            background-color: #f8d7da;
            color: #012048;
        }
    </style>
</head>

<body>
    <button class="menu-toggle" aria-label="Toggle Sidebar">☰</button>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Teacher Dashboard</h2>
        <ul>
            <li><a href="teacher_dashboard.php">Dashboard</a></li> <!-- Updated path -->
            <li><a href="teacher_logout.php" class="logout-btn">Logout</a></li> <!-- Updated path -->
        </ul>
        <!-- Notification Button with Badge -->
        <div class="notification-section">
            <div class="notification-button" id="notification-button">
                <?php if ($unread_count > 0) : ?>
                    <span class="badge" id="notification-badge"><?php echo $unread_count; ?></span>
                <?php endif; ?>
                <div class="button-content">
                    <button type="button">Notifications</button>
                    <i class="fas fa-bell" aria-label="Show Notifications"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header1">
            <div>
                <h2>University of Agriculture Faisalabad</h2>
                <p>Welcome, <?php echo htmlspecialchars($teacher_name); ?> (Department: <?php echo htmlspecialchars($teacher_department); ?>)</p>
            </div>
        </div>

        <!-- Display Success or Error Messages -->
        <?php if (isset($success_message)) : ?>
            <div class="success-message" id="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)) : ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Approved Applications Section -->
        <div class="section">
            <h2>Approved Applications</h2>
            <?php if ($resultApproved->num_rows > 0) { ?>
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $resultApproved->fetch_assoc()) { ?>
                        <tr>
                            <td data-label="Application ID"><?php echo $row['id']; ?></td>
                            <td data-label="Student Name"><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                            <td data-label="Status">
                                <span class="status-approved">Approved</span>
                            </td>
                            <td data-label="Actions">
                                <a href="preview_applicationT.php?id=<?php echo $row['id']; ?>" class="btn btn-review">View Details</a>
                                <?php if (!isset($_SESSION['sent_assists'][$row['id']])) : ?>
                                    <button class="btn btn-assist assist-btn" data-app-id="<?php echo $row['id']; ?>" data-student-id="<?php echo $row['student_id']; ?>" data-status="approved">Assist</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p class="no-applications">No approved applications found.</p>
            <?php } ?>
        </div>

        <!-- Rejected Applications Section -->
        <div class="section">
            <h2>Rejected Applications</h2>
            <?php if ($resultRejected->num_rows > 0) { ?>
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $resultRejected->fetch_assoc()) { ?>
                        <tr>
                            <td data-label="Application ID"><?php echo $row['id']; ?></td>
                            <td data-label="Student Name"><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                            <td data-label="Status">
                                <span class="status-rejected">Rejected</span>
                            </td>
                            <td data-label="Actions">
                                <a href="preview_applicationT.php?id=<?php echo $row['id']; ?>" class="btn btn-review">View Details</a>
                                <?php if (!isset($_SESSION['sent_assists'][$row['id']])) : ?>
                                    <button class="btn btn-assist assist-btn" data-app-id="<?php echo $row['id']; ?>" data-student-id="<?php echo $row['student_id']; ?>" data-status="rejected">Assist</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p class="no-applications">No rejected applications found.</p>
            <?php } ?>
        </div>

        <!-- Assist Form for Approved Applications (Exam Date Reminder) -->
        <div class="assist-form-container" id="assist-form-approved">
            <form method="POST" action="">
                <input type="hidden" name="action" value="send_exam_reminder">
                <input type="hidden" name="application_id" id="approved-app-id">
                <input type="hidden" name="student_id" id="approved-student-id">
                <label for="exam_date">Exam Date:</label>
                <input type="date" name="exam_date" id="exam_date" required>
                <button type="submit">Send Reminder</button>
                <button type="button" class="close-btn" onclick="document.getElementById('assist-form-approved').classList.remove('active')">Close</button>
            </form>
        </div>

        <!-- Assist Form for Rejected Applications (Reapply Suggestions) -->
        <div class="assist-form-container" id="assist-form-rejected">
            <form method="POST" action="">
                <input type="hidden" name="action" value="send_reapply_suggestions">
                <input type="hidden" name="application_id" id="rejected-app-id">
                <input type="hidden" name="student_id" id="rejected-student-id">
                <label for="suggestions">Suggestions for Reapplication:</label>
                <textarea name="suggestions" id="suggestions" required></textarea>
                <button type="submit">Send Suggestions</button>
                <button type="button" class="close-btn" onclick="document.getElementById('assist-form-rejected').classList.remove('active')">Close</button>
            </form>
        </div>

        <!-- Notification Panel -->
        <div class="notification-panel" id="notification-panel">
            <h3>All Notifications</h3>
            <?php if ($notifications_result_all->num_rows > 0) : ?>
                <?php while ($notification = $notifications_result_all->fetch_assoc()) : ?>
                    <div class="notification <?php echo $notification['is_read'] ? '' : 'unread'; ?>">
                        <p><?php echo htmlspecialchars($notification['message']); ?></p>
                        <small><?php echo htmlspecialchars($notification['created_at']); ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No notifications found.</p>
            <?php endif; ?>
        </div>
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

        // Toggle Notification Panel
        const notificationButton = document.getElementById('notification-button');
        const notificationPanel = document.getElementById('notification-panel');
        const notificationBadge = document.getElementById('notification-badge');

        notificationButton.addEventListener('click', function() {
            notificationPanel.classList.toggle('active');
            if (notificationBadge) {
                fetch('teacher_dashboard.php?action=mark_read')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            notificationBadge.remove();
                            document.querySelectorAll('.notification.unread').forEach(notification => {
                                notification.classList.remove('unread');
                            });
                        }
                    })
                    .catch(error => console.error('Error marking notifications as read:', error));
            }
            if (notificationPanel.classList.contains('active')) {
                setTimeout(function() {
                    notificationPanel.classList.remove('active');
                }, 10000); // 10 seconds
            }
        });

        // Close Notification Panel when clicking outside
        document.addEventListener('click', function(event) {
            if (!notificationButton.contains(event.target) && !notificationPanel.contains(event.target)) {
                notificationPanel.classList.remove('active');
            }
        });

        // Handle Assist Button Clicks
        const assistButtons = document.querySelectorAll('.assist-btn');
        const approvedForm = document.getElementById('assist-form-approved');
        const rejectedForm = document.getElementById('assist-form-rejected');

        assistButtons.forEach(button => {
            button.addEventListener('click', function() {
                const appId = this.getAttribute('data-app-id');
                const studentId = this.getAttribute('data-student-id');
                const status = this.getAttribute('data-status');

                if (status === 'approved') {
                    document.getElementById('approved-app-id').value = appId;
                    document.getElementById('approved-student-id').value = studentId;
                    approvedForm.classList.add('active');
                    rejectedForm.classList.remove('active');
                } else if (status === 'rejected') {
                    document.getElementById('rejected-app-id').value = appId;
                    document.getElementById('rejected-student-id').value = studentId;
                    rejectedForm.classList.add('active');
                    approvedForm.classList.remove('active');
                }
            });
        });

        // Close Assist Forms when clicking outside
        document.addEventListener('click', function(event) {
            if (!approvedForm.contains(event.target) && !rejectedForm.contains(event.target) && !event.target.classList.contains('assist-btn')) {
                approvedForm.classList.remove('active');
                rejectedForm.classList.remove('active');
            }
        });
    </script>
</body>

</html>