<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure the body stretches to at least the full height of the viewport */
        }
        .content {
            flex: 1; /* This makes the content div take up all available vertical space */
            display: flex;
        }
        .sidebar {
            /* Add styles for sidebar if needed */
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: pink;
        }
        
    </style>
</head>
<body>
    <?php include 'user_navbar.php' ?>
    <div class="content">
        <?php include 'usersidebar.php' ?>
        <div>
            <?php
                include('../connection.php'); // Assuming your connection file is in the parent directory

                // Check if 'account_no' is set in the URL
                if (isset($_GET['id'])) {
                    $account_no = $_GET['id'];

                    // Fetch transaction history for the user
                    $query = "SELECT * FROM transaction_history WHERE account_no = ?";
                    $stmt = $con->prepare($query);
                    $stmt->bind_param("s", $account_no);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Display transaction history
                        echo "<br><br><div style=\"margin-left: 20px;\">";
                        echo "<h2>Transaction History for Account Number: $account_no</h2>";
                        echo "<table>";
                        echo "<tr><th>Transaction ID</th><th>Transaction Type</th><th>Amount</th><th>Timestamp</th><th>Current Balance</th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['transaction_id'] . "</td>";
                            echo "<td>" . $row['transaction_type'] . "</td>";
                            echo "<td>" . $row['amount'] . "</td>";
                            echo "<td>" . $row['timestamp'] . "</td>";
                            echo "<td>" . $row['Current_bal'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table></div>";
                    } else {
                        echo "No transaction history found for Account Number: $account_no";
                    }

                    $stmt->close();
                } else {
                    echo "Invalid request. Missing 'account_no' parameter.";
                }

                $con->close();
            ?>
        </div>
    </div>
</body>
</html>
