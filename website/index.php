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
    <title>Debt Slasher | Home</title>

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

<!-- Content -->
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">

            <!-- Title Section -->
                <div class="row mt-5 p-2" id="title-row">
                    <div class="col-6">
                        <p>Month of</p>
                        <h1><?php echo date('F'); ?></h1>
                    </div>
                    <div class="col-6 t-center justify-content-end">
                        <a href="./transaction-form.php"><img class="add-button" src="./resources/images/icons/add-button-w.svg" alt="add transaction"></a>
                    </div>
                </div>
            <!-- Bar Graph Section -->
                <div class="row mt-3" id="bar-graph-row">
                    <div class="col-4 h-100 p-0">
                        <div id="bar-budget"></div>
                    </div>
                    <div class="col-4 h-100 p-0 bx-sides">
                        <div id="bar-income"></div>
                    </div>
                    <div class="col-4 h-100 p-0">
                        <div id="bar-expense"></div>
                    </div>
                </div>
                <div class="row bg-color-secondary by-sides">
                    <div class="col-4 p-2 d-flex justify-content-center">
                        <div>
                            <p class="b-title">Budgeted Expenses</p>
                            <p id="budget-total">$0.00</p>
                        </div>
                    </div>
                    <div class="col-4 p-2 bx-sides d-flex justify-content-center">
                        <div>
                            <p class="b-title">Income</p>
                            <p id="income-total">+$0.00</p>
                        </div>
                    </div>
                    <div class="col-4 p-2 d-flex justify-content-center">
                        <div>
                            <p class="b-title">Expenses</p>
                            <p id="expense-total">-$0.00</p>
                        </div>
                    </div>
                </div>
                <div class="row bg-color-secondary footer-m">
                    <div class="col-6 p-2 d-flex justify-content-center">
                        <div>
                            <p class="b-title">Budget Balance</p>
                            <p id="budget-balance">$0.00</p>
                        </div>
                    </div>
                    <div class="col-6 p-2 bx-sides d-flex justify-content-center">
                        <div>
                            <p class="b-title">Income Balance</p>
                            <p id="income-balance">+$0.00</p>
                        </div>
                    </div>
                </div>

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
<script>

    function originalData() {
        var userId = 563;
        var userdata = {'userId':userId};
        $.ajax({
                type: "POST",
                url: "./resources/includes/update-budget-graph.php",
                data:userdata, 
                success: function(data){
                    var obj = JSON.parse(data);

                    // Delete all old rows
                    $("#info").empty();

                    // Add design
                    if (obj.budgetBal < 0) {
                        var fBudgetBal = "-$" + (obj.budgetBal - (2 * obj.budgetBal)).toFixed(2);
                    } else {
                        var fBudgetBal = "$" + obj.budgetBal.toFixed(2);
                    }
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
                    if (obj.totalBudgetBal < 0) {
                        var fTotalBudgetBal = "-$" + (obj.totalBudgetBal - (2 * obj.totalBudgetBal)).toFixed(2);
                        $('#budget-balance').addClass('text-color-alert');
                    } else {
                        var fTotalBudgetBal = "$" + obj.totalBudgetBal.toFixed(2);
                        $('#budget-balance').removeClass('text-color-alert');
                    }
                    if (obj.totalIncomeBal < 0) {
                        var fTotalIncomeBal = "-$" + (obj.totalIncomeBal - (2 * obj.totalIncomeBal)).toFixed(2);
                        $('#income-balance').addClass('text-color-alert');
                    } else {
                        var fTotalIncomeBal = "$" + obj.totalIncomeBal.toFixed(2);
                        $('#income-balance').removeClass('text-color-alert');
                    }

                    // Update bar graph
                    $('#bar-budget').css('height', obj.budgetVal);
                    $('#bar-budget').css('margin-top', obj.budgetMarg);
                    $('#bar-income').css('height', obj.incomeVal);
                    $('#bar-income').css('margin-top', obj.incomeMarg);
                    $('#bar-expense').css('height', obj.expenseVal);
                    $('#bar-expense').css('margin-top', obj.expenseMarg);

                    // Update values
                    $('#budget-total').text(fBudgetBal);
                    $('#income-total').text(fIncomeBal);
                    $('#expense-total').text(fExpenseBal);
                    $('#budget-balance').text(fTotalBudgetBal);
                    $('#income-balance').text(fTotalIncomeBal);

                    // Assign rows for each category
                    var i;
                    for (i = 0; i < obj.rowResult.length; i++) {
                        $( "#info" ).append( obj.rowResult[i] );
                    }
                }
                });
    }

    originalData();

</script>

</body>
</html>
