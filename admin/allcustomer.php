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
            <th>Email</th>
            <th>Birthdate</th>
            <th>Usertype</th>
            <th>Delete</th>
            <th>Edit</th>
        </tr>

        <?php
        include('connection.php');
        
        $q = "SELECT UID, first_name, email, dob, usertypes FROM users";
        $result = mysqli_query($con, $q) or die("Query error");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["UID"] . "</td>";
                echo "<td>" . $row["first_name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["dob"] . "</td>";
                echo "<td>" . $row["usertypes"] . "</td>";
                echo "<td><a href='delete.php?id=" . $row["UID"] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a></td>";
                echo "<td><a href='update.php?id=" . $row["UID"] . "'>Edit</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No users found</td></tr>";
        }

        mysqli_close($con);
        ?>
    
        </table>
    </div>
</div>



</body>
</html>