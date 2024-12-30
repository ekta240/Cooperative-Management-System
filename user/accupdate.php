<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $UID = $_POST['UID'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    // Using prepared statement to prevent SQL injection
    $query = "UPDATE users SET first_name=?, last_name=?, email=?, dob=?,  WHERE UID=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $dob, $UID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Your account has been updated successfully";
    } else {
        echo "No user found with UID $UID or no changes made";
    }

    $stmt->close();
} else {
    echo "Invalid request. Missing form submission data.";
}

$con->close();

?>
