<?php
    require '../inc/session.php';

    if($_SERVER['REQUEST_METHOD'] === 'GET')
    {
        if(isset($_GET['display']))
        {
            $_SESSION['display'] = $_GET['display'];
            if($_GET['display'] === 'update')
            {
                $_SESSION['update_stage'] = 'welcome';
                session_write_close();
            }
            elseif($_GET['display'] === 'message')
            {
                $_SESSION['stage'] = 'group';
                session_write_close();

            }
            elseif ($_GET['display'] === 'new_customer')
            {
                $_SESSION['display'] = 'new_customer';
                setSession('l_stage',0);
//                print_r($_SESSION);
//                die();
            }

            $url = $_SERVER['HTTP_REFERER'];



        }

        // set limit
        if(isset($_GET['limit']))
        {
            $limit = $_GET['limit'];
            $_SESSION['limit'] = $limit;
//            back();
        }

        // pagination
        if(isset($_GET['pagination']))
        {
            $direction = $_GET['pagination'];

            if($direction === 'next')
            {
                $last = $_GET['last'];
                $_SESSION['where'] = "WHERE `first_name` > '$last'";
            }
            elseif ($direction === 'previous')
            {
                //get limi
                $limit = $_SESSION['limit'];
                $mult_limit = $limit * 2;
                $last = $_GET['last'];
                br($mult_limit);
                br("last : ".$last);

                //get item with limit
                $sql = "SELECT `first_name` FROM `customers` WHERE `first_name` < '$last' ORDER BY `first_name` DESC LIMIT $mult_limit";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $sm = 0;
                while($res = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                    $first_name = $res['first_name'];

                }
                br($first_name);
                $_SESSION['where'] = "WHERE `first_name` > '$first_name'";


            }
            br($_SESSION['where']);
//            back();
        }

        // set order
        if(isset($_GET['sort']) && isset($_GET['col']))
        {
            $column = $_GET['col'];
            $sort = $_GET['sort'];

            $_SESSION['order'] = "ORDER BY `$column` $sort";
            echo $_SESSION['order'];
//            back();
        }

        //loyalty query
        if(isset($_GET['stage']))
        {
            $_SESSION['stage'] = $_GET['stage'];
//            back();
        }

        // send sms
        elseif (isset($_GET['sendSms']))
        {
            $stage = $_GET['s'];
            if($stage === 'check_session')
            {
                br("Session Stable");
            }
            //validate numbers
            elseif ($stage === 'validate_numbers')
            {
                try
                {
                    $valid = 0;
                    $not_valid = 0;

                    $sql = $_SESSION['query'];
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $total_customers = $stmt->rowCount();
                    while ($customer = $stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        $phone_number = $customer['phone'];

                        // replace country code to 0
                        $phone = str_replace('+233','0', $phone_number);

                        // check if number is exactly 10
                        if(strlen($phone) === 10)
                        {
                            $valid ++;
                        }
                        else
                        {
                            $not_valid ++;
                        }
                    }
                    $message = ' <strong> Valid</strong> : ' . $valid . '/' . $total_customers . ' <strong>Invalid</strong> : ' .  $not_valid . '/' . $total_customers ;
                    br($message);
                } catch (Throwable $e)
                {
                    echo $e.' <br>';
                }

            }

            // populate table
            elseif ($stage === 'populate_table')
            {
                $message = time_stamp_msg("Populating contacts in message group");
                $m = $message;
                $message_group = $_SESSION['grp_id'];
                $message = $m . time_stamp_msg("Goup ID: ".$message_group);
                $m= $message;
                $valid = 0;
                $exist = 0;
                try
                {

                    //insert contacts
                    $sql = $_SESSION['query'];
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $total_customers = $stmt->rowCount();
                    while ($customer = $stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        $phone_number = $customer['phone'];

                        // replace country code to 0
                        $phone = str_replace('+233','0', $phone_number);

                        // check if number is exactly 10
                        if(strlen($phone) === 10)
                        {

                            $tname = $customer['first_name'] . ' ' . $customer['other_name'] . ' ' . $customer['last_name'];

                            try {
                                if(rowsOf('buld_sm_recipient', "`msg_id`='$message_group' AND `recipient_phone` = '$phone'", $pdo) < 1)
                                {
                                    $valid ++;
                                    insertIntoDatabase('buld_sm_recipient', "`msg_id`,`recipient_phone`, `recipient`", "'$message_group','$phone', '$tname'", $pdo);
                                }
                                else
                                {
                                    $exist ++;
                                }


                            } catch (\Throwable $e)
                            {
                                br($e);
                            }


                        }
                        else
                        {
                            $not_valid ++;
                        }
                    }
                    $messageX = ' <strong> ' . $valid . '</strong> M<sub>s</sub>Q and <strong>'.$exist.'</strong> DP<sub>s</sub>';
                    br($messageX);

                } catch (Throwable $e)
                {
                    echo $e.' <br>';
                }


            }

            // send message
            elseif ($stage === 'send' && isset($_GET['msg']))
            {
                $grp_id = $_SESSION['grp_id'];
                $grp_name = $_SESSION['grp_name'];
                $msg = $_GET['msg'];

                $send_sql = "SELECT * FROM `buld_sm_recipient` WHERE `status` = 0 AND `msg_id` = '$grp_id'";
                $send_stmt = $pdo->prepare($send_sql);
                $send_stmt->execute();
                if($send_stmt->rowCount() > 0)
                {
                    $result = $send_stmt->fetch(PDO::FETCH_ASSOC);
                    $phone = $result['recipient_phone'];
                    $customer = $result['recipient'];

                    $s_msg = str_replace('TNAME', $customer, $msg);

                    br("Delivering SMS To ".$phone . ' .......');

                    br("hello");

                    // send message
//                    if(sms('mnotify','','SNEDA SHOP',$phone,$s_msg,'1000'))
//                    {
//                        br('Delivered To '.$phone);
//                        //mark message as sent
//                        try {
//                            updateRecord('buld_sm_recipient', "`status` = 1", "`recipient_phone` = '$phone' AND `msg_id` = '$grp_id'",$pdo);
//
//                        } catch (\Throwable $e)
//                        {
//                            br($e);
//                        }
//                    }
//                    else
//                    {
//                        br('Failed Delivering '.$phone);
//                    }
                }
                else
                {
                    br('Nothing To Send');
                }

            }
        }

        elseif (isset($_GET['l_stage_previous']))
        {
            $stage = getSession('l_stage');
            if($stage > 0)
            {
                setSession('l_stage', $stage - 1);
            }
        }
    }

//    echo $new_url = explode('=',$url)[0]."=".session_id();
//    header("Location:$new_url");
    back();
