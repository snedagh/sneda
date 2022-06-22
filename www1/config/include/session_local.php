<?php

    session_start();

    if(!isset($_GET['token']))
    {
        $token = session_id();
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        echo $actual_link;
        die();
    }

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
