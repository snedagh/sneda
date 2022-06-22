<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require '../config/include/core.php';
    $user_id = $_SESSION['user_id'];
    $user_details = fetchFunc('users',"`id` = $user_id",$pdo);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMDESK - CONFIGURE PROFILE</title>
    <link rel="icon" href="../assets/icons/general/logo.png">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/colors.css">
</head>
<body class="bg-light d-flex flex-wrap align-content-center justify-content-center">

    <div class="w-100 overflow-hidden d-flex flex-wrap align-content-center justify-content-center h-100">

        <div class="w-30 card mx-auto border">
            <div class="card-header p-2">
                <strong class="card-text">Quick Setup</strong> <p class="m-0">It’s quick and easy.</p>

            </div>

            <div class="card-body p-2">
                <form id="regular_form" method="post" action="../config/proc/form_process.php">
                    <div class="w-100 d-flex flex-wrap justify-content-between">
                        <p class="m-0 font-weight-bold">Personal Information</p>
                        <!--FIRST NAME-->
                        <div class="w-100 mb-2">
                            <input required type="text" class="form-control" name="first_name" autocomplete="off" placeholder="First name">

                        </div>

                        <input type="hidden" name="token" id="" value="<?php echo session_id() ?>">

                        <!--LAST NAME-->
                        <div class="w-100 mb-2">
                            <input required type="text" class="form-control" name="last_name" autocomplete="off" placeholder="Last name">

                        </div>

                        <div class="w-100 d-flex flex-wrap">

                            <select name="gender" class="custom-select">
                                <option value="0" selected>Gender</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <!--PHONE EMAIL-->
                    <div class="w-100 d-flex flex-wrap justify-content-between">
                        <p class="m-0 font-weight-bold">Contact Information</p>
                        <!--PHONE-->
                        <div class="w-100 mb-2">
                            <input required minlength="10" maxlength="10" type="tel" class="form-control" name="phone" autocomplete="off" placeholder="Mobile number">

                        </div>
                        <!--EMAIL ADDRESS-->
                        <div class="w-100 mb-2">
                            <input type="email" class="form-control" name="email" autocomplete="off" placeholder="Email address">

                        </div>
                    </div> <hr>

                    <!--SECUIRUTY-->
                    <div class="w-100 d-flex mb-2 flex-wrap justify-content-between">
                        <p class="m-0 font-weight-bold w-100">Contact Information</p>
                        <!--PHONE-->
                        <div class="w-45 mb-2">
                            <input required type="password" class="form-control" name="password" autocomplete="off" placeholder="Password">

                        </div>
                        <!--EMAIL ADDRESS-->
                        <div class="w-45 mb-2">
                            <input type="password" required class="form-control" name="comf_password" autocomplete="off" placeholder="Confirm Password">

                        </div>
                    </div>

                    <div class="text-center w-100">
                        <small class="text-info">
                            Please note, data provided is collected and used for internal testing purposes only.
                        </small>
                    </div>

                    <div class="w-100 text-center">
                        <input type="submit" value="SUBMIT" name="setup" class="btn btn-success">
                    </div>

                </form>
            </div>

        </div>



    </div>

</body>

<!-- jQuery library -->
<script src="../js/jquery.min.js"></script>

<!-- Popper JS -->
<script src="../js/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="../js/bootstrap.min.js"></script>
<!-- ANTON -->
<script src="../js/anton.js"></script>