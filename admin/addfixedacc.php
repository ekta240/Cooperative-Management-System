<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Fixed Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">    
    <style>
        /* Custom CSS */
        .thead {
            background-color: pink;
            color: yellow;
            text-align: center;
        }
        
        /* Decrease width of table */
        .table-bordered {
            width: 60%;
        }

        /* Style buttons */
        .table-bordered tbody button {
            width: 100%; /* Make buttons same size */
        }

        /* Align buttons in center */
        .table-bordered tbody button {
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>
<?php include 'admin_navbar.php' ?>
<div class="d-flex" style="display: flex; width: 100%;">
    
    <?php include 'sidebar.php' ?>
<?php
include('../connection.php'); // Assuming your connection file is in the parent directory

// Check if 'first_name' and 'last_name' are set in the URL
if (isset($_GET['first_name']) && isset($_GET['last_name'])) {
    $first_name = $_GET['first_name'];
    $last_name = $_GET['last_name'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve new information from the form
        $amount = $_POST['amount'];
        $interest = $_POST['interest'];
        $time_period = $_POST['time_period'];

        // Insert new Fixed Account information into the database
        $query = "INSERT INTO fixed_acc (first_name, last_name, amount, interest, time_period) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssidi", $first_name, $last_name, $amount, $interest, $time_period);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Fixed Account added successfully!";
        } else {
            echo "Error adding Fixed Account. Please try again.";
        }

        $stmt->close();
    }
}
?>

<div class="container mt-5">
    <h1>Add Fixed Account for <?php echo "$first_name $last_name"; ?></h1>
    <form action="" method="post">
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        </div>
        <table class="table table-bordered">
            <thead class="thead">
                <tr>
                    <th>Time Period</th>
                    <th>Quarterly Interest Rate</th>
                    <th>Annually Interest Rate</th>
                    <th>Wholelly Interest Rate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1 year</td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(0.12, 1, 'quarterly');">12%</button>
                        <input type="hidden" id="quarterly_interest_1" name="quarterly_interest">
                        <input type="hidden" id="quarterly_time_period_1" name="quarterly_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(0.13, 1, 'annually');">13%</button>
                        <input type="hidden" id="annually_interest_1" name="annually_interest">
                        <input type="hidden" id="annually_time_period_1" name="annually_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(0.12, 1, 'wholely');">12%</button>
                        <input type="hidden" id="wholely_interest_1" name="wholely_interest">
                        <input type="hidden" id="wholely_time_period_1" name="wholely_time_period">
                    </td>
                </tr>
                <tr>
                    <td>2 year</td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(0.13, 2, 'quarterly');">13%</button>
                        <input type="hidden" id="quarterly_interest_2" name="quarterly_interest">
                        <input type="hidden" id="quarterly_time_period_2" name="quarterly_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(0.135, 2, 'annually');">13.5%</button>
                        <input type="hidden" id="annually_interest_2" name="annually_interest">
                        <input type="hidden" id="annually_time_period_2" name="annually_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(0.13, 2, 'wholely');">13%</button>
                        <input type="hidden" id="wholely_interest_2" name="wholely_interest">
                        <input type="hidden" id="wholely_time_period_2" name="wholely_time_period">
                    </td>
                </tr>
                <tr>
                    <td>3 year</td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(0.14, 3, 'quarterly');">14%</button>
                        <input type="hidden" id="quarterly_interest_3" name="quarterly_interest">
                        <input type="hidden" id="quarterly_time_period_3" name="quarterly_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(0.1425, 3, 'annually');">14.25%</button>
                        <input type="hidden" id="annually_interest_3" name="annually_interest">
                        <input type="hidden" id="annually_time_period_3" name="annually_time_period">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary" onclick="setInterestAndTimePeriod(0.145, 3, 'wholely');">14.5%</button>
                        <input type="hidden" id="wholely_interest_3" name="wholely_interest">
                        <input type="hidden" id="wholely_time_period_3" name="wholely_time_period">
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Add Fixed Account</button>
    </form>
</div>
</div>

<script>
    function setInterestAndTimePeriod(interest, timePeriod, type) {
        document.getElementById(type + '_interest_1').value = interest;
        document.getElementById(type + '_time_period_1').value = timePeriod;
    }
    
</script>
<?php
$con->close();
?>

</body>
</html>
