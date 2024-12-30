<?php
include('connection.php');

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $UID = $_GET['id'];

    // Fetch user data based on UID
    $query = "SELECT * FROM users WHERE UID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $UID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Display a form to update user information
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit User</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        </head>
        <body>
        <?php include 'user_navbar.php' ?>
        <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'usersidebar.php' ?>
            <div class="container mt-2">
                <h1>Edit User</h1>
                <form action="" method="post">
                    <input type="hidden" name="UID" value="<?php echo $user['UID']; ?>">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Birthdate</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $user['dob']; ?>" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                <br>
                

            </div>
    </div>  
        </body>
        </html>
        <?php
    } else {
        echo "No user found with UID $UID";
    }

    $stmt->close();
} else {
    echo "Invalid request. Missing 'id' parameter.";
}

$con->close();
?>
