<?php
session_start();
session_destroy();
header("Location: advisor_login.php"); // Updated path
exit;
