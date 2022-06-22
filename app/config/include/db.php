<?php
    $host = "192.168.2.3";
    $user ="anton";



    //connection
    $route = mysqli_connect($host,$user,$password,$db);
    if($route != true)
    {
        echo 'connection failed';
        die();
    }

    //PDO CONNECT

    //set DSN
try {
    $dns = 'mysql:host='.$host.';dbname='.$db;

    //create pdo instanse
    $pdo = new PDO($dns,$user,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $err)
{
    echo $err = $err->getMessage();
    die();
}


    // connect to sql server
    try {
        $dsn = "sqlsrv:Server=192.168.2.4,1433;Database=$sql_db";
        $conn = new PDO($dsn, "sa", "sa@123456");
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch(PDOException $e) {
        echo $err = $e->getMessage();
        die();
    }

try {
    $dsn = "sqlsrv:Server=192.168.2.4,1433;Database=SMSEXP_REST";
    $conn_rest = new PDO($dsn, "sa", "sa@123456");
    $conn_rest->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//    $conn_rest->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL);
} catch(PDOException $e) {
    echo $err = $e->getMessage();
    die();
}



