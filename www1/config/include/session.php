<?php




    if(!isset($_GET['token']))
    {

        if(isset($_POST['token']))
        {
            $token = $_POST['token'];
        }

        else
        {
            session_start();
            $token = session_id();
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            header("location:$actual_link?token=$token");
        }

    }
    else
    {


        $token = $_GET['token'];
    }

    session_id($token);
    session_start();
    $_SESSION['error'] = 0;

    if(isset($_SESSION['authenticated']) && !empty($_SESSION['authenticated']) && $_SESSION['authenticated'] == true)
    {
        $login = 'yes';
    }
    else
    {
        $login = 'no';
        if(!isset($_SESSION['login']))
        {
            $_SESSION['login'] = 'username';
        }
    }
