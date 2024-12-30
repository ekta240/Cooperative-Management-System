<?php

// Include database connection
$mysqli = require __DIR__ . "/connection.php";

// Get token and passwords from POST data
$token = $_POST["token"];
$password = $_POST["password"];
$password_confirmation = $_POST["password_confirmation"];

// Prepare and execute query to fetch user by token
$sql = "SELECT * FROM users WHERE reset_token_hash = ?";
$stmt = $mysqli->prepare($sql);
$token_hash = hash("sha256", $token);
$stmt->bind_param("s", $token_hash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user exists and token is valid
if (!$user) {
    die("Token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}

// Trim passwords and debug statements
$password = trim($password);
$password_confirmation = trim($password_confirmation);



// Check if passwords match
if ($password !== $password_confirmation) {
    die("Passwords must match");
}

// Validate password strength (optional)
if (strlen($password) < 8) {
    die("Password must be at least 8 characters");
}

if (!preg_match("/[a-zA-Z]/", $password)) {
    die("Password must contain at least one letter");
}

if (!preg_match("/[0-9]/", $password)) {
    die("Password must contain at least one number");
}

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Update user's password and clear reset token
$sql = "UPDATE users
        SET password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE UID = ?";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Error in SQL query: " . mysqli_error($mysqli));
}

$stmt->bind_param("si", $password_hash, $user["UID"]);
$stmt->execute();

echo "Password updated. You can now login.";

// Close database connection
$stmt->close();
$mysqli->close();
?>


