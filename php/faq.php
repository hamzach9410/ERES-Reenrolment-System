<?php
session_start();
include 'db.php'; // Added database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - Re-enrollment System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/faq.css ?v=<?php echo time(); ?>"> <!-- Adjusted path -->
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Adjusted path, removed duplicate -->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark faq-nav">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="http://127.0.0.1:5500/#section1">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/university_db/php/discipline.php">Discipline</a> <!-- Updated path -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/university_db/php/institute.php">Institutes</a> <!-- Updated path -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/university_db/php/faq.php">Faqs</a> <!-- Updated path -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://localhost/university_db/php/login.php">Login</a> <!-- Updated path -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="faq-logo-section">
            <!-- University Logo -->
            <img src="../images/favicon.jpg" alt="University Logo" class="logo"> <!-- Adjusted path -->

            <!-- Campus Name -->
            <p class="campus-name">University of Agriculture Faisalabad<br>Sub Campus Depalpur Okara</p>
        </div>
        <h2 class="text-center mb-4">Frequently Asked Questions (FAQs)</h2>
        <div class="accordion" id="faqAccordion">

            <div class="accordion-item">
                <h2 class="accordion-header" id="q1">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#a1">
                        1. Who can apply for re-enrollment?
                    </button>
                </h2>
                <div id="a1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Students who have received a 'D' or 'F' grade in previous semesters can apply for re-enrollment in subjects matching their current semester parity (odd/even).
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="q2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2">
                        2. How do I check my eligible re-enrollment subjects?
                    </button>
                </h2>
                <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        The system automatically filters eligible subjects based on your current semester (odd/even) and previous performance.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="q3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a3">
                        3. Can I re-enroll in a subject from an even semester while in an odd semester?
                    </button>
                </h2>
                <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        No, students can only re-enroll in subjects from past semesters that match their current semester parity (odd/even).
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="q4">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a4">
                        4. How can I submit my re-enrollment application?
                    </button>
                </h2>
                <div id="a4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        You can submit your application through the student panel by selecting eligible subjects and confirming your submission.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="q5">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a5">
                        5. What happens after I submit my application?
                    </button>
                </h2>
                <div id="a5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Your application is forwarded to your advisor, who will review it and either approve or reject it. You will be notified of the decision.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="q6">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a6">
                        6. Can my advisor reject my application?
                    </button>
                </h2>
                <div id="a6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, the advisor can reject your application if it does not meet the eligibility criteria. You will be informed about the reason.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="q7">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a7">
                        7. What if my application is approved?
                    </button>
                </h2>
                <div id="a7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        If approved, your application will be forwarded to the respective teacher for further processing.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="q8">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a8">
                        8. Can I track the status of my application?
                    </button>
                </h2>
                <div id="a8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, you can check your application status in the student panel under the 'My Applications' section.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container text-center mt-4">
        <a href="http://127.0.0.1:5500/" class="btn btn-primary">Back to Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>