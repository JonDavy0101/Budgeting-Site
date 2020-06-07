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
    <title>Debt Slasher | Transaction Form</title>

    <!-- Metas and General Links -->
    <?php
        include_once('./resources/includes/head.php');
    ?>

</head>
<body>
<!-- Login Code -->
    <?php
    if ( ! empty( $_POST ) ) {
        include_once('./resources/includes/connect.php');

        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO `dsTransactions` (`transId`, `userId`, `type`, `account`, `amount`, `pending`, `transDate`, `payee`, `category`, `description`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $userId, $type, $account, $amount, $pending, $date, $payee, $category, $description);

        // escape variables for security, set parameters and execute
        $userId = mysqli_real_escape_string($conn, $_SESSION['id']);
        $type = mysqli_real_escape_string($conn, $_POST['type']); 
        $account = mysqli_real_escape_string($conn, $_POST['account']); 
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $pending = mysqli_real_escape_string($conn, $_POST['pending']); 
        $date = mysqli_real_escape_string($conn, date('Y-m-d', strtotime($_POST['date'])) );
        $payee = mysqli_real_escape_string($conn, $_POST['payee']); 
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $description = mysqli_real_escape_string($conn, $_POST['description']); 
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: ./transactions.php");
    }
    ?>

<div class=" min-100 d-flex flex-column">
<!-- Navbar -->
    <?php
        include_once('./resources/includes/navbar.php');
    ?>

<!-- Content -->
    <div class="container footer-m">
        <div class="row">
            <div class="col-md-6 mx-auto">

            <!-- Title Section -->
                <div class="row bg-color-secondary mt-5">
                    <div class="col">
                        <h2 class="py-3 px-2 m-0">Transaction</h2>
                    </div>
                </div>
            <!-- Budget Categories -->
                <form method="post" action="">
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Type</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <div class="styled-select">
                                <select id="type" name="type" required>
                                    <option value="" disabled selected>Select..</option> 
                                    <option value="Income">Income</option>
                                    <option value="Expense">Expense</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Account</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <div class="styled-select">
                                <select id="account" name="account" required>
                                    <?php
                                        include_once('./resources/includes/connect.php');
                                        $user = mysqli_real_escape_string($conn, $_SESSION['id']);

                                        $sql = "SELECT account FROM dsAccounts WHERE userId = $user ORDER BY account ASC";

                                        $result = mysqli_query($conn, $sql);

                                        if (mysqli_num_rows($result) > 0) {
                                            echo '<option value="" disabled selected>Select..</option>';
                                            while($row = mysqli_fetch_assoc($result)) {
                                               if ($result->num_rows > 0) {
                                                    // $budgetBal += $row["budget"];
                                                    echo '<option value="' . $row["account"] . '">' . $row["account"] . '</option>';
                                                }
                                            }
                                        } else {
                                            echo '<option value="" disabled selected>Add account first</option>';
                                        }

                                        mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Amount</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input type="text" name="amount" step="1" pattern="^\d{1,9}(\.\d{0,2})?$" placeholder="ex. 1000.00" maxlength="12" autocomplete="off" required>
                        </div>
                    </div>
                    
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Pending</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <div>
                                <input type="radio" name="pending" value="yes" autocomplete="off" required>
                                <label for="male">&nbsp;&nbsp;yes</label><br>
                                <input type="radio" name="pending" value="no">
                                <label for="female">&nbsp;&nbsp;no</label>
                            </div>
                        </div>
                    </div>
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Date</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input type="date" name="date" placeholder="date" required>
                        </div>
                    </div>
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Payee</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input type="text" name="payee" placeholder="ex. Walmart" maxlength="12" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Category</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <div class="styled-select">
                                <select id="category" name="category" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Description</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input type="text" name="description" placeholder="ex. Coffee and donuts" maxlength="20" autocomplete="off" required>
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
    $(document).ready(function(){
        // Update data when date changes
        $('#type').change(function() {
            var newType = $(this).val();
            var userdata = {'newType':newType};
            $.ajax({
                    type: "POST",
                    url: "./resources/includes/update-transaction-form.php",
                    data:userdata,
                    success: function(data){
                        var obj = JSON.parse(data);

                        // Delete all old rows
                        $("#category").empty();

                        // Assign rows for each category
                        var i;
                        for (i = 0; i < obj.rowResult.length; i++) {
                            $( "#category" ).append( obj.rowResult[i] ); 
                        }
                    }
                    });
        });

        $('#cancel-button').click(function() {
            window.location.href = "./transactions.php";
        });
    });
</script>

</body>
</html>
