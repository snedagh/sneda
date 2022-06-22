<?php


    if(isset($_GET['token']))
    {
        $token = $_GET['token'];
    }
    elseif (isset($_POST['token']))
    {
        $token = $_POST['token'];
    }
    else
    {
        die("No Token");
        header("location:http://www1.sneda.gh");
    }


    session_id($token);
    session_start();
   

    //check if there is login
    if(!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true){

        $page = $_SERVER['SCRIPT_NAME'];
        $login = 'no';
        if (!isset($_SESSION['login']))
        {
            $_SESSION['login'] = 'username';
        }

        header("location:http://www1.sneda.gh");
        exit();
        require 'parts/no_access.php';
        die();

    }
    else
    {
        require 'functions.php';
        $user_id = $_SESSION['user_id'];
        $my = fetchFunc("users" , "`id` = $user_id" , $pdo);
        $my_username = $my['username'];
        $my_extension = $my['dp'];

    }



