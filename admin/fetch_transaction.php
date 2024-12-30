<?php
include ("connection.php");
$query = "SELECT `transaction_id`, `account_no`, `transaction_type`, `amount`, `timestamp` FROM `transaction_history`";

$result = mysqli_query($con, $query);

if ($result) {
    // Initialize an array to store the transaction data
    $transactions = array();

    // Fetch associative array of the result row by row
    while ($row = mysqli_fetch_assoc($result)) {
        // Append each row to the transactions array
        $transactions[] = $row;
    }

    // Free result set
    mysqli_free_result($result);

    // Close connection
    mysqli_close($con);

    // Send JSON response containing the transaction data
    echo json_encode($transactions);
} else {
    // If the query fails, send an error message
    echo "Error executing query: " . mysqli_error($con);
}

?>
