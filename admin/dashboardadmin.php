<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        /* Adjust padding of navbar links */
        .navbar-nav .nav-link {
            padding: 0.5rem 1rem; /* You can adjust these values as needed */
        }
        
        /* Adjust the size of the chart */
        #transactionChart {
            width: 500px !important;
            height: 500px !important;
        }
    </style>
</head>
<body>
    <?php include 'admin_navbar.php' ?>
    <div class="d-flex" style="display: flex; width: 100%;">
        
        <?php include 'sidebar.php' ?>

        <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
            <h1 style="color: black; font-size: 46px; text-align: center;">Account Holders</h1>

            <?php
            include('connection.php');

            // Count the number of saving account holders
            $savingQuery = "SELECT COUNT(*) as saving_count FROM saving_acc";
            $savingResult = mysqli_query($con, $savingQuery) or die("Saving Account count error");
            $savingCount = mysqli_fetch_assoc($savingResult)['saving_count'];

            // Count the number of fixed account holders
            $fixedQuery = "SELECT COUNT(*) as fixed_count FROM fixed_acc";
            $fixedResult = mysqli_query($con, $fixedQuery) or die("Fixed Account count error");
            $fixedCount = mysqli_fetch_assoc($fixedResult)['fixed_count'];

            // Fetch transaction data from the database
            $query_transactions = "SELECT SUM(amount) as total_amount, transaction_type 
                       FROM transaction_history 
                       WHERE transaction_type IN ('withdraw', 'deposit') 
                       GROUP BY transaction_type";

            $result_transactions = mysqli_query($con, $query_transactions);

            // Initialize arrays to store data for the chart
            $transaction_labels = array();
            $transaction_data = array();

            // Fetching data for the chart
            while ($row = mysqli_fetch_assoc($result_transactions)) {
                $transaction_labels[] = $row['transaction_type'];
                $transaction_data[] = $row['total_amount'];
            }

            // Close connection
            mysqli_close($con);
            ?>

            <div class="btn-group mt-3" role="group">
                <button type="button" class="btn btn-danger btn-lg mx-2">
                    Total Saving Account Holders:<br> <?php echo $savingCount; ?>
                </button>

                <button type="button" class="btn btn-warning btn-lg mx-2">
                    Total Fixed Account Holders: <br><?php echo $fixedCount; ?>
                </button>
            </div>
            <!-- Display donut chart -->
            <canvas id="transactionChart" style="height: 650px;width:650px"></canvas>
        </div>
    </div>

    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script to initialize the chart -->
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
    </script>
</body>
</html>
