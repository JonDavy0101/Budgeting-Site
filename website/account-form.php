<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if ( isset( $_SESSION['id'] ) ) {
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages
} else {
    // Redirect them to the login page
    header("Location: ./login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Debt Slasher | Account Form</title>

    <!-- Metas and General Links -->
    <?php
        include_once('./resources/includes/head.php');
    ?>

</head>
<body>
<!-- Submit Form -->
    <?php
    if ( ! empty( $_POST ) ) {
        include_once('./resources/includes/connect.php');

        // prepare and bind
        $accStmt = $conn->prepare("INSERT INTO `dsAccounts` (`accountId`, `userId`, `account`) VALUES (NULL, ?, ?)");
        $accStmt->bind_param("is", $userId, $account);

        // escape variables for security, set parameters and execute
        $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
        $account = mysqli_real_escape_string($conn, $_POST['account']);
        $accStmt->execute();
        $accStmt->close();

        // prepare and bind
        $transStmt = $conn->prepare("INSERT INTO `dsTransactions` (transId, userId, type, account, amount, pending, transDate, payee, category, description) VALUES (NULL, ?, 'Initial Balance', ?, ?, 'no', ?, 'None', 'Initial Bal', 'Initial Balance')");
        $transStmt->bind_param("isss", $userId, $account, $startBal, $date);

        // escape variables for security, set parameter and execute
        $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
        $account = mysqli_real_escape_string($conn, $_POST['account']);
        $startBal = mysqli_real_escape_string($conn, $_POST['amount']); 
        $date = mysqli_real_escape_string($conn, date('Y-m-d'));
        $transStmt->execute();
        $transStmt->close();
        $conn->close();

        header("Location: ./accounts.php");
    }
    ?>

<div class=" min-100 d-flex flex-column">
<!-- Navbar -->
    <?php
        include_once('./resources/includes/navbar.php');
    ?>

<!-- Form Content -->
    <div class="container footer-m" id="form-content">
        <div class="row">
            <div class="col-md-6 mx-auto">

            <!-- Title Section -->
                <div class="row bg-color-secondary mt-5">
                    <div class="col">
                        <h2 class="py-3 px-2 m-0">Account</h2>
                    </div>
                </div>
            <!-- Budget Categories -->
                <form method="post" action="">
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Account Name</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input type="text" name="account" placeholder="ex. Cash" maxlength="12" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Starting Balance</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input type="text" name="amount" step="1" pattern="^\d{1,9}(\.\d{0,2})?$" placeholder="ex. 1000.00" maxlength="12" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row tb-sides py-2">
                        <div class="col p-2 t-center justify-content-center">
                            <input id="save-button" type="submit" name="save-button" value="Save">
                        </div>
                        <div class="col p-2 t-center justify-content-center">
                            <input id="cancel-button" type="button" name="cancel-button" value="Cancel">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Footer -->
    <?php
        include_once('./resources/includes/footer.php');
    ?>
</div>

<script src="./resources/jquery-3.4.1.min.js"></script>
<script src="./resources/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
<script>
    $('#cancel-button').click(function() {
        window.location.href = "./accounts.php";
    });
</script>

</body>
</html>
