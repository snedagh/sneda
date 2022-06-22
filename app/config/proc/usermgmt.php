<?php
    include '../include/core.php';


    if (isset($_SESSION['usermgmt_main']))
    {
        $main = $_SESSION['usermgmt_main'];
    }

    if (isset($_SESSION['usermgmt_main_sub']))
    {
        $sub = $_SESSION['usermgmt_main_sub'];
    }



    ### USER LOGIN ###
    if (isset($_POST['login']))
    {

        $stage = $_SESSION['login'];
        if ($stage === 'username')
        {
            $username = htmlentities($_POST['username']);

            //validate username

            $userExist = rowsOf("users" , "`username` = '$username'" , $pdo);

            if ($userExist > 0)
            {
                $userDetails = fetchFunc("users" , "`username` = '$username'" , $pdo);
                $loginCount = $userDetails['login_count'];
                $lockTime = $userDetails['locked_time'];
                $userId = $userDetails['id'];

                if ($loginCount >= 3)
                {

                    echo $current_time . '<br>';
                    echo $lockTime . '<br>';
                    $time_diff =  dateDifference($current_time , $lockTime, '%i');
                    $remainingTime = 30 - $time_diff;
                    if ($remainingTime <= 0)
                    {
                        //update count
                        updateRecord("users" , "`login_count` = 0" , "`id` = $userId" , $pdo);
                        //update date
                        updateRecord("users" , "`locked_time` = NULL" , "`id` = $userId" , $pdo);

                        $tools->setSession('username','password');


                    }
                    else
                    {
                        $_SESSION['username_err'] = "Account locked, try in <kbd>" . $remainingTime . "</kbd> Minutes time";
                    }
                }
                else
                {
                    $_SESSION['username'] = $username;
                    $_SESSION['login'] = 'password';
                }


            }
            else
            {
                $_SESSION['username_err'] = "Account does not exist";
            }
            $tools->back();

        }
        elseif ($stage === 'password')
        {
            $password = htmlentities($_POST['password']);
            $user = $_SESSION['username'];

            $userDetail = fetchFunc("users" , "`username` = '$user'" , $pdo);
            $token = $userDetail['token'];
            $loginCount = $userDetail['login_count'] + 1;
            $userId = $userDetail['id'];

            echo $password;

            //validate
            if (password_verify($password , $token))
            {

                echo 'Login';
                //update count
                updateRecord("users" , "`login_count` = 0" , "`id` = $userId" , $pdo);
                //update date
                updateRecord("users" , "`locked_time` = NULL" , "`id` = $userId" , $pdo);

                // check if first login
                echo $first_login = fetchFunc('users',"`id` = '$userId'",$pdo)['first_login'];

                if($first_login == 1)
                {
                    $l = "$app/setup/";
                    setSession('user_id', $userId);
                    header("Location:$l");

                }
                else
                {
                    $l = "http://www1.sneda.gh";
                    //set sessions
                    $_SESSION['authenticated'] = true;
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['username'] = $userDetail['username'];
                    $_SESSION['location'] = 'home';
                    header("Location:".$_SERVER['HTTP_REFERER']);
                }
                echo $l;

                die();




            }
            else
            {
                //update user login count

                if ($userDetail['login_count'] >= 3)
                {
                    updateRecord("users" , "`locked_time` = current_timestamp()" , "`id` = $userId" , $pdo);
                    $_SESSION['username_err'] = "Account has been locked, please try 30 minutes time";
                    $_SESSION['login'] = 'username';

                }
                else
                {
                    updateRecord("users" , "`login_count` = $loginCount" , "`id` = $userId" , $pdo);
                    $_SESSION['password_err'] = "Wrong password combination <kbd>" . 3-$loginCount. "</kbd> attempts remaining)";
                }

            }
            gb();
        }
    }


    ### UPDATE USER ACCOUNT
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUserAccount']))
    {
        $firstName = htmlentities($_POST['firstName']);
        $lastName = htmlentities($_POST['lastName']);
        $phone = htmlentities($_POST['phone']);
        $email = htmlentities($_POST['email']);
        $extension = htmlentities($_POST['extension']);
        $company = htmlentities($_POST['company']);
        $branch = htmlentities($_POST['branch']);
        $position = htmlentities($_POST['position']);



        //validation
        if ($company === 'unknown')
        {
            error("Company cant be ".$company);
            gb();
            exit();
        }

        if ($branch === 'unknown')
        {
            error("Branch cant be ".$branch);
            gb();
            exit();
        }

        if ($position === 'unknown')
        {
            error("Position cant be ".$position);
            gb();
            exit();
        }

        $set = "`first_name` = '$firstName' ,`last_name` = '$lastName' , `phone` = '$phone' ,`email` = '$email' ,`extension` = '$extension' ,`company` = '$company' , `branch` = '$branch' , `phone` = '$phone' , `position` = '$position'";

        if(updateRecord("users" , $set , "`id` = $user_id" , $pdo))
        {
            echo "DB UPDATED >";
        }

        print_r($_FILES);



        //upload image
        if ((isset($_FILES['fileToUpload']['name'])))
        {
            $target_dir = "../../assets/profile_pics/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
            $newfilename = md5($my['username']) . '.' . end($temp);

            // Check if image file is a actual image or fake image
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }





            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {

                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_dir. $newfilename)) {

                    updateRecord("users" , "`dp` = '$imageFileType'", "`id` = $user_id" , $pdo);
                    echo 'File Uploaded';

                } else {
                    echo "Sorry, there was an error uploading your file.";

                }
            }
        }
        else
        {
            echo 'no file';
            die();
        }



        gb();
    }

    ######################
    ### navigating among #
    ### mains           ##
    ######################
    elseif(isset($_GET['nav']))
    {
        $level = $_GET['level'];
        if($level === 'top')
        {
            $to = $_GET['to'];
            $_SESSION['usermgmt_main'] = $to;
            header("Location:".$_SERVER['HTTP_REFERER']);
        }
        elseif ($level === 'sub')
        {
            $action = $_GET['to'];
            $_SESSION['usermgmt_main_sub'] = $action;
            gb($_SERVER['HTTP_REFERER']);
            exit();
        }
        if($level === 'usr')
        {
            $_SESSION['actusr_sub'] = $_GET['to'];
            gb('');
            exit();
        }
    }

    ################################
    ### IF NAVIGATING NEXT AND PREV#
    ################################
    elseif(isset($_GET['item_nav']))
    {
        $direction = $_GET['direction']; //diiretion

        if($direction == 'Previous') //if previous
        {
            if($main == 'groups') //if navigating to previous group
            {
                //PREVIOUS
                $sql = "SELECT * FROM `user_access_level` WHERE `id` < ? ORDER BY `id` DESC LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['current_group']]);
                $stmt_res = $stmt->fetch(PDO::FETCH_ASSOC);
                $prev_id = $stmt_res['id'];

                $_SESSION['current_group'] = $prev_id;
                gb($_SERVER['HTTP_REFERER']);
            }

            elseif($main == 'users') //if navigating to previous user
            {
                $curr_user = $_SESSION['curr_user'];
                $prev_user = isConditionTrue('users' , 'id' , '`id`<'.$curr_user.' ORDER BY `id` DESC LIMIT 1' , 'id' , $pdo);

                $_SESSION['curr_user'] = $prev_user;
                gb($_SERVER['HTTP_REFERER']);
            }
        }
        elseif($direction == 'Next') //if next
        {
            if($main == 'groups') // if navigating to next group
            {
                //get next item
                $sql = "SELECT * FROM `user_access_level` WHERE `id` > ? LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['current_group']]);
                $stmt_res = $stmt->fetch(PDO::FETCH_ASSOC);
                $next_id = $stmt_res['id'];
                $_SESSION['current_group'] = $next_id;
                gb($_SERVER['HTTP_REFERER']);
            }
            elseif($main == 'users') // if navigating to user group
            {
                $curr_user = $_SESSION['curr_user'];
                $next_user = isConditionTrue('users' , 'id' , '`id`>'.$curr_user.' LIMIT 1' , 'id' , $pdo);

                $_SESSION['curr_user'] = $next_user;
                gb($_SERVER['HTTP_REFERER']);
            }
        }
        else
        {
            echo 'no option';
        }
    }

    ##############################
    ### SUB NAV ##################
    ##############################
    elseif(isset($_GET['sub_nav']))
    {
        $_SESSION['usermgmt_main_sub'] = $_GET['sub'];
        header("Location:".$_SERVER['HTTP_REFERER']);
    }


    ##############################
    ### SAVING EDIT ##############
    ##############################
    elseif(isset($_POST['commitEdit']))
    {
        

        if($main === 'groups')// if edititng a group
        {
            $act_group = $_SESSION['current_group'];

            //get variables
            intval($grp_name = htmlentities($_POST['group_name']));
            intval($access_level = htmlentities($_POST['access_level']));


            if (updateRecord("user_access_level" , "SET `privilege` = '$access_level' , `name` = '$grp_name'" , "`id` = '$act_group'" , $pdo))
            {
                info("Access Level Updated");
                $_SESSION['usermgmt_main_sub'] = "View";
                gb($_SERVER['HTTP_REFERER']);
                exit();
            }
            else
            {
                die("COULD NOT UPDATE ACCESS LEVEL");
            }
        }

        elseif($main === 'users')// if edititng a user
        {

            $ual = htmlentities($_POST['ual']);
            $user = $_SESSION['curr_user'];

            if (updateRecord('users' , 'SET `access_level` = '.$ual , '`id` = '.$user , $pdo))
            {
                info("User Access Level Changed");
                $_SESSION['usermgmt_main_sub'] = 'View';
                gb($_SERVER['HTTP_REFERER']);
            }
            else
            {
                echo 'Could Not Update User Level';
                die();
            }

            echo "Saving user edit";
        }
        
    }

    ##############################
    ### DELETE #### ##############
    ##############################
    elseif(isset($_GET['del']))
    {

        if ($main === 'group')
        {
            die("Deleting Group");
        }
        elseif ($main === 'users')
        {
            $curr_user = $_SESSION['curr_user'];

            //delete current user
            if (deleteRow('users' , '`id` = '.$curr_user, $pdo))
            {
                $nextCount = '`id` > '.$_SESSION['curr_user'];
                $prevCount = '`id` < '.$_SESSION['curr_user'];

                //get previous
                $previous = nextPrevious('users' , $prevCount , $pdo);

                $next = nextPrevious('users' , $nextCount , $pdo);

                if ($next === true)
                {
                $curr_user = $_SESSION['curr_user'];
                    $next_user = isConditionTrue('users' , 'id' , '`id`>'.$curr_user.' LIMIT 1' , 'id' , $pdo);

                    $_SESSION['curr_user'] = $next_user;
                    $_SESSION['usermgmt_main_sub'] = 'View';
                    gb('');
                }
                elseif ($previous === true)
                {
                    $curr_user = $_SESSION['curr_user'];
                    $prev_user = isConditionTrue('users' , 'id' , '`id`<'.$curr_user.' ORDER BY `id` DESC LIMIT 1' , 'id' , $pdo);

                    $_SESSION['curr_user'] = $prev_user;
                    $_SESSION['usermgmt_main_sub'] = 'View';
                    gb('');
                }
                else
                {
                    echo 'confuse <br>';
                }
            }

            die("Deleting Users");
        }

    }


    ##############################
    ### COMMIT USERS RE-ASSIGN #####
    ##############################
    elseif(isset($_POST['re_assign_user']))
    {
        echo 're assign users';
    }

    ##############################
    ### ADD NEW USER GROUP #######
    ##############################
    elseif (isset($_POST['saveNew']))
    {
        if($_SESSION['usermgmt_main'] === 'groups')
        {
            $grp_name = htmlentities($_POST['grp_name']);
            $owner = $_SESSION['username'];
            $condition = "`name` = " . "'".$grp_name."'";

            //check if group exist
            if (ifRecordExist("user_access_level" , "name" , "'$grp_name'" , $pdo))
            {
                info("Group Exist");
                gb($_SERVER['HTTP_REFERER']);
                exit();
            }
            else
            {
                //add group to record
                if (insertIntoDatabase('user_access_level' , "`name` , `owner`" , "'$grp_name' ,  '$owner'" , $pdo))
                {
                    $perm_sql = "select COLUMN_NAME from information_schema.columns where table_name='user_access_level' and column_name like 'Perm%'";
                    $perm_stmt = $pdo->prepare($perm_sql);
                    $perm_stmt->execute();
                    while ($perm = $perm_stmt->fetch(PDO::FETCH_ASSOC))
                    {
                        $permission = $perm['COLUMN_NAME'];
                        $group = htmlentities($_POST['group']);
                        if (isset($_POST[$permission]))
                        {

                            $set = "`$permission`" . " = 1";
                            $condition = "`name` = '".$grp_name."'";
                            updateRecord('user_access_level' , "SET ".$set , $condition , $pdo);
                            echo $permission . "<span style='color: green'>". ' &plus; <br>' . "</span>";
                        }
                        else
                        {
                            $set = "`$permission`" . " = 0";
                            $condition = "`name` = '".$grp_name."'";
                            updateRecord('user_access_level' , "SET ".$set , $condition , $pdo);
                            echo $permission . "<span style='color: red'>". ' &times; <br>' . "</span>";
                        }
                    }

                    $_SESSION['usermgmt_main_sub'] = 'View';
                    info($grp_name . ' added successfully');
                    gb($_SERVER['HTTP_REFERER']);
                    exit();
                }
            }
        }

        elseif ($_SESSION['usermgmt_main'] === 'users')
        {
            $username = htmlentities($_POST['username']);
            $access_level = htmlentities($_POST['access_level']);
            $password = password_hash('12345' , PASSWORD_DEFAULT);
            $owner = $_SESSION['username'];

            if (ifRecordExist('users' , 'username',"'".$username."'" , $pdo))
            {
                info("Username taken");
                $_SESSION['usermgmt_main_sub'] = "View";
                gb($_SERVER['HTTP_REFERER']);
                exit();
            }
            else
            {
                //insert into database
                if (insertIntoDatabase('users' , '`username`,`password`,`ual`, `owner`' , "'$username' , '$password' , '$access_level','$owner'" , $pdo))
                {
                    //user_task
                    $task_msg = "Initialize your account";
                    if (insertIntoDatabase('user_task' , '`task`,`message`,`user`' , "1,'$task_msg','$username'" , $pdo))
                    {
                        info("User Added Successfully");
                        $_SESSION['usermgmt_main_sub'] = "View";
                        gb($_SERVER['HTTP_REFERER']);
                        exit();
                    }
                    else
                    {
                        info("User added but task not scheduled");
                        $_SESSION['usermgmt_main_sub'] = "View";
                        gb($_SERVER['HTTP_REFERER']);
                        die("User added but task not scheduled");
                    }
                }
                else
                {
                    die('COULD NOT ADD USER TO DATABASE');
                }
            }


        }


    }

    ##############################
    ### UPDATE USER PERMISSION ###
    ##############################
    elseif (isset($_POST['modifyPermissions']))
    {

        //Perm_Live_Update
        $perm_sql = "select COLUMN_NAME from information_schema.columns where table_name='user_access_level' and column_name like 'Perm%'";
        $perm_stmt = $pdo->prepare($perm_sql);
        $perm_stmt->execute();
        while ($perm = $perm_stmt->fetch(PDO::FETCH_ASSOC))
        {
            $permission = $perm['COLUMN_NAME'];
            $group = htmlentities($_POST['group']);
            if (isset($_POST[$permission]))
            {

                $set = "`$permission`" . " = 1";
                $condition = "`name` = '".$group."'";
                updateRecord('user_access_level' , "SET ".$set , $condition , $pdo);
                echo $permission . "<span style='color: green'>". ' &plus; <br>' . "</span>";
            }
            else
            {
                $set = "`$permission`" . " = 0";
                $condition = "`name` = '".$group."'";
                updateRecord('user_access_level' , "SET ".$set , $condition , $pdo);
                echo $permission . "<span style='color: red'>". ' &times; <br>' . "</span>";
            }
        }

        echo "<script>window.close()</script>";

    }

    ############################
    #### RESET PASSWORD #######
    ###########################
    elseif (isset($_GET['reset_password']) && isset($_SESSION['curr_user']))
    {
        $user = $_SESSION['curr_user'];

        //get user details
        $target_uname = fetchFunc('users',"`id` = '$user'",$pdo)['username'];
        $newPassword = password_hash('12345', PASSWORD_DEFAULT);

        //reset password
        if(updateRecord('users' , "SET `password` = '$newPassword'", "`id`=$user", $pdo))
        {
            //create task
            if (insertIntoDatabase('user_task',"`user`,`task_status`,`task`,`message`", "'$target_uname' , '1' , '2' , 'Password Reset'",$pdo))
            {
                info("Account rest successfully");
            }

            else
            {
                info("Could not schedule user task");
            }
            $_SESSION['usermgmt_main_sub'] = 'View';
            gb($_SERVER['HTTP_REFERER']);

        }

        echo "reset password for user ".$target_uname;
    }
