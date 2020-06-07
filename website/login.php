<!DOCTYPE html>
<html lang="en">
<head>
    <title>Debt Slasher | Login</title>

    <!-- Metas and General Links -->
    <?php
        include_once('./resources/includes/head.php');
    ?>

</head>
<body>
<!-- Login Code -->
    <?php
    // Always start this first
    session_start();

    if ( ! empty( $_POST ) ) {
        if ( isset( $_POST['username'] ) && isset( $_POST['password']) ) {
            // Getting submitted user data from database
            include_once('./resources/includes/connect.php');

            // Create connection
            $stmt = $conn->prepare("SELECT * FROM coolUser WHERE username = ?");
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_object();
            $stmt->close();
                
            //print_r($user);
               // password_hash($_POST['password'],PASSWORD_BCRYPT, array('cost',12));
            // Verify user password and set $_SESSION
            if ( password_verify( $_POST['password'], $user->password ) ) {
                $conn->close();

                $_SESSION['id'] = $user->userId;
                $_SESSION['username'] = $user->username;
                $_SESSION['last-login'] = time();
                $_SESSION['logged-in'] = true;

                header("Location: ./index.php");
            }else{
                $conn->close();
                $failPassword = true;
            }
        }
    }
    ?>

<!-- Navbar -->
<div class=" min-100 d-flex flex-column">
    <?php
        include_once('./resources/includes/navbar.php');
    ?>

<!-- Content -->
    <div class="container-fluid bg-color-light  my-auto">
    <!-- Login Form -->
        <div class="row my-auto">
            <div class="col-lg-4 col-md-7 mx-auto text-center">
                <form action="" method="post">
                    <h1 class="text-color-secondary pt-5 pb-0 px-1 mb-1">Login</h1>
                    <br>
                    <?php
                        if($failPassword)
                        {   
                    ?>
                            <p id='failed-password'>Your Username or Password is incorrect</p>
                    <?php   
                        }   
                    ?>
                    <br>
                    <input class="pass-text w-100 mb-2" type="text" name="username" placeholder="Username..">
                    <br>
                    <input class="pass-text w-100 mb-5" type="password" name="password" placeholder="Password..">
                    <br>
                    <input class="mb-5 mx-1" type="submit" value="Submit">
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
</body>
</html>