<?php
    include "../../include/core.php";
    $ts = new tools();



    if(isset($_POST['app']))
    {
        $app = post('app');
        if($app === 'cli_mgmt_sys')
        {
            if(isset($_POST['function']))
            {
                $function = post('function');
                if($function === 'newClient')
                {
                    // get details
                    $Full_Name = post('Full_Name');
                    $email = post('email');
                    $phone = post('phone');
                    $org = post('org');
                    $pos = post('pos');

                    if(rowsOf('cli_mgmt_sys_clients',"`phone` = '$phone'",$pdo) > 0 )
                    {
                        echo 'done';
                        die();
                    }


                    try {
                        $pdo->exec("insert into cli_mgmt_sys_clients (`name`, email, phone, org, `pos`)
                        values ('$Full_Name','$email','$phone','$org','$pos');");
                        echo 'done';
                    } catch (PDOException $exception)
                    {
                        error($exception->getMessage());
                    }
                }

                elseif ($function === 'update_client')
                {
                    if(empty($_POST['update_details']))
                    {
                        echo 'resub';
                        die();
                    }

                    $update_details = base64_encode($_POST['update_details']);
                    $client = getSession('client');
                    $owner = $my_username;

                    // save
                    $pdo->exec("insert into cli_mgmt_sys_response (details, client, owner)
values ('$update_details','$client','$owner');");
                    echo 'done';


                }
            }
        }
    }