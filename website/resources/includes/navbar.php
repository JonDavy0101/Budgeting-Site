<!-- Navbar -->
<div class="bg-color-secondary" id="main-navbar">
    <div class="container p-0">
        <nav class="navbar navbar-expand-md py-3">
            <a class="navbar-brand pr-2" href="./index.php"><img id="nav-logo" src="./resources/images/logos/ds-logo.svg"></a>
            <button class="navbar-toggler p-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <img src="./resources/images/icons/hamburger-icon-w-r.svg" width="40px">
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="./index.php">Home<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./budget.php">Budget</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./transactions.php">Transactions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./accounts.php">Accounts</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto justify-content-end mt-md-0 mt-4">
                    <?php
                        if($_SESSION['logged-in'] == true)
                        {   
                    ?>
                            <li><a href="./resources/includes/logout.php"><img class="nav-glyph d-inline-block" src="./resources/images/icons/logout-w.png"><p class="d-inline-block m-0"> Logout</p></a></li>
                    <?php   
                        } else 
                        {
                    ?>
                            <li><a href="./login.php"><img class="nav-glyph d-inline-block" src="./resources/images/icons/login-w.png"><p class="d-inline-block m-0"> Login</p></a></li>
                    <?php
                        }
                    ?>
                </ul>
            </div>
        </nav>
    </div>
</div>