<?php

// Check if the user is logged in and has a first name in the session
if(isset($_SESSION['first_name']) ) {
    // Store the user's first name in a variable
    $first_name = $_SESSION['first_name'];
    // $account_no = $_SESSION['account_no'];

} else {
    // Redirect the user to the login page if they are not logged in
    header("Location: login.php");
    exit; // Prevent further execution
}
?>

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
    <style>
        .sidebar {
            flex: 0 0 auto; /* Ensure sidebar doesn't grow or shrink */
            width: 300px; /* Set sidebar width */
            max-width: 30%; /* Set maximum sidebar width */
            height: 91vh; /*Set sidebar height to full viewport height */
            background: #BAB2B5; /* Sidebar background color */
            overflow-y: auto; /* Enable vertical scrollbar if content overflows */
        }
        .sidebar a {
            font-size: 25px; /* Increase text size */
            text-decoration: none; /* Remove text decoration */
            color: black;
            padding: 10px; /* Set text color */
        }
        .menu-title {
            font-size: 30px; /* Increase text size */
            font-weight: bold; /* Make the text bold */
            color: white; /* Set text color */
            padding: 10px;
            margin-bottom: 10px; 
        }
        .menu-item {
            margin-bottom: 40px; /* Add margin bottom to create a gap between items */
        }
    </style>
</head>
<body>
    <div class="d-flex flex-column mb-3 sidebar">
        <ul class="nav navbar-nav">
            <li class="menu-title">Menu</li>
            
            <li class="menu-item-has-children dropdown menu-item">
                <a href="transaction.php?id=<?php echo $_SESSION['account_no']; ?>">All Transaction</a>
            </li>
            <li class="menu-item-has-children dropdown menu-item">
                <a href="fixedacc.php?id=<?php echo urlencode($_SESSION['UID']); ?>&account_no=<?php echo urlencode($_SESSION['account_no']); ?>">Create FD</a>
            </li>
            <li class="menu-item-has-children dropdown menu-item">
                <a href="myfixedacc.php?id=<?php echo urlencode($_SESSION['UID']); ?>&account_no=<?php echo urlencode($_SESSION['account_no']); ?>">Fixed Deposit</a>
            </li>
            <li class="menu-item-has-children dropdown menu-item">
                <a href="loanprocess.php?first_name=<?php echo urlencode($_SESSION['first_name']); ?>&last_name=<?php echo urlencode($_SESSION['last_name']); ?>">Loan Request</a>
            </li>
            <li class="menu-item-has-children dropdown menu-item">
                <a href="EMI_schedule.php?first_name=<?php echo urlencode($_SESSION['first_name']); ?>&last_name=<?php echo urlencode($_SESSION['last_name']); ?>">EMI Schedule</a>
            </li>



            <!-- <li class="menu-item-has-children dropdown menu-item"> -->
                <!-- <?php if(isset($_SESSION['account_no'])) : ?> -->
                    <!-- <a href="mysaving.php?id=<?php echo $_SESSION['account_no']; ?>">My Saving</a> -->
                <!-- <?php else : ?> -->
                    <!-- <a href="#">My Saving</a> Provide a fallback link or handle the case where account_no is not set -->
                <!-- <?php endif; ?> -->
            <!-- </li> -->
        </ul>
    </div>
    <!-- Bootstrap JavaScript -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>
