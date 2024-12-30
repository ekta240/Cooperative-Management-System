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
            height: 100vh; /* Set sidebar height to full viewport height */
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
        <div class="menu-title" >Menu</div>
        <div class="allcus" style="height: 78px; color: black; font-size: 29px; font-family: Inter; font-weight: 400; word-wrap: break-word; padding: 10px;"><a href="register.php">Register customer</a></div>
        <div class="allcus" style="height: 78px; color: black; font-size: 29px; font-family: Inter; font-weight: 400; word-wrap: break-word; padding: 10px;"><a href="allcustomer.php">All Account</a></div>
        <div class="fixed" style="height: 78px; color: black; font-size: 29px; font-family: Inter; font-weight: 400; word-wrap: break-word; padding: 10px;"><a href="viewfixedacc.php">Fixed Account</a></div>
        <div class="saving" style="height: 78px; color: black; font-size: 29px; font-family: Inter; font-weight: 400; word-wrap: break-word; padding: 10px;"><a href="viewSaving.php">Saving Account</a></div>
    </div>
    



<!-- Bootstrap JavaScript -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>
