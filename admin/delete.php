<?php
include('connection.php');

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Begin a transaction
    mysqli_begin_transaction($con);

    try {
        // Delete related records in the transaction_history table
        $query = "DELETE FROM transaction_history WHERE account_no IN (SELECT account_no FROM users WHERE UID = ?)";
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Error preparing the statement for transaction_history.");
        }

        // Delete related records in the saving_acc table
        $query = "DELETE FROM saving_acc WHERE UID = ?";
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Error preparing the statement for saving_acc.");
        }

        // Delete related records in the fixed_acc table
        $query = "DELETE FROM fixed_acc WHERE UID = ?";
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Error preparing the statement for fixed_acc.");
        }

        // Delete the user from the users table
        $query = "DELETE FROM users WHERE UID = ?";
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Error preparing the statement for users.");
        }

        // Commit the transaction
        mysqli_commit($con);

        // Display success message using JavaScript alert
        echo "<script>alert('User and related records deleted successfully.');</script>";
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        mysqli_rollback($con);

        // Display error message using JavaScript alert
        echo "<script>alert('Error deleting user: " . $e->getMessage() . "');</script>";
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
}

// Close the database connection
mysqli_close($con);
?>

<script>
    // Redirect to view_users.php after showing alert
    window.location.href = 'allcustomer.php';
</script>
