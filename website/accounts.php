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
    <title>Debt Slasher | Accounts</title>

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

<!-- Accounts Content -->
    <div class="container footer-m" id="account-content">
        <div class="row">
            <div class="col-md-6 mx-auto">

            <!-- Title Section -->
                <div class="row mt-5" id="title-row">
                    <div class="col-6">
                        <h2 class="py-3 px-2 m-0">Accounts</h2>
                    </div>
                    <div class="col-6 t-center justify-content-end">
                        <a href="./account-form.php"><img class="add-button" src="./resources/images/icons/add-button-w.svg" alt="add account"></a>
                    </div>
                </div>
            <!-- Budget Categories -->
                <div class="row tt-sides">
                    <div class="col-12">
                        <?php
                            include_once('./resources/includes/connect.php');
                            $user = mysqli_real_escape_string($conn, $_SESSION['id']);

                            $sql = "SELECT accountId, account FROM dsAccounts WHERE userId = $user ORDER BY account ASC";

                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                   if ($result->num_rows > 0) {
                                        echo '<div onclick="originalData(\'' . $row["accountId"] . '\', \'' . $row["account"] . '\')" class="data-row row tb-sides"><div class="col p-2 t-center"><img class="t-symbol" src="./resources/images/icons/account-symbol.svg" alt="account-img"><p class="acc-name t-descrip d-inline-block">' . $row["account"] . '</p></div></div>';
                                    }
                                }
                            } else {
                                echo '<div class="row tb-sides"><div class="col p-2 t-center"><p class="t-descrip d-inline-block text-color-alert">Add a currency account to get started</p></div></div>';
                            }

                            mysqli_close($conn);
                        ?>
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
                        <h2 class="py-3 px-2 m-0">Edit Account</h2>
                    </div>
                </div>
            <!-- Budget Categories -->
                <form method="post" action="./resources/includes/edit-account.php">
                    <div class="row tb-sides">
                        <div class="col-4 p-2 t-center justify-content-end">
                            <p class="m-0">Account Name</p>
                        </div>
                        <div class="col-8 p-2 t-center">
                            <input id="account-name" type="text" name="account" placeholder="ex. Cash" maxlength="12" autocomplete="off" required>
                            <p class="d-none" id="account"></p>
                            <p class="d-none" id="orig-account"></p>
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
<script>
    $(document).ready(function(){
        $('#save-button').click(function() {
            var editAcc = $("#account").text();
            var editOrigAcc = $("#orig-account").text();
            var editAccName = $("#account-name").val();
            var userdata = {'acc':editAcc, 'accName':editAccName, 'origAccName':editOrigAcc};
            $.ajax({
                    type: "POST",
                    url: "./resources/includes/edit-account.php",
                    data:userdata, 
                    success: function(data){
                        window.location.href = "./accounts.php";
                    }
                    });
        });

        $('#cancel-button').click(function() {
            window.location.href = "./accounts.php";
        });

        $('#delete-button').click(function() {
            var delAcc = $("#account").text();
            var delAccName = $("#account-name").val();
            var alert = confirm("Are you sure you want to delete " + delAccName + "? Your individual transactions will not be deleted.");
            if (alert == true) {
                var userdata = {'acc':delAcc};
                $.ajax({
                        type: "POST",
                        url: "./resources/includes/edit-account.php",
                        data:userdata, 
                        success: function(data){
                            window.location.href = "./accounts.php";
                        }
                        });
            }
        });
    });

    $('#form-content').hide();

    function originalData(acc, accName) {
        $('#account-content').hide();
        $('#form-content').show();
        $("#account").text(acc);
        $("#orig-account").text(accName);
        $("#account-name").val(accName);
    }
</script>

</body>
</html>
