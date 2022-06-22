<?php

    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);

    require 'session.php';
    $session_id = session_id();

    // initialize classes
    require 'anton.php';
    require 'db_handler.php';
    $anton = new anton();
    $db = new db_handler();
    $today = date('Y-m-d');
    $root_host = $_SERVER['DOCUMENT_ROOT'];
//    die($root_host);
//    $anton->done($today);
//    die();

    if(isset($_SESSION['cli_login']) && $_SESSION['cli_login'] === 'true')
    {
        $session_id = session_id();
        $clerk_id = $anton->get_session('clerk_id');
        $my = $db->get_rows('users',"`id` = '$clerk_id'");
        $myName = $my['username'];




    }




    // set core sessions
    /*
     * module = main mods
     * sub_module = sub mods
     * */
    $module = $anton->get_session('module');
