<?php
include('connection.php');

// Function to calculate quarterly interest
function calculateQuarterlyInterest($amount, $interestRate) {
    return $amount * ($interestRate / 4 / 100);
}

// Function to calculate annual interest
function calculateAnnualInterest($amount, $interestRate) {
    return $amount * ($interestRate / 100);
}

// Function to update last quarterly calculation date
function updateLastQuarterlyCalculation($FID, $date) {
    global $con;
    $updateQuery = "UPDATE fixed_acc SET last_quarterly_calculation = ? WHERE FID = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("si", $date, $FID);
    $updateStmt->execute();
    $updateStmt->close();
}

// Function to update last annual calculation date
function updateLastAnnualCalculation($FID, $date) {
    global $con;
    $updateQuery = "UPDATE fixed_acc SET last_annual_calculation = ? WHERE FID = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("si", $date, $FID);
    $updateStmt->execute();
    $updateStmt->close();
}

// Function to handle maturity actions
function handleMaturity($FID) {
    global $con;
    $updateQuery = "UPDATE fixed_acc SET is_matured = 1 WHERE FID = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("i", $FID);
    $updateStmt->execute();
    $updateStmt->close();
}

// Fetch all fixed accounts
$query = "SELECT FID, amount, interest, time_period, last_quarterly_calculation, last_annual_calculation, maturity_date FROM fixed_acc";
$result = $con->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $FID = $row['FID'];
        $amount = $row['amount'];
        $interestRate = $row['interest'];
        $timePeriod = $row['time_period'];
        $lastQuarterlyCalculation = $row['last_quarterly_calculation'];
        $lastAnnualCalculation = $row['last_annual_calculation'];
        $maturityDate = strtotime($row['maturity_date']);

        // Current timestamp
        $currentDate = time();

        // Check if quarterly interest needs to be calculated
        if (empty($lastQuarterlyCalculation) || strtotime("+3 months", strtotime($lastQuarterlyCalculation)) <= $currentDate) {
            $quarterlyInterest = calculateQuarterlyInterest($amount, $interestRate);
            // Update amount with quarterly interest
            $amount += $quarterlyInterest;
            // Update last quarterly calculation date
            updateLastQuarterlyCalculation($FID, date('Y-m-d', $currentDate));
        }

        // Check if annual interest needs to be calculated
        if (empty($lastAnnualCalculation) || strtotime("+1 year", strtotime($lastAnnualCalculation)) <= $currentDate) {
            $annualInterest = calculateAnnualInterest($amount, $interestRate);
            // Update amount with annual interest
            $amount += $annualInterest;
            // Update last annual calculation date
            updateLastAnnualCalculation($FID, date('Y-m-d', $currentDate));
        }

        // Check if account has matured
        if ($maturityDate <= $currentDate && $row['is_matured'] == 0) {
            handleMaturity($FID);
        }

        // Update the amount in fixed_acc table
        $updateQuery = "UPDATE fixed_acc SET amount = ? WHERE FID = ?";
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->bind_param("di", $amount, $FID);
        $updateStmt->execute();
        $updateStmt->close();
    }
} else {
    echo "No fixed accounts found.";
}

$con->close();
?>
