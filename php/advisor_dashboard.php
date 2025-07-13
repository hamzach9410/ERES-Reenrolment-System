<?php
session_start();
include 'db.php'; // Updated path to db.php

if (!isset($_SESSION['advisor_id'])) {
    die("Access denied. Please log in.");
}

$advisor_id = $_SESSION['advisor_id'];
$advisor_name = $_SESSION['advisor_name'] ?? 'Guest';
$advisor_department = $_SESSION['advisor_department'] ?? 'N/A';

// Handle AJAX request to mark notifications as read
if (isset($_GET['action']) && $_GET['action'] === 'mark_read') {
    $update_query = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND user_type = 'teacher'";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("i", $advisor_id);
    $update_stmt->execute();
    $update_stmt->close();
    echo json_encode(['status' => 'success']);
    exit();
}

// Function to fetch applications by status and department
function fetchApplications($conn, $advisor_department, $status)
{
    $query = "
        SELECT applications.id, students.name AS student_name, students.department, applications.status 
        FROM applications
        JOIN students ON applications.student_id = students.id
        WHERE LOWER(students.department) = LOWER(?) AND applications.status = ?
    ";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $advisor_department, $status);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    return $stmt->get_result();
}

// Fetch applications by status
$pending_result = fetchApplications($conn, $advisor_department, 'Pending');
$approved_result = fetchApplications($conn, $advisor_department, 'Approved');
$rejected_result = fetchApplications($conn, $advisor_department, 'Rejected');

// Fetch all notifications for the panel (only from students on application submission)
$query_notifications_all = "
    SELECT message, created_at, is_read 
    FROM notifications 
    WHERE user_id = ? AND user_type = 'teacher' 
    ORDER BY created_at DESC
";
$stmt_notifications_all = $conn->prepare($query_notifications_all);
$stmt_notifications_all->bind_param("i", $advisor_id);
$stmt_notifications_all->execute();
$notifications_result_all = $stmt_notifications_all->get_result();

// Count unread notifications for the badge
$query_unread = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND user_type = 'teacher' AND is_read = 0";
$stmt_unread = $conn->prepare($query_unread);
$stmt_unread->bind_param("i", $advisor_id);
$stmt_unread->execute();
$unread_result = $stmt_unread->get_result();
$unread_count = $unread_result->fetch_assoc()['unread_count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advisor Dashboard</title>
    <link rel="stylesheet" href="../css/style_dashboard.css ?v=<?php echo time(); ?>"> <!-- Adjusted path -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Adjusted path -->
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
    </style>
</head>

<body>
    <button class="menu-toggle" aria-label="Toggle Sidebar">☰</button>
    <div class="sidebar">
        <h2>Advisor Dashboard</h2>
        <ul>
            <li><a href="advisor_dashboard.php">Dashboard</a></li>
            <li><a href="advisor_logout.php" class="logout-btn">Logout</a></li>
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
    <div class="main-content">
        <div class="header1">
            <div>
                <h2>University of Agriculture Faisalabad</h2>
                <p>Welcome, <?php echo htmlspecialchars($advisor_name); ?> (Department: <?php echo htmlspecialchars($advisor_department); ?>)</p>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="section">
            <h2>Pending Applications</h2>
            <?php if ($pending_result->num_rows > 0) : ?>
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $pending_result->fetch_assoc()) : ?>
                        <tr>
                            <td data-label="Application ID"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td data-label="Student Name"><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                            <td data-label="Status">
                                <span class="status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td data-label="Actions">
                                <a href="approve_application.php?id=<?php echo $row['id']; ?>" class="btn btn-approve">Approve</a>
                                <a href="reject_application.php?id=<?php echo $row['id']; ?>" class="btn btn-reject">Reject</a>
                                <a href="preview_application.php?id=<?php echo $row['id']; ?>" class="btn btn-review">Preview</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else : ?>
                <p class="no-applications">No pending applications found.</p>
            <?php endif; ?>
        </div>

        <!-- Approved Applications -->
        <div class="section">
            <h2>Approved Applications</h2>
            <?php if ($approved_result->num_rows > 0) : ?>
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $approved_result->fetch_assoc()) : ?>
                        <tr>
                            <td data-label="Application ID"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td data-label="Student Name"><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                            <td data-label="Status">
                                <span class="status-approved">Approved</span>
                            </td>
                            <td data-label="Actions">
                                <a href="preview_application.php?id=<?php echo $row['id']; ?>" class="btn btn-review">View Details</a>
                                <a href="reject_application.php?id=<?php echo $row['id']; ?>" class="btn btn-reject">Reject</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else : ?>
                <p class="no-applications">No approved applications found.</p>
            <?php endif; ?>
        </div>

        <!-- Rejected Applications -->
        <div class="section">
            <h2>Rejected Applications</h2>
            <?php if ($rejected_result->num_rows > 0) : ?>
                <table>
                    <tr>
                        <th>Application ID</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $rejected_result->fetch_assoc()) : ?>
                        <tr>
                            <td data-label="Application ID"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td data-label="Student Name"><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                            <td data-label="Status">
                                <span class="status-rejected">Rejected</span>
                            </td>
                            <td data-label="Actions">
                                <a href="preview_application.php?id=<?php echo $row['id']; ?>" class="btn btn-review">View Details</a>
                                <a href="approve_application.php?id=<?php echo $row['id']; ?>" class="btn btn-approve">Approve</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else : ?>
                <p class="no-applications">No rejected applications found.</p>
            <?php endif; ?>
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
            // Toggle the panel
            notificationPanel.classList.toggle('active');

            // If there are unread notifications, mark them as read via AJAX
            if (notificationBadge) {
                fetch('advisor_dashboard.php?action=mark_read')
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