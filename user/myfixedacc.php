<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Fixed Accounts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">    
    <style>
        /* Custom CSS */
        .thead {
            background-color: pink;
            color: yellow;
            text-align: center;
        }
        
        /* Decrease width of table */
        .table-bordered {
            width: 60%;
        }

        /* Style buttons */
        .table-bordered tbody button {
            width: 100%; /* Make buttons same size */
        }

        /* Align buttons in center */
        .table-bordered tbody button {
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>
    <?php include 'user_navbar.php' ?>
    <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'usersidebar.php' ?>
        <?php
include('../connection.php'); // Include your database connection file

// Function to calculate interest
function calculateInterest($amount, $interestRate, $timePeriod, $compoundingType) {
    $timePeriodYears = $timePeriod / 12;
    switch ($compoundingType) {
        case 'quarterly':
            $totalInterest = $amount * pow((1 + $interestRate / 4 / 100), 4 * $timePeriodYears) - $amount;
            $quarterlyInterestPayment = $amount * ($interestRate / 4 / 100);
            return ['totalInterest' => $totalInterest, 'periodicPayment' => $quarterlyInterestPayment];
        case 'annually':
            $totalInterest = $amount * pow((1 + $interestRate / 100), $timePeriodYears) - $amount;
            $annualInterestPayment = $amount * ($interestRate / 100);
            return ['totalInterest' => $totalInterest, 'periodicPayment' => $annualInterestPayment];
        case 'wholely':
            $totalInterest = $amount * ($interestRate / 100) * $timePeriodYears;
            return ['totalInterest' => $totalInterest, 'periodicPayment' => $totalInterest]; // Total interest shown as periodic payment
        default:
            return ['totalInterest' => 0, 'periodicPayment' => 0];
    }
}

// Check if 'UID' and 'account_no' are set in the session
if (isset($_SESSION['UID']) && isset($_SESSION['account_no'])) {
    $UID = $_SESSION['UID'];
    $account_no = $_SESSION['account_no'];

    // Query to fetch all fixed accounts associated with the user
    $query = "SELECT * FROM fixed_acc WHERE UID = ? AND account_no = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $UID, $account_no);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are fixed accounts associated with the user
    if ($result->num_rows > 0) {
        echo "<div class='container mt-5'>";
        echo "<h1>Fixed Accounts for Account: $account_no</h1>";
        echo "<table class='table table-bordered'>";
        echo "<thead class='thead'>
                <tr>
                    <th>Amount</th>
                    <th>Interest Rate</th>
                    <th>Time Period</th>
                    <th>Type</th>
                    <th>Periodic Payment</th>
                </tr>
              </thead>";
        echo "<tbody>";
        
        // Display each fixed account
        while ($row = $result->fetch_assoc()) {
            $amount = $row['amount'];
            $interestRate = $row['interest'];
            $timePeriod = $row['time_period'];
            $type = $row['type'];

            // Calculate interest based on compounding type
            $interestData = calculateInterest($amount, $interestRate, $timePeriod, $type);
            $periodicPayment = $interestData['periodicPayment'];

            echo "<tr>";
            echo "<td>{$amount}</td>";
            echo "<td>{$interestRate}%</td>";
            echo "<td>{$timePeriod} months</td>";
            echo "<td>{$type}</td>";
            echo "<td>{$periodicPayment}</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='container mt-5'>";
        echo "No fixed accounts found for User: $UID, Account: $account_no";
        echo "</div>";
    }

    // Close prepared statement
    $stmt->close();
} else {
    echo "<div class='container mt-5'>";
    echo "UID and account_no not set in the session";
    echo "</div>";
}

// Close database connection
$con->close();
?>


    </div>
</body>
</html>
