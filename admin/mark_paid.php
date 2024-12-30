<?php
// Include database connection
include('connection.php');

// Check if EMI ID is set in the URL
if (isset($_GET['id'])) {
    $emi_id = $_GET['id'];

    // Update payment status of the EMI to 'Paid'
    $update_query = "UPDATE emis SET payment_status = 'Paid' WHERE emi_id = ?";
    $update_stmt = $con->prepare($update_query);
    $update_stmt->bind_param("i", $emi_id);
    
    if ($update_stmt->execute()) {
        // Redirect back to the page displaying EMIs with a success message
        header("Location: emi_info.php?message=EMI+marked+as+paid");
        exit();
    } else {
        // Redirect back to the page displaying EMIs with an error message
        header("Location: view_emis.php?error=Failed+to+mark+EMI+as+paid");
        exit();
    }
} else {
    // Redirect back to the page displaying EMIs if EMI ID is not set in the URL
    header("Location: view_emis.php");
    exit();
}

// Close database connection
$con->close();
?>
