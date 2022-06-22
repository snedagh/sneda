<?php 

    require 'db.php';

//    br(session_id());
//    print_r($_SESSION);


    if(isset($_SESSION['view']))
    {
        $view = $_SESSION['view'];
        $display = $_SESSION['display'];
        if($display === 'customers' && $view === 'all')
        {
            if(!isset($_SESSION['limit']))
            {
                $_SESSION['limit'] = 20;
            }

            $limit = $_SESSION['limit'];

            if(!isset($_SESSION['order']))
            {
                $_SESSION['order'] = 'ORDER BY `id` DESC';
            }

            // set where
            if(!isset($_SESSION['where']))
            {
                $_SESSION['where'] = '';
            }

            $where = $_SESSION['where'];

            $order = $_SESSION['order'];

            $sql = "SELECT * FROM `customers` $where  ORDER BY `id` DESC LIMIT $limit";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $f_count = $stmt->rowCount();
        }
        elseif($view === 'single')
        {
            $id = $_SESSION['customer'];
            $sql = "SELECT * FROM `customers` WHERE `id` = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();
        }

        ## UPDATING EXISTING RECORD
        elseif ($display === 'update')
        {
            if(!isset($_SESSION['update_stage']))
            {
                $_SESSION['update_stage'] = 'welcome';
            }

            $update_stage = $_SESSION['update_stage'];
        }

        ## SEND MESSAGE
        elseif ($display === 'message')
        {
            if(!isset($_SESSION['stage']))
            {
                $_SESSION['stage'] === 'group';
            }

            $stage = $_SESSION['stage'];
            $bal = file_get_contents('https://apps.mnotify.net/smsapi/balance?key=VE36nLRD9rCWroMvHE6i2C097');
           // $bal = "Dynamic";

            if($stage === 'text_message')
            {
                $sql = $_SESSION['query'];
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $stmt_count = $stmt->rowCount();
            }
        }

        ## new customer
        elseif ($display === 'new_customer')
        {


            $l_stage = $_SESSION['l_stage'];


        }
    }
