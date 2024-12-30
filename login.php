<?php
session_start(); // Start session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('connection.php');

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Retrieve user input from the login form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic server-side validation
    if (empty($email) || empty($password)) {
        echo "Both email and password are required.";
    } else {
        // Fetch user data from the database using a prepared statement
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the password using password_verify
            if (password_verify($password, $row['password'])) {
                // Store user data in session
                $_SESSION['UID'] = $row['UID'];
                $_SESSION['first_name'] = $row['first_name']; // Assuming first_name is the user's name
                $_SESSION['last_name'] = $row['last_name'];
                $_SESSION['account_no'] = $row['account_no'];
                // Redirect based on user type
                if ($row['usertypes'] == "user") {
                    header("Location: user/dashboarduser.php");
                } elseif ($row['usertypes'] == "admin"){
                    header("Location: admin/dashboardadmin.php");
                }
                exit(); // Make sure to exit after a header redirect
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "User with this email does not exist.";
        }

        $stmt->close();
    }

    $con->close();
}
?>
