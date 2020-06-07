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
    <title>Debt Slasher | Budget</title>

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

<!-- Budget Content -->
    <div class="container footer-m" id="budget-content">
        <div class="row">
            <div class="col-md-6 mx-auto">

            <!-- Title Section -->
                <div class="row mt-5 p-2" id="title-row">
                    <div class="col-6">
                        <p>Month of</p>
                        <h1><?php echo date('F'); ?></h1>
                    </div>
                    <div class="col-6 t-center justify-content-end">
                        <a href="./budget-form.php"><img class="add-button" src="./resources/images/icons/add-button-w.svg" alt="add transaction"></a>
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
                            <p id="budget-total">$0</p>
                        </div>
                    </div>
                    <div class="col-4 p-2 bx-sides d-flex justify-content-center">
                        <div>
                            <p class="b-title">Income</p>
                            <p id="income-total">+$0</p>
                        </div>
                    </div>
                    <div class="col-4 p-2 d-flex justify-content-center">
                        <div>
                            <p class="b-title">Expenses</p>
                            <p id="expense-total">-$0</p>
                        </div>
                    </div>
                </div>
                <div class="row bg-color-secondary mb-2">
                    <div class="col-6 p-2 d-flex justify-content-center">
                        <div>
                            <p class="b-title">Budget Balance</p>
                            <p id="budget-balance">$0</p>
                        </div>
                    </div>
                    <div class="col-6 p-2 bl-sides d-flex justify-content-center">
                        <div>
                            <p class="b-title">Income Balance</p>
                            <p id="income-balance">$0</p>
                        </div>
                    </div>
                </div>
            <!-- Budget Categories -->
                <div class="row tt-sides">
                    <div class="col-12" id="info">
                    </div>
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
                        <h2 class="py-3 px-2 m-0">Budget Category</h2>
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
                            <p class="m-0">Category</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input id="category" type="text" name="category" placeholder="ex. Electric" maxlength="12" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Budget</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input id="budget" type="text" name="amount" step="1" pattern="^\d{1,9}(\.\d{0,2})?$" placeholder="ex. 1000.00" maxlength="12" autocomplete="off" required>
                            <p class="d-none" id="budg"></p>
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

<script>

    $(document).ready(function(){

        $('#save-button').click(function() {
            var editBudg = $("#budg").text();
            var editType = $("#type").val();
            var editCat = $("#category").val();
            var editAmount = $("#budget").val();
            var userdata = {'editBudg':editBudg, 'editType':editType, 'editCat':editCat, 'editAmount':editAmount};
            $.ajax({
                    type: "POST",
                    url: "./resources/includes/edit-budget.php",
                    data:userdata, 
                    success: function(data){
                        window.location.href = "./budget.php";
                    }
                    });
        });

        $('#cancel-button').click(function() {
            window.location.href = "./budget.php";
        });

        $('#delete-button').click(function() {
            var delBudg = $("#budg").text();
            var delCat = $("#category").val();
            var alert = confirm("Are you sure you want to delete " + delCat + "? Your individual transactions will not be deleted.");
            if (alert == true) {
                var userdata = {'delBudg':delBudg};
                $.ajax({
                        type: "POST",
                        url: "./resources/includes/edit-budget.php",
                        data:userdata, 
                        success: function(data){
                            window.location.href = "./budget.php";
                        }
                        });
            }
        });
    });

    function originalData(budg, type, category, budget) {
        $('#budget-content').hide();
        $('#form-content').show();
        $("#budg").text(budg);
        $("#type").val(type);
        $("#category").val(category);
        $("#budget").val(budget);
    }

    function readData() {
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

    $('#form-content').hide();
    readData();

</script>

</body>
</html>
