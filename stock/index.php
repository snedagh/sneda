<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    require 'backend/includes/core.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMHOS - CLI</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/anton.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/keyboard.css">


    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="js/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sweetalert.min.css">

    <script src="js/error_handler.js"></script>
    <script src="js/anton.js"></script>
    <script src="js/keyboard.js"></script>
    <link rel="stylesheet" href="css/sweetalert.min.css">









</head>
<body  class="bg-light">

    <div class="container h-100">
        <?php if(isset($_SESSION['cli_login']) && $_SESSION['cli_login'] === 'true'){ ?>
            <div class="row h-100 d-flex flex-wrap align-content-center justify-content-center">
            <div class="col-sm-6">
                <form  id="general_form" action="backend/process/form_process.php" method="post" class="input-group">
                    <input type="hidden" name="function" value="addItem">
                    <input type="number" required placeholder="qty" class="form-control mb-2 w-25" autofocus autocomplete="off" name="qty" id="qty">
                    <input type="text" placeholder="barcode" class="form-control w-75" autofocus autocomplete="off" name="barcode" id="barcode">
                    <input style="display: none" type="submit" >
                </form>
            </div>
            <div class="col-sm-6 h-50 pt-2">
                <div class="w-100 h-100 card overflow-hidden">
                    <header class="bg_balance">

                        <div class="d-flex flex-wrap cart_item align-content-center justify-content-between border-dotted pb-1 pt-1">

                            <div class="75 h-100 d-flex flex-wrap align-content-center pl-1">
                                <p class="m-0 p-0 font-weight-bolder">Barcode</p>
                            </div>

                            <div class="25 h-100 d-flex flex-wrap align-content-center pl-1">
                            <p class="m-0 p-0 font-weight-bolder">Quantity</p>
                        </div>


                        </div>

                    </header>
                    <article>
                        <div id="itemsRes" class="w-100 h-100 overflow-auto">

                        </div>
                    </article>
                </div>
            </div>
        </div>
        <?php } else { ?>
            <div class="row h-100 d-flex flex-wrap align-content-center justify-content-center">
                <div class="col-sm-4">
                    <form id="login_form" action="backend/process/user_mgmt.php" method="post" class="input-group">
                        <input type="text" autocomplete="off" autofocus name="username" placeholder="Username" class="form-control rounded-0  w-100 mb-2">
                        <input type="password" autocomplete="off" name="password" placeholder="Password" class="form-control w-100 rounded-0 mb-2">
                        <button class="w-100 btn btn-success rounded-0">AUTH</button>
                    </form>
                </div>
            </div>
        <?php } ?>

    </div>
    
</body>
</html>

<script>

</script>




