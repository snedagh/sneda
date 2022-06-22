<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
    require '../inc/session.php';
    require '../inc/db.php';




//    if(isset($_GET['upload_file']))
//    {
//        echo 'hello';
//    }

    // set customer id
    if (isset($_GET['UUX']))
    {
        echo $l_stage = getSession('l_stage');
    }


    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {

        // search customer
        if(isset($_POST['search_customer'])) {
            $query = htmlentities($_POST['query']);

            // sql
            $search_query = "select * from customers where phone like '%$query%' OR first_name like '%$query%' OR last_name = '%$query%' OR card_no like '%$query%' ORDER BY first_name LIMIT 50";
            $search_stmt = $pdo->prepare($search_query);
            $search_stmt->execute();
            if ($search_stmt->rowCount() < 1) {
                echo "
                    <span class='text-info'>No Result</span>
                ";
            } else
            {
                echo "
                    <table class='table table-sm table-striped table-hover'>
                    <thead class='thead-dark'>
                        <tr><th>Card N<u>0</u></th><th>Customer</th><th>Phone</th></tr>
                    </thead>
                    <tbody>
                    
                ";
                while($result = $search_stmt->fetch(PDO::FETCH_ASSOC))
                {
                    $id = $result['id'];
                    $first_name = $result['first_name'];
                    $last_name = $result['last_name'];
                    $card_number = $result['card_no'];
                    $phone = $result['phone'];
                    $set_session = "view=single,customer=$id";

                    echo "
                        <tr class='pointer'>
                            <td><span onclick='set_session(".'"'.$token.'",'.'"'.$set_session.'"'.")' class='fi fi-rs-eye text-info pointer mr-2'></span> $card_number</td><td>$first_name $last_name</td><td>$phone</td>
                        </tr>
                    ";



                }
                echo "</tbody></table>";

            }

        }

        // set session
        if(isset($_POST['set_session']))
        {
            $data = $_POST['data'];

            // split data
            $data_split = explode(',',$data);

            // loop through data
            foreach ($data_split as $data)
            {
                // split data too
                if(count(explode('=',$data)) > 1)
                {
                    $var = explode('=',$data)[0];
                    $val = explode('=',$data)[1];

                    setSession("$var","$val");
                }
            }

        }

        // get uploaded id
        if(isset($_POST['get_uploaded_id']))
        {
            $user = $_SESSION['user_id'];
			$file_str = "tmp_".$user;
            $file = glob ("../../assets/customers/$file_str*");
            if(count($file) > 0)
            {
                // get first file
                $id = $file[0];
                $id_ext = pathinfo($id,PATHINFO_BASENAME);
                $id_ext;
                echo $new_file = getSession('card_number');
                echo "Y%%$id_ext";
            }
            else
            {
                echo 'N%%';
            }

        }

        ############################
        ## PERSONAL INFO       #####
        ############################
        if(isset($_POST['personal_info']))
        {

            $first_name = htmlentities($_POST['first_name']);
            if(isset($_POST['other_name']))
            {
                $other_name = htmlentities($_POST['other_name']);
            }
            else
            {
                $other_name = 'none';
            }
            $last_name = htmlentities($_POST['last_name']);
            $email = htmlentities($_POST['email']);
            $phone = htmlentities($_POST['phone']);

            echo $gender;
            die();
            exit();

            # make them in sessions
            $_SESSION['first_name'] = $first_name;
            $_SESSION['other_name'] = $other_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            
            

            $_SESSION['update_stage'] = 'identity';
            back();
        }

        ############################
        ## IDENTITY INFO       #####
        ## Validate Gender      ####
        ## Process Image of ID  ####
        ## Send OTP             ####
        ############################
        elseif(isset($_POST['identity']))
        {

            $gender = htmlentities($_POST['gender']);

            if($gender === 'Z')
            {
                $_SESSION['error'] = $gender . ' Not Recognised';
                back();
                exit();
            }

            //set gender
            $_SESSION['gender'] = $gender;

            //upload it if there is
            if(isset($_FILES['id_card']))
            {
                //upload image
                $image = $_FILES['id_card'];
                $img_tmp = $image['tmp_name'];
                $file_name = $image['name'];
                $file_ext = $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                
                $new_file = '../../assets/customers/'.$_SESSION['card'].'.'.$file_ext;
                
                
                //upload file
                if(move_uploaded_file($img_tmp, $new_file))
                {
                    $_SESSION['id_card'] = 1;
                    $_SESSION['id_image'] = $_SESSION['card'].'.'.$file_ext;

                }
                else
                {
                    $_SESSION['id_car'] = 0;
                    $_SESSION['error'] = "Could Not Upload ID Card";
                    br($new_file);
                    back();
                    exit();
                }


            }
            else
            {
                //if file does not exist
                if(isset($_SESSION['id_image']))
                {
                    $image = '../../assets/customers/'.$_SESSION['id_image'];
                }
                else
                {
                    $image = '';
                }
                if(!file_exists($image))
                {
                    $_SESSION['error'] = "Select Image";
                    back();
                    exit();
                }

            }


            //send otp
            echo $phone_number = $_SESSION['phone'];
            echo "<br>";
            $otp = rand(126735,999999);
            $_SESSION['otp'] = $otp;
            $message = "Hello " . $_SESSION['first_name'] . ' ' . $_SESSION['other_name'] . ' ' . $_SESSION['last_name']. " to complete your verification process, provide OTP : ".$otp;

            if(sms('mnotify','VE36nLRD9rCWroMvHE6i2C097','SNEDA SHOP',$phone_number,$message,'1000'))
            {
                br('OTP : '.$otp);
                $_SESSION['update_stage'] = 'otp';
            }
            else
            {
                error("Could not send OTP");
            }
            back();


        }


        ###############################################
        ## % CARD DETAILS                            ##
        ## % Submit card number                      ##
        ## % if card found, get values               ##
        ##   > if card not updated                   ##
        ##     * keep values in session variables    ##
        ##     * switch session                      ##
        ##   > if card updated                       ##
        ##     * go back with card updated info      ##
        ## % if card does not exist                  ##
        ##    > return with error                    ##
        ###############################################
        elseif(isset($_POST['get_card']))
        {
            //get card details from database
            $card_number = htmlentities($_POST['card_number']);
            
            $card_sql = "SELECT * FROM `customers` WHERE `card_no` = ?";
            $card_stmt = $pdo->prepare($card_sql);
            $card_stmt->execute([$card_number]);
            
            if($card_stmt->rowCount() < 1)
            {
                echo "Card Does Not Exist";
                $_SESSION['error'] = 'Card '.$card_number .' does not exist';
                back();
            }
            else
            {
                
                $card = $card_stmt->fetch(PDO::FETCH_ASSOC);
                
                $card_updated = $card['v2'];
                echo $card_updated;
                
                if($card_updated == 1)
                {
                    $_SESSION['error'] = $card_number . " has been updated";
                    back();
                    echo $_SESSION['error'];
                    exit();
                }

                



                //get card details
                $_SESSION['first_name'] = $card['first_name'];
                $_SESSION['last_name'] = $card['last_name'];
                $_SESSION['gender'] = $card['gender'];
                $_SESSION['other_name'] = $card['other_name'];
                $phone_num = str_replace('+233','0',$card['phone']);
                $_SESSION['phone'] = $phone_num;

                $_SESSION['email'] = $card['email'];
                $_SESSION['card'] = $card_number;
                $_SESSION['update_stage'] = 'personal_info';
                back();
            }


        }

        ############################
        ## CHANGING PHONE NUMBER ###
        ############################
        elseif(isset($_POST['change_phone']))
        {
            $current = $_SESSION['phone'];
            $new = htmlentities($_POST['phone']);

            if(strval($current) === strval($new))
            {
                $_SESSION['error'] = "current ( ".$current." ) and new ( ".$new." ) phone numbers can't be same";
            }
            else
            {
                $otp = rand(126735,999999);
                echo $message = "Hello " . $_SESSION['first_name'] . ' ' . $_SESSION['other_name'] . ' ' . $_SESSION['last_name']. " to complete your verification process, provide OTP : ".$otp;
                echo "<br>";
                
                
                $api_key = 'VE36nLRD9rCWroMvHE6i2C097';
                $sender_id = "SNEDA SHOP";
                $url = "https://apps.mnotify.net/smsapi?key=$api_key&to=$new&msg=".$message."&sender_id=$sender_id";
                $response = file_get_contents(urldecode($url));
                $result = json_decode($response);

                $code = $result->code;
                if(sms('mnotify','VE36nLRD9rCWroMvHE6i2C097','SNEDA SHOP',$new,$message,'1000'))
                {
                    br('OTP : '.$otp);
                    $_SESSION['phone'] = $new;
                    $_SESSION['update_stage'] = 'otp';
                    back();
                }
                else
                {

                    $_SESSION['error'] = $code . ": Could not send OTP to ".$new;
                }
            }
            back();
        }

        ## SENDING SMS PHP - ROOTECH.INC ##
        ###################################################################
        ##                                                              ###
        ## SET MESSAGE GROUP                                            ###
        ## > get group name from form                                   ###
        ## > assign session var values                                  ###
        ##   * set session group name to submitted                      ###
        ##   * set session group id to md5 converted form of group name ###
        ##     combined with current date and time                      ###
        ## > switch stage to condition                                  ###
        ##                                                              ###
        ###################################################################
        elseif (isset($_POST['set_group']))
        {
            $group = htmlentities($_POST['group']);

            if(isset($_SESSION['grp_id']))
            {
                $msg_grp = $_SESSION['grp_id'];
                if(rowsOf('bulk_sms_message', "`msg_id` = '$msg_grp'", $pdo) < 1)
                {
                    insertIntoDatabase('bulk_sms_message', "`grp`, `msg_id`,`owner`", "'$group','$msg_grp', '$my_username'", $pdo);
                }
                else
                {
                    br("exist");
                }

                br("done");

            }
            else
            {

                setSession('group_name',$group);
                setSession('grp_id', md5($group.date("Y-m-d H:s")));

                $msg_grp = getSession('grp_id');

                //check if message group exist


                //create message group in database
                insertIntoDatabase('bulk_sms_message', "`grp`, `msg_id`,`owner`", "'$group','$msg_grp', '$my_username'", $pdo);


            }
            $_SESSION['stage'] = 'condition';
            back();
        }

        ## SENDING SMS PHP - ROOTECH.INC ##
        ###################################################################
        ##                                                              ###
        ## SET QUERY                                                    ###
        ## > get condition from form                                    ###
        ## > if condition is not custom set query base on condition     ###
        ##   * set session query                                        ###
        ##   * set session group id to md5 converted form of group name ###
        ##     combined with current date and time                      ###
        ## > switch stage to condition                                  ###
        ##                                                              ###
        ###################################################################
        elseif (isset($_POST['set_query']))
        {
            $condition = htmlentities($_POST['condition']);

            $query = '';

            if($condition === 'custom_query')
            {
                $query = htmlentities($_POST['custom_query']);
            }
            else
            {
                // all customers

                if($condition === 'all_customers')
                {
                    $query = "SELECT * from `customers`";
                }

                // not updated customer
                elseif ($condition === 'v2_not')
                {
                    $query = "SELECT * from `customers` WHERE `v2` = 0";
                }

                // updated customer
                elseif ($condition === 'v2')
                {
                    $query = "SELECT * from `customers` WHERE `v2` = 1";
                }
            }

            //test query
            try {
                $sql = $query;
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                br($query . " Pass with " . $stmt->rowCount() . " record found");

                //insert all in groups

                // set sessions
                $_SESSION['query'] = $query;
                $_SESSION['col'] = htmlentities($_POST['column']);
                $_SESSION['stage'] = 'text_message';

                back();

            } catch (Throwable $e)
            {
                br($query);
                br($e);
                error("Error : Contact System's Admin");
                back();
            }
        }

        ## process new customer data ##
        elseif (isset($_POST['new_customer']))
        {

            // l_stage1 = getting variables and send otp
            // l_stage2 = verify otp
            // l_stage3 = store id card

            $l_stage = getSession('l_stage');

            if(strval($l_stage) === '0')
            {


                setSession('first_name',form_data('POST', 'first_name'));
                setSession('last_name', form_data('POST', 'last_name'));
                setSession('email',form_data('POST', 'email'));
                setSession('phone',form_data('POST', 'phone'));
                setSession('date_of_birth',form_data('POST', 'date_of_birth'));
                setSession('gender',form_data('POST', 'gender'));
                $first_name = getSession('first_name');
                $last_name = getSession('last_name');
                $phone = getSession('phone');
                $email = getSession('email');
                $date_of_birth = getSession('date_of_birth');
                $gender = getSession('gender');



                // check if phone number taken
                $phone_f1 =  rowsOf('customers',"`phone` = '$phone'",$pdo);
                $pf2 = '+233' . ltrim($phone,$phone[0]);
                $phone_f2 = rowsOf('customers',"`phone` LIKE '$pf2%'",$pdo);

                // check data of birth


                $today = date('Y-m-d');

                $days = dateDifference($date_of_birth,$today);
                $age = number_format($days / 365);





                if($phone_f1 == 0 && $phone_f2 == 0)
                {
                    if($age >= 18)
                    {

                        $rand = setSession('otp',rand(546324,999999));
                        $otp = getSession('otp');

                        $msg = "Dear $first_name $last_name, Provide OTP : " . "$otp" . " to validate your phone number";

//                         send otp
                        if(sms('mnotify','','SNEDA SHOP',"$phone","$msg",'1000'))
                        {

                            setSession('l_stage',1);
                            echo 'LS00';
                        }
//                        if(1 < 2)
//                        {
//                            echo 'LS00';
//                            setSession('l_stage',1);
//                            getSession('l_stage');
//
//                        }
                        else
                        {
                            echo "LS12";
                        }
                    }
                    else
                    {
                        echo "LS13";
                    }


                }
                else
                {
                    echo 'LS11';
                }

//            echo "
//            First Name : $first_name
//            Last Name : $last_name
//            Email : $email
//            Phone : $phone
//            Birth : $date_of_birth
//            Gender : $gender
//            ";
            }

            // verify otp
            elseif (strval($l_stage) === '1')
            {
                $sentOtp = getSession('otp');
                $providedOtp = form_data('POST','otp');



                if($sentOtp == $providedOtp)
                {
                    // if matched
                    setSession('l_stage',2);
                    echo "LX1";
                }
                else
                {
                    echo "LX2";
                }
            }

            // finalise
            elseif ($l_stage == 3)
            {



                // get details to insert into database
                $first_name = getSession('first_name');
                $last_name = getSession('last_name');
                $phone = getSession('phone');
                $email = getSession('email');
                $date_of_birth = getSession('date_of_birth');
                $gender = getSession('gender');
                $owner = getSession('username');
                $card_num = form_data('POST','card_number');

                // check if card exist

                $card_exist = rowsOf('customers',"`card_no` = '$card_num'",$pdo);

                if($card_exist > 0)
                {
                    echo "LX3";
                }
                else
                {
                    $ip_addr = "192.168.2.0";
                    $domain = explode('.',$ip_addr)[2];
                    if($domain == 2)
                    {
                        $location = "HQ";
                    }
                    elseif($domain == 1)
                    {
                        $location = "SPINTEX";
                    }
                    elseif ($domain == 3)
                    {
                        $location = 'NIA';
                    }
                    else
                    {
                        $location = $ip_addr;
                    }

                    $cols = "`first_name`, `last_name`, `gender`, `phone`, `email`, `location`, `owner`, `card_no`";
                    $vals = "'$first_name','$last_name','$gender','$phone','$email','$location','$owner','$card_num'";

                    if(insertIntoDatabase('customers',"$cols",$vals,$pdo))
                    {

                        // rename file
                        $user = $_SESSION['user_id'];
						$file_str = "tmp_".$user;
                        $file = glob ("../../assets/customers/$file_str*");
                        if(count($file) > 0)
                        {
                            // get first file
                            $file_id = $file[0];
                            $id_ext = pathinfo($file_id,PATHINFO_EXTENSION);
                            $id_ext;
                        }
                        else
                        {
                            $file_id = '';
                        }
						

                        $image_file = '../../assets/customers/'.$file_id;

                        if(file_exists($image_file))
                        {
                            $new_file = "../../assets/customers/".$card_num.".$id_ext";


                            rename($image_file,$new_file);
                        }


                        setSession('notification',"$card_num added successfully"); // set notification
                        setSession('l_stage', 0); // reset l stage
                        setSession('display','home'); // reset display

                        // unset sessions
                        unsetSession(['first_name','last_name','phone','email','date_of_birth','gender','otp','id_ext']);

                        echo "LS00";

                    }
                    else
                    {
                        echo "Failed";
                    }
                }


            }


        }

        ## sm config
        elseif (isset($_POST['sms_config']))
        {
            $stage = getSession('stage');
            if($stage == 'group')
            {
                // setting group
                setSession('message_group',form_data('POST','group'));
                if(!isset($_SESSION['message_id']))
                {
                    // create session variable for new message id
                    setSession('message_id', md5(form_data('POST','group').date('Y-m-d H:m:s')));
                }
                $message_id = getSession('message_id');
                $message_group = getSession('message_group');
                $message_exist = rowsOf('bulk_sms_message',"`message_id` = '$message_id'",$pdo);

                if($message_exist > 0)
                {
                    // update message group
                    if(updateRecord('bulk_sms_message',"`message_group` = '$message_group'", "`message_id` = '$message_id'", $pdo))
                    {
                        setSession('stage','condition');
                        echo "MSG01";
                    }
                    else
                    {
                        setSession('stage','condition');
                        echo "MSGERR";
                    }

                }
                elseif ($message_exist < 1)
                {
                    // create new message group and title
                    $cols = "`message_id` , `message_group`,`owner`";
                    $vals = "'$message_id', '$message_group', '$my_username'";

                    if (insertIntoDatabase('bulk_sms_message',$cols, $vals, $pdo))
                    {
                        echo "MSG01";
                    }
                    else
                    {
                        echo "MSGERR";
                    }
                }





               //echo 'MSG01';



            }
            elseif ($stage == 'condition')
            {
                /*
                 * CONDITIONS
                 * all_customers = all loyalty customers
                 * v2_not = customers that are not updated yet
                 * v2 = customers that have their record updated
                 * custom_query = custom query
                 * */

                $condition = form_data('POST', 'query');
                setSession('query', $condition);
                setSession('stage','text_message');
                echo 'MSG01';

            }
            elseif ($stage === 'text_message')
            {


                $message = form_data('POST','message');
                $message_id = getSession('message_id');

                // set message
                updateRecord('bulk_sms_message',"`message` = '$message'", "`message_id` = '$message_id'",$pdo);

                $sql = getSession('query');
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                if($stmt->rowCount() > 0)
                {
                    while ($customer = $stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        $phone_number = $customer['phone'];
                        $name = $customer['first_name'] . " " . $customer['last_name'];

                        $msg = str_replace('CNAME',"$name" , $message);

                        // add message details to database
                        $snt_count = rowsOf('buld_sm_recipient',"`message_id` = '$message_id' AND `recipient_phone` = '$phone_number'", $pdo);
                        if($snt_count < 1)
                        {
                            // send message
                            $status = sms('mnotify','','SNEDA SHOP',"$phone_number","$msg",1000);
                            echo $status;

                            // insert into sent database
                            $msg_grp = getSession('message_group');
                            $cols = "`message_id`, `msg_group`, `status`, `recipient`, `recipient_phone`, `error`";
                            $vals = "'$message_id', '$msg_grp', '$status', '$name' , '$phone_number', $status";

                            insertIntoDatabase('buld_sm_recipient', $cols, $vals,$pdo);
                        }


                    }
                }

                // unset sessions
                unsetSession(['query','message_id','message_group']);
                setSession('stage','group');
                setSession('display','home');
                setSession('notification',"SMS SENT");

                echo "MSG01";

            }
        }

    }
    elseif($_SERVER['REQUEST_METHOD'] === "GET")
    {
        if(isset($_GET['stage']))
        {
            $_SESSION['update_stage'] = $_GET['stage'];

            if($_GET['stage'] === 'welcome')
            {
                
                unset(
                    
                    $_SESSION['card']
                );
                
            }


            back();
            
        }
        elseif(isset($_GET['update']))
        {

            //get values
            $card = $_SESSION['card'];
            $first_name = $_SESSION['first_name'];
            $last_name = $_SESSION['last_name'];
            $gender = $_SESSION['gender'];

            $email = $_SESSION['email'];
            $phone = $_SESSION['phone'];
            $id = $_SESSION['id_card'];

            //update
            $update = "UPDATE `customers` SET `first_name` = '$first_name',`last_name` = '$last_name',`gender` = '$gender',`email` = '$email',`phone` = '$phone',`v2` = 1 WHERE `card_no` = ?";
            echo $update;
            $update_stmt = $pdo->prepare($update);
            if($update_stmt->execute([$card]))
            {
                echo $card . " Updated Successfully";

                //unset sessions values
                setSession('notification',"$card updated!");
                setSession('display','home');
                setSession('update_stage','welcome');
                unsetSession(
                    [
                        'first_name','last_name','other_name','gender','email','phone','id_card'
                    ]
                );

                

            }
            else
            {
                echo "Could Not Update";
            }


            $_SESSION['update_stage'] = 'done';
            back();
        }
        elseif(isset($_GET['checkOtp']))
        {
            $otp = $_GET['otp'];
            if(strlen($otp) < 6)
            {
                echo 'remaining ' . 6-strlen($otp) . " characters";
            }

            $c_otp = $_SESSION['otp'];

            //compare
            if(strlen($otp) === 6 && strval($otp) === strval($c_otp))
            {
                echo "OTP MATCH";
                $_SESSION['update_stage'] = 'comfirm_date';
            }
            elseif(strlen($otp) === 6 && $otp !== $c_otp)
            {
                echo strlen($otp)." <span class='text-danger'>WRONG OTP c:".$c_otp." prov: ".$otp."</span>";
            }
        }

        ## AJAX TEST QUERY
        elseif (isset($_GET['testQuery']))
        {
            try {
                $sql = $_GET['q'];

                $stmt = $pdo->prepare($sql);
                if( $stmt->execute())
                {
                    echo "<small class='text-success'> valid query </small>";
                }
            } catch (Throwable $e)
            {
                echo "<small class='text-danger'>Invalid Query</small>";
            }
        }


    }

    if(isset($_GET['cust_id']))
    {
        // get post

        if(isset($_FILES['file']))
        {

            $id = $_FILES['file'];
            $tmp_name = $id['tmp_name'];
            $file_name = $id['name'];
            $file_ext = setSession('id_ext',pathinfo($file_name,PATHINFO_EXTENSION));
            $new_name = $my_username.'_tmp_newuser.'.getSession('id_ext');
            $new_file = "../../assets/customers/".$new_name;
            $error = $id['error'];




            if($error == 0)
            {
                if(move_uploaded_file($tmp_name,$new_file) == true)
                {
                    setSession('l_stage',3);
                    echo "UP01";

                }
                else
                {
                    echo "UP02";
                }
            }
            else
            {
//                setSession('l_stage',3);
                echo "UPS0";
            }
        }
        else
        {

        }
    }


    