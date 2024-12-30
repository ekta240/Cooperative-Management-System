<?php
$registrationSuccess = false;

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
    $account_no = $_POST['account_no']; 
    $usertype = $_POST['usertype'];

    // Check if email already exists
    $emailCheckQuery = "SELECT * FROM users WHERE email = '$email'";
    $emailCheckResult = $con->query($emailCheckQuery);
    if ($emailCheckResult->num_rows > 0) {
        die("Email already exists. Please choose a different email.");
    }

    // Basic server-side validation
    if (empty($firstName) || empty($lastName) || empty($dob) || empty($email) || empty($password)) {
        echo "All fields are required.";
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
        $sql = "INSERT INTO users (first_name, last_name, dob, email, password, account_no,usertypes) VALUES ('$firstName', '$lastName', '$dob', '$email', '$hashedPassword','$account_no', '$usertype')";

        if ($con->query($sql) === TRUE) {
            $registrationSuccess = true;
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }

    $con->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
</head>
<body >
<?php include 'admin_navbar.php' ?>

<div class="d-flex" style="display: flex; width: 100%;">
    
<?php include 'sidebar.php' ?>

<div class="container mt-2"><br>
            <h1>Register Customer</h1>
            <br>
     <form action="register.php" method="post" >
        <label for="firstName" class="form-label">First Name</label>
        <input type="text" class="form-control" id="firstName" name="firstName" style="width: 500px;" required>
        
        <label for="lastName" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="lastName" name="lastName" style="width: 500px;" required>

        <label for="dob" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" style="width: 500px;" required>

        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" style="width: 500px;" required>
        
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" style="width: 500px;" required>   

        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" style="width: 500px;" required>   

        <label for="account_no" class="form-label">Account No.</label>
        <input type="text" class="form-control" id="account_no" name="account_no" style="width: 500px;" required>  

        <input type="hidden" name="usertype" value="user">
         <input type="hidden" name="staus" value="pending"> 
        <button type="submit"  class="btn btn-primary">Register</button>
    </form> 

    <?php
    if ($registrationSuccess) {
        echo '<script>alert("Registration successful!");</script>';
    }
    ?>

</div>
</div>

</body>
</html>
