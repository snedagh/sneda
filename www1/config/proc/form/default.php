<?php

    include "../../include/core.php";
    $ts = new tools();


    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if(isset($_POST['insert']))
        {
            // get form data
            $user_name = $tools->formData('POST','record');

            // insert
            if ($db->insert('users',"`username`","'$user_name'"))
            {
                $tools->setSession('result',"$user_name inserted");
            }

            $tools->back();
        }
        elseif (isset($_POST['delete']))
        {
            $user_name = $tools->formData('POST','record');

            // delete
            if($db->delete('users',"`username`= '$user_name'"))
            {
                $tools->setSession('result',"$user_name deleted");
            }
            $tools->back();
        }
        else
        {
            echo 'Unknown';
        }
    }