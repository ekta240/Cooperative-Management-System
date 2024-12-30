<?php
include('connection.php'); // Include your database connection file

// Function to calculate interest
function calculateInterest($amount, $interestRate) {
    return ($amount * $interestRate);
}

// Open or create the log.txt file in append mode
$logFile = fopen("log.txt", "a");

// Fetch all records from saving_acc table
$query = "SELECT SID, UID, account_no, amount, interest, last_interest_date FROM saving_acc";
$result = $con->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $SID = $row['SID'];
        $UID = $row['UID'];
        $account_no = $row['account_no'];
        $amount = $row['amount'];
        $interestRate = $row['interest'];
        $lastInterestDate = $row['last_interest_date'];
        $currentDate = date('Y-m-d');

        // Check if interest has already been added for this month
        if ($lastInterestDate === null || date('Y-m', strtotime($lastInterestDate)) !== date('Y-m')) {
            // Calculate interest
            $interestAmount = calculateInterest($amount, $interestRate);

            // Update the amount in the saving_acc table
            $newAmount = $amount + $interestAmount;
            $updateQuery = "UPDATE saving_acc SET amount = ?, last_interest_date = ? WHERE SID = ?";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->bind_param("dsi", $newAmount, $currentDate, $SID);
            $updateStmt->execute();

            if ($updateStmt->affected_rows > 0) {
                // Insert the transaction into the transaction_history table
                $transactionType = 'Interest from saving account';
                $transactionQuery = "INSERT INTO transaction_history (account_no, transaction_type, amount, current_bal) VALUES (?, ?, ?, ?)";
                $transactionStmt = $con->prepare($transactionQuery);
                $transactionStmt->bind_param("ssdd", $account_no, $transactionType, $interestAmount, $newAmount);
                $transactionStmt->execute();

                if ($transactionStmt->affected_rows > 0) {
                    $message = "Interest of $interestAmount added to account number $account_no. New balance is $newAmount.";
                } else {
                    $message = "Error updating transaction history for account number $account_no.";
                }

                // Write the message to the log file
                fwrite($logFile, date('Y-m-d H:i:s') . " - $message" . PHP_EOL);
            } else {
                $message = "Error updating amount for account number $account_no.";
                fwrite($logFile, date('Y-m-d H:i:s') . " - $message" . PHP_EOL);
            }

            $updateStmt->close();
        } else {
            $message = "Interest already added for account number $account_no this month.";
            fwrite($logFile, date('Y-m-d H:i:s') . " - $message" . PHP_EOL);
        }
    }
} else {
    $message = "No records found in saving_acc table.";
    fwrite($logFile, date('Y-m-d H:i:s') . " - $message" . PHP_EOL);
}

// Close the log file
fclose($logFile);

// Close the database connection
$con->close();
?>
