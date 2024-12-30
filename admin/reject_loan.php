<?php
include('connection.php');

if (isset($_GET['id'])) {
    $loan_id = $_GET['id'];

    $update_query = "UPDATE loans SET status = 'Rejected' WHERE loan_id = ?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param("i", $loan_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: loanrequest.php");
        exit();
    } else {
        echo "Failed to reject loan request. Please try again.";
    }

    $stmt->close();
} else {
    echo "Loan ID not specified.";
}

$con->close();
?>
