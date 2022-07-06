<?php

    include "../../include/core.php";
    $ts = new tools();



    $new_customer_stage = $ts->getSession('new_customer_stage');

    if($new_customer_stage === 'details') // details
    {
        $first_name = post('first_name');
        $last_name = post('last_name');
        if(empty($_POST['email_address']))
        {
            $email_address = 'none';
        }
        else
        {
            $email_address = post('email_address');
        }

        $phone = post('phone');
        $gender = post('gender');
        $date_of_birth = post('date_of_birth');


        // check if phone number taken
        $phone_f1 =  rowsOf('customers',"`phone` = '$phone'",$pdo);
        $pf2 = '+233' . ltrim($phone,$phone[0]);
        $phone_f2 = rowsOf('customers',"`phone` LIKE '$pf2%'",$pdo);

        // validate gender
        $today = date('Y-m-d');

        $days = dateDifference($date_of_birth,$today);
        $age = number_format($days / 365);

        setSession('first_name',"$first_name");
        setSession('last_name',"$last_name");
        setSession('email_address',"$email_address");
        setSession('phone',"$phone");
        setSession('gender',"$gender");
        setSession('date_of_birth',"$date_of_birth");

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

                    // set session data
                    //sms('mnotify','','SNEDA SHOP',"$phone","hello solo",'1000');
                    setSession('new_customer_stage','verify_otp');
                    setSession('otp',"$otp");


                    echo 'done';
                }
                else
                {
                    echo "could_not_send_otp";
                }
            }
            else
            {
                echo "not_18";
            }


        }
        else
        {
            echo 'phone_number_taken';
        }

    }

    elseif ($new_customer_stage === 'verify_otp')
    {
        $otp =  getSession('otp');
        $customerOtp =  post('otp1').post('otp2'). post('otp3').post('otp4').post('otp5').post('otp6');

        if($otp == $customerOtp)
        {

            setSession('new_customer_stage','upload_id');
            echo 'done';

        } else {
            echo 'otp_failed';
        }



    }

    elseif (isset($_POST['get_uploaded_id'])) // validate card uploaded
    {
        $user = $_SESSION['user_id'];
        $file_str = "tmp_".$user;
        $file = glob ("../../../assets/customers/$file_str*");

        if(count($file) > 0)
        {
            // get first file
            $id = $file[0];
            $id_ext = pathinfo($id,PATHINFO_BASENAME);
            $id_ext;
            //echo $new_file = getSession('card_number');
            echo "Y%%$id_ext";
        }
        else
        {
            echo 'N%%';
        }
    }

    elseif (isset($_POST['validate_card'])) // validate loyalty card
    {
        $card = post('card');

        if(rowsOf('customers',"`card_no` = '$card'",$pdo) === 0) // if card does not exist
        {
            echo 'card_available';
        } else
        {
            // car exist
            echo 'card_taken';
        }

    }

    elseif ($new_customer_stage === 'final')
    {
        // end all



        // get details to insert into database
        $first_name = getSession('first_name');
        $last_name = getSession('last_name');
        $phone = getSession('phone');
        $email = getSession('email_address');
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
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip_addr = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip_addr = $_SERVER['REMOTE_ADDR'];
            }

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
                $file = glob ("../../../assets/customers/$file_str*");
                if(count($file) > 0)
                {
                    // get first file
                    $file_id = $file[0];


                }
                else
                {
                    $file_id = '';
                    echo 'no file';
                    die();
                }




                $image_file = $file_id;


                if(file_exists($image_file))
                {
                    $id_ext = pathinfo($file_id,PATHINFO_EXTENSION);
                    $new_file = "../../../assets/customers/".$card_num.".$id_ext";



                    rename($image_file,$new_file);
                }



                setSession('notification',"$card_num added successfully"); // set notification
                setSession('view','customers'); // reset display

                // unset sessions
                unsetSession(['first_name','last_name','phone','email','date_of_birth','gender','otp','id_ext','new_customer_stage']);
                unset($_SESSION['new_customer_stage']);

                echo "done";

            }
            else
            {
                echo "Failed";
            }
        }

    }

    elseif (isset($_POST['function'])) // if isset function
    {
        $function = post('function');


        if($function === 'customerNavigator') // navigate user
        {
            $direction = post('direction');
            $current_customer = getSession('customer');
            if($direction === 'next')
            {
                $query = $pdo->query("SELECT * FROM `customers` WHERE `id` > $current_customer LIMIT 1");
            }
            elseif ($direction === 'previous')
            {
                $query = $pdo->query("SELECT * FROM `customers` WHERE `id` < $current_customer order by `id` desc LIMIT 1");
            } else {
                die();
            }

            if($query->rowCount() > 0)
            {
                $customer_details = $query->fetch(PDO::FETCH_ASSOC);
                $first_name = $customer_details['first_name'];
                $last_name = $customer_details['last_name'];
                $phone = $customer_details['phone'];
                $email = $customer_details['email'];
                $owner = $customer_details['owner'];
                $location = $customer_details['location'];
                $id = $customer_details['id'];
                $card_no = $customer_details['card_no'];


                $res = "$first_name^$last_name^$phone^$email^$owner^$location^$id^$card_no";
                echo $res;

            }
            else
            {
                echo 'no customer';
            }
        }

        elseif ($function === 'row_count')// check row
        {
            $table = post('table');
            $condition = $_POST['condition'];

            echo rowsOf("$table","$condition",$pdo);

        }

        elseif ($function === 'search_customer') // search customer
        {
            $query = post('query');

            $sql = $pdo->query("SELECT * FROM `customers` WHERE `first_name` like '%$query%' or `last_name` like '%$query%' or `card_no` like '%$query%' limit 10");
            if($sql->rowCount() > 0)
            {
                $code = 'done%%';
                while ($result = $sql->fetch(PDO::FETCH_ASSOC))
                {
                    $name = $result['first_name'] . " " . $result['last_name'];
                    $card = $result['card_no'];
                    $id = $result['id'];

                    $code .= "<tr class='pointer' onclick='selectCustomer(".$id.")'><td>$name</td><td>$card</td></tr>";
                }

                echo $code;
            }
            else
            {
                echo 'no user found';
            }

        }

        elseif ($function === 'activate_customer') // select search customer
        {
            $cust = post('customer');

            $query = $pdo->query("SELECT * FROM `customers` WHERE `id` = '$cust' ");

            if($query->rowCount() > 0)
            {
                $customer_details = $query->fetch(PDO::FETCH_ASSOC);
                $first_name = $customer_details['first_name'];
                $last_name = $customer_details['last_name'];
                $phone = $customer_details['phone'];
                $email = $customer_details['email'];
                $owner = $customer_details['owner'];
                $location = $customer_details['location'];
                $id = $customer_details['id'];
                $card_no = $customer_details['card_no'];


                $res = "$first_name^$last_name^$phone^$email^$owner^$location^$id^$card_no";
                echo $res;


            }
            else
            {
                echo 'no customer';
            }

        }

        elseif ($function === 'confirm_card') // comfirm card
        {
            $card_number = post('card_number');
            //echo rowsOf('customers',"`card_no` = '$card_number'",$pdo);
            // check if card exist
            if(rowsOf('customers',"`card_no` = '$card_number'",$pdo) === 1)
            {
                setSession('card_number', $card_number);
                setSession('stage','user_details');

                echo 'reload';
            }
            else
            {
                echo "CARD_DOES_NOT_EXIST";
            }
        }

        if($function === 'confirm_details') // details
        {

            $first_name = post('first_name');
            $last_name = post('last_name');
            if(empty($_POST['email_address']))
            {
                $email_address = 'none';
            }
            else
            {
                $email_address = post('email_address');
            }

            $phone = post('phone');
            $gender = post('gender');
            $date_of_birth = post('date_of_birth');
            $card = getSession('card_number');


            // check if phone number taken
            $phone_f1 =  rowsOf('customers',"`phone` = '$phone' AND `card_no` != '$card'",$pdo);
            $pf2 = '+233' . ltrim($phone,$phone[0]);
            $phone_f2 = rowsOf('customers',"`phone` LIKE '$pf2%' AND `card_no` != '$card'",$pdo);

            // validate gender
            $today = date('Y-m-d');

            $days = dateDifference($date_of_birth,$today);
            $age = number_format($days / 365);


            setSession('first_name',"$first_name");
            setSession('last_name',"$last_name");
            setSession('email_address',"$email_address");
            setSession('phone',"$phone");
            setSession('gender',"$gender");
            setSession('date_of_birth',"$date_of_birth");
            $card = getSession('card_number');

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

                        // set session data
                        //sms('mnotify','','SNEDA SHOP',"$phone","hello solo",'1000');
                        setSession('stage','verify_otp');
                        setSession('otp',"$otp");


                        echo 'done';

                    }
                    else
                    {
                        echo "could_not_send_otp";

                    }
                }
                else
                {
                    echo "not_18";
                }


            }
            else
            {
                echo("phone_number_taken");
            }


        }

        elseif ($function === 'update_otp')
        {
            $otp =  getSession('otp');
            $customerOtp =  post('otp1').post('otp2'). post('otp3').post('otp4').post('otp5').post('otp6');

            if($otp == $customerOtp)
            {

                setSession('stage','verify_id');
                echo 'done';

            } else {
                echo 'otp_failed';
            }
        }

        elseif ($function === 'update_customer')
        {

            $card_number = getSession('card_number');
            $first_name = getSession('first_name');
            $last_name = getSession('last_name');
            $email_address = getSession('email_address');
            $phone = getSession('phone');
            $gender = getSession('gender');
            $date_of_birth = getSession('date_of_birth');


            // update
            $q = "UPDATE `customers` set `first_name` = '$first_name', `last_name` = '$last_name',`email` = '$email_address', `phone` = '$phone', `gender` = '$gender', `date_of_birth` = '$date_of_birth', `v2` = 1  WHERE `card_no` = '$card_number'";


            try {
                $pdo->exec($q);
                // rename id card
                // rename file
                $user = $_SESSION['user_id'];
                $file_str = "tmp_".$user;
                $file = glob ("../../../assets/customers/$file_str*");



                if(count($file) > 0)
                {
                    // get first file
                    $file_id = $file[0];
                    $id_ext = pathinfo($file_id,PATHINFO_EXTENSION);
                    $image_file = '../../../assets/customers/'.$file_id;



                    if(file_exists($file_id))
                    {

                        $new_file = "../../../assets/customers/".$card_number.".$id_ext";


                        rename($file_id,$new_file);

                    }

                }



                // enable card from mycom db





                // unset sessuon

                unsetSession(['card_number','first_name','last_name','email_address','email_address','phone','gender','date_of_birth']);
                setSession('view','home');
                setSession('stage','comf_num');
                $msg = "Your card has been linked to this phone number. You can continue to enjoy mega discount whilst shopping";
                sms('mnotify','','SNEDA SHOP',"$phone","$msg",'1000');

                // enable card
                $sql = "UPDATE LoyaltyCustomer SET valid = ( ?)";
                $params = array( 1 );
                $stmt = sqlsrv_query( $sql_srv_con, $sql, $params);

                echo 'done';
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }


        }
    }