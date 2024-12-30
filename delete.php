<?php

include('connection.php');

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $UID = $_GET['id']; // Use 'id' because that's what you used in the link

    // Using prepared statement to prevent SQL injection
    $query = "DELETE FROM users WHERE UID = ?";
    $stmt = $con->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $UID); // Assuming UID is an integer, use "s" if it's a string
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "User with UID $UID deleted successfully";
        } else {
            echo "No user found with UID $UID";
        }

        $stmt->close();
    } else {
        echo "Error in preparing the statement: " . $con->error;
    }
} else {
    echo "Invalid request. Missing 'id' parameter.";
}

$con->close();
?>
