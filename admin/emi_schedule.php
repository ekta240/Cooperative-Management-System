<?php
// Include the connection file
include('connection.php');

// Query to fetch loan details from the database
$query = "SELECT `loan_id`, `first_name`, `last_name`, `amount`, `interest_rate`, `term`, `date_approved` FROM `loans` WHERE status='approved'";
$result = $con->query($query);

// Check if the query was successful
if ($result) {
    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Calculate EMI details
            $loan_id = $row['loan_id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $amount = $row['amount'];
            $interest_rate = $row['interest_rate'];
            $term = $row['term'];
            $date_approved = $row['date_approved'];

            // Calculate EMI amount
            $emi_amount = calculateEmi($amount, $interest_rate, $term);

            // Generate due dates for EMIs
            $due_dates = generateDueDates($term, $date_approved);

            // Insert EMIs into the database
            foreach ($due_dates as $due_date) {
                // Insert query for emis table
                $insert_query = "INSERT INTO `emis`(`loan_id`, `emi_amount`, `due_date`, `payment_status`) VALUES (?, ?, ?, 'Unpaid')";
                $insert_stmt = $con->prepare($insert_query);
                $insert_stmt->bind_param("ids", $loan_id, $emi_amount, $due_date);
                $insert_stmt->execute();
            }
        }
    } else {
        echo "No loans found.";
    }
} else {
    echo "Error: " . $con->error;
}

// Close the connection
$con->close();


// Function to calculate EMI amount
function calculateEmi($loan_amount, $interest_rate, $term) {
    // Convert interest rate to decimal
    $monthly_interest_rate = ($interest_rate / 100) / 12;

    // Calculate EMI using the formula
    $emi = ($loan_amount * $monthly_interest_rate * pow(1 + $monthly_interest_rate, $term)) / (pow(1 + $monthly_interest_rate, $term) - 1);

    // Round off the EMI to two decimal places
    $emi = round($emi, 2);

    return $emi;
}

// Function to generate due dates for EMIs
function generateDueDates($term, $date_approved) {
    // Create an array to store due dates
    $due_dates = [];

    // Convert date_approved to DateTime object
    $start_date = new DateTime($date_approved);

    // Add one month for each term to generate due dates
    for ($i = 0; $i < $term; $i++) {
        // Clone the start date to avoid modifying it directly
        $due_date = clone $start_date;

        // Add one month to the due date
        $due_date->modify("+{$i} months");

        // Format due date as 'Y-m-d'
        $due_dates[] = $due_date->format('Y-m-d');
    }

    return $due_dates;
}


?>
