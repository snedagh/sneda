<?php
    $host = "localhost";
    $user = "root";
    $password = "Sunderland@411";
    $db = "smdesk";

    //connection
    $route = mysqli_connect($host,$user,$password,$db);
    if($route != true)
    {
        echo 'connection failed';
        die();
    }

    //PDO CONNECT

    //set DSN
    $dns = 'mysql:host='.$host.';dbname='.$db;

    //create pdo instanse
    $pdo = new PDO($dns,$user,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
?>