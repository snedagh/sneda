<?php

    //includes
    include "../include/core.php";



    //get live income query
    if(isset($_GET['update']))
    {

        $sql = "SELECT `amount_paid` FROM `payment` WHERE `date_paid` = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$today]);

        $payment = 0.00;

        while ($payment_row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $p = $payment_row['amount_paid'];
            if ($p > 0)
            {
                $payment =+ $p;
            }
        }

        //check if there is dot
        if (explode('.',$payment) === true)
        {
            $act_live_income = $payment;
        }
        else
        {
            $act_live_income = $payment.'.00';
        }


        echo $_SESSION['currency'] . ' ' . $act_live_income;
    }

    //check shift start
    elseif (isset($_GET['check_sift_start']))
    {
        $date = date('d/m/Y');
        $shift_check = fetchFunc('admin.company_setup' , '`c_name` = "'.$company_setup['c_name'].'"',$pdo)['shift_start'];
        if ($shift_check === 1)
        {
            echo 'reload';
        }
        else
        {
            echo 'no_reload';
            echo $date;
        }
    }

    //cancel sessions
    elseif (isset($_GET['unset_sessions']))
    {
        $sessions_array = ['info' , 'error' , 'warming' , 'success'];
        foreach ($sessions_array as $key => $value)
        {
            if(isset($_SESSION[$value]))
            {
                unset($_SESSION[$value]);
            }
        }
    }

    // install app
    elseif (isset($_GET['install']))
    {
        $my_apps = 'user_'.$_SESSION['user_id'].'_apps';
        $app = $_GET['install'];

        // get app details
        $app_info = fetchFunc('appstore', "`uni` = '$app'", $pdo);

        echo 'Installing '.$app_info['name'] . '....... ';

        //install
        if(insertIntoDatabase($my_apps,"`app`", "'$app'",$pdo))
        {
            echo "Done";
        }
        else
        {
            echo "Failed";
        }



    }

    elseif (isset($_GET['uninstall']))
    {
        $my_apps = 'user_'.$_SESSION['user_id'].'_apps';
        $app = $_GET['uninstall'];

        // get app details
        $app_info = fetchFunc('appstore', "`uni` = '$app'", $pdo);

        echo 'Uninstalling '.$app_info['name'] . '....... ';

        //install
        if(deleteRow($my_apps, "`app` = '$app'", $pdo))
        {
            echo "Done";
        }
        else
        {
            echo "Failed";
        }



    }

    // switch tool
    elseif (isset($_GET['switch_tool']))
    {
        $tool = get('switch_tool');

        setSession('tool', $tool);
        setSession('view','all');
    }
    elseif (isset($_POST['switch_tool']))
    {

        $tool = post('switch_tool');

        setSession('tool', $tool);
    }
    elseif (isset($_POST['function']))
    {
        $func = post('function');
        if($func == 'set_session')
        {
            $data = $_POST['data'];
            print_r($_POST);

            // split data
            $data_sp = explode(',',$data);
            if(count($data_sp) > 0)
            {
                // iterate through data
                foreach ($data_sp as $data)
                {
                    // separate session into var val
                    $sp = explode('=',$data);
                    if(count($sp) > 1)
                    {
                        $var = $sp[0];
                        $val = $sp[1];

                        setSession("$var","$val");

                    }
                }
            }
        }
        elseif ($func == 'add_task_track')
        {
            $update_text = post('update_text');
            $title = post('title');
            $task_id = getSession('task');

            $vales = '"'.$title.'",'.'"'.$update_text.'",'.'"'.$task_id.'"';
            $cols = "`title`,`details`,`issue`";
            if(insertIntoDatabase('task_tracking',"$cols","$vales",$pdo))
            {
                echo 'done';
            }
        }
        elseif ($func == 'complete_task') // mark task as completed
        {
            // mark task as complete
            $task =  getSession('task');

            // update
            updateRecord('task',"`status` = 1","`id` = '$task'",$pdo);
        }
        elseif ($func == 'update_app') // update an app
        {
            print_r($_POST);
            // get values
            $name = post('name');
            $trigger = post('trigger');
            $url = post('url');
            $type = post('type');
            $details = post('details');
            $app = post('app');
            $sets = post('sets');

            // update
            updateRecord(
                'appstore',
                "`name` = '$name', `type` = '$type' ,`details` =".'"'.$details.'"'." , `path` = '$url', `app_trigger` = '$trigger' , `sets` = '$sets'",
                "`id` = $app",
                $pdo);

            header("Location:".$_SERVER['HTTP_REFERER']);

        }
        elseif ($func == 'new_app') // add new app
        {
            $name = post('name');
            $trigger = post('trigger');
            $url = post('url');
            $type = post('type');
            $details = post('details');
            $app = post('app');
            $sets = post('sets');
            $uni = md5($name.$trigger.$url.date('y-m-d h:m:s'));

            $cols = "`uni`,`name`,`type`,`details`,`path`,`app_trigger`,`sets`";
            $vals = "'$uni','$name', '$type' ,".'"'.$details.'"'." , '$url', '$trigger' , '$sets'";

            // check if app exist
            if(rowsOf('appstore',"`name` = '$name'",$pdo) < 1)
            {
                insertIntoDatabase('appstore',"$cols","$vals",$pdo);
            }
        }
        elseif ($func == 'uninstall_app') // uninstall app
        {
            $app = post('app');
            // uninstall app
            deleteRow($my_apps,"`app` = '$app'",$pdo);

            // set sessions to all

        }
        elseif ($func == 'install_app') // install app
        {
            $app = post('app');
            // uninstall app
            insertIntoDatabase($my_apps,"`app`","'$app'",$pdo);

            // set sessions to all

        }
        elseif ($func == 'app_store_search') // search app
        {
            $q = post('string');
            $query = $pdo->query("SELECT * FROM `appstore` where `name` like '%$q%' AND `type` != 'store'");
            if($query->rowCount() > 0 && strlen($q) > 0)
            {
               while($ap = $query->fetch(PDO::FETCH_ASSOC))
               {
                   $ap_name = $ap['name'];
                   $ap_det = $ap['details'];
                   $ap_uni = $ap['uni'];
                   echo "
                <div onclick='' class='w-100 btn-light p-2 border-bottom pointer'>
                    <p class='text-info m-0 p-0 font-weight-bolder'>$ap_name</p>
                    <small>$ap_det</small>
                </div>
                ";
               }
            }
            else
            {

            }

        }
        elseif ($func === 'rest_search') // restaurant search
        {

            $query_string = post('q');

            // execute
            $sql = "select top(50) * from item_buttons where button_descr like '%$query_string%' order by button_descr";
            $server = "192.168.2.4";
            $connectionInfo = array( "Database"=>"SMSEXP_REST", "UID"=>"sa", "PWD"=>"sa@123456" );
            $conn = sqlsrv_connect( $server, $connectionInfo );

            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $conn, $sql , $params, $options );

            $row_count = sqlsrv_num_rows( $stmt );

            if ($row_count === false)
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    echo $row['code'].", ".$row['code']."<br />";
                }
            else
                $code = '';
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    $barcode = $row['code'];
                    $desc = $row['button_descr'];
                    $code .=  "<div class='border-bottom p-2'><kbd onclick='console.log(".'"'.$barcode.'"'.")' class='pointer btn-danger'>Delete</kbd> $barcode -  $desc</div>";
                }

                echo $code;

            die();
            if($query->rowCount() > 0)
            {
                echo 'yes';
            }
            else
            {
                echo 'no';
            }
        }
        elseif ($func === 'del_rest_tem')// del rest item
        {
            $item = post('barcode');

            // delete item
            $sql = "delete top(1) from prod_mast where barcode = '$item'";
            if($conn_rest->exec($sql))
            {
                echo "$sql deleted";
            }
        }
        elseif ($func === 'navigate') // navigate
        {
            $todo = post('todo');
            $direction = post('direction');

            if($todo === 'loyalty_customer') // loyalty nav
            {
                $active_customer = $_SESSION['active_customer'];
                if($direction === 'prev') // previous customer
                {
                    // get previous item
                    $current_customer_id = fetchFunc('customers',"`card_no` = '$active_customer'",$pdo)['id'];
                    $q = $pdo->query("SELECT `card_no` FROM `customers` where `id` < $current_customer_id ORDER BY `id` DESC LIMIT 1");
                    $r = $q->fetch(PDO::FETCH_ASSOC);
                    $current_loyalty_customer = $r['card_no'];
                    $_SESSION['active_customer'] = $current_loyalty_customer;
                }
                elseif ($direction === 'next')
                {
                    // ge next
                    $current_customer_id = fetchFunc('customers',"`card_no` = '$active_customer'",$pdo)['id'];
                    $q = $pdo->query("SELECT `card_no` FROM `customers` where `id` > $current_customer_id LIMIT 1");
                    $r = $q->fetch(PDO::FETCH_ASSOC);
                    $current_loyalty_customer = $r['card_no'];
                    $_SESSION['active_customer'] = $current_loyalty_customer;
                }
            }
        }
        elseif ($func === 'search') // general search
        {
            $todo = post('todo');
            $query = post('query');

            if($todo === 'loyalty_customers') // loyalty customers
            {
                //get loyalty and then boon
                $q = "SELECT * FROM `customers` WHERE 
                                `card_no` LIKE '$query' OR 
                                `first_name` LIKE '$query' OR 
                                `last_name` LIKE '$query' OR 
                                `phone` LIKE '$query' OR 
                                `email` LIKE `$query` order by `first_name`";
                $stmt = $pdo->query($q);
                if($stmt->rowCount() > 0)
                {
                    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
                    $stmt->rowCount();
                }
                else
                {
                    echo 'null';
                }
            }
        }
    }


