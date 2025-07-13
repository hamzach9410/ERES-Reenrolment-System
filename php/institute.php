<?php
session_start();
include 'db.php'; // Added database connection

$institutes = [
    [
        "name" => "University of Agriculture Faisalabad - Main Campus",
        "address" => "University of Agriculture, Faisalabad, Punjab, Pakistan",
        "image" => "../images/uaf_main.jpg" // Adjusted path
    ],
    [
        "name" => "UAF Sub Campus Depalpur Okara",
        "address" => "Depalpur, Okara, Punjab, Pakistan",
        "image" => "../images/uaf_depalpur.jpg" // Adjusted path
    ],
    [
        "name" => "UAF Sub Campus Burewala ",
        "address" => "Burewala, Punjab, Pakistan",
        "image" => "../images/uaf_burewala.jpg" // Adjusted path
    ],
    [
        "name" => "UAF Sub Campus Toba Tek Singh",
        "address" => "Toba Tek Singh, Punjab, Pakistan",
        "image" => "../images/uaf_tts.jpg" // Adjusted path
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAF Institutes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/institute.css ?v=<?php echo time(); ?>"> <!-- Adjusted path -->
    <link rel="icon" type="image/gif" href="../images/logo1.gif"> <!-- Adjusted path, removed duplicate -->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark contain">
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

    <header>
        <div class="container">
            <div class="lo">
                <!-- University Logo -->
                <img src="../images/favicon.jpg" alt="University Logo" class="logo"> <!-- Adjusted path -->

                <!-- Campus Name -->
                <p class="campus-name">University of Agriculture Faisalabad<br>Sub Campus Depalpur Okara</p>
            </div>

            <div class="lo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card shadow-lg">
                            <img src="<?= htmlspecialchars($institutes[0]['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($institutes[0]['name']); ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($institutes[0]['name']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($institutes[0]['address']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <?php for ($i = 1; $i < count($institutes); $i++) : ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-lg">
                                <img src="<?= htmlspecialchars($institutes[$i]['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($institutes[$i]['name']); ?>">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?= htmlspecialchars($institutes[$i]['name']); ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($institutes[$i]['address']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </header>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>