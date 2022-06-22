<?php
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);




    require 'config/inc/db.php';
    require 'config/inc/session.php';
    require 'config/inc/queries.php';



//    print_r($_SESSION);




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
          href="css/bootstrap.min.css">
    <link rel="stylesheet"
          href="css/colors.css">
    <link rel="stylesheet"
          href="css/general.css">
    <link rel='stylesheet' href='/css/uicons-regular-straight.css'>
    <link rel="shortcut icon"
          href="assets/icons/loyalty.png"
          type="image/x-icon">
    <title>Loyalty || <?php if (isset($_SESSION['username'])){echo $_SESSION['username'];}else{echo 'Login';} ?></title>
    <script>
        //check otp
        function checkOtp(otp)
        {

            if(otp.length > 0)
            {
                document.getElementById('spinner').style.display = '';
                document.getElementById('otpRes').style.display = '';
            }
            else
            {
                document.getElementById('spinner').style.display = 'none';
                document.getElementById('otpRes').style.display = 'none';
            }

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
                    document.getElementById('otpRes').innerHTML = result;

                    if(result === 'OTP MATCH')
                    {
                        location.reload();
                    }

                }
            }

            xmlhttp.open('GET', 'config/proc/form_process.php?token=<?php  echo $token ?>&checkOtp&otp=' + otp, true);
            xmlhttp.send();
        }

        //make query set
        function customQuery(action) {
            if(action === 'on')
            {
                document.getElementById('querySet').style.display = '';
                document.getElementById('qRes').style.display = '';
            }
            else
            {
                document.getElementById('querySet').style.display = 'none';
                document.getElementById('qRes').style.display = 'none';
            }
        }

        // test query
        function testQuery(val)
        {
            const query_x = document.getElementById('querySet').value;
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
                    const result = xmlhttp.responseText;
                    document.getElementById('qRes').innerHTML = result;
                    console.log(result);

                    switch (result)
                    {
                        case "<small class='text-success'> valid query </small>" :
                            document.getElementById('validQuery').disabled = false;
                            break
                        case "<small class='text-danger'>Invalid Query</small>" :
                            document.getElementById('validQuery').disabled = true;
                            document.getElementById('validQuery').style.cursor = 'disabled';
                            break;
                        default :
                            console.log('no option');

                    }

                }
            }

            xmlhttp.open('GET', 'config/proc/form_process.php?token=<?php  echo $token ?>&testQuery&q=' + val, true);
            xmlhttp.send();
        }



        function br(str)
        {

            var year = new Date().getFullYear();
            var month = new Date().getMonth();
            var day = new Date().getUTCDay();
            var d = year + ':' + month + ':' + day;

            var result = d + ' ' +  str.toString() + '<br>';
            return result;
        }

        // AJAX FUNCTION
        function ajax(url,variable,result_type,result_box)
        {
            var msgbox = document.getElementById(result_box); // message box

            if (window.XMLHttpRequest)
            {
                variable = new XMLHttpRequest(); // make XMLHttp Object for chromium browsers
            }
            else
            {
                variable = new ActiveXObject('Microsoft.XMLHTTP'); // make XMLHttp Object for Microsoft Pains LOL
            }

            variable.onreadystatechange = function ()
            {
                if (variable.readyState === 4 && variable.status === 200)
                {
                    const result = variable.responseText; // Ajax Response

                    switch(result_type) // make a switch case
                    {
                        case 'msgbox': // display result in a message box provided
                            msgbox.innerHTML =  result + msgbox.innerHTML;
                            break;
                        case 'console': // log result in console
                            console.log(result);
                            break;
                        case 'reload' :
                            location.reload();
                            break;
                        default: // no display set. just console log this for debugging
                            console.log('no result type for ajax to work with')
                    }

                    console.log(result);

                }
            }

            variable.open('GET', url, true);
            variable.send();

        }
        // VALIDATE SMS
        function validateSms(msg)
        {

            const message_len = msg.length;
            const valid_len = 20 - message_len;
            if(message_len >= 20 && contacts > 0)
            {
                document.getElementById('senSms').disabled = false;
            }
        }

        //send sms
        function sendSms()
        {
            var selectedCustomers = document.getElementById('selectedCustomers'); // Customers Frame
            var execTerminal = document.getElementById('execTerminal'); // Terminal Frame
            var msgbox = document.getElementById('terminalMessage');

            //hide customers and show terminal
            selectedCustomers.style.display = 'none';
            execTerminal.style.display = '';



            var max = 3;

            for (let i = 0; i <= max; i++) {
                if(i === 0)
                {
                    //check session
                    ajax('config/proc/task.php?token=<?php  echo $token ?>&sendSms&s=check_session','check_session','msgbox','terminalMessage');
                }
                else if(i === 1)
                {
                    // validate contacts
                    ajax('config/proc/task.php?token=<?php  echo $token ?>&sendSms&s=validate_numbers','validate_numbers','msgbox','terminalMessage');
                }
                else if (i === 2)
                {
                    // populate recipient
                    ajax('config/proc/task.php?token=<?php  echo $token ?>&sendSms&s=populate_table','populate_table','msgbox','terminalMessage');
                }
                else if (i === 3)
                {
                    // alert(i)
                    var msg = document.getElementById('msg').value;
                    //setInterval
                    //(
                    //    ajax('config/proc/task.php?token=<?php  echo $token ?>&token=<?php //echo session_id() ?>//&sendSms&s=send&msg='+msg,'send','msgbox','terminalMessage'),
                    //    3000
                    //);
                    function hello(str)
                    {
                        document.getElementById('terminalMessage').innerHTML = str
                    }
                    setInterval(
                        function (){
                            ajax('config/proc/task.php?token=<?php  echo $token ?>&sendSms&s=send&msg='+msg,'send','msgbox','terminalMessage')
                        }, 5000
                    );

                }
            }









        }


        function disable(id)
        {
            document.getElementById(id).disabled = true;
        }
    </script>
</head>

<body>
        <div class="container-fluid w-100 default_bg h-100 d-flex flex-wrap align-content-center justify-content-center">
            <!-- LOADER MODAL -->
            <div class="modal fade in" data-keyboard="false" data-backdrop="static" id="loader">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <strong class="modal-title">Log</strong>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body p-2">
                            <div class="w-100 bg-light p-2 text-center">
                                <button class="btn m-0 btn-light" disabled>
                                    <span class="spinner-grow spinner-grow-sm"></span>
                                    Processing..
                                </button>
                                <p class="text-danger" id="errplace"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-75 h-95 p-1 card cust_shadow bg-lign">

               <header class="bg-loyalty d-flex flex-wrap align-content-center justify-content-between pl-2">
                    <div class="w-40 h-100 d-flex flex-wrap align-content-center">

                        <button onclick="location.href='http://www1.sneda.gh/'" data-toggle="tooltip" title="Main Desk" class="h_btn_md pointer p-0">
                            <img src="assets/icons/arrow_left.png" class="img-fluid">
                        </button>

                        <div style="width: fit-content !important" class="pl-2 d-flex flex-wrap align-content-center">
                            <?php if($display === 'message'){ ?>
                                <strong>BULK SMS <kbd class="text-info">Balance <span class="text-warning"> : <?php echo $bal ?> Messages</span></kbd></strong>
                            <?php } else {echo 'Loyalty Card Processing';} ?>
                        </div>

                    </div>

                    <div class="w-40 d-flex p-1 o-hide flex-wrap align-content-center justify-content-end">

                        <?php if($display !== 'home'): ?>
                            <button onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&display=home'" data-toggle="tooltip" title="Home" class="h_btn_md ml-2 pointer p-0">
                                <img src="assets/icons/apps.png" class="img-fluid">
                            </button>

                            <div  data-keyboard="false" data-backdrop="static" class="modal bg-light fade" id="loader">
                                <div class="modal-dialog border-0 bg-light modal-dialog-centered">
                                    <div class="modal-content border-0 bg-light">
                                        <div class="modal-body bg-light text-center text-dark">
                                            <span class="spinner-grow spinner-grow-sm"></span>
                                            Processing..
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                    </div>
               </header>

                <article class="d-flex flex-wrap align-content-center justify-content-center">

                    <?php if($display === 'home'): ?>


                        <div onclick="set_session('<?php echo $token ?>','display=new_customer,l_stage=0')" class="card btnxx cust_shadow d-flex flex-wrap align-content-center justify-content-center o-hide pointer m-3 p-3">
                            <div class="w-100 text-center">
                                <img src="assets/icons/loyalty-card.png" class="img-fluid" alt="NEW CUST">
                                <p class="font-weight-bold text_eclips text_xsm">NEW CUST</p>
                            </div>

                        </div>


                        <div onclick="set_session('<?php echo $token ?>','display=customers,view=all')" class="card btnxx cust_shadow d-flex flex-wrap align-content-center justify-content-center o-hide pointer m-3 p-3">
                            <div class="w-100 text-center">
                                <img src="assets/icons/customer.png" class="img-fluid" alt="CUSTOMERS">
                                <p class="font-weight-bold text_eclips text_xsm">CUSTOMERS</p>
                            </div>

                        </div>

                        <div onclick="set_session('<?php echo $token ?>','display=update,update_stage=welcome')" class="card btnxx cust_shadow d-flex flex-wrap align-content-center justify-content-center o-hide pointer m-3 p-3">
                            <div class="w-100 text-center">
                                <img src="assets/icons/update.png" class="img-fluid" alt="CUSTOMERS">
                                <p class="font-weight-bold text_eclips text_xsm">UPDATE</p>
                            </div>
                        </div>

                        <div onclick="set_session('<?php echo $token ?>','display=message,stage=group')" class="card btnxx cust_shadow d-flex flex-wrap align-content-center justify-content-center o-hide pointer m-3 p-3">
                            <div class="w-100 text-center">
                                <img src="assets/icons/send_sms.png" class="img-fluid" alt="CUSTOMERS">
                                <p class="font-weight-bold text_eclips text_xsm">SEND SMS</p>
                            </div>
                        </div>

                    <?php endif; ?>

                    <?php if($display === 'new_customer'): ?>

                        <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center p-2">

                            <?php
                                # get customer details
                                if(isset($l_stage) && strval($l_stage) === '0'): ?>

                                <form method="post" action="config/proc/form_process.php" class="w-75 card p-5 cust_shadow" id="process_customer">
                                    <input type="hidden" value="<?php echo session_id() ?>" name="token">
                                    <strong class="mb-3">Registeration</strong>

                                    <!-- FIRST NAME LAST NAME-->
                                    <div class="w-100 mb-2 d-flex flex-wrap align-content-center justify-content-between">
                                        <!--FIRST NAME-->
                                        <div class="w-45">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input autofocus class="form-control cust_shadow_innser" type="text" value="<?php if(getSession('first_name') !== 'not available'){echo getSession('first_name');} ?>" required autocomplete="off" name="first_name">
                                        </div>
                                        <!--LAST NAME-->
                                        <div class="w-45">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input class="form-control cust_shadow_innser" value="<?php if(getSession('last_name') !== 'not available'){echo getSession('last_name');} ?>" type="text" required autocomplete="off" name="last_name">
                                        </div>
                                    </div>

                                    <!--EMAIL PHONE-->
                                    <div class="w-100 d-flex mb-2 flex-wrap align-content-center justify-content-between">
                                        <!--EMAIL-->
                                        <div class="w-45">
                                            <label>Email</label>
                                            <input value="<?php if(getSession('email') !== 'NOT SET'){echo getSession('email');} ?>" class="form-control cust_shadow_innser" type="email" autocomplete="off" name="email">
                                            <div id="emailInfo">
                                                <small class="text-info">valid email address</small>
                                            </div>

                                        </div>
                                        <!--Phone-->
                                        <div class="w-45">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input value="<?php if(getSession('phone') !== 'not available'){echo getSession('phone');} ?>" minlength="10" maxlength="10" class="form-control cust_shadow_innser" type="tel" required autocomplete="off" name="phone">
                                            <div id="phoneInfo">
                                                <small class="text-info">valid number (10 digits)</small>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- IDENTITY-->
                                    <div class="w-100 d-flex mb-3 flex-wrap align-content-center justify-content-between">
                                        <!--Date of birth-->
                                        <input type="hidden" readonly name="form_func" value="l_stage1">
                                        <div class="w-45">
                                            <label>Birthday</label>
                                            <input value="<?php if(getSession('date_of_birth') !== 'NOT SET'){echo getSession('date_of_birth');} ?>" class="form-control cust_shadow_innser" type="date" autocomplete="off" name="date_of_birth">
                                            <div id="ageInfo">
                                                <small class="text-info">Age must be grater than 18</small>
                                            </div>
                                        </div>
                                        <!--Gender-->
                                        <div class="w-45">

                                            <label>Gender <span class="text-danger">*</span></label>
                                            <select required class="form-control cust_shadow_innser" name="gender">
                                                <option value="M">Male</option>
                                                <option value="F">Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <div class="w-100 d-flex flex-wrap justify-content-center">
                                            <input type="submit" name="new_customer" class="btn btn-success rounded-0" value="PROCESS">
                                        </div>

                                    </div>



                                </form>

                            <?php endif; ?>

                            <?php
                                # verify otp
                                if(isset($l_stage) && strval($l_stage) === '1'):
                            ?>

                                <form method="post"  action="config/proc/form_process.php"  class="w-50 card p-5 cust_shadow" id="process_customer">
                                    <input type="hidden" value="<?php echo session_id() ?>" name="token">
                                    <strong class="mb-3">Provide OTP</strong>
                                    <div id="otpRes"><small class="text-info">Provide Code sent to customer</small></div>
                                    <input type="tel" name="otp"  autofocus class="form-control cust_shadow_innser text-center mb-2" autocomplete="off" required minlength="6" maxlength="6">

                                    <div class="w-100 d-flex flex-wrap justify-content-between">
                                        <div onclick="ajax('config/proc/task.php?token=<?php  echo $token ?>&l_stage_previous','l_stage','reload','xyz')" class="btn btn-dark w-45 btn-sm rounded-0">
                                            Back
                                        </div>
                                        <input type="submit" name="new_customer" class="btn btn-info btn-sm w-40 rounded-0" value="Next">
                                    </div>




                                </form>

                            <?php endif; ?>

                            <?php
                                # get customer id card to finalise process
                                if(isset($l_stage) && strval($l_stage) === '2'):
                            ?>

                                    <div class="w-50 overflow-hidden card p-5 cust_shadow">

                                        <div style='width:325px' class="mx-auto">
                                            <img
                                                    src=""
                                                    class="img-fluid mb-2"
                                                    id="image_display"
                                            >
                                        </div>

                                        <div id="img_ckeck" class="alert alert-info text-center">
                                            <span class="spinner-border spinner-border-sm"></span>
                                            Upload id using LIP from phone
                                        </div>



                                        <div class="w-100 d-flex flex-wrap justify-content-between">
                                            <div onclick="ajax('config/proc/task.php?token=<?php  echo $token ?>&l_stage_previous','l_stage','reload','xyz')" class="btn btn-dark w-45 btn-sm rounded-0">
                                                Back
                                            </div>
                                            <button onclick="set_session('<?php echo session_id() ?>','l_stage=3')" disabled id="next" class="btn btn-info btn-sm rounded-0 bg-sm w-40">NEXT</button>
                                        </div>

                                    </div>

                            <?php endif; ?>

                            <?php
                                # assign card to customer
                                if(isset($l_stage) && $l_stage == 3):
                            ?>

                                    <form method="post"  action="config/proc/form_process.php"  class="w-50 card p-5 cust_shadow" id="process_customer">
                                        <input type="hidden" value="<?php echo session_id() ?>" name="token">
                                        <strong class="mb-3">Card Number</strong>
                                        <div id="cardRes"><small class="text-info">Provide number of card to be assigned</small></div>
                                        <input type="tel" name="card_number" autofocus class="form-control cust_shadow_innser text-center mb-2" autocomplete="off" required minlength="10" maxlength="10">

                                        <div class="w-100 d-flex flex-wrap justify-content-between">
                                            <div onclick="ajax('config/proc/task.php?token=<?php  echo $token ?>&l_stage_previous','l_stage','reload','xyz')" class="btn btn-dark w-45 btn-sm rounded-0">
                                                Previous
                                            </div>

                                            <input type="submit" name="new_customer" class="btn btn-success rounded-0 w-45 btn-sm" value="FINISH">
                                        </div>


                                    </form>

                            <?php endif; ?>

                            <?php
                                # assign card to customer
                                if(isset($l_stage) && $l_stage == 4):
                                ?>

                                    <div class="w-50 card p-5 cust_shadow">



                                    </div>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                    <?php if($display === 'customers'):
                        $customers_count = rowsOf('customers','none',$pdo);
                        if(isset($_SESSION['view']))
                        {
                            $view = $_SESSION['view'];
                        }
                        else
                        {
                            $view = 'all';
                        }
                    ?>
                        <?php if ($view == 'all'): ?>

                            <?php if($customers_count > 0): ?>

                                <div class="o-auto h-90 w-100">

                                    <table class="table table-sm table-striped table-hover">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                Customer Name
                                                <!--                                        <span onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&col=first_name&sort=ASC'" data-toggle="tooltip" title="Ascending" class="pointer text-success mr-1 ml-1">&bigtriangleup;</span>-->
                                                <!--                                        <span onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&col=first_name&sort=DESC'" data-toggle="tooltip" title="Descending" class="pointer text-info">&bigtriangledown;</span>-->
                                            </th>
                                            <th>
                                                Card Number
                                                <!--                                        <span onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&col=card_no&sort=ASC'" data-toggle="tooltip" title="Ascending" class="pointer text-success mr-1 ml-1">&bigtriangleup;</span>-->
                                                <!--                                        <span onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&col=card_no&sort=DESC'" data-toggle="tooltip" title="Descending" class="pointer text-info">&bigtriangledown;</span>-->
                                            </th>
                                            <th>
                                                Phone
                                                <!--                                        <span onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&col=phone&sort=ASC'" data-toggle="tooltip" title="Ascending" class="pointer text-success mr-1 ml-1">&bigtriangleup;</span>-->
                                                <!--                                        <span onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&col=phone&sort=DESC'" data-toggle="tooltip" title="Descending" class="pointer text-info">&bigtriangledown;</span>-->
                                            </th>
                                            <th>
                                                Email
                                                <!--                                        <span onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&col=email&sort=ASC'" data-toggle="tooltip" title="Ascending" class="pointer text-success mr-1 ml-1">&bigtriangleup;</span>-->
                                                <!--                                        <span onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&col=email&sort=DESC'" data-toggle="tooltip" title="Descending" class="pointer text-info">&bigtriangledown;</span>-->
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="myTable">

                                        <?php
                                        $last = '';
                                        while ($customer = $stmt->fetch(PDO::FETCH_ASSOC)):
                                            $last = $customer['first_name'];
                                            $id = $customer['id'];
                                            ?>
                                            <?php
                                            $customer_name = $customer['first_name'] . ' '. $customer['last_name'] ;
                                            ?>
                                            <tr onclick="set_session('<?php echo $token ?>','view=single,customer=<?php echo $id ?>')" class="text_sm pointer">
                                                <td><?php echo $customer_name ?></td>
                                                <td><?php echo $customer['card_no'] ?></td>
                                                <td><?php echo $customer['phone'] ?></td>
                                                <td><?php echo $customer['email'] ?></td>
                                                <td>

                                                    <div class="dropdown">
                                                        <img class="img-fluid h_btn_sm pointer" data-toggle="dropdown" src="assets/icons/more.png">
                                                        <div class="dropdown-menu cust_shadow">
                                                            <a class="dropdown-item" href="#">Edit</a>
                                                            <a class="dropdown-item" href="#">Delete</a>
                                                            <a class="dropdown-item" href="#">Link 3</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php endwhile; ?>

                                        </tbody>
                                    </table>

                                </div>

                                <div class="d-flex h-10 w-100 flex-wrap align-content-center justify-content-between">


                                    <div class="w-25">
                                        <div class="d-flex flex-wrap justify-content-start">


                                            <?php if(rowsOf('customers', "`first_name` < '$last'",$pdo) > 0){ ?>
                                                <button onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&pagination=previous&last=<?php echo $last ?>'" data-toggle="tooltip" title="Previous" data-placement="left" class="btn btn-sm ml-1 btn-info">
                                                    &vartriangleleft;
                                                </button>
                                            <?php } else { ?>
                                                <button  data-toggle="tooltip" title="Start" disabled data-placement="left" class="btn btn-sm ml-1 btn-info">
                                                    &vartriangleleft;
                                                </button>
                                            <?php } ?>

                                            <?php if(rowsOf('customers', "`first_name` > '$last'",$pdo) > 0){ ?>
                                                <button onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&pagination=next&last=<?php echo $last ?>'" data-toggle="tooltip" title="Next" data-placement="right" class="btn btn-sm ml-1 btn-info">
                                                    &vartriangleright;
                                                </button>
                                            <?php } else { ?>
                                                <button  data-toggle="tooltip" title="End" disabled data-placement="right" class="btn btn-sm ml-1 btn-info">
                                                    &vartriangleright;
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="w-25 d-flex flex-wrap justify-content-end">
                                        <select onchange="location.href='config/proc/task.php?token=<?php  echo $token ?>&limit='+this.value" class="form-control cust_shadow w-50 form-control-sm" style="width: fit-content !important">
                                            <option value="<?php echo $limit ?>"><?php echo $limit ?></option>
                                            <option value="20">20</option>
                                            <option value="40">40</option>
                                            <option value="60">60</option>
                                            <option value="80">80</option>
                                            <option value="100">100</option>
                                            <option value="200">200</option>
                                        </select>
                                        <button data-toggle="modal" data-target="#search" class="btn btn-sm ml-1 btn-info">
                                            <span class="fi fi-rs-search"></span>
                                        </button>

                                        <div class="modal fade" id="search" data-keyboard="false" data-backdrop="static">
                                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <form class="m-0">
                                                            <input onkeyup="search_customer(this.value,'<?php echo session_id() ?>')" class="form-control m-0" type="search" autocomplete="off" placeholder="Query">
                                                        </form>
                                                        <button class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <div class="modal-body p-2">
                                                        <div id="searchResult" style="height: 70vh" class="w-100">
                                                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                                                <p class="enc">Search with customer name, phone number or card number</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            <?php endif; ?>

                            <?php if($customers_count < 1):  ?>

                                <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                    <p class="enc">NO CUSTOMER</p>
                                </div>

                            <?php endif; ?>

                        <?php endif; ?>

                        <?php if($view == 'single'): ?>
                            <div class="w-100 h-100 d-flex flex-wrap">
                                <!-- DETAILS -->
                                <div class="w-50 h-100 p-2">

                                    <div class="card rounded-0 mb-5  shadow-lg">
                                        <div class="card-header">
                                            <strong class="card-title">Personal Information</strong>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="w-100 d-flex flex-wrap">
                                                <div class="w-20">
                                                    <strong>Card : </strong>
                                                </div>
                                                <div class="w-75">
                                                    <p class="text_eclips"><?php echo $customer['card_no']?></p>
                                                </div>
                                            </div>
                                            <div class="w-100 d-flex flex-wrap">
                                                <div class="w-20">
                                                    <strong>Name : </strong>
                                                </div>
                                                <div class="w-75">
                                                    <p class="text_eclips"><?php echo $customer['first_name']. ' '. $customer['last_name'] ?></p>
                                                </div>
                                            </div>
                                            <div class="w-100 d-flex flex-wrap">
                                                <div class="w-20">
                                                    <strong>Gender : </strong>
                                                </div>
                                                <div class="w-75">
                                                    <p class="text_eclips"><?php echo $customer['gender'] ?></p>
                                                </div>
                                            </div>
                                            <div class="w-100 d-flex flex-wrap">
                                                <div class="w-20">
                                                    <strong>Phone : </strong>
                                                </div>
                                                <div class="w-75">
                                                    <p class="text_eclips"><?php echo $customer['phone'] ?></p>
                                                </div>
                                            </div>
                                            <div class="w-100 d-flex flex-wrap">
                                                <div class="w-20">
                                                    <strong>Email : </strong>
                                                </div>
                                                <div class="w-75">
                                                    <p class="text_eclips"><?php echo $customer['email'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button onclick="set_session('<?php echo $token ?>','view=all')" class="btn btn-info w-100">ALL CUSTOMERS</button>

                                </div>

                                <!-- ID -->
                                <div class="w-50 p-2 h-100 overflow-hidden">
                                    <div class="card c_hover rounded-0  shadow-lg">

                                        <div class="card-header">
                                            <strong class="card-title">ID Info</strong>
                                        </div>
										
										<?php
											$img_str = $customer['card_no'];
											$cust_id_image = glob ("assets/customers/$img_str*");
											if(count($cust_id_image) > 0)
											{
												// get first file
												$img_file = $cust_id_image[0];
												$id_img = $cust_id_image[0];
											} else {
												$id_img = 'assets/customers/no_id.png';
											}

											
											
										?>

                                        <div class="card-body text-center p-2">
                                            <div class="w-65 mx-auto">
                                                <img
                                                        class="img-fluid"
                                                        alt="Customer Name"
                                                        src="<?php echo srcData($id_img) ?>"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>


                    <?php if($display === 'update'): ?>

                        <article class="w-75 o-auto cust_shadow">

                            <header class="bg-jet">
                                <div class="w-100 h-100 d-flex flex-wrap p-2 justify-content-between align-content-center">
                                    <?php
                                    if($update_stage === 'welcome')
                                    {
                                        echo '<strong>Card Number</strong>';
                                    }
                                    elseif($update_stage === 'personal_info')
                                    {
                                        echo "<strong>" . $_SESSION['card'] . ":  Personal Information and Contact </strong>";

                                    }
                                    elseif($update_stage === 'identity')
                                    {
                                        echo "<strong>" . $_SESSION['card'] . ":  Comfirm Identity </strong>";
                                    }
                                    elseif($update_stage === 'otp')
                                    {
                                        echo "<strong>" . $_SESSION['card'] . ":  Comfirm OTP sent to ".$_SESSION['phone']." </strong>";
                                    }
                                    elseif($update_stage === 'comfirm_date')
                                    {
                                        echo "<strong>" . $_SESSION['card'] . ":  Comfirm Data  </strong>";
                                    }
                                    ?>

                                    <i class="text-danger"><?php if(isset($_SESSION['error'])){echo $_SESSION['error'];unset($_SESSION['error']);} ?></i>
                                </div>
                            </header>

                            <article class="d-flex flex-wrap align-content-center justify-content-center">

                                <?php if ($update_stage === 'welcome'): ?>
                                    <form method="POST" action="config/proc/form_process.php" class="w-50 cust_shadow card border-0">

                                        <div class="card-body p-2">
                                            <div class="w-100 p-2">
                                                <input autocomplete="off" type="text" name="card_number" class="form-control form-control-lg text-center" required placeholder="Card Number" id="">
                                            </div>
                                            <div class="p-2 text-center w-100">
                                                <button name="get_card" class="btn btn-info">GET DETAILS</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>

                                <?php if ($update_stage === 'personal_info'): ?>
                                    <form method="POST" action="config/proc/form_process.php" class="card border-0">


                                        <div class="card-body p-2">
                                            <div class="w-100 p-2 mb-2">
                                                <label for="first_name"><span class="text-info">First Name</span> <span class="text-danger">*</span></label>
                                                <input type="text" value="<?php if(getSession('first_name') != 'NOT SET'){echo getSession('first_name');} ?>" required name="first_name" class="form-control" autocomplete="off">
                                            </div>
                                            <div class="w-100 p-2 mb-2">
                                                <label for="first_name"><span class="text-info">Last Name</span> <span class="text-danger">*</span></label>
                                                <input type="text" value="<?php if(getSession('last_name') != 'NOT SET'){echo getSession('last_name');} ?>" required name="last_name" class="form-control" autocomplete="off">
                                            </div>

                                            <!--EMAIL AND PHONE-->
                                            <div class="w-100 p-2 mb-2 clearfix">
                                                <!--EMAIL-->
                                                <div class="w-45 float-left">
                                                    <div class="w-100 mb-2">
                                                        <label for="email"><span class="text-info">Email</span> <span class="text-danger">*</span></label>
                                                        <input type="email" value="<?php if(getSession('email') != 'NOT SET'){echo getSession('email');} ?>" required name="email" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>
                                                <!--PHONE-->
                                                <div class="w-45 float-right">
                                                    <div class="w-100 mb-2">
                                                        <label for="first_name"><abbr title="Will be required for OTP Validation"><span class="text-info">Valid Phone</abbr> Number</span> <span class="text-danger">*</span></label>
                                                        <input type="tel" value="<?php if(isset($_SESSION['phone'])){echo $_SESSION['phone'];} ?>" required name="phone" class="form-control" autocomplete="off">
                                                        <small></small>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="w-100 p-2 clearfix">
                                                <div onclick="location.href='config/proc/form_process.php?token=<?php  echo $token ?>&stage=welcome'" class="btn btn-warning w-45 float-left" name="personal_info">CANCEL</div>
                                                <button class="btn btn-info w-45 float-right" name="personal_info">PROCESS</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>

                                <?php if ($update_stage === 'identity'): ?>
                                <form method="POST" enctype="multipart/form-data" action="config/proc/form_process.php" class="w-50 cust_shadow card border-0">


                                    <div class="card-body p-2">
                                        <div class="w-100 p-2 mb-2">
                                            <label for="first_name">Gender <span class="text-danger">*</span></label>
                                            <select required name="gender" class="form-control">
                                                <option value="Z">Select Gender</option>
                                                <option <?php if($_SESSION['gender'] === 'M'){echo 'selected';} ?> value="M">Male</option>
                                                <option <?php if($_SESSION['gender'] === 'F'){echo 'selected';} ?> value="F">Female</option>
                                            </select>
                                        </div>

                                        <div class="w-50 mx-auto">
                                            <?php
                                            if(isset($_SESSION['id_image']))
                                            {
                                                $image = 'assets/customers/'.$_SESSION['id_image'];
                                            }
                                            else
                                            {
                                                $image = '';
                                            }
                                            if(file_exists($image))
                                            {

                                                // Read image path, convert to base64 encoding
                                                $imageData = base64_encode(file_get_contents($image));

                                                // Format the image SRC:  data:{mime};base64,{data};
                                                $src = 'data: '.mime_content_type($image).';base64,'.$imageData;

                                                // Echo out a sample image


                                            }

                                            else
                                            {
                                                $src = '';
                                            }

                                            ?>
                                            <img id="blah" class="img-fluid cust_shadow" src="<?php echo $src ?>">
                                        </div>

                                        <div class="w-100 p-2 mb-2">
                                            <label>JPEG or PNG Files only</label>
                                            <?php if(file_exists($image)): ?>
                                                <div class="custom-file p-2">

                                                    <input accept="image/png, image/jpeg" name="id_card"  type="file" class="custom-file-input" id="imgInp">
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div>
                                            <?php endif; ?>

                                            <?php if(!file_exists($image)): ?>
                                                <div class="custom-file p-2">

                                                    <input accept="image/png, image/jpeg" name="id_card" required  type="file" class="custom-file-input" id="imgInp">
                                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                                </div>
                                            <?php endif; ?>
                                        </div>


                                    </div>
                                    <div class="card-footer">
                                        <div class="w-100 h-100 clearfix">
                                            <div onclick="location.href='config/proc/form_process.php?token=<?php  echo $token ?>&stage=personal_info'" class="btn btn-warning w-40 float-left"">Go Back</div>
                                        <button class="btn btn-info w-40 float-right" name="identity">PROCESS</button>
                                    </div>
            </div>
            </form>
            <?php endif; ?>

                            <?php if($update_stage === 'otp'): ?>

                                <div class="card w-50 cust_shadow">


                                    <form action="" class="card-body p-2">
                                        <div class="form-group form-group-lg">
                                            <input onkeyup="checkOtp(this.value)" maxlength="6" minlength="6" type="txt" name="otp" required class="form-control form-control-lg text-center" placeholder="OTP">
                                            <a onclick="location.href='config/proc/form_process.php?token=<?php  echo $token ?>&stage=welcome'" class="text-danger pointer float-left"><i>Cancel</i></a>
                                            <a data-toggle="modal" data-target="#changeNumber" class="text-info pointer float-right"><i>Wrong Number?</i></a>
                                        </div>
                                        <?php if(isset($_SESSION['error'])): ?>
                                            <
                                            <?php unset($_SESSION['error']); endif; ?>
                                        <div class="text-righ clearfix">
                                            <div id="spinner" style="display: none;" class="spinner-border spinner-border-sm"></div>
                                            <span class="text-dark pl-2" id="otpRes"></span>

                                        </div>
                                    </form>
                                    <div id="changeNumber" class="modal fade">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <strong class="modal-title">Changing Number</strong>
                                                    <button class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <div class="modal-body p-2">
                                                    <form action="config/proc/form_process.php" method="POST" class="w-100 mx-auto">
                                                        <div class="input-group input-group-lg">
                                                            <input type="tel" placeholder="New Phone Number" name="phone" required autocomplete="off" minlength="10" maxlength="10" class="form-control text-center mb-2">
                                                            <button name="change_phone" class="btn btn-info w-100">COMMIT CHANGE</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <?php if($update_stage === 'comfirm_date'): ?>

                <div class="card border-0">




                    <div class="card-body p-2 max-h-90vh overflow-auto">

                        <div class="w-100 d-flex flex-wrap justify-content-between">

                            <div class="w-45">
                                <div class="p-2 cust_shadow mb-2">
                                    <div class="w-100 d-flex flex-wrap justify-content-between">
                                        <strong>Personal Information</strong>
                                        <kbd onclick="location.href='config/proc/form_process.php?token=<?php  echo $token ?>&stage=personal_info'" class="pointer">Edit</kbd>
                                    </div><hr>
                                    <p class="mb-0">First Name : <span><?php if(isset($_SESSION['first_name'])){echo $_SESSION['first_name'];} ?></span></p>
                                    <p class="mb-0">Other Name : <span><?php if(isset($_SESSION['other_name'])){echo $_SESSION['other_name'];} ?></span></p>
                                    <p class="mb-0">Last Name : <span><?php if(isset($_SESSION['last_name'])){echo $_SESSION['last_name'];} ?></span></p>
                                </div>
                            </div>

                            <div class="w-45">
                                <div class="p-2 cust_shadow mb-2">
                                    <div class="w-100 d-flex flex-wrap justify-content-between">
                                        <strong>Contact Information</strong>
                                        <kbd onclick="location.href='config/proc/form_process.php?token=<?php  echo $token ?>&stage=personal_info'" class="pointer">Edit</kbd>
                                    </div><hr>
                                    <p class="mb-0">Email : <span><?php if(isset($_SESSION['email'])){echo $_SESSION['email'];} ?></span></p>
                                    <p class="mb-0">Phone : <span><?php if(isset($_SESSION['phone'])){echo $_SESSION['phone'];} ?></span></p>
                                </div>
                            </div>

                            <div class="w-45">
                                <div class="p-2 cust_shadow mb-2">
                                    <div class="w-100 d-flex flex-wrap justify-content-between">
                                        <strong>Identity Information</strong>
                                        <kbd onclick="location.href='config/proc/form_process.php?token=<?php  echo $token ?>&stage=identity'" class="pointer">Edit</kbd>
                                    </div><hr>
                                    <p class="mb-0">Gender : <span>Male</span></p>
                                    <div class="w-50">
                                        <?php
                                        $image = 'assets/customers/'.$_SESSION['id_image'];
                                        // Read image path, convert to base64 encoding
                                        $imageData = base64_encode(file_get_contents($image));

                                        // Format the image SRC:  data:{mime};base64,{data};
                                        $src = 'data: '.mime_content_type($image).';base64,'.$imageData;

                                        // Echo out a sample image

                                        ?>
                                        <img class="img-fluid img-thumbnail" src="<?php echo $src ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="w-45">
                                <button onclick="location.href='config/proc/form_process.php?token=<?php  echo $token ?>&update'" class="btn enc btn-sm btn-success w-100 h-50">Update</button>
                            </div>
                        </div>







                    </div>

                </div>

            <?php endif; ?>

                            <?php if($update_stage === 'done'): ?>

                                <div class="w-50 mx-auto p-2 text-center alert-success cust_shadow">
                                    <div class="card-header">COMPLETE</div>
                                    <div class="card-body">
                                        <i>Card <?php echo $_SESSION['card'] ?> has been updated successfully</i><br>
                                    </div>

                                    <button onclick="location.href='config/proc/form_process.php?token=<?php  echo $token ?>&stage=welcome'" class="btn btn-info">HOME</button>
                                </div>

                            <?php endif; ?>

            </article>

                        </article>

                    <?php endif; ?>

                    <?php if($display === 'message'): ?>

                        <?php if ($stage === 'group'): ?>

                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                <div class="w-25 card border-0 cust_shadow rounded">
                                    <div class="card-header bg-jet">
                                        <p class="m-0 text-center">Message Group</p>
                                    </div>

                                    <div class="card-body p-2">
                                        <form method="POST" action="config/proc/form_process.php" id="process_customer">
                                            <div class="input-group mb-2">
                                                <input type="text" required class="form-control" value="<?php getValue('message_group');  ?>" name="group" autocomplete="off" placeholder="Group Name" >
                                            </div>
                                            <input type="submit" name="sms_config" class="btn btn-info w-100" value="NEXT">
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <?php if($stage === 'condition'): ?>

                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                <div class="w-50 p-2 card border-0 cust_shadow rounded">
                                    <div class="card-header bg-jet d-flex flex-wrap align-content-center justify-content-between">
                                        <button onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&stage=group'" data-toggle="tooltip" title="Set Query" class="h_btn_md mr-2 pointer p-0">
                                            <img src="assets/icons/arrow_left.png" class="img-fluid">
                                        </button>
                                        <p class="m-0 text-center">Condition</p>
                                    </div>

                                    <div class="card-body p-2">
                                        <form method="POST" action="config/proc/form_process.php" id="process_customer">
<!--                                            <!--ALL CUSTOMERS-->-->
<!--                                            <div class="custom-control mb-2 custom-checkbox">-->
<!--                                                <input value="all_customers" onclick="customQuery('off')" type="radio" class="custom-control-input" id="all_customers" name="condition">-->
<!--                                                <label class="custom-control-label" for="all_customers">-->
<!--                                                    All Customers-->
<!--                                                    <small class="text-info">-->
<!--                                                        --><?php
//                                                            echo rowsOf('customers' , "none", $pdo);
//                                                        ?>
<!--                                                    </small>-->
<!--                                                </label>-->
<!--                                            </div>-->
<!---->
<!--                                            <!--CUSTOMERS WHO ARE NOT UPDATED-->-->
<!--                                            <div class="custom-control mb-2 custom-checkbox">-->
<!--                                                <input value="v2_not" onclick="customQuery('off')" type="radio" class="custom-control-input" id="v2_not" name="condition">-->
<!--                                                <label class="custom-control-label" for="v2_not">-->
<!--                                                    Details Not Updated-->
<!--                                                    <small class="text-info">-->
<!--                                                        --><?php
//                                                        echo rowsOf('customers' , "`v2` = 0", $pdo);
//                                                        ?>
<!--                                                    </small>-->
<!--                                                </label>-->
<!--                                            </div>-->
<!---->
<!--                                            <div class="custom-control mb-2 custom-checkbox">-->
<!--                                                <input value="v2" onclick="customQuery('off')" type="radio" class="custom-control-input" id="v2" name="condition">-->
<!--                                                <label class="custom-control-label" for="v2">-->
<!--                                                    Details Updated-->
<!--                                                    <small class="text-info">-->
<!--                                                        --><?php
//                                                        echo rowsOf('customers' , "`v2` = 1", $pdo);
//                                                        ?>
<!--                                                    </small>-->
<!--                                                </label>-->
<!--                                            </div>-->
<!---->
<!--                                            <div class="custom-control mb-2 custom-checkbox">-->
<!--                                                <input value="custom_query" onclick="customQuery('on')" type="radio" class="custom-control-input" id="cust" name="condition">-->
<!--                                                <label class="custom-control-label" for="cust">Custom Query</label>-->
<!--                                            </div>-->

                                            <div id="querySet" style="" class="mb-2">
                                                <textarea id="queryConsole" autofocus onkeyup="testQuery(this.value)" class="form-control bg-dark text-success w-60 mb-2" rows="5" name="query" placeholder="Query"></textarea>

                                            </div>

                                            <div class="w-100 d-flex flex-wrap">
                                                <span onclick="query('all_customer')" class="btn btn-outline-primary pointer m-1">
                                                    All Customers <span class="badge badge-pill badge-primary"><?php echo rowsOf('customers','none',$pdo) ?></span>
                                                </span>
                                                <span onclick="query('updated_customers')" class="btn btn-outline-dark pointer m-1">
                                                    Updated Only <span class="badge badge-pill badge-primary"><?php echo rowsOf('customers',"`v2` = 1",$pdo) ?></span>
                                                </span>
                                                <span onclick="query('pending_v2')" class="btn btn-outline-warning pointer m-1">
                                                    Not Updated <span class="badge badge-pill badge-primary"><?php echo rowsOf('customers',"`v2` < 1",$pdo) ?></span>
                                                </span>
                                            </div>


                                            <div class="p-2" style="display: " id="qRes">

                                            </div>



                                            <input type="submit" id="validQuery" disabled name="sms_config" class="btn btn-info w-100" value="NEXT">
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <?php if($stage === 'text_message'): ?>

                            <div class="w-100 h-100 d-flex flex-wrap align-content-center justify-content-center">
                                <div class="w-90 o-hide h-75 card border-0 cust_shadow rounded">
                                    <header class="card-header p-1 bg-jet d-flex flex-wrap align-content-center justify-content-between">
                                        <button onclick="location.href='config/proc/task.php?token=<?php  echo $token ?>&stage=condition'" data-toggle="tooltip" title="Set Query" class="h_btn_md mr-2 pointer p-0">
                                            <img src="assets/icons/arrow_left.png" class="img-fluid">
                                        </button>
                                        <p class="m-0 text-center">Message</p>
                                    </header>

                                    <article class="p-2 o-hide">
                                        <div class="w-100 o-hide d-flex flex-wrap justify-content-between h-100">

                                            <div class="w-30 p-1 h-100 o-hide">
                                                <form method="post" action="config/proc/form_process.php" class="card cust_shadow w-100 p-1 h-100 o-auto" id="process_customer">
                                                    <div class="card-header clearfix">
                                                        <strong class="card-title">Message</strong>
                                                        <span class="float-right badge badge-info">+20 character</span>
                                                    </div>

                                                    <div class="card-body p-2">
                                                        <div class="w-100 h-100 o-auto">
                                                            <textarea name="message" id="msg" onkeyup="validateSms(this.value)" autofocus class="form-control cust_shadow_innser w-100 h-100" placeholder="Message"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="card-footer">
                                                        <input id='senSms'  disabled="true" name="sms_config" class="btn btn-sm btn-success w-100" type="submit" value="SEND">
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="w-65 p-1 h-100 o-hide">
                                                <div id="selectedCustomers" style="display:" class="card cust_shadow w-100 p-1 h-100 o-hide">
                                                    <div class="card-header">
                                                        <strong class="card-title">Contact : </strong> <?php echo $_SESSION['query'] ?>
                                                    </div>

                                                    <div class="card-body o-auto p-1">
                                                        <table class="table mb-0 table-sm table-bordered table-hover">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Customer</th>
                                                                    <th>Phone Number</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>

                                                                <?php $n =1; while($cust = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                                                    <tr class="pointer">
                                                                        <td><?php echo $n++ ?></td>
                                                                        <td><?php echo $cust['first_name'] . ' ' . $cust['last_name'] ?></td>
                                                                        <td><?php echo str_replace('+233', '0', $cust['phone']) ?></td>
                                                                    </tr>
                                                                <?php endwhile; ?>

                                                            <script>
                                                                const contacts = <?php echo $n?>
                                                            </script>

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>

                                                <div id="execTerminal" style="display: none" class="card cust_shadow_innser bg-dark w-100 p-1 h-100 o-hide">
                                                    <div class="card-header bg-jet">
                                                        SENDING RESULT
                                                    </div>

                                                    <div id="terminalMessage" class="card-body p-1 o-auto">
                                                        <?php br("Existing"); ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </article>
                                </div>
                            </div>

                        <?php endif; ?>



                    <?php endif; ?>

                </article>

            </div>
        </div>
</body>

</html>

<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/anton.js"></script>

<script>

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).ready(function(){
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });


    // imgInp.onchange = evt => {
    //     const [file] = imgInp.files
    //     if (file) {
    //         blah.src = URL.createObjectURL(file)
    //     }
    // }

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });


    <?php
    if(isset($l_stage) && strval($l_stage) === '2'):
    ?>

    find_img("<?php echo session_id() ?>");

    <?php endif; ?>


</script>