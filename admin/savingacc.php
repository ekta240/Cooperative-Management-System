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
        <h1 style="color: black; font-size: 46px; text-align: center;">View Users</h1>

        <table style="width: 90%; border-collapse: collapse; margin-top: 20px;">
            <tr style="background-color: pink;">
                <th>ID</th>
                <th>First Name</th>
                <th>Update</th>
            </tr>

            <?php
            include('connection.php');
            
            $q = "SELECT UID, account_no FROM saving_acc";
            $result = mysqli_query($con, $q) or die("Query error");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["UID"] . "</td>";
                    echo "<td>" . $row["account_no"] . "</td>";
                    echo "<td><a href='addsavingacc.php?UID=" . $row['UID'] . "&account_no=" . $row['account_no'] . "'>Update</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No users found</td></tr>";
            }

            mysqli_close($con);
            ?>
        
        </table>
        
    </div>
</div>

</body>
</html>
