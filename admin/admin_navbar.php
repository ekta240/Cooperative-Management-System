<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Banking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
                    <a class="nav-link active text-white" aria-current="page" href="dashboardadmin.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="savingacc.php">Saving accountholders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="viewfixedacc.php">Fixed accountholders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="loanrequest.php">Loan</a>
                </li>
            </ul>
            <?php
            session_start();
            // Check if user is logged in
            if(isset($_SESSION['first_name'])) {
                echo '<div class="dropdown ml-auto">
                          <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> ' . $_SESSION['first_name'] . '
                          </a>
                          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                            </ul>
                        </div>';
            }
            ?>
        </div>
    </div>
</nav>


<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
