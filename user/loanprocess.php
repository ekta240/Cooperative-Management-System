

<?php
include('connection.php');

// Initialize variables
$first_name = ""; // Initialize first name
$last_name = ""; // Initialize last name
$message = ""; // Initialize message variable

// Check if first_name and last_name are set in the URL
if (isset($_GET['first_name']) && isset($_GET['last_name'])) {
    $first_name = $_GET['first_name'];
    $last_name = $_GET['last_name'];

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve loan information from the form
        $amount = $_POST['amount'];
        $term = $_POST['term'];

        // Check if the amount is greater than 50000
        if ($amount > 50000) {
            $interest_rate = 12; // Set interest rate to 12%
            $status = 'Pending'; // Set status as pending initially
            $date_applied = date('Y-m-d'); // Get current date

            // Prepare the insert query
            $insert_query = "INSERT INTO loans (first_name, last_name, amount, interest_rate, term, status, date_applied) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $con->prepare($insert_query);
            
            // Bind parameters and execute the insert query
            $insert_stmt->bind_param("ssdiiss", $first_name, $last_name, $amount, $interest_rate, $term, $status, $date_applied);
            
            if ($insert_stmt->execute()) {
                $message = "Loan request sent successfully!";
            } else {
                $message = "Error sending loan request. Please try again.";
            }

            $insert_stmt->close(); // Close the statement
        } else {
            $message = "Error: Amount must be greater than 50000.";
        }
    }
} else {
    $message = "First name and/or last name not found in the URL.";
}

$con->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Loan</title>
</head>
<body>
    <?php include 'user_navbar.php' ?>
    <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'usersidebar.php' ?>
        <div class="container mt-2"><br><br>
            <h1>Request loan</h1>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?first_name=' . urlencode($first_name) . '&last_name=' . urlencode($last_name); ?>" method="POST">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" style="width: 500px;" required>
                <label for="term" class="form-label">Time period (in months)</label>
                <input type="number" class="form-control" id="term" name="term" style="width: 500px;" required><br>
                <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
                <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                <button type="submit" class="btn btn-primary">Request</button>
            </form>
            
            <?php
            // Display message below the request button
            if (!empty($message)) {
                echo "<p>$message</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
