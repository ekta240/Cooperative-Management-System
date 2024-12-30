<?php
include('connection.php');

// Fetch all loan requests
$select_query = "SELECT * FROM loans ";
$result = $con->query($select_query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Requests</title>
</head>
<body>
    <?php include 'admin_navbar.php' ?>
    <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'sidebar.php' ?>
        <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
            <h1 style="color: black; font-size: 46px; text-align: center;">Loan Requests</h1>

            <table style="width: 90%; border-collapse: collapse; margin-top: 20px;">
                <tr style="background-color: pink;">
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Amount</th>
                    <th>Term</th>
                    <th>Rate</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>EMI Schedule</th>
                </tr>
                <?php
                // Display loan requests
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['loan_id'] . "</td>";
                        echo "<td>" . $row['first_name'] . "</td>";
                        echo "<td>" . $row['last_name'] . "</td>";
                        echo "<td>" . $row['amount'] . "</td>";
                        echo "<td>" . $row['term'] . "</td>";
                        echo "<td>" . $row['interest_rate'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td><a href='approve_loan.php?id=" . $row['loan_id'] . "'>Approve</a> | <a href='reject_loan.php?id=" . $row['loan_id'] . "'>Reject</a></td>";
                        echo "<td><a href='emi_info.php?id=" . $row['loan_id'] . "'>EMI Schedule</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No pending loan requests.</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>

<?php
$con->close();
?>
