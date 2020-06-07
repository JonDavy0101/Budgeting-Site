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
    <title>Debt Slasher | Transactions</title>

    <!-- Metas and General Links -->
    <?php
        include_once('./resources/includes/head.php');
    ?>

</head>
<body>
<div class=" min-100 d-flex flex-column">
<!-- Navbar -->
    <?php
        include_once('./resources/includes/navbar.php');
    ?>

<!-- Transactions Content -->
    <div class="container footer-m" id="transaction-content">
        <div class="row">
            <div class="col-md-6 mx-auto">

            <!-- Title Section -->
                <div class="row mt-5" id="title-row">
                    <div class="col-6 t-center">
                        <div class="styled-select my-2">
                            <select id="account" name="account" required>
                                <?php
                                    include_once('./resources/includes/connect.php');
                                    $user = mysqli_real_escape_string($conn, $_SESSION['id']);

                                    $sql = "SELECT account FROM dsAccounts WHERE userId = $user ORDER BY account ASC";

                                    $result = mysqli_query($conn, $sql);
                                    $x = 0;
                                    $accArr = array();

                                    if (mysqli_num_rows($result) > 0) {
                                        echo '<option value="All Accounts" selected>All Accounts</option>';
                                        while($row = mysqli_fetch_assoc($result)) {
                                           if ($result->num_rows > 0) {
                                                // $budgetBal += $row["budget"];
                                                echo '<option value="' . $row["account"] . '">' . $row["account"] . '</option>';
                                                $accArr[$x] = '<option value="' . $row["account"] . '">' . $row["account"] . '</option>';
                                                $x++;
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
                    <div class="col-6 t-center justify-content-end">
                        <a href="./transaction-form.php"><img class="add-button" src="./resources/images/icons/add-button-w.svg" alt="add transaction"></a>
                    </div>
                </div>
            <!-- Bar Graph Section -->
                <div class="row mt-3" id="bar-graph-row">
                    <div class="col-4 h-100 p-0">
                        <div id="bar-income"></div>
                    </div>
                    <div class="col-4 h-100 p-0 bx-sides">
                        <div id="bar-expense"></div>
                    </div>
                    <div class="col-4 h-100 p-0">
                        <div id="bar-pending-expense"></div>
                    </div>
                </div>
                <div class="row bg-color-secondary by-sides">
                    <div class="col-4 p-2 d-flex justify-content-center">
                        <div>
                            <p class="b-title">Income</p>
                            <p id="income-total">+$0.00</p>
                        </div>
                    </div>
                    <div class="col-4 p-2 bx-sides d-flex justify-content-center">
                        <div>
                            <p class="b-title">Expenses</p>
                            <p id="expense-total">-$0.00</p>
                        </div>
                    </div>
                    <div class="col-4 p-2 d-flex justify-content-center">
                        <div>
                            <p class="b-title">Pending Expenses</p>
                            <p id="pending-expense-total">-$0.00</p>
                        </div>
                    </div>
                </div>
                <div class="row bg-color-secondary mb-2">
                    <div class="col-6 p-2 d-flex justify-content-center">
                        <div>
                            <p class="b-title">Account Balance</p>
                            <p id="account-balance">$0.00</p>
                        </div>
                    </div>
                    <div class="col-6 p-2 bl-sides d-flex justify-content-center">
                        <div>
                            <p class="b-title">Total Acc Balance</p>
                            <p id="total-acc-balance">+$0.00</p>
                        </div>
                    </div>
                </div>
            <!-- Budget Categories -->
                <div class="row tt-sides">
                    <div class="col-12" id="info"></div>
                </div>

            </div>
        </div>
    </div>

<!-- Form Content -->
    <div class="container footer-m" id="form-content">
        <div class="row">
            <div class="col-md-6 mx-auto">

            <!-- Title Section -->
                <div class="row bg-color-secondary mt-5">
                    <div class="col">
                        <h2 class="py-3 px-2 m-0">Edit Transaction</h2>
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
                                <select id="edit-account" name="account" required>
                                    <?php
                                        if (count($accArr) > 0) {
                                            echo '<option value="" disabled selected>Select..</option>';
                                            for($y = 0; $y < count($accArr); $y++) {
                                                echo $accArr[$y];
                                            }
                                        } else {
                                            echo '<option value="" disabled selected>Add account first</option>';
                                        }
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
                            <input id="amount" type="text" name="amount" step="1" pattern="^\d{1,9}(\.\d{0,2})?$" placeholder="ex. 1000.00" maxlength="12" autocomplete="off" required>
                        </div>
                    </div>
                    
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Pending</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <div>
                                <input id="pending" type="radio" name="pending" value="yes" autocomplete="off" required>
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
                            <input id="date" type="date" name="date" placeholder="date" required>
                        </div>
                    </div>
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Payee</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input id="payee" type="text" name="payee" placeholder="ex. Walmart" maxlength="12" autocomplete="off" required>
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
                            <input id="description" type="text" name="description" placeholder="ex. Coffee and donuts" maxlength="20" autocomplete="off" required>
                            <p class="d-none" id="trans"></p>
                        </div>
                    </div>
                    <div class="row tb-sides py-2">
                        <div class="col p-2 t-center justify-content-center">
                            <input id="save-button" type="button" name="save-button" value="Save">
                        </div>
                        <div class="col p-2 t-center justify-content-center">
                            <input id="cancel-button" type="button" name="cancel-button" value="Cancel">
                        </div>
                        <div class="col p-2 t-center justify-content-center">
                            <input id="delete-button" type="button" name="delete-button" value="Delete">
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

<!-- Calculate Bar Graph -->
<?php
    include_once('./resources/includes/budget-graph.php')
?>

<script>
    $(document).ready(function(){
        $('#account').change(function() {
            var newAccount = $(this).val();
            var userdata = {'newAccount':newAccount};
            $.ajax({
                    type: "POST",
                    url: "./resources/includes/update-transaction-graph.php",
                    data:userdata, 
                    success: function(data){
                        var obj = JSON.parse(data);

                        // Delete all old rows
                        $("#info").empty();

                        // Add design
                        if (obj.incomeBal < 0) {
                            var fIncomeBal = "-$" + (obj.incomeBal - (2 * obj.incomeBal)).toFixed(2);
                        } else {
                            var fIncomeBal = "+$" + obj.incomeBal.toFixed(2);
                        }
                        if (obj.expenseBal < 0) {
                            var fExpenseBal = "-$" + (obj.expenseBal - (2 * obj.expenseBal)).toFixed(2);
                        } else {
                            var fExpenseBal = "$" + obj.expenseBal.toFixed(2);
                        }
                        if (obj.pendingExpenseBal < 0) {
                            var fPendingExpenseBal = "-$" + (obj.pendingExpenseBal - (2 * obj.pendingExpenseBal)).toFixed(2);
                        } else {
                            var fPendingExpenseBal = "$" + obj.pendingExpenseBal.toFixed(2);
                        }
                        if (obj.totalAccountBal < 0) {
                            var fTotalAccountBal = "-$" + (obj.totalAccountBal - (2 * obj.totalAccountBal)).toFixed(2);
                            $('#account-balance').addClass('text-color-alert');
                        } else {
                            var fTotalAccountBal = "$" + obj.totalAccountBal.toFixed(2);
                            $('#account-balance').removeClass('text-color-alert');
                        }
                        if (obj.totalTotalAccBal < 0) {
                            var fTotalTotalAccBal = "-$" + (obj.totalTotalAccBal - (2 * obj.totalTotalAccBal)).toFixed(2);
                            $('#total-acc-balance').addClass('text-color-alert');
                        } else {
                            var fTotalTotalAccBal = "$" + obj.totalTotalAccBal.toFixed(2);
                            $('#total-acc-balance').removeClass('text-color-alert');
                        }

                        // Update bar graph
                        $('#bar-income').css('height', obj.incomeVal);
                        $('#bar-income').css('margin-top', obj.incomeMarg);
                        $('#bar-expense').css('height', obj.expenseVal);
                        $('#bar-expense').css('margin-top', obj.expenseMarg);
                        $('#bar-pending-expense').css('height', obj.pendingExpenseVal);
                        $('#bar-pending-expense').css('margin-top', obj.pendingExpenseMarg);

                        // Update values
                        $('#income-total').text(fIncomeBal);
                        $('#expense-total').text(fExpenseBal);
                        $('#pending-expense-total').text(fPendingExpenseBal);
                        $('#account-balance').text(fTotalAccountBal);
                        $('#total-acc-balance').text(fTotalTotalAccBal);

                        // Assign rows for each category
                        var i;
                        for (i = 0; i < obj.transItems.length; i++) {
                            $( "#info" ).append( obj.transItems[i] );
                        }
                    }
                    });
        });

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

        $('#save-button').click(function() {
            var editTrans = $("#trans").text();
            var editType = $("#type").val();
            var editAcc = $("#edit-account").val();
            var editAmount = $("#amount").val();
            var editPend = $("#pending").val(); // not sure if this will work
            var editDate = $("#date").val();
            var editPayee = $("#payee").val();
            var editCat = $("#category").val();
            var editDescrip = $("#description").val();
            var userdata = {'editTrans':editTrans, 'editType':editType, 'editAcc':editAcc, 'editAmount':editAmount, 'editPend':editPend, 'editDate':editDate, 'editPayee':editPayee, 'editCat':editCat, 'editDescrip':editDescrip};

            // console.log('editTrans: ' + editTrans + 'editType: ' + editType + 'editAcc: ' + editAcc + 'editAmount: ' + editAmount + 'editPend: ' + editPend + 'editDate: ' + editDate + 'editPayee: ' + editPayee + 'editCat: ' + editCat + 'editDescrip: ' + editDescrip);

            $.ajax({
                    type: "POST",
                    url: "./resources/includes/edit-transaction.php",
                    data:userdata, 
                    success: function(data){
                        window.location.href = "./transactions.php";
                    }
                    });
        });

        $('#cancel-button').click(function() {
            window.location.href = "./transactions.php";
        });

        $('#delete-button').click(function() {
            var delTrans = $("#trans").text();
            var alert = confirm("Are you sure you want to delete this transaction?");
            if (alert == true) {
                var userdata = {'delTrans':delTrans};
                $.ajax({
                        type: "POST",
                        url: "./resources/includes/edit-transaction.php",
                        data:userdata, 
                        success: function(data){
                            window.location.href = "./transactions.php";
                        }
                        });
            }
        });
    });

    function originalData(trans, type, account, amount, pending, date, payee, category, description) {
        $('#transaction-content').hide();
        $('#form-content').show();
        // $("#type").val(type);
        // $("#account").val(account);
        $("#amount").val(amount);
        // $("#pending").val(pending); // not sure if this will work
        $("#date").val(date);
        $("#payee").val(payee);
        // $("#category").val(category);
        $("#description").val(description);
        $("#trans").text(trans);
    }

    function readData() {
        var newAccount = "All Accounts";
        var userdata = {'newAccount':newAccount};
        $.ajax({
                type: "POST",
                url: "./resources/includes/update-transaction-graph.php",
                data:userdata, 
                success: function(data){
                    var obj = JSON.parse(data);

                    // Delete all old rows
                    $("#info").empty();

                    // Add design
                    if (obj.incomeBal < 0) {
                        var fIncomeBal = "-$" + (obj.incomeBal - (2 * obj.incomeBal)).toFixed(2);
                    } else {
                        var fIncomeBal = "+$" + obj.incomeBal.toFixed(2);
                    }
                    if (obj.expenseBal < 0) {
                        var fExpenseBal = "-$" + (obj.expenseBal - (2 * obj.expenseBal)).toFixed(2);
                    } else {
                        var fExpenseBal = "$" + obj.expenseBal.toFixed(2);
                    }
                    if (obj.pendingExpenseBal < 0) {
                        var fPendingExpenseBal = "-$" + (obj.pendingExpenseBal - (2 * obj.pendingExpenseBal)).toFixed(2);
                    } else {
                        var fPendingExpenseBal = "$" + obj.pendingExpenseBal.toFixed(2);
                    }
                    if (obj.totalAccountBal < 0) {
                        var fTotalAccountBal = "-$" + (obj.totalAccountBal - (2 * obj.totalAccountBal)).toFixed(2);
                        $('#account-balance').addClass('text-color-alert');
                    } else {
                        var fTotalAccountBal = "$" + obj.totalAccountBal.toFixed(2);
                        $('#account-balance').removeClass('text-color-alert');
                    }
                    if (obj.totalTotalAccBal < 0) {
                        var fTotalTotalAccBal = "-$" + (obj.totalTotalAccBal - (2 * obj.totalTotalAccBal)).toFixed(2);
                        $('#total-acc-balance').addClass('text-color-alert');
                    } else {
                        var fTotalTotalAccBal = "$" + obj.totalTotalAccBal.toFixed(2);
                        $('#total-acc-balance').removeClass('text-color-alert');
                    }

                    // Update bar graph
                    $('#bar-income').css('height', obj.incomeVal);
                    $('#bar-income').css('margin-top', obj.incomeMarg);
                    $('#bar-expense').css('height', obj.expenseVal);
                    $('#bar-expense').css('margin-top', obj.expenseMarg);
                    $('#bar-pending-expense').css('height', obj.pendingExpenseVal);
                    $('#bar-pending-expense').css('margin-top', obj.pendingExpenseMarg);

                    // Update values
                    $('#income-total').text(fIncomeBal);
                    $('#expense-total').text(fExpenseBal);
                    $('#pending-expense-total').text(fPendingExpenseBal);
                    $('#account-balance').text(fTotalAccountBal);
                    $('#total-acc-balance').text(fTotalTotalAccBal);

                    // Assign rows for each category
                    
                    var i;
                    for (i = 0; i < obj.transItems.length; i++) {
                        $( "#info" ).append( obj.transItems[i] );
                    }
                }
                });
    }

    $('#form-content').hide();
    readData();

</script>

</body>
</html>
