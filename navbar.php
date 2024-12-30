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

<!-- Main Navbar -->
<nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand text-white" href="#">Rochak Savings and Cooperative</a>
        <div class="navbar-nav flex-row justify-content-start">
            <a class="nav-link active text-white" aria-current="page" href="dashboardadmin.php">Saving accountholders</a>
            <a class="nav-link active text-white" aria-current="page" href="dashboardadmin.php">Fixed accountholders</a>
            <a class="nav-link active text-white" aria-current="page" href="loanrequest.php">Loan</a>
            <?php
            session_start();
            // Check if user is logged in
            if(isset($_SESSION['first_name'])) {
                echo '<div class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> ' . $_SESSION['first_name'] . '
                          </a>
                          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                          </ul>
                      </div>';
            }
            ?>
        </div>
    </div>
</nav>

<!-- Offcanvas Navbar -->
<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Menu</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="navbar-nav justify-content-start">
      <li class="nav-item">
        <a  class="nav-link" href="register.php">Register customer</a>      
      </li>
      <li class="nav-item">
        <a class="nav-link" href="allcustomer.php">All Account</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="viewfixedacc.php">Fixed Account</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="viewSaving.php">Saving Account</a>
      </li>
    </ul>
  </div>
</div>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
