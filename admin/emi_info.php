<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMI Info</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Custom CSS */
        .admin-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000; /* Ensure the navbar appears above other content */
        }
        
        .sidebar {
            position: fixed;
            top: 40px; /* Height of the navbar */
            left: 0;
            width: 200px;
            background-color: #f4f4f4;
            padding: 5px;
            box-sizing: border-box;
            z-index: 999; /* Ensure the sidebar appears above other content */
        }

        .content {
            margin-top: 40px; /* Height of the navbar */
            margin-left: 200px; /* Width of the sidebar */
            padding: 50px 50px 50px 120px;
            box-sizing: border-box;
            overflow-y: auto; /* Allow content to scroll vertically */
        }

        .table-container {
            width: 100%; /* Fill the width of the content container */
            margin: auto; /* Center the table */
        }

        .table-container table {
            width: 100%; /* Fill the width of the table container */
            border-collapse: collapse;
        }

        .table-container th, .table-container td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
       

        .table-container th, .table-container td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .table-container th {
            background-color: pink;
            color: black;
        }

        .table-container tbody button {
            width: 100%; /* Make buttons same size */
            display: block;
            margin: auto; /* Align buttons in center */
        }
    </style>
</head>
<body>
    <div class="admin-navbar">
        <?php include 'admin_navbar.php' ?>
    </div>

    <div class="d-flex">
        <div class="sidebar">
        <?php include 'sidebar.php' ?>
        </div>

        <div class="content">
            

            <div class="table-container">
            <h1>EMI schedule</h1>
            <?php
                $loan_id = $_GET['id'];
                include('connection.php');

                $select_query = "SELECT * FROM emis WHERE loan_id = '$loan_id'";
                $result = $con->query($select_query);

                if ($result->num_rows > 0) {
                    echo "<table>";
                    echo "<thead><tr><th>EMI ID</th><th>Loan ID</th><th>EMI Amount</th><th>Due Date</th><th>Status</th><th>Action</th></tr></thead>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['emi_id'] . "</td>";
                        echo "<td>" . $row['loan_id'] . "</td>";
                        echo "<td>" . $row['emi_amount'] . "</td>";
                        echo "<td>" . $row['due_date'] . "</td>";
                        echo "<td>" . $row['payment_status'] . "</td>";
                        echo "<td><a href='mark_paid.php?id=" . $row['emi_id'] . "'>Mark Paid</a></td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "The loan request has not been approved.";
                }

                // Close database connection
                $con->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>
