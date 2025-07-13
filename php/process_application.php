<?php
session_start();
include 'db.php'; // Updated path to db.php

if (!isset($_SESSION['advisor_id'])) {
    header("Location: advisor_login.php"); // Updated path
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['application_id'])) {
    $application_id = $_POST['application_id'];
    $status = isset($_POST['approve']) ? "Approved" : "Rejected";

    $sql = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $application_id);
    $stmt->execute();
    $stmt->close();

    header("Location: advisor_dashboard.php"); // Updated path
    exit();
}

$conn->close();
