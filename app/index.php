<?php
//    TODO Work on task manager as an app
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    require 'config/include/core.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        if ($login === 'yes')
        {
            echo "<title>SMDESK</title>";
        }
        else
        {
            echo "<title>Welcome - Login</title>";
        }
    ?>
    <link rel="icon" href="assets/icons/general/logo.png">
    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/colors.css">
    <link rel="stylesheet" href="css/all.css">
    <link href="css/video-js.css" rel="stylesheet" />
<!--    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.9/plyr.css" />-->
<!---->
<!--    <script src="https://cdn.plyr.io/3.6.9/plyr.js"></script>-->

    <!-- jQuery library -->
    <script src="js/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="js/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="js/bootstrap.min.js"></script>


    <script src="js/all.js"></script>




</head>
<body class="bg-home d-flex flex-wrap align-content-center justify-content-center">
    <input type="hidden" name="token" id="session_token" value="<?php echo session_id() ?>">
    <!-- LOADER MODAL -->
    <div class="modal fade in" data-keyboard="false" data-backdrop="static" id="loader">
        <div class="modal-dialog modal-dialog-centered">
            <div class="w-100 bg-light p-2 text-center">
                <button class="btn m-0 btn-light" disabled>
                    <span class="spinner-grow spinner-grow-sm"></span>
                    Processing..
                    <div class="text-center text-danger" id="loader_error_place"></div>
                </button>
                <p class="text-danger" id="errplace"></p>
                <p class="text-success" id="successplace"></p>
                <p class="text-success" id="infoplace"></p>
            </div>
        </div>
    </div>


    <?php if ($login === 'no'): ?>
        <!--LOGIN FORM-->
        <div class="w-25 p-2">

            <div class="w-25 mb-2 rounded-circle overflow-hidden mx-auto">
                <?php
                    $image = 'assets/icons/general/logo.png';
                    // Read image path, convert to base64 encoding
                    $imageData = base64_encode(file_get_contents($image));

                    // Format the image SRC:  data:{mime};base64,{data};
                    $src = 'data: '.mime_content_type($image).';base64,'.$imageData;
                ?>
                <img src="<?php echo $src ?>" class="img-fluid">
            </div>

            <?php if ($_SESSION['login'] === 'username'): ?>

                <legend class="text-center">
                    Sign in to Smart Desk
                </legend>

                <form id="loginForm" style="border: 1px solid; border-color: #dee2e6;" id="form" class="form-inline bg-light rounded w-100" action="config/proc/usermgmt.php" method="post">
                    <input autofocus onfocus="typing()" type="text" value="<?php if (isset($_SESSION['username'])){ echo $_SESSION['username'];unset($_SESSION['username']);} ?>" autocomplete="off" class="form-control p-3 form-bg-none w-90 no-outline" placeholder="Username" name="username">

                    <button type="submit" name="login" class="btn w-10 p-0 border-light no-outline">
                        <div class="w-100 h-100 d- flex-wrap align-content-center justify-content-center">
                            <?php
                                $image = 'assets/icons/login/arrow.png';
                                // Read image path, convert to base64 encoding
                                $imageData = base64_encode(file_get_contents($image));

                                // Format the image SRC:  data:{mime};base64,{data};
                                $src = 'data: '.mime_content_type($image).';base64,'.$imageData;
                            ?>

                            <img src="<?php echo $src ?>" class="img-fluid rounded-circle border h_btn_md">
                        </div>
                    </button>
                </form>
                <i class="text_sm text-warning"><?php if (isset($_SESSION['username_err'])){ echo $_SESSION['username_err']; unset($_SESSION['username_err']);}  ?></i>

                <script>
                    function typing()
                    {
                        document.getElementById('loginForm').style.borderColor = '#17a2b8';
                    }
                </script>

            <?php endif; ?>

            <?php if ($_SESSION['login'] === 'password'): ?>

                <legend class="text-center">
                Sign in to Smart Desk
            </legend>

                <form id="loginForm" style="border: 1px solid; border-color: #dee2e6;" id="form" class="form-inline bg-light rounded w-100" action="config/proc/usermgmt.php" method="post">
                <input autofocus onfocus="typing()" type="password" autocomplete="off" class="form-control p-3 form-bg-none w-90 no-outline" placeholder="Password" name="password">

                <button type="submit" name="login" class="btn w-10 p-0 border-light no-outline">
                    <div class="w-100 h-100 d- flex-wrap align-content-center justify-content-center">
                        <?php
                                $image = 'assets/icons/login/arrow.png';
                                // Read image path, convert to base64 encoding
                                $imageData = base64_encode(file_get_contents($image));

                                // Format the image SRC:  data:{mime};base64,{data};
                                $src = 'data: '.mime_content_type($image).';base64,'.$imageData;
                            ?>

                        <img src="<?php echo $src ?>" class="img-fluid rounded-circle border h_btn_md">
                    </div>
                </button>
            </form>

            <i class="text_sm text-warning"><?php if (isset($_SESSION['password_err'])){ echo $_SESSION['password_err']; unset($_SESSION['password_err']);}  ?></i>

            <script>
                function typing()
                {
                    document.getElementById('loginForm').style.borderColor = '#17a2b8';
                }
            </script>

            <?php endif; ?>

        </div>
    <?php endif; ?>

    <?php if ($login === 'yes'): ?>

       <div class="w-100 h-100 overflow-auto">



           <!--WORK SPACE-->
           <div class="h-100">

               <?php
                    if($location == 'home')
                    {
                        // include home
                        include "config/include/parts/home.php";
                    }
                    elseif($location == 'app_store')
                    {
                        // include app store
                        include "config/include/parts/appstore/index.php";
                    }
                    elseif ($location == 'profile')
                    {
                        // include user profile
                        include "config/include/parts/user_profile.php";
                    }
                    elseif ($location == 'admin_panel')
                    {
                        // include admin panel
                        include 'config/include/parts/admin_panel/root.php';
                    }

                    elseif ($location == 'videos')
                    {
                        include "config/include/parts/home.php";
                    } elseif ($location == 'taskmgr')
                    {
                        require 'config/include/parts/admin_panel/task_manager.php';
                    }
                    elseif ($location === 'mycom')
                    {
                        // spintext sql
                        try {
                            $dsn = "sqlsrv:Server=192.168.1.2,1433;Database=POSDBV4";
                            $spin_retail = new PDO($dsn, "sa", "sa@123456");
                            $spin_retail->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                            //    $conn_rest->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL);
                            
                        } catch(PDOException $e) {
                            echo $err = $e->getMessage();
                            die();
                        }

                        // nia retails
                        try {
                            $dsn = "sqlsrv:Server=192.168.3.2,1433;Database=POSDBV4";
                            $niaDD = new PDO($dsn, "sa", "sa@123456");
                            $niaDD->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                            //    $conn_rest->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL);

                        } catch(PDOException $e) {
                            echo $err = $e->getMessage();
                            die();
                        }
                        // mycom
                        if($tool === 'loyalty')
                        {
                            if(!isset($_SESSION['do']))
                            {
                                $_SESSION['do'] = 'view';
                            }
                            $do = $_SESSION['do'];

                            if($view === 'customers')
                            {
                                // get first customer
                                if(!isset($_SESSION['active_customer']))
                                {
                                    $q = $pdo->query("SELECT `card_no` FROM `customers` ORDER BY `id` DESC LIMIT 1");
                                    $r = $q->fetch(PDO::FETCH_ASSOC);
                                    $current_loyalty_customer = $r['card_no'];
                                    $_SESSION['active_customer'] = $current_loyalty_customer;
                                }

                                $active_customer = $_SESSION['active_customer'];
                                rowsOf('customers',"`card_no` = '$active_customer'",$pdo) ;

                                if(rowsOf('customers',"`card_no` = '$active_customer'",$pdo) > 0 )
                                {
                                    $customer = fetchFunc('customers',"`card_no` = '$active_customer'",$pdo);


                                    $customer_exist = 'yes';
                                }
                                else
                                {
                                    $customer_exist = 'no';
                                }


                            }
                        }
                        elseif ($tool === 'dashboard')
                        {
                            $today = date('Y-m-d');

                            ## SPINTEX
                            // get sales
                            $retail_sales = $spin_retail->query("select  SUM(tran_amt) as sales from bill_tran where type_code In ('RS','VV','DS','PI','NF');");
                            $s_sale = $retail_sales->fetch(PDO::FETCH_ASSOC);
                            $spintex_sales = 0;
                            if(is_numeric($s_sale['sales']))
                            {
                                $spintex_sales = $s_sale['sales'];
                            }


                            $tax_sql = $spin_retail->query("select SUM(tax_amount) as spintex_tax from bill_tax_tran where bill_date =  '$today'");
                            $tax_stmt = $tax_sql->fetch(PDO::FETCH_ASSOC);
                            $spintex_tax = 0;
                            if(is_numeric($tax_stmt['spintex_tax']))
                            {
                                $spintex_tax = $tax_stmt['spintex_tax'];
                            }

                            // spintex discount
                            $disc_sql = $spin_retail->query("SELECT SUM( tran_amt )  AS discount  FROM bill_tran  
WHERE type_code In ('RR','RC','DR','DM','DP','MN','MP','W','DN','PO','DT') ");
                            $disc_stmt = $disc_sql->fetch(PDO::FETCH_ASSOC);
                            $spintex_disc = 0;
                            if(is_numeric($disc_stmt['discount']))
                            {
                                $spintex_disc = str_replace('-',0,$disc_stmt['discount']);
                            }

                            $x = $spintex_sales - $spintex_disc;
                            $sp_sales = $x - $spintex_tax;

                            // get machines
                            $machines_sql = $spin_retail->query('select mech_no from mech_setup');
                            $niaD = $niaDD->query("SELECT mech_no, SUM( tran_amt ) AS sales  FROM bill_tran WHERE type_code In ('RS','VV','DS','PI','NF') group by mech_no");
                            ## SPINTEX

                            ## NIA
                            $niaSales = $niaDD->query("select  SUM(tran_amt) as sales from bill_tran where type_code In ('RS','VV','DS','PI','NF');");
                            $niaSaleFetch = $niaSales->fetch(PDO::FETCH_ASSOC);
                            $niaSale = 0;
                            if(is_numeric($niaSaleFetch['sales']))
                            {
                                $niaSale = $niaSaleFetch['sales'];
                            }


                            $niaTax_sql = $niaDD->query("select SUM(tax_amount) as spintex_tax from bill_tax_tran where bill_date =  '$today'");
                            $niaTax_stmt = $niaTax_sql->fetch(PDO::FETCH_ASSOC);
                            $niaTax = 0;
                            if(is_numeric($niaTax_stmt['spintex_tax']))
                            {
                                $niaTax = $niaTax_stmt['spintex_tax'];
                            }

                            // spintex discount
                            $niaDisc_sql = $niaDD->query("select  SUM(tran_amt) as discount from bill_tran where type_code In ('RR','RC','DR','DM','DP','MN','MP','W','DN','PO','DT')");
                            $niaDisc_stmt = $niaDisc_sql->fetch(PDO::FETCH_ASSOC);
                            $niaDisc = 0;
                            if(is_numeric($niaDisc_stmt['discount']))
                            {
                                $niaDisc = str_replace('-',0,$niaDisc_stmt['discount']);
                            }

                            $niaGross = $niaSale - $niaDisc;
                            $niaNet = $niaGross - $niaTax;

                            // get machines
                            $niaMachines = $niaDD->query('select mech_no from mech_setup');
                            ## NIA

                            ## together
                            $toNet = $niaNet + $sp_sales;
                        }
                        require 'config/include/parts/mycom/home.php';

                    }

                    else
                    {

                        require_once 'config/include/parts/application_not_configured.html';
                    }

               ?>



           </div>

           <!--HEADER-->
           <?php require 'config/include/parts/nav.php' ?>

       </div>

    <?php endif; ?>

</body>
</html>


<!-- ANTON -->
<script src="js/anton.js"></script>


<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(".custom-file-input").on("change", function (){
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass('selected').html(fileName);
    });

    function installApplication(task,app,token)
    {
        const install_log = document.getElementById('info');
        install_log.innerHTML = 'installing ' + app;

        if (window.XMLHttpRequest)
        {
            xmlhttp = new XMLHttpRequest();
        }
        else
        {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        }

        xmlhttp.onreadystatechange = function ()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                var result = xmlhttp.responseText;
                console.log(result);
                install_log.innerHTML = result;
                alert(result);
                location.reload()

            }
        }

        if(task === 'i')
        {
            xmlhttp.open('GET', 'config/proc/ajax.php?install=' + app + '&token='+token, true)
        }
        else if (task === 'u')
        {
            xmlhttp.open('GET', 'config/proc/ajax.php?uninstall=' + app + '&token='+token, true)
        }
        xmlhttp.send();

    }



</script>