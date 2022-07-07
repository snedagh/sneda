<?php


    require '../include/core.php';






    if($_SERVER['REQUEST_METHOD'] == 'POST') // submitting a form with post method
    {



        if(isset($_POST['setup'])) // setup
        {
            $first_name = post('first_name');
            $last_name = post('last_name');
            $gender = post('gender');
            $phone = post('phone');
            $email = post('email');
            $password = post('password');
            $comf_password = post('comf_password');
            $user = getSession('user_id');



            // validate gender
            if($gender == '0')
            {
                echo "GENWRONG";
                die();
            }

            // validate password
            if($password != $comf_password)
            {
                echo "PASWRONG";
                die();
            }




            // enc password
            $enc_password = password_hash($password, PASSWORD_DEFAULT);

            $set = "`first_name` = '$first_name' , `last_name` = '$last_name' , `gender` = '$gender' , `phone` = '$phone' ,
         `email` = '$email' ,`token` = '$enc_password', `first_login` = '0'";

            // update
            if(updateRecord('users',$set,"`id` = '$user'",$pdo))
            {

                // destroy session
                $_SESSION = array();

                // Destroy the session.
                session_destroy();

                echo "USERUPDATED";
                // Redirect to login page
                $hos = $_SERVER['HTTP_HOST'];
                header("location:http://$hos");

            }
            else
            {
                echo  "NO UPDATE";
            }


        }




        elseif($location == 'admin_panel') // if we are in admin panel
        {



            if($tool === 'user_management') // managing users
            {
                if(isset($_POST['username'])) // addning user
                {
                    $username = form_data('POST','username');
                    $ual = form_data('POST','ual');

                    $password = setSession('password',rand(1846738,9846274));
                    $token = password_hash('1111111',PASSWORD_DEFAULT);

                    // check if user exis in database
                    if(rowsOf('users', "`username` = '$username'",$pdo) > 0)
                    {
                        echo 'USEXT';
                    }
                    else
                    {
                        // add user
                        $cols = "`username`,`token`,`ual`,`dp`";
                        $vals = "'$username', '$token','$ual','default'";
                        if(insertIntoDatabase("users","$cols","$vals",$pdo))
                        {

                            // create user table
                            $user_id_x = fetchFunc('users',"`username` = '$username'",$pdo)['id'];
                            $table_name = "user_$user_id_x"."_apps";

                            $sql = "CREATE TABLE `$table_name` (
                                  `id` int NOT NULL AUTO_INCREMENT,
                                  `app` text,
                                  `date_installed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                  `status` int NOT NULL DEFAULT '0',
                                  PRIMARY KEY (`id`)
                                ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
                                ";

                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();

                            echo 'done';
                        }
                    }

                }
                elseif (isset($_POST['level'])) // adding user access level
                {
                    $level = form_data('POST','level');
                    $desc = form_data('POST','ual');

                    // check if user exis in database
                    if(rowsOf('users_ual', "`description` = '$desc'",$pdo) > 0)
                    {
                        echo 'USEXT';
                    }
                    else
                    {
                        // add user
                        $cols = "`description`,`level`";
                        $vals = "'$desc', '$level'";
                        if(insertIntoDatabase("users_ual","$cols","$vals",$pdo))
                        {

                            echo 'done';
                        }
                    }

                }
            }

            elseif ($tool == 'stock')
            {
                if(isset($_POST['new_bill']))
                {
                    $supplier = post('supplier');
                    $invoice = post('invoice');
                    $items = post('items');

//                    foreach(preg_split("/((\r?\n)|(\r\n?))/", $items) as $line){
//                        echo "LINE : $line \n";
//                    }

                    // insert add invoice into db into db
                    if(rowsOf('invoice',"`supp` = '$supplier' AND `inv_num` = '$invoice'",$pdo) < 1)
                    {
                        // insert into db
                        $col = "`supp`,`inv_num`";
                        $val = "'$supplier','$invoice'";

                        insertIntoDatabase('invoice',"$col","$val",$pdo);

                    }



                    // check items
                    foreach (preg_split("/((\r?\n)|(\r\n?))/", $items) as $item)
                    {
                        $item_exp = explode(':',$item);
                        $desc = get_array($item_exp,0);
                        $qty = get_array($item_exp,1);
                        $price = get_array($item_exp,2);

                        // insert into database
                        $col = '`invoice`,`item`,`quantity`,`price`';
                        $val = "'$invoice','$desc','$qty','$price'";

                        if(rowsOf('stock_in',"`invoice` = '$invoice' AND `item` = '$desc' AND `quantity` = '$qty'",$pdo) < 1)
                        {
                            insertIntoDatabase('stock_in',"$col","$val",$pdo);
                        }

                    }



                }
            }
        }

        elseif ($location == 'taskmgr') // task manager
        {

            $new_stage = getSession('new_stage');
            // new stage 0 = getting task details
            // new stage 1 means = adding media if any
            // new stage 2 = confirm report

            if($new_stage == 0)
            {
                setSession('owner', form_data('POST','owner'));
                setSession('title', form_data('POST','title'));
                setSession('domain', form_data('POST','domain'));
                setSession('details', form_data('POST','details'));
                setSession('relative', form_data('POST','relative'));
                setSession('assigned_to',post('assigned_to'));

                if(getSession('domain') == 0)
                {
                    echo 'WRONGD';
                }
                elseif (getSession('relative') == 0)
                {
                    echo 'WRONGR';
                }
                else
                {
                    setSession('new_stage',1);
                    echo '1PASS';
                }


            }
            elseif ($new_stage == 1)
            {



                if(!empty(array_filter($_FILES['file']['name'])))
                {
                    $_SESSION['tmp_files'] = '';

                    foreach ($_FILES['file']['tmp_name'] as $key => $value)
                    {

                        $file_tmpname = $_FILES['file']['tmp_name'][$key];
                        $file_name = htmlentities($_FILES['file']['name'][$key]);
                        $file_size = $_FILES['file']['size'][$key];
                        $act_file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                        $file_ext = strtolower($act_file_ext);

                        $new_name = md5($file_name.getSession('title').getSession('details')).".$act_file_ext";
                        $tmp_files = $_SESSION['tmp_files'];
                        $_SESSION['tmp_files'] = $tmp_files ."$new_name/";

                        if(move_uploaded_file($file_tmpname,"../../assets/storage/admin_panel/issues/$new_name"))
                        {


                        }

                    }



                }
                // prepare to insert into database
                // setSession('owner', form_data('POST','owner'));
                // setSession('title', form_data('POST','title'));
                // setSession('domain', form_data('POST','domain'));
                // setSession('details', form_data('POST','details'));
                // setSession('relative', form_data('POST','relative'));
                $owner = getSession('owner');
                $title = getSession('title');
                $domain = getSession('domain');
                $details = getSession('details');
                $relative = getSession('relative');
                $files = getSession('tmp_files');
                $id = md5($owner.$title.$domain.$relative.$files);
                $assigned_to = getSession('assigned_to');

                $col = "`id`,`title`,`owner`,`domain`,`details`,`relative`,`files`,`assigned_to`";
                $val = "'$id','$title','$owner','$domain',".'"'.$details.'"'.",'$relative','$files','$assigned_to'";

                if(insertIntoDatabase('task',$col,$val,$pdo))
                {

                    // insert into task tracking
                    $t_col = "`title`,`details`,`issue`";
                    $t_val = "'Assignment','Task has been assigned','$id'";

                    if(insertIntoDatabase('task_tracking',"$t_col","$t_val",$pdo))
                    {
                        setSession('new_stage',0);
                        // unset sessions
                        unset(
                            $_SESSION['owner'],
                            $_SESSION['title'],
                            $_SESSION['details'],
                            $_SESSION['relative'],
                            $_SESSION['tmp_files']
                        );
                        echo "2PASS";
                    }


                }
                else
                {
                    echo "2FAIL";
                }
            }
        }

        if(isset($_POST['function']))
        {
            $func = $_POST['function'];
            if($func == 'upload_video') // upload video
            {
                // check if there is a file
                if(isset($_FILES['video_file']))
                {
                    // get file details into variable
                    $video_file = $_FILES['video_file'];
                    $name = $video_file['name'];
                    $tmp_name = $video_file['tmp_name'];
                    $size = $video_file['size'];
                    $type = $video_file['type'];
                    $ext =pathinfo($name,PATHINFO_EXTENSION);
                    if($type == 'video/mp4' && $ext == 'mp4')
                    {
                        // upload upload file
                        $file_id = md5($tmp_name.$name.date('y-m-d h:m:s'));
                        $title = post('title');
                        $description = post('description');
                        $owner = $my['username'];
                        $file_name = $file_id.".$ext";

                        // check if title and then description exist
                        if(rowsOf(
                            'videos',
                            "`title` = '$title' AND `description` = '$description' AND `owner` = '$owner' AND `file_size` = '$size'",
                            $pdo) < 1)
                        {
                            if(move_uploaded_file($tmp_name,"../../assets/videos/$file_name"))
                            {
                                // insert into database
                                $val = "'$file_id','$title','$description','$owner','$file_name','$size'";
                                $cols = "`spec_id`,`title`,`description`,`owner`,`file_name`,`file_size`";

                                try {
                                    insertIntoDatabase('videos',"$cols","$val",$pdo);
                                    echo 'done';
                                } catch (\Throwable $err)
                                {
                                    echo $err->getMessage();
                                }

                            } else
                            {
                                echo 'could not upload file';
                            }
                        }




                    } else {
                        // dont
                    }

                } else
                {
                    echo 'no';
                }
            }

            elseif ($func == 'new_task') // new task
            {
                $new_stage = getSession('new_stage');
                // new stage 0 = getting task details
                // new stage 1 means = adding media if any
                // new stage 2 = confirm report

                if($new_stage == 0)
                {
                    setSession('owner', form_data('POST','owner'));
                    setSession('title', form_data('POST','title'));
                    setSession('domain', form_data('POST','domain'));
                    setSession('details', form_data('POST','details'));
                    setSession('relative', form_data('POST','relative'));
                    setSession('assigned_to',post('assigned_to'));

                    if(getSession('domain') == 0)
                    {
                        echo 'WRONGD';
                    }
                    elseif (getSession('relative') == 0)
                    {
                        echo 'WRONGR';
                    }
                    else
                    {
                        $owner = getSession('owner');
                        $title = getSession('title');
                        $domain = getSession('domain');
                        $details = getSession('details');
                        $relative = getSession('relative');
                        $files = getSession('tmp_files');
                        $id = md5($owner.$title.$domain.$relative.$files);
                        $assigned_to = getSession('assigned_to');
                        if(isset($_POST['device']))
                        {
                            $device = post('device');
                        }
                        else
                        {
                            $device = 'unknown';
                        }

                        $col = "`id`,`title`,`owner`,`domain`,`details`,`relative`,`files`,`assigned_to`,`device`";
                        $val = "'$id','$title','$owner','$domain',".'"'.$details.'"'.",'$relative','$files','$assigned_to','$device'";

                        if(insertIntoDatabase('task',$col,$val,$pdo))
                        {

                            // insert into task tracking
                            $t_col = "`title`,`details`,`issue`";
                            $t_val = "'Assignment','Task has been assigned','$id'";

                            if(insertIntoDatabase('task_tracking',"$t_col","$t_val",$pdo))
                            {
                                setSession('new_stage',0);
                                // unset sessions
                                unset(
                                    $_SESSION['owner'],
                                    $_SESSION['title'],
                                    $_SESSION['details'],
                                    $_SESSION['relative'],
                                    $_SESSION['tmp_files']
                                );
                                echo "done";
                            }


                        }
                        else
                        {
                            echo "2FAIL";
                        }
                    }


                }
                elseif ($new_stage == 1)
                {



                    if(!empty(array_filter($_FILES['file']['name'])))
                    {
                        $_SESSION['tmp_files'] = '';

                        foreach ($_FILES['file']['tmp_name'] as $key => $value)
                        {

                            $file_tmpname = $_FILES['file']['tmp_name'][$key];
                            $file_name = htmlentities($_FILES['file']['name'][$key]);
                            $file_size = $_FILES['file']['size'][$key];
                            $act_file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                            $file_ext = strtolower($act_file_ext);

                            $new_name = md5($file_name.getSession('title').getSession('details')).".$act_file_ext";
                            $tmp_files = $_SESSION['tmp_files'];
                            $_SESSION['tmp_files'] = $tmp_files ."$new_name/";

                            if(move_uploaded_file($file_tmpname,"../../assets/storage/admin_panel/issues/$new_name"))
                            {


                            }

                        }



                    }
                    // prepare to insert into database
                    // setSession('owner', form_data('POST','owner'));
                    // setSession('title', form_data('POST','title'));
                    // setSession('domain', form_data('POST','domain'));
                    // setSession('details', form_data('POST','details'));
                    // setSession('relative', form_data('POST','relative'));
                    $owner = getSession('owner');
                    $title = getSession('title');
                    $domain = getSession('domain');
                    $details = getSession('details');
                    $relative = getSession('relative');
                    $files = getSession('tmp_files');
                    $id = md5($owner.$title.$domain.$relative.$files);
                    $assigned_to = getSession('assigned_to');

                    $col = "`id`,`title`,`owner`,`domain`,`details`,`relative`,`files`,`assigned_to`";
                    $val = "'$id','$title','$user_id','$domain',".'"'.$details.'"'.",'$relative','$files','$assigned_to'";

                    if(insertIntoDatabase('task',$col,$val,$pdo))
                    {

                        // insert into task tracking
                        $t_col = "`title`,`details`,`issue`";
                        $t_val = "'Assignment','Task has been assigned','$id'";

                        if(insertIntoDatabase('task_tracking',"$t_col","$t_val",$pdo))
                        {
                            setSession('new_stage',0);
                            // unset sessions
                            unset(
                                $_SESSION['owner'],
                                $_SESSION['title'],
                                $_SESSION['details'],
                                $_SESSION['relative'],
                                $_SESSION['tmp_files']
                            );
                            echo "2PASS";
                        }


                    }
                    else
                    {
                        echo "2FAIL";
                    }
                }
            }

            elseif ($func === 'new_workstation') // adding workstation
            {

                $user = post('owner');
                $department = post('department');
                $cpu = post('cpu');
                $cpu_type = post('cpu_type');
                $model = post('cpu_model');
                $serial = post('serial');
                $hdd_size = post('hdd_size');
                $ram_size = post('ram_size');
                $ram_type = post('ram_type');
                $processor = post('processor');
                $mon_man = post('mon_man');
                $mon_model = post('mon_model');
                $mon_serial = post('mon_serial');
                $mon_dimension = post('mon_dimension');
                $ups = post('ups');
                $printer = post('printer');
                $mouse = post('mouse');
                $keyboard = post('keyboard');
                $ip = GetIP();
                $os = post('os');

                $uni = md5($user.$cpu.$cpu_type.$department.$ip);

                // check empty pleases
                if(empty($department) || empty($cpu) || empty($cpu_type) || empty($mon_man))
                {
                    echo 'empty_form';
                    die();
                }


                // insert into users
                $insert_query = "INSERT INTO `devices`
                (`uni`,`user`,`department`,`cpu_type`,`cpu`,`cpu_model`,`cpu_serial`,`hdd_size`,`ram_size`,`ram_type`,`processor`,`monitor_brand`,`monitor_model`,`monitor_serial`,`monitor_dimension`,`phe_ups`,`phe_printer`,`mouse`,`keyboard`,`ip_addr`,`os`)VALUES
                ('$uni','$user','$department','$cpu_type','$cpu','$model','$serial','$hdd_size','$ram_size','$ram_type','$processor','$mon_man','$mon_model','$mon_serial','$mon_dimension','$ups','$printer','$mouse','$keyboard','$ip','$os');";



                try {
                    $pdo->exec($insert_query);
                    setSession('todo','view_workstation');
                    unset($_SESSION['stage']);
                    echo 'done';
                } catch (PDOException $error)
                {
                    echo $error->getMessage();
                }



            }

            elseif ($func === 'new_none_computing') // adding new new_none_computing
            {
                $description = post('description');
                $branch = post('branch');
                $department = post('department');
                $manufacturer = post('manufacturer');
                $serial = post('serial');
                $model = post('model');
                $details = post('details');
                $type = "n_ws";
                $uni = md5($department.$description.$branch.date("Y-M-D H:m:s"));

                $query = "INSERT INTO `devices` (`uni`,`department`,`branch`,`cpu`,`cpu_model`,`cpu_serial`,`details`,`type`,`user`,`hdd_size`,`ram_size`) values 
                            ('$uni','$department','$branch','$description','$model','$serial','$details','$type','$username',0,0)";

                try {
                    $pdo->exec($query);

                    echo "2PASS";
                } catch (PDOException $exception)
                {
                    echo $exception->getMessage();
                }


            }

            elseif ($func === 'update_workstation') // update workstation
            {
                // get post details
                $user = post('user');
                $email = post('email');
                $os = post('os');
                $ip = post('ip_addr');
                $department = post('department');
                $cpu = post('cpu_type');
                $system_type = post('system_type');
                $cpu_model = post('system_module');
                $cpu_serial = post('system_serial');
                $hdd_size = post('hdd_size');
                $ram_size = post('ram_size');
                $ram_type = post('ram_type');
                $processor = post('processor');
                $monitor_brand = post('monitor_brand');
                $monitor_model = post('monitor_model');
                $monitor_serial = post('monitor_serial');
                $monitor_dimension = post('monitor_dimension');
                $ups = post('phe_ups');
                $printer = post('phe_printer');
                $mouse = post('mouse');
                $keyboard = post('keyboard');
                $uni = getSession('device');



                // check empty pleases
                if(empty($department) || empty($cpu) || empty($system_type) || empty($monitor_brand))
                {
                    echo 'empty_form';
                    die();
                }


                // update
                $update_query = "UPDATE `devices` SET 
                                    `user` = '$user',`department` = '$department',
                                    `cpu_type` = '$system_type',`cpu` = '$cpu',`cpu_model` = '$cpu_model',
                                    `cpu_serial` = '$cpu_serial',`hdd_size` = '$hdd_size',
                                    `ram_size` = '$ram_size',`ram_type` = '$ram_type',
                                    `processor` = '$processor',`monitor_brand` = '$monitor_brand',
                                    `monitor_model` = '$monitor_model',`monitor_serial` = '$monitor_serial',
                                    `monitor_dimension` = '$monitor_dimension',
                                    `phe_ups` = '$ups',`phe_printer` = '$printer',
                                    `mouse` = '$mouse',`keyboard` = '$keyboard',`email` = '$email' 
                                    WHERE `uni` = '$uni';";

                try {
                    $pdo->exec($update_query);
                    setSession('view','single');
                    echo 'done';
                } catch (PDOException $error)
                {
                    echo $update_query;
                    echo $error->getMessage();
                }



            }

            elseif ($func === 'msinfo32') // create pdf
            {
                $dev = $_SESSION['device'];
                $dev_info = fetchFunc('devices',"`uni` = '$dev'",$pdo);
                $dep = $dev_info['department'];
                $department = fetchFunc('departments',"`id` = '$dep'",$pdo)['desc'];
                $man = $dev_info['cpu'];
                $manx = fetchFunc('brand_cpu',"`id`='$man'",$pdo)['desc'];
                $mon_man = $dev_info['monitor_brand'];
                $monxx = fetchFunc('brand_mon',"`id`='$man'",$pdo)['desc'];

                $pdf = new FPDF('P','mm','A4');
                $pdf->AddPage();
                $pdf->SetFont('Times','B',12);
                $pdf->SetLeftMargin(15);
                $pdf->SetRightMargin(15);

                // heading
                $pdf->Cell('30',5,'Spec','1',0,'L');
                $pdf->Cell('150',5,'Value','1',1,'L');

                // body
                $pdf->SetFont('Times','',10);

                //user
                $pdf->Cell('30',5,'User','1',0,'L');
                $pdf->Cell('150',5,$dev_info['user'],'1',1,'L');
                //os
                $pdf->Cell('30',5,'OS','1',0,'L');
                $pdf->Cell('150',5,$dev_info['os'],'1',1,'L');
                //ip
                $pdf->Cell('30',5,'Last known IP','1',0,'L');
                $pdf->Cell('150',5,$dev_info['ip_addr'],'1',1,'L');
                //department
                $pdf->Cell('30',5,'Department','1',0,'L');
                $pdf->Cell('150',5,$department,'1',1,'L');
                //sys man
                $pdf->Cell('30',5,'System Manufac.','1',0,'L');
                $pdf->Cell('150',5,$manx,'1',1,'L');
                //sys type
                $pdf->Cell('30',5,'System Type','1',0,'L');
                $pdf->Cell('150',5,$dev_info['cpu_type'],'1',1,'L');
                //sys model
                $pdf->Cell('30',5,'System Model','1',0,'L');
                $pdf->Cell('150',5,$dev_info['cpu_model'],'1',1,'L');
                //sys sku
                $pdf->Cell('30',5,'System SKU','1',0,'L');
                $pdf->Cell('150',5,$dev_info['cpu_serial'],'1',1,'L');
                //hdd size
                $pdf->Cell('30',5,'HDD Size','1',0,'L');
                $pdf->Cell('150',5,$dev_info['hdd_size'],'1',1,'L');
                //ram size
                $pdf->Cell('30',5,'RAM Size','1',0,'L');
                $pdf->Cell('150',5,$dev_info['ram_size'],'1',1,'L');
                //ram type
                $pdf->Cell('30',5,'RAM Type','1',0,'L');
                $pdf->Cell('150',5,$dev_info['ram_type'],'1',1,'L');
                //processor
                $pdf->Cell('30',5,'Processor','1',0,'L');
                $pdf->Cell('150',5,$dev_info['processor'],'1',1,'L');
                //monitor
                $pdf->Cell('30',5,'Monitor','1',0,'L');
                $pdf->Cell('150',5,$monxx,'1',1,'L');
                //monitor model
                $pdf->Cell('30',5,'Mon. Model','1',0,'L');
                $pdf->Cell('150',5,$dev_info['monitor_model'],'1',1,'L');
                //monitor serial
                $pdf->Cell('30',5,'Mon. Serial','1',0,'L');
                $pdf->Cell('150',5,$dev_info['monitor_serial'],'1',1,'L');
                //dimension
                $pdf->Cell('30',5,'Mon. Dimension','1',0,'L');
                $pdf->Cell('150',5,$dev_info['monitor_dimension'],'1',1,'L');
                //keyboard
                $pdf->Cell('30',5,'Keyboard','1',0,'L');
                $pdf->Cell('150',5,$dev_info['keyboard'],'1',1,'L');
                //mouse
                $pdf->Cell('30',5,'Mouse','1',0,'L');
                $pdf->Cell('150',5,$dev_info['mouse'],'1',1,'L');
                //ups
                $pdf->Cell('30',5,'UPS','1',0,'L');
                $pdf->Cell('150',5,$dev_info['phe_ups'],'1',1,'L');
                //printer
                $pdf->Cell('30',5,'Printer','1',0,'L');
                $pdf->Cell('150',5,$dev_info['phe_printer'],'1',1,'L');

                $pdf->Output("/var/www/html/assets/devices/$dev.pdf",'F');
                echo 'done';
            }

            elseif ($func === 'get_session') // get session
            {
                $var = post('variable');
                echo getSession($var);
            }

            elseif ($func === 'change_device_part') // change device part
            {
                $device = post('device');
                $part = post('part');
                $new = post('new');
                $change_reason = post('change_reason');
                $col = $part;


                // change device
                try {
                    $pdo->exec("UPDATE `devices` SET `$part` = '$new' WHERE `uni` = '$device' ");

                    // insert into log
                    $insert = "INSERT INTO `device_maint_log` (`device`,`part`,`new`,`reason`) values ('$device','$part','$new','$change_reason')";
                    if($pdo->exec($insert))
                    {
                        echo 'done';
                    }
                    else{
                        error('could not add log');
                    }


                } catch (PDOException $e)
                {
                    echo $e->getMessage();
                    error('could no change');
                }
            }

            elseif ($func === 'new_po')
            {
                $last_serial = fetchFunc('doc_serials',"`doc` = 'PO' AND `season` = '$this_year'",$pdo)['last_doc'];
                $entry_no = "PO" . $last_serial + 1;
                $supplier = post('supplier');
                $supplier_contact = post('supplier_contact');
                $loc = post('loc');
                $remarks  = post('remarks');
                $gross = post('gross');
                $discount = post('discount');
                $net = post('net');





                foreach ($_POST['descr'] as $key => $value)
                {
                    $line_no = $key;
                    $descr = $value;
                    $unit_cost = $_POST['unit_cost'][$key];
                    $qty = $_POST['qty'][$key];
                    $total_cost = $_POST['total_cost'][$key];

                    // insert in po trans
                    $pdo->exec("INSERT INTO po_trans(entry_no, line, descr, unit_cost, qty, total_cost, created_by) VALUES ('$entry_no','$line_no','$descr','$unit_cost','$qty','$total_cost','$my_username')");

                }
                // insert into po header
                $pdo->exec("INSERT INTO po_hd (entry_no, supplier, supplier_contact, loc, remarks, gross, discount, net, created_by) VALUES ('$entry_no','$supplier','$supplier_contact','$loc','$remarks','$gross','$discount','$net','$my_username')");
                $pdo->exec("UPDATE doc_serials SET last_doc = last_doc + 1 where doc  = 'PO'");
                setSession('view','view');
                echo 'done';
                // update last serial

            }

            else{
                print_r($_POST);
            }
        }

    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET')
    {

        if(isset($_GET['db_sync']) && isset($_GET['db']) && !empty($_GET['db']))
        {
            $db = get('db');


            if($db == 'loyalty')
            {
                // count not synced customers
                $not_synced_customers = rowsOf('customers',"`synced`= 0",$pdo);

                if($not_synced_customers > 0)
                {
                    //echo $not_synced_customers . " Customers Found \n";
                    // TODO SYNC CUSTOMERS
                    $customer_stmt = $pdo->prepare('SELECT * FROM `customers` WHERE `synced` = 0 LIMIT 1');
                    $customer_stmt->execute();

                    foreach ($customer_stmt as $customer)
                    {
                        //get customer details
                        $id = $customer['id'];
                        $first_name = $customer['first_name'] ;
                        $last_name = $customer['last_name'];
                        $email = $customer['email'];
                        $phone = $customer['phone'];
                        $card = $customer['card_no'];
                        $gender = $customer['gender'];
                        $owner = $customer['owner'];
                        $date_created = $customer['date_created'];
                        $labor_card = $phone;

                        // check if constomer already exist
                        $validate_query = $conn->query("SELECT COUNT(card_No) FROM LoyaltyCustomer WHERE card_No = '$card'");
                        $validate_query->execute();


                        $count = $validate_query->fetchColumn();

                        if($count == '0') // if customer does not exist
                        {
                            // TODO add record
                            // get last card
                            $last_customer_code = $conn->prepare('SELECT TOP 1 * from LoyaltyCustomer ORDER BY cust_Code DESC');
                            $last_customer_code->execute();
                            $result = $last_customer_code->fetch(PDO::FETCH_ASSOC);

                            if(isset($result['cust_Code']))
                            {
                                $last_code = $result['cust_Code'];
                                $cust_code = $last_code+1;
                                $ac_cust_code = strval('0'.$cust_code);
                            }
                            else
                            {
                                $ac_cust_code = '0100000001';
                            }

                            //echo $ac_cust_code;



                            try {

                                $insert = "INSERT into LoyaltyCustomer
                                (first_name,last_name,cust_Code,Mobile,Email,card_type,Loyalty_Type1,Card_No,Card_Issue_Date,Card_Exp_Date,Start_Date,Valid_Till_Date,Last_Purchase_Date,birth_date,valid,gender,dwnld_sts,upd_flag,Cust_Total,labour_card_no) values
                                ('$first_name','$last_name','$ac_cust_code','$phone','$email','PC','L','$card',GETDATE(),DATEADD(yy,1,GETDATE()),GETDATE(),DATEADD(yy,1,GETDATE()),GETDATE(),GETDATE(),1,'$gender',1,0,0.000,$labor_card)";

                                $ins_q = $conn->prepare($insert);

                                // execute insert
                                try {
                                    $ins_q->execute();

                                    // insert into more cards
                                    try {

                                        $insert_more_cards = "
                                            INSERT INTO  loyalty_more_cards 
                                            (card_no,custcode,cardname,cust_birthday,card_issue_date,card_exp_date,start_date,upd_flag,card_type,valid,first_name,last_name,uploaded,activated,card_category) VALUES 
                                            ('$card','$ac_cust_code','POINTS CARD',GETDATE(),GETDATE(),DATEADD(yy,1,GETDATE()),GETDATE(),0,'PC',1,'$first_name','$last_name',NULL,NULL,'L')
                                            ";

                                        $ins_m_q = $conn->prepare($insert_more_cards);
                                        $ins_m_q->execute();
                                        //echo "$first_name ( $card ) synced \n";

                                    } catch (Throwable $e)
                                    {
                                        echo "Could Not Add More \n";
                                        echo $e->getMessage();
                                    }


                                    // update card as synced
                                    if(updateRecord('customers', "synced = 1", "`card_no` = '$card'",$pdo))
                                    {
                                        echo "info%% $card synced";
                                    }
                                    else
                                    {
                                        $_SESSION['error'] =+1;
                                    }

                                } catch (Throwable $err)
                                {
                                    echo $err->getMessage() . "\n";
                                }

                            } catch (Throwable $err)
                            {
                                echo $err->getMessage();
                            }

                            // mark card


                        }
                        else
                        {
                            echo "info%%$card Already synced";
                            // TODO Update record as synced
                            // update card as synced
                            if(updateRecord('customers', "synced = 1", "`card_no` = '$card'",$pdo))
                            {

                            }
                            else
                            {
                                $_SESSION['error'] =+1;
                            }

                        }



                    }
                }
                else
                {

                    echo "info%%Nothing to process";
                }
            }

            

        }
    }
    
    
//    header("Location:".$_SERVER['HTTP_REFERER']);
