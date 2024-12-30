<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Savings Account</title>
</head>
<body>
    <?php
    include('../connection.php'); // Include your database connection file
    include('admin_navbar.php');
    ?>
    <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'sidebar.php' ?>
        <div class="container mt-5">
            <?php
            // Check if 'UID' and 'account_no' are set in the URL
            if (isset($_GET['UID']) && isset($_GET['account_no'])) {
                $UID = $_GET['UID'];
                $account_no = $_GET['account_no'];

                // Check if the account exists for the given UID and account number
                $query = "SELECT * FROM saving_acc WHERE UID = ? AND account_no = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("ss", $UID, $account_no);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // Account already exists
                    echo "Saving account already exists for $account_no.";
                } else {
                    // Account not found, create a new account
                    if (isset($_POST['initial_deposit'])) {
                        $initial_deposit = $_POST['initial_deposit'];
                        $interest_rate = 0.062; // Set the interest rate

                        // Insert new account with initial deposit and interest rate
                        $query = "INSERT INTO saving_acc (UID, account_no, amount, interest) VALUES (?, ?, ?, ?)";
                        $stmt = $con->prepare($query);
                        $stmt->bind_param("ssdd", $UID, $account_no, $initial_deposit, $interest_rate);
                        $stmt->execute();

                        // Check if the account was successfully created
                        if ($stmt->affected_rows > 0) {
                            // Insert initial deposit transaction into transaction_history table
                            $query = "INSERT INTO transaction_history (account_no, transaction_type, amount, current_bal) VALUES (?, 'Initial Balance', ?, ?)";
                            $stmt = $con->prepare($query);
                            $stmt->bind_param("sdd", $account_no, $initial_deposit, $initial_deposit);
                            $stmt->execute();
                            ?>
                            <h1>New Saving Account for <?php echo "$account_no "; ?></h1>
                            <?php
                        } else {
                            echo "Error creating saving account for $account_no";
                        }
                    } else {
                        // Ask for initial deposit
                        echo "<h1>Create Savings Account</h1>";
                        echo "<p>Please enter the initial deposit amount for $account_no:</p>";
                        ?>
                        <form action="" method="post">
                            <input type="number" name="initial_deposit" required>
                            <button type="submit">Create Account</button>
                        </form>
                        <?php
                    }
                }

                $stmt->close();
            } else {
                echo "Invalid request. Missing 'UID' or 'account_no' parameters.";
                exit; // Exit script
            }

            $con->close();
            ?>
        </div>
    </div>
</body>
</html>
