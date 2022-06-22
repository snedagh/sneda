<?php

    include "../../include/core.php";
    $ts = new tools();

    if(isset($_POST['function']))
    {
        $function = post('function');
        if($function === 'newClerk')
        {
            $phone_number = post('phone_number');
            $name = post('name');

            $clerk_code = mt_rand(1363, 9999);
            $password = str_shuffle($clerk_code);

            $message = "Dear $name, Your details for Retail POS is as follows. Code: $clerk_code, Password: $password";
            // send sms to user
//            sms('mnotify','','SNEDA SHOP',"$phone_number","$message",'1000');
//            die();
            // add user
            if($spin_retail->query("insert into clerk_mast (clrk_code,clrk_name,clrk_pwd,clrk_type,login_type,Register) values 
                                                                                        ('$clerk_code','$name','$password',0,0,0)"))
            {
                // send sms to user
                sms('mnotify','','SNEDA SHOP',"$phone_number","$message",'1000');
                echo 'done%%';
            }

        }
    }