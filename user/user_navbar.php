<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar {
    max-width: 1600px; /* Set max-width */
    height: 80px; /* Adjust height as needed */
}

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark" >

    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">Rochak Savings and Cooperative</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active text-white" aria-current="page" href="dashboarduser.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="">About_us </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="#">Contact_us </a>
                </li>
                
            </ul>
            <?php
            if(session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            // Check if user is logged in
            if(isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) {
                echo '<div class="dropdown ml-auto">
                <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i> ' . $_SESSION['first_name'] . '
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="accprofile.php?id=' . $_SESSION['UID'] . '">Account Profile</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </div>';
            }
            ?>
            
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
