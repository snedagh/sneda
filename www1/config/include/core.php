<?php


    $hos = $_SERVER['HTTP_HOST'];


    if($hos === 'www1.sneda.dev')
    {
        $app = 'http://www1.sneda.dev';
        $host = "localhost";
        $user ="root";
        $password = "Sunderland@411";
        $db = "smdesk";
        $sql_db = 'UAT_RETAIL_INV';
        $sql_rest = 'SMSEXPV17_REST';

        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    }
    else
    {
        $app = 'http://www1.sneda.gh';
        $host = "192.168.2.3";
        $user ="anton";
        $password = "258963";
        $db = "smdesk";
        $sql_db = 'SMSEXPV17';

        error_reporting(E_ERROR | E_PARSE);


    }





    //require 'Database.php';
    require 'session.php';
    require 'tools.php';
    require 'functions.php';
    require 'fpdf/fpdf.php';

    $tools = new tools();
    //$db = new db(true);

    $tool = getSession('tool');







    if($login == 'yes')
    {


        if(!isset($_SESSION['view']))
        {
            setSession('view','all');
        }

        $view = getSession('view');

        if (!isset($_SESSION['app']))
        {
            $tools->setSession('app','all');
        }
        $app = $tools->getSession('app');
        $location = $tools->getSession('location');
        $user_id = $tools->getSession('user_id');
        $my = fetchFunc("users" , "`id` = $user_id" , $pdo);



        $my_username = $my['username'];
        $my_extension = $my['dp'];
        $my_apps = 'user_'.$user_id.'_apps';




        if ($location == 'home')
        {


            if ($app == 'all')
            {

                $videos = $pdo->query("SELECT * FROM `videos`");
                $videos_count = $videos->rowCount();

            } elseif ($app == 'single')
            {
                $v_id = getSession('video');
                $vid = $pdo->query("SELECT * FROM `videos`");
                $vid_count = $vid->rowCount();

                $fp = 'no';
                if($vid_count > 0)
                {

                    $video = $vid->fetch(PDO::FETCH_ASSOC);
                    $file = $video['file_name'];
                    $file_path = "assets/videos/$file";

                    if(is_file($file_path))
                    {
                        $fp = 'yes';
                    }
                }





            }





        }
        elseif ($location === 'app_store')
        {
            //get apps

            if($view == 'all')
            {
                $apps_sql = "SELECT * FROM `appstore` WHERE `type` = 'store' ORDER BY `name`";
                $apps_stmt = $pdo->prepare($apps_sql);
                $apps_stmt->execute();
                $apps_count = $apps_stmt->rowCount();
            } elseif ($view == 'single')
            {
                $uni = getSession('uni');
                if(rowsOf('appstore',"`uni` = '$uni'",$pdo) > 0)
                {
                    $app_exist = 'yes';
                } else {
                    $app_exist = 'no';
                }

                if($app_exist == 'yes')
                {
                    $ap = fetchFunc('appstore',"`uni` = '$uni'",$pdo);
                }

            }
        }

        elseif ($location == 'admin_panel')
        {





            if($tool === 'task_manager')
            {
                if(!isset($_SESSION['new_stage']))
                {
                    setSession('new_stage',0);
                }
                if(!isset($_SESSION['view']))
                {
                    setSession('view','all');
                }

                $view = getSession('view');

                if($view == 'all')
                {
                    $task_sql = "select * from task order by `status` asc, `date_added` desc";
                    $task_stmt = $pdo->prepare($task_sql);
                    $task_stmt->execute();
                    $t_count = $task_stmt->rowCount();
                }

                if($view == 'single')
                {
                    $task_id = getSession('task');
                    $task = fetchFunc('task',"`id` = '$task_id'",$pdo);
                    $tracking = $pdo->query("select * from task_tracking where `issue` = '$task_id'");


                }

                $new_stage = getSession('new_stage');


            }

            elseif($tool === 'user_management')
            {
                if(!isset($_SESSION['new_stage']))
                {
                    setSession('new_stage',0);
                }
                if(!isset($_SESSION['view']))
                {
                    setSession('view','all');
                }

                $view = getSession('view');




                $new_stage = getSession('new_stage');

                if($view == 'all')
                {
                    $user_sql = "SELECT * FROM `users`";
                    $user_stmt = $pdo->prepare($user_sql);
                    $user_stmt->execute();
                    $u_count = $user_stmt->rowCount();
                }
            }
            elseif ($tool == 'stock')
            {

                if(!isset($_SESSION['view']))
                {
                    setSession('view','all');
                }

                $view = getSession('view');

                if($view == 'all')
                {
                    $stmt = $pdo->query('SELECT * FROM `invoice` order by `idinvoice` DESC');
                } elseif ($view == 'single')
                {
                    $invoice = getSession('invoice');
                    $stmt = $pdo->query("SELECT * FROM `stock_in` WHERE `invoice` = '$invoice'");
                    $total = 0;
                    $inv_inv = fetchFunc('invoice',"`inv_num` = '$invoice'",$pdo);
                    $company = $inv_inv['supp'];
                    $tax_rate = $inv_inv['tax_rate'];

                }
            }
            elseif ($tool == 'applications')
            {
                $apps = $pdo->query("SELECT * FROM `appstore`");
            }
        }

        elseif ($location == 'videos')
        {
            if($app == 'all')
            {
                $videos = $pdo->query("SELECT * FROM `videos`");
                $videos_count = $videos->rowCount();
            }
        }
        elseif ($location === 'taskmgr' || $location === 'task_manager')
        {
            if(!isset($_SESSION['new_stage']))
            {
                setSession('new_stage',0);
            }
            if(!isset($_SESSION['view']))
            {
                setSession('view','all');
            }

            $view = getSession('view');

            if($view == 'all')
            {
                $task_sql = "SELECT * FROM `task` where `owner` = '$user_id' order by `status` asc";
                $task_stmt = $pdo->prepare($task_sql);
                $task_stmt->execute();
                $t_count = $task_stmt->rowCount();
            }

            if($view == 'single')
            {
                $task_id = getSession('task');
                $task = fetchFunc('task',"`id` = '$task_id'",$pdo);
                $tracking = $pdo->query("select * from task_tracking where `issue` = '$task_id'");


            }

            $new_stage = getSession('new_stage');
        }
    }






