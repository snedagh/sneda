<?php

    $token = $_GET['token'];

    require 'MySQLSessionHandler.php';

    define("REGEN_INTERVAL_SEC", 600);

    $sesHandler = new MySQLSessionHandler(new mysqli('192.168.2.3', 'anton', '258963', 'smdesk'));

    /* Example using native session_start with manual controls - NOTE: this example does exactly what MySQLSessionHandler::start() does */
    try{
        session_id($token);
        session_start();
        /* Regenerate stagnant session id */
        if( $sesHandler->getInitTime < ($sesHandler->getCurReqTime() - REGEN_INTERVAL_SEC) ){
            /* Preserve session date while marking session expired */
            $sesHandler->setExpire(true);
            session_regenerate_id(false);
        }
    }catch(SessionAuthException $e){
        /* Client/request could not be authenticated - start a fresh session */
        session_id(null);
        session_start();
    }

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";
    // Append the host(domain name, ip) to the URL.
    $url.= $_SERVER['HTTP_HOST'];

    // Append the requested resource location to the URL
    $url.= $_SERVER['REQUEST_URI'];

    $n_url = explode('?',$url)[0];

//    print_r($_SESSION);
//    die();

    header("Location:$n_url");


