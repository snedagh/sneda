<?php
    require '../inc/db.php';
    require '../inc/session.php';

    if(isset($_GET['view']))
    {
        if ($_GET['view'] === 'single')
        {
            $_SESSION['rec_id'] = $_GET['id'];
            $_SESSION['view'] = 'single';
            header("Location:".$_SERVER['HTTP_REFERER']);

        }
        elseif($_GET['view'] === 'all')
        {
            unset($_SESSION['rec_id']);
            $_SESSION['view'] = 'all';
            header("Location:".$_SERVER['HTTP_REFERER']);
        }
    }

    elseif (isset($_GET['delete']))
    {
        $id = $_GET['id'];

        //detele
        $sql = "DELETE FROM `customers` where `id` = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        unset($_SESSION['rec_id']);
        $_SESSION['view'] = 'all';
        header("Location:".$_SERVER['HTTP_REFERER']);
    }
    //register customer
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register']))
    {
        //get form values
        $id = htmlentities($_POST['id']);
        //get customer details
        $cust_sql = "SELECT * FROM `customers` WHERE `id` = $id";
        $cust_stmt = $pdo->prepare($cust_sql);
        $cust_stmt->execute();
        $cust = $cust_stmt->fetch(PDO::FETCH_ASSOC);

        $cust_name = $cust['first_name'] . ' ' . $cust['last_name'];


        $c_number = htmlentities($_POST['card_number']);
        $reg_by = $_SESSION['username'];

        $sql = "UPDATE `customers` set `card_no` = ? , `reg_by` = ? , `stat` = 'registered' , `reg_time` = CURRENT_TIMESTAMP() , `synced` = 0 WHERE `id` = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$c_number , $reg_by , $id]);
        //send message
        $api_key = 'VE36nLRD9rCWroMvHE6i2C097';
        $to = $cust['phone'];
        $message = 'Hello '.$cust_name . ' Your Loyalty Card has been registred Please visit Out '.$cust['location'] . ' Branch to claim card. Card Number : '.$c_number . 'Sneda Shopping Center';
        $sender_id = "SNEDA SHOP";
        $url = "https://apps.mnotify.net/smsapi?key=$api_key&to=$to&msg=$message&sender_id=$sender_id";
        $response = file_get_contents($url);
        unset($_SESSION['rec_id']);
        $_SESSION['view'] = 'all';
        header("Location:".$_SERVER['HTTP_REFERER']);
        die("ERROR");
    }
    //if updating record
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modButt']))
    {
        //get form values
        $first_name = htmlentities($_POST['first_name']);
        $last_name = htmlentities($_POST['last_name']);
        $phone = htmlentities($_POST['phone']);
        $email = htmlentities($_POST['email']);
        $gender = htmlentities($_POST['gender']);
        $location = htmlentities($_POST['location']);
        $updater = htmlentities($_SESSION['username']);
        $id = htmlentities($_POST['id']);

        //update
        $sql = "UPDATE `customers` SET `first_name` = ? , `last_name` = ? , `gender` = ? , `phone` = ? , `email` = ? , `location` = ? , `updater` = ? , `updated_time` = CURRENT_TIMESTAMP() WHERE `id` = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$first_name , $last_name , $gender , $phone, $email , $location , $updater, $id]);
        header("Location:".$_SERVER['HTTP_REFERER']);
    

    }

    //givee card to customer
    elseif (isset($_GET['give_out']))
    {
        $id = $_GET['id'];
        $given_by = $_SESSION['username'];

        $sql = "UPDATE `customers` SET `stat` = 'out' , `given_by` = ? , `given_time` = CURRENT_TIMESTAMP() WHERE `id` = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$given_by , $id]);
        unset($_SESSION['rec_id']);
        $_SESSION['view'] = 'all';
        header("Location:".$_SERVER['HTTP_REFERER']);

    }

    //alert
    elseif (isset($_GET['alert'])) 
    {
        //get all records registerd
        $sql = "SELECT * FROM `customers` WHERE `stat` = 'registered'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $count = 0;
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $customer = $row['first_name'] . ' ' . $row['last_name'];

            $title = '';
            if ($row['gender'] === 'male')
            {
                $title = 'Mr.';
            }
            elseif ($row['gender'] === 'female') 
            {
                # code...
                $title = 'Mrs.';
            }

            $location = $row['location'];
            if ($row['location'] === 'NIA')
            {
                $location = 'New Indistrial Area';
            }
            
            $card = $row['card_no'];
            $phone = $row['phone'];
            $santPhone = $phone;
            if (strlen($phone) > 10)
            {
               $santPhone = '0' . substr($phone,4);
            }

            $message = "Hello " . $title. " " . $customer . ", Your Loyalty card has been registered, please vist our " . $location . " branch to claim your card. Your card Number is ".$card . " SNEDA SHOPPING CENTER!! THANK YOU";


            //send message
            $api_key = 'VE36nLRD9rCWroMvHE6i2C097';
            $to = $santPhone;
            $sender_id = "SNEDA SHOP";
            $url = "https://apps.mnotify.net/smsapi?key=$api_key&to=$to&msg=$message&sender_id=$sender_id";
            $response = file_get_contents($url);

            $count++;

        }
        
        $_SESSION['info'] = $count . ' customers reminded';
        $back = $_SERVER['HTTP_REFERER'];
        echo "<script>location.href='".$back."'</script>";
       
    }


