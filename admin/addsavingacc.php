<?php
include('../connection.php'); // Include your database connection file

// Check if 'UID' and 'account_no' are set in the URL
if (isset($_GET['UID']) && isset($_GET['account_no'])) {
    $UID = $_GET['UID'];
    $account_no = $_GET['account_no'];

    // Check if the account exists for the given UID and account number
    $query = "SELECT * FROM saving_acc WHERE UID = ? AND account_no = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $UID, $account_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Account exists, fetch account details
        $account = $result->fetch_assoc();
    } else {
        // Account not found
        echo "Saving account not found for UID $UID and account number $account_no.";
        exit; // Exit script
    }

    // Handle form submission for deposit or withdrawal
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $amount = $_POST['amount'];
        $transaction_type = $_POST['transaction_type'];

        if ($transaction_type === 'deposit') {
            // Deposit operation
            $new_amount = $account['amount'] + $amount;
        } elseif ($transaction_type === 'withdraw') {
            // Withdrawal operation
            if ($amount <= $account['amount']) {
                $new_amount = $account['amount'] - $amount;
            } else {
                $message = "Insufficient balance for withdrawal.";
            }
        }

        // Update account balance in the database
        $query = "UPDATE saving_acc SET amount = ? WHERE UID = ? AND account_no = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("dss", $new_amount, $UID, $account_no);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Transaction was successful, update transaction history
            $query = "INSERT INTO transaction_history (account_no, transaction_type, amount, current_bal) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssdd", $account_no, $transaction_type, $amount, $new_amount);
            $stmt->execute();

            $message = $transaction_type === 'deposit' ? "Deposit successful!" : "Withdrawal successful!";
        } else {
            $message = "Error processing transaction. Please try again.";
        }

        $stmt->close();
    }

    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Saving Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <?php include('admin_navbar.php')?>
    <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'sidebar.php' ?>
        <div class="container mt-5">
            <h1>Manage Saving Account for Account no. <?php echo "$account_no"; ?></h1>
            <p>Account Balance: Rs <?php echo $account['amount']; ?></p>
            <h2>Deposit or Withdraw</h2>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="transaction_type" id="deposit" value="deposit" checked>
                    <label class="form-check-label" for="deposit">Deposit</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="transaction_type" id="withdraw" value="withdraw">
                    <label class="form-check-label" for="withdraw">Withdraw</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <br>
            <h4>
            <?php if(isset($message)) { ?>
                <p><?php echo $message; ?></p>
            <?php } ?>
            </h4>
        </div>    
    </div>
</body>
</html>
