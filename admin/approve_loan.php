<?php
include('connection.php');

// Check if the loan ID is set in the URL
if (isset($_GET['id'])) {
    $loan_id = $_GET['id'];

    $check_query = "SELECT `status` FROM `loans` WHERE `loan_id` = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("i", $loan_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $loan_data = $check_result->fetch_assoc();
        $status = $loan_data['status'];

        if ($status === 'approved') {
            header("Location: loanrequest.php?message=Loan+already+approved");
            exit();
        } else {
            // Get the current date
            $date_approved = date('Y-m-d');

            // Update the status and date_approved of the loan request in the database
            $update_query = "UPDATE loans SET status = 'approved', date_approved = ? WHERE loan_id = ?";
            $update_stmt = $con->prepare($update_query);
            $update_stmt->bind_param("si", $date_approved, $loan_id);

            if ($update_stmt->execute()) {
                // Fetch the loan details
                $loan_query = "SELECT `amount`, `interest_rate`, `term` FROM `loans` WHERE `loan_id` = ?";
                $loan_stmt = $con->prepare($loan_query);
                $loan_stmt->bind_param("i", $loan_id);
                $loan_stmt->execute();
                $loan_result = $loan_stmt->get_result();

                if ($loan_result->num_rows > 0) {
                    $loan_data = $loan_result->fetch_assoc();
                    $amount = $loan_data['amount'];
                    $interest_rate = $loan_data['interest_rate'];
                    $term = $loan_data['term'];

                    // Calculate EMI amount
                    $emi_amount = calculateEmi($amount, $interest_rate, $term);

                    // Generate due dates for EMIs
                    $due_dates = generateDueDates($term, $date_approved);

                    // Insert EMIs into the database
                    foreach ($due_dates as $due_date) {
                        $insert_query = "INSERT INTO `emis`(`loan_id`, `emi_amount`, `due_date`, `payment_status`) VALUES (?, ?, ?, 'Unpaid')";
                        $insert_stmt = $con->prepare($insert_query);
                        $insert_stmt->bind_param("ids", $loan_id, $emi_amount, $due_date);
                        $insert_stmt->execute();
                    }
                }

                // Redirect back to the admin dashboard with a success message
                header("Location: loanrequest.php?success=Loan+approved+and+EMI+schedule+generated");
                exit();
            } else {
                // Redirect back to the admin dashboard with an error message
                header("Location: admin_dashboard.php?error=Failed+to+approve+loan");
                exit();
            }
        }
    } else {
        // Redirect back to the admin dashboard with an error message
        header("Location: admin_dashboard.php?error=Loan+ID+not+found");
        exit();
    }
} else {
    // Redirect back to the admin dashboard if loan ID is not set in the URL
    header("Location: admin_dashboard.php");
    exit();
}

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
    $due_dates = [];

    $start_date = new DateTime($date_approved);

    for ($i = 0; $i < $term; $i++) {
        $due_date = clone $start_date;

        // Add one month to the due date
        $due_date->modify("+{$i} months");

        // Format due date as 'Y-m-d'
        $due_dates[] = $due_date->format('Y-m-d');
    }

    return $due_dates;
}
?>
