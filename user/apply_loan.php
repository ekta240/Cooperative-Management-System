<?php
include('connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Calculator</title>
</head>
<body>
    <?php include 'user_navbar.php' ?>
        <div class="d-flex" style="display: flex; width: 100%;">
        <?php include 'usersidebar.php' ?>
            <div class="container mt-2">
        <h1>Loan Calculator</h1>
       <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="amount">Loan Amount:</label>
        <input type="text" id="amount" name="amount" required><br><br>

        <label>Choose a Loan Plan:</label><br>
        <input type="radio" id="plan1" name="plan" value="plan1" checked>
        <label for="plan1">Plan 1 (5% interest, 12 months)</label><br>

        <input type="radio" id="plan2" name="plan" value="plan2">
        <label for="plan2">Plan 2 (6% interest, 24 months)</label><br>

        <input type="radio" id="plan3" name="plan" value="plan3">
        <label for="plan3">Plan 3 (7% interest, 36 months)</label><br>

        <input type="radio" id="plan4" name="plan" value="plan4">
        <label for="plan4">Plan 4 (8% interest, 48 months)</label><br>

        <input type="radio" id="plan5" name="plan" value="plan5">
        <label for="plan5">Plan 5 (9% interest, 60 months)</label><br><br>

        <button type="submit" name="submit">Calculate Installment</button>
    </form>


    <?php
    // Define loan plans
    $loanPlans = array(
        "plan1" => array("interest" => 0.05, "term" => 12),
        "plan2" => array("interest" => 0.06, "term" => 24),
        "plan3" => array("interest" => 0.07, "term" => 36),
        "plan4" => array("interest" => 0.08, "term" => 48),
        "plan5" => array("interest" => 0.09, "term" => 60)
    );

    // Function to calculate monthly installment
    function calculateInstallment($amount, $interest, $term) {
        $monthlyInterest = $interest / 12;
        $monthlyInstallment = ($amount * $monthlyInterest) / (1 - pow(1 + $monthlyInterest, -$term));
        return round($monthlyInstallment, 2);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        $amount = $_POST["amount"];
        $plan = $_POST["plan"];

        if (!is_numeric($amount) || $amount <= 0) {
            echo "<p>Please enter a valid loan amount.</p>";
        } else {
            $interest = $loanPlans[$plan]["interest"];
            $term = $loanPlans[$plan]["term"];
            $installment = calculateInstallment($amount, $interest, $term);
            echo "<p>Your monthly installment for Plan " . substr($plan, -1) . " is: $" . $installment . "</p>";
        }
    }
    ?>
    </div>
</div>
</body>
</html>
