<?php
include('../connection.php');
include('user_navbar.php');

// Check if 'UID' and 'account_no' are set in the session
if(isset($_SESSION['UID']) && isset($_SESSION['account_no'])) {
    $UID = $_SESSION['UID'];
    $account_no = $_SESSION['account_no'];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve new information from the form
        $amount = $_POST['amount'];
        $interest = $_POST['interest'];
        $time_period = $_POST['time_period'];
        $type = $_POST['type'];

        // Calculate maturity date based on the selected time period
        $maturity_date = date('Y-m-d', strtotime("+$time_period months"));

        // Check if the user has sufficient balance in their savings account
        $query = "SELECT amount FROM saving_acc WHERE UID = ? AND account_no = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ss", $UID, $account_no);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $savings_balance = $row['amount'];
            $stmt->close();

            // Check if the user has enough balance for the fixed account
            if ($savings_balance >= $amount) {
                $updated_savings_balance = $savings_balance - $amount;
                $update_query = "UPDATE saving_acc SET amount = ? WHERE UID = ? AND account_no = ?";
                $update_stmt = $con->prepare($update_query);
                $update_stmt->bind_param("dss", $updated_savings_balance, $UID, $account_no);
                $update_stmt->execute();

                if ($update_stmt->affected_rows > 0) {
                    // Insert new Fixed Account information into the database
                    $insert_query = "INSERT INTO fixed_acc (UID, account_no, amount, interest, time_period, type, created_date, maturity_date) VALUES (?, ?, ?, ?, ?, ?, CURDATE(), ?)";
                    $insert_stmt = $con->prepare($insert_query);
                    $insert_stmt->bind_param("sssdiss", $UID, $account_no, $amount, $interest, $time_period, $type, $maturity_date);
                    $insert_stmt->execute();

                    if ($insert_stmt->affected_rows > 0) {
                        // Transaction was successful, update transaction history
                        $transaction_type = 'Fixed account open';
                        $query = "INSERT INTO transaction_history (account_no, transaction_type, amount, current_bal) VALUES (?, ?, ?, ?)";
                        $stmt = $con->prepare($query);
                        $stmt->bind_param("ssdd", $account_no, $transaction_type, $amount, $updated_savings_balance);
                        $stmt->execute();
                        echo "<script>alert('Fixed Account added successfully!');</script>";
                    } else {
                        echo "<script>alert('Error adding Fixed Account. Please try again.');</script>";
                    }

                    $insert_stmt->close();
                } else {
                    echo "<script>alert('Error updating savings account. Please try again.');</script>";
                }

                $update_stmt->close();
            } else {
                echo "<script>alert('Insufficient balance in the savings account.');</script>";
            }
        } else {
            echo "<script>alert('Savings account not found for user.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Fixed Account</title>
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
<div class="d-flex" style="display: flex; width: 100%;">
    <?php include 'usersidebar.php' ?>
    <div style="margin-left: 20px;">
    <br><br>
        <h1>Add Fixed Account for <?php echo "Account: $account_no"; ?></h1>
        <form action="" method="post">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        
            <table class="table table-bordered">
                <thead class="thead">
                    <tr>
                        <th>Time Period</th>
                        <th>Quarterly Interest Rate</th>
                        <th>Annually Interest Rate</th>
                        <th>Wholelly Interest Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <input type="hidden" id="type" name="type">
                    <input type="hidden" id="time_period" name="time_period">
                    <input type="hidden" id="interest" name="interest">

                    <tr>
                        <td>1 year</td>
                        <td>
                            <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(12, 12, 'quarterly',1);">12%</button>
                            <input type="hidden" id="quarterly_interest_1" name="quarterly_interest">
                            <input type="hidden" id="quarterly_time_period_1" name="quarterly_time_period">
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(13, 12, 'annually',1);">13%</button>
                            <input type="hidden" id="annually_interest_1" name="annually_interest">
                            <input type="hidden" id="annually_time_period_1" name="annually_time_period">
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(12, 12, 'wholely',1);">12%</button>
                            <input type="hidden" id="wholely_interest_1" name="wholely_interest">
                            <input type="hidden" id="wholely_time_period_1" name="wholely_time_period">
                        </td>
                    </tr>
                    <tr>
                    <td>2 year</td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(13, 24, 'quarterly',2);">13%</button>
                        <input type="hidden" id="quarterly_interest_2" name="quarterly_interest">
                        <input type="hidden" id="quarterly_time_period_2" name="quarterly_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(13.5, 24, 'annually',2);">13.5%</button>
                        <input type="hidden" id="annually_interest_2" name="annually_interest">
                        <input type="hidden" id="annually_time_period_2" name="annually_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(13, 24, 'wholely',2);">13%</button>
                        <input type="hidden" id="wholely_interest_2" name="wholely_interest">
                        <input type="hidden" id="wholely_time_period_2" name="wholely_time_period">
                    </td>
                </tr>
                <tr>
                    <td>3 year</td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(14, 36, 'quarterly',3);">14%</button>
                        <input type="hidden" id="quarterly_interest_3" name="quarterly_interest">
                        <input type="hidden" id="quarterly_time_period_3" name="quarterly_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(14.25, 36, 'annually',3);">14.25%</button>
                        <input type="hidden" id="annually_interest_3" name="annually_interest">
                        <input type="hidden" id="annually_time_period_3" name="annually_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(14.5, 36, 'wholely',3);">14.5%</button>
                        <input type="hidden" id="wholely_interest_3" name="wholely_interest">
                        <input type="hidden" id="wholely_time_period_3" name="wholely_time_period">
                    </td>
                </tr>
                </tbody>
            </table>
            <!-- Input field for maturity date -->
            <div class="mb-3">
                <label for="maturity_date" class="form-label">Maturity Date</label>
                <input type="date" class="form-control" id="maturity_date" name="maturity_date" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Add Fixed Account</button>
        </form>
    </div>
</div>

<script>
    function setInterestAndTimePeriod(interest, timePeriod, type, row) {
        document.getElementById(type + '_interest_' + row).value = interest;
        document.getElementById(type + '_time_period_' + row).value = timePeriod;
        document.getElementById('interest').value = interest;
        document.getElementById('time_period').value = timePeriod;
        document.getElementById('type').value = type;

        // Calculate maturity date
        const currentDate = new Date();
        const maturityDate = new Date(currentDate.setMonth(currentDate.getMonth() + timePeriod));
        const maturityDateString = maturityDate.toISOString().split('T')[0];
        document.getElementById('maturity_date').value = maturityDateString;
    }
</script>
<?php
$con->close();
?>
</body>
</html>
