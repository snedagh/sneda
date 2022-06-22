<?php

    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);



    require '../include/session.php';

    //update user status
//    if(updateRecord('users','`online` = 0' , '`id` = '.$_SESSION['user_id'] , $pdo))
//    {
//        //log user activity
//
//        $cols = "`user_id`,`username`,`func`";
//        $vals = $_SESSION['user_id'] . ',' ."'". $_SESSION['username'] ."'". ',' . "'".'logout'."'";
//
//        insertIntoDatabase('user_login_log',$cols, $vals, $pdo);
//    }

    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();


    // Redirect to login page
    $hos = $_SERVER['HTTP_HOST'];
    header("location:http://$hos");
    exit;
