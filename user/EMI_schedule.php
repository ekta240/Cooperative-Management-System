<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMI Schedule</title>
    <style>
        /* Add your CSS styles here */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: pink;
        }
    </style>
</head>
<body>
    <?php include 'user_navbar.php'; ?> <!-- Include your navigation bar -->
    
    <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'usersidebar.php'; ?> <!-- Include your sidebar -->
        
        <div class="container mt-2">
            
            <?php
            // Include the connection file
            include('connection.php');

            // Check if first_name and last_name parameters are present
            if (isset($_GET['first_name']) && isset($_GET['last_name'])) {
                $first_name = $_GET['first_name'];
                $last_name = $_GET['last_name'];

                // Query to fetch loan details from the database
                $loan_query = "SELECT `loan_id`, `status` FROM `loans` WHERE `first_name` = ? AND `last_name` = ?";
                $loan_stmt = $con->prepare($loan_query);
                $loan_stmt->bind_param("ss", $first_name, $last_name);
                $loan_stmt->execute();
                $loan_result = $loan_stmt->get_result();

                if ($loan_result->num_rows > 0) {
                    $loan_data = $loan_result->fetch_assoc();
                    $loan_id = $loan_data['loan_id'];
                    $status = $loan_data['status'];

                    // Check if the loan is approved
                    if ($status === 'approved') {
                        // Query to fetch EMI details from the database
                        $query = "SELECT `emi_id`, `emi_amount`, `due_date`, `payment_status` FROM `emis` WHERE `loan_id` = ?";
                        $stmt = $con->prepare($query);
                        $stmt->bind_param("i", $loan_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Check if the query was successful
                        if ($result->num_rows > 0) {
                            echo "<h2>EMI Schedule for Loan ID: $loan_id</h2>";
                            echo "<table>";
                            echo "<tr><th>EMI ID</th><th>EMI Amount</th><th>Due Date</th><th>Payment Status</th></tr>";

                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['emi_id'] . "</td>";
                                echo "<td>" . $row['emi_amount'] . "</td>";
                                echo "<td>" . $row['due_date'] . "</td>";
                                echo "<td>" . $row['payment_status'] . "</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                        } else {
                            echo "<p>No EMI records found for Loan ID: $loan_id.</p>";
                        }

                        $stmt->close();
                    } else {
                        echo "<script>alert('The loan request has not been approved or the request is pending.');</script>";
                    }
                } else {
                    echo "<script>alert('Loan ID not found for the provided name.');</script>";
                }

                $loan_stmt->close();
            } else {
                echo "<script>alert('Loan ID not specified.');</script>";
            }

            // Close the connection
            $con->close();
            ?>
        </div>
    </div>
</body>
</html>
