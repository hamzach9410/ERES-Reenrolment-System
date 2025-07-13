<?php
session_start();
include 'db.php'; // Updated path to db.php

if (!isset($_SESSION['student_id'])) {
    die("Access denied. Please log in.");
}

// Retrieve student session data
$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['student_name'] ?? 'Guest';
$student_department = $_SESSION['student_department'] ?? 'N/A';

// Handle AJAX request to mark notifications as read
if (isset($_GET['action']) && $_GET['action'] === 'mark_read') {
    $update_query = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND user_type = 'student'";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $student_id);
    $update_stmt->execute();
    $update_stmt->close();
    echo json_encode(['status' => 'success']);
    exit();
}

// Function to fetch student applications by status
function fetchStudentApplications($conn, $student_id, $status)
{
    $query = "
        SELECT applications.id, applications.serial_number, applications.date, applications.status 
        FROM applications
        WHERE applications.student_id = ? AND applications.status = ?
    ";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("is", $student_id, $status);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    return $stmt->get_result();
}

// Fetch applications by status
$pending_result = fetchStudentApplications($conn, $student_id, 'Pending');
$approved_result = fetchStudentApplications($conn, $student_id, 'Approved');
$rejected_result = fetchStudentApplications($conn, $student_id, 'Rejected');

// Fetch all notifications for the panel
$query_notifications_all = "SELECT message, created_at, is_read FROM notifications WHERE user_id = ? AND user_type = 'student' ORDER BY created_at DESC";
$stmt_notifications_all = $conn->prepare($query_notifications_all);
$stmt_notifications_all->bind_param("i", $student_id);
$stmt_notifications_all->execute();
$notifications_result_all = $stmt_notifications_all->get_result();

// Count unread notifications for the badge
$query_unread = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND user_type = 'student' AND is_read = 0";
$stmt_unread = $conn->prepare($query_unread);
$stmt_unread->bind_param("i", $student_id);
$stmt_unread->execute();
$unread_result = $stmt_unread->get_result();
$unread_count = $unread_result->fetch_assoc()['unread_count'];

// Function to fetch teacher feedback (exam date or comment) for an application
function getTeacherFeedback($conn, $application_id, $status, $student_id)
{
    // Look for the most recent notification related to this application
    $query = "
        SELECT message 
        FROM notifications 
        WHERE user_id = ? AND user_type = 'student' 
        AND message LIKE ? 
        ORDER BY created_at DESC 
        LIMIT 1
    ";
    $search_pattern = "%application (ID: $application_id)%";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $student_id, $search_pattern);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $message = $row['message'];

        if ($status === 'Approved') {
            // Extract exam date from the message
            // Expected format: "Reminder: Your approved application (ID: X) has an exam scheduled on YYYY-MM-DD. Please prepare accordingly."
            if (preg_match("/exam scheduled on (\d{4}-\d{2}-\d{2})/", $message, $matches)) {
                return $matches[1]; // Return the exam date (YYYY-MM-DD)
            }
        } elseif ($status === 'Rejected') {
            // Extract suggestions from the message
            // Expected format: "Your application (ID: X) was rejected. Please reapply with the following suggestions: [suggestions]"
            if (preg_match("/suggestions: (.+)$/", $message, $matches)) {
                return $matches[1]; // Return the suggestions
            }
        }
    }
    return "N/A"; // Return N/A if no relevant notification is found
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/style_dashboard.css?v=<?php echo time(); ?>"> <!-- Updated path -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Updated path -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for bell icon -->
    <style>
        .cgpa-section {
            display: none;
            /* Hidden by default */
        }

        .cgpa-section.active {
            display: block;
            /* Show when active */
        }

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
    </style>
</head>

<body>
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" aria-label="Toggle Sidebar">☰</button>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Student Dashboard</h2>
        <ul>
            <li><a href="student_dashboard.php">Dashboard</a></li>
            <li><a href="#" id="toggle-cgpa">Result</a></li>
            <li><a href="apply_reenrollment.php">Apply for Re-enrollment</a></li>
            <li><a href="student_logout.php" class="logout-btn">Logout</a></li>
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
                <p>Welcome, <?php echo htmlspecialchars($student_name); ?> (Department: <?php echo htmlspecialchars($student_department); ?>)</p>
                <?php if (isset($_GET['message'])) : ?>
                    <p id="message" style="color: red; text-align: center; transition: opacity 1s;">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Application Preview -->
        <div class="section">
            <h2>Application Preview</h2>
            <?php
            // Fetch all applications for the student
            $query_all = "
                SELECT applications.id, applications.status, students.name, students.department
                FROM applications
                JOIN students ON applications.student_id = students.id
                WHERE applications.student_id = ?
            ";
            $stmt_all = $conn->prepare($query_all);
            if ($stmt_all === false) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt_all->bind_param("i", $student_id);
            if (!$stmt_all->execute()) {
                die("Execute failed: " . $stmt_all->error);
            }
            $result_all = $stmt_all->get_result();
            ?>
            <?php if ($result_all->num_rows > 0) : ?>
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Teacher Feedback</th>
                        <th>Action</th>
                    </tr>
                    <?php while ($row = $result_all->fetch_assoc()) : ?>
                        <tr>
                            <td data-label="Application ID"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                            <td data-label="Status">
                                <span class="status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td data-label="Teacher Feedback">
                                <?php
                                // Fetch teacher feedback (exam date or comment) based on application status
                                $feedback = getTeacherFeedback($conn, $row['id'], $row['status'], $student_id);
                                echo htmlspecialchars($feedback);
                                ?>
                            </td>
                            <td data-label="Action">
                                <a href="application_status.php?id=<?php echo $row['id']; ?>" class="btn btn-review">Preview</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else : ?>
                <p class="no-applications">No applications found.</p>
            <?php endif; ?>
        </div>

        <!-- Embedded CGPA Calculator -->
        <div class="section cgpa-section" id="cgpa-section">
            <h2>CGPA Calculator</h2>
            <div id="cgpa-content">
                <iframe src="https://muazshaban.vercel.app/cgpa-calculator/auto" width="100%" height="600" frameborder="0" style="border: none;" id="cgpa-iframe"></iframe>
            </div>
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
        // Message Fade-Out Script
        setTimeout(function() {
            var message = document.getElementById("message");
            if (message) {
                message.style.opacity = "0";
            }
        }, 1000); // Disappear after 1 second

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

        // Toggle CGPA Calculator Section
        document.getElementById('toggle-cgpa').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior
            const cgpaSection = document.getElementById('cgpa-section');
            cgpaSection.classList.toggle('active');
        });

        // Toggle Notification Panel
        const notificationButton = document.getElementById('notification-button');
        const notificationPanel = document.getElementById('notification-panel');
        const notificationBadge = document.getElementById('notification-badge');

        notificationButton.addEventListener('click', function() {
            // Toggle the panel
            notificationPanel.classList.toggle('active');

            // If there are unread notifications, mark them as read via AJAX
            if (notificationBadge) {
                fetch('student_dashboard.php?action=mark_read')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Remove the badge
                            notificationBadge.remove();
                            // Update notification styles (remove unread class)
                            document.querySelectorAll('.notification.unread').forEach(notification => {
                                notification.classList.remove('unread');
                            });
                        }
                    })
                    .catch(error => console.error('Error marking notifications as read:', error));
            }

            // Auto-hide the panel after 10 seconds if it's active
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
    </script>
</body>

</html>