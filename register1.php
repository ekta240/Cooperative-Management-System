<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection (replace with your credentials)
    include('connection.php');

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Retrieve user input from the form
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic server-side validation
    if (empty($firstName) || empty($lastName) || empty($dob) || empty($email) || empty($password)) {
        echo "All fields are required.";
    } else {
        // Check if the user is at least 18 years old
        $today = new DateTime();
        $birthdate = new DateTime($dob);
        $age = $birthdate->diff($today)->y;

        if ($age < 18) {
            echo "You must be at least 18 years old to register.";
        } else {
            if (strlen($_POST["password"]) < 8) {
                die("Password must be at least 8 characters");
            }
            
            if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
                die("Password must contain at least one letter");
            }
            
            if ( ! preg_match("/[0-9]/", $_POST["password"])) {
                die("Password must contain at least one number");
            }
            
            if ($_POST["password"] !== $_POST["password_confirmation"]) {
                die("Passwords must match");
            }
            // Hash the password (use a stronger hashing method in production)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $sql = "INSERT INTO users (first_name, last_name, dob, email, password) VALUES ('$firstName', '$lastName', '$dob', '$email', '$hashedPassword')";

            if ($con->query($sql) === TRUE) {
                echo "Registration is successful! <a href='loginpage.html'>Login to your account</a>";
                // Redirect to login page
                // header("Location: loginpage.html");
                // exit();  // Ensure that no additional code is executed after the redirection
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        }
    }

    $con->close();
}
?>
