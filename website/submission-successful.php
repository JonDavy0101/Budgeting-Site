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
    <title>Debt Slasher | Submission Successful</title>

    <!-- Metas and General Links -->
    <?php
        include_once('./resources/includes/head.php');
    ?>

</head>
<body>
<!-- Navbar -->
<div class=" min-100 d-flex flex-column">
    <?php
        include_once('./resources/includes/navbar.php');
    ?>

<!-- Content -->
    <div class="container-fluid bg-color-light my-auto">
    <!-- Login Form -->
        <div class="row my-auto">
            <div class="col-lg-4 col-md-7 mx-auto text-center">
                <h1 class="text-color-secondary pt-5 pb-0 px-1 mb-5">Submission Successful!</h1>
                <br>
                <input class="mb-5 mx-1" type="button" value="HOME">
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
</body>
</html>