<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>

<?php include 'admin_navbar.php' ?>

<div class="d-flex" style="display: flex; width: 100%;">
    
<?php include 'sidebar.php' ?>

    <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
        <h1 style="color: black; font-size: 46px; text-align: center;">View fixed accounts</h1>

        <table style="width: 90%; border-collapse: collapse; margin-top: 20px;">
        <tr style="background-color: pink;">
            <th>FID</th>
            <th>UID</th>
            <th>Account no.</th>
            <th>Amount</th>
            <th>Interest</th>
            <th>Time period</th>
            <th>Type</th>
        </tr>

        <?php
        include('connection.php');
        
        
        // Construct the SQL query based on the provided parameters
        $q = "SELECT `FID`, `UID`, `account_no`, `amount`, `interest`, `time_period`,`type` FROM `fixed_acc` WHERE 1";
        $result = mysqli_query($con, $q) or die("Query error");

        // Display user data in a table
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["FID"] . "</td>";
                echo "<td>" . $row["UID"] . "</td>";
                echo "<td>" . $row["account_no"] . "</td>";
                echo "<td>" . $row["amount"] . "</td>";
                echo "<td>" . $row["interest"] . "</td>";
                echo "<td>" . $row["time_period"] . "</td>";
                echo "<td>" . $row["type"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No users found</td></tr>";
        }

        // Close connection
        mysqli_close($con);
        ?>
    
        </table>
        <br>
        
    </div>
</div>



</body>
</html>