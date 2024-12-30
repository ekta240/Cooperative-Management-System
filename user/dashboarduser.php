<?php
include('../connection.php'); // Assuming your connection file is in the parent directory
session_start();
// Check if 'account_no' is set in the session
if(isset($_SESSION['account_no'])) {
    $account_no = $_SESSION['account_no'];
    $query = "SELECT amount FROM saving_acc WHERE account_no = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $account_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the row from the result set
        $row = $result->fetch_assoc();
        $saving_amount = $row['amount'];
    } else {
        $saving_amount = "N/A";
    }

    // Close the statement
    $stmt->close();
} else {
    echo "No records found for the given account number.";
}

// Fetch transaction data for the donut chart
$query_transactions = "SELECT SUM(amount) as total_amount, transaction_type 
                       FROM transaction_history 
                       WHERE account_no = ? AND transaction_type IN ('withdraw', 'deposit') 
                       GROUP BY transaction_type";
$stmt_transactions = $con->prepare($query_transactions);
$stmt_transactions->bind_param("s", $account_no);
$stmt_transactions->execute();
$result_transactions = $stmt_transactions->get_result();

// Initialize arrays to store data for the donut chart
$transaction_labels = array();
$transaction_data = array();

// Fetching data for the donut chart
while ($row = $result_transactions->fetch_assoc()) {
    $transaction_labels[] = $row['transaction_type'];
    $transaction_data[] = $row['total_amount'];
}

// Close the statement
$stmt_transactions->close();

// Fetch transaction data for the bar chart (last 6 months) based on the number of transactions
$query_monthly_transactions = "
    SELECT DATE_FORMAT(timestamp, '%Y-%m') AS month, COUNT(*) AS transaction_count
    FROM transaction_history
    WHERE account_no = ? AND timestamp >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY month
    ORDER BY month";
$stmt_monthly_transactions = $con->prepare($query_monthly_transactions);
$stmt_monthly_transactions->bind_param("s", $account_no);
$stmt_monthly_transactions->execute();
$result_monthly_transactions = $stmt_monthly_transactions->get_result();

// Initialize arrays to store data for the bar chart
$monthly_labels = array();
$monthly_data = array();

// Fetching data for the bar chart
while ($row = $result_monthly_transactions->fetch_assoc()) {
    $monthly_labels[] = $row['month'];
    $monthly_data[] = $row['transaction_count'];
}

// Close the statement
$stmt_monthly_transactions->close();

// Close the connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Balance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #charts {
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
            width: 100%;
            gap: 100px; /* Add space between charts */
        }
        canvas {
            width: 400px !important;
            height: 400px !important;
        }
    </style>
</head>
<body>
    <?php include 'user_navbar.php'; ?>
    <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'usersidebar.php'; ?>
        <div>
            <!-- Display user's balance here -->
            <h1 style="padding-left: 50px; padding-bottom: 50px">Current balance: <?php echo isset($saving_amount) ? $saving_amount : 'N/A'; ?></h1>
            <div id="charts">
                <!-- Donut chart for transaction types -->
                <canvas id="transactionChart"></canvas>
                <!-- Bar chart for monthly transactions -->
                <canvas id="monthlyTransactionChart"></canvas>
            </div>
            <script>
                var ctx = document.getElementById('transactionChart').getContext('2d');
                var transactionChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: <?php echo json_encode($transaction_labels); ?>,
                        datasets: [{
                            label: 'Transaction Amount',
                            data: <?php echo json_encode($transaction_data); ?>,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)', // Red for Withdraw
                                'rgba(54, 162, 235, 0.5)', // Blue for Deposit
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });

                var ctx2 = document.getElementById('monthlyTransactionChart').getContext('2d');
                var monthlyTransactionChart = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($monthly_labels); ?>,
                        datasets: [{
                            label: 'Number of Transactions',
                            data: <?php echo json_encode($monthly_data); ?>,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>    
</body>
</html>
