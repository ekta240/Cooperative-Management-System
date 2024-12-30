<?php
include('../connection.php'); // Assuming your connection file is in the parent directory

// Check if 'account_no' is set in the URL
if (isset($_GET['id'])) {
    $account_no = $_GET['id'];
    $query = "SELECT * FROM saving_acc WHERE account_no = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $account_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Check if amount is set in the POST data
        if(isset($_POST['amount'])) {
            // Retrieve new information from the form
            $amount = $_POST['amount'];

            // Insert new Saving Account information into the database using prepared statement
        }

        // Fetch the row from the result set
        $row = $result->fetch_assoc();
        $balance = $row['amount'];
    } else {
        echo "No records found for the given account number.";
    }

    $stmt->close();
    $con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Balance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include 'user_navbar.php' ?>
    <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'usersidebar.php' ?>
        <div>
            <!-- Display user's balance here -->
            <h1>
                <p>Your balance is <?php echo isset($balance) ? $balance : 'N/A'; ?></p>
            </h1>
        </div>
    </div>    
</body>
</html>

<?php
} else {
    echo "Invalid request. Missing 'account_no' parameter.";
}
?>
