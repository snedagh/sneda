<?php

    include "../../include/core.php";
    $ts = new tools();


    if(isset($_POST['function']))
    {
        $function = post('function');

        if($function === 'assign_app')
        {
            $user_id = post('user');
            $user_apps_table = "user_$user_id".'_apps';

            if(isset($_POST['app_uni']) && is_array($_POST['app_uni']))
            {
                // loop through
                foreach ($_POST['app_uni'] as $key=>$value)
                {
                    $app_uni = $value;

                    // check if app exist
                    if(rowsOf("$user_apps_table","`app` = '$app_uni'",$pdo) === 0)
                    {
                        // add app
                        $q = "INSERT INTO $user_apps_table (`app`) value ('$app_uni')";
                        try {
                            $pdo->exec($q);

                        } catch (PDOException $exception)
                        {
                            echo $exception->getMessage();
                        }
                    }
                }
            }

            header("Location:".$_SERVER['HTTP_REFERER']);
        }
    }