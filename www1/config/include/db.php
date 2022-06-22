<?php



    $host = "192.168.2.3";
    $user ="anton";



    //PDO CONNECT

    try {
        $dns = 'mysql:host='.$host.';dbname='.$db;

        //create pdo instanse
        $pdo = new PDO($dns,$user,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $err)
    {
        echo $err = $err->getMessage();
        die('no connection');
    }


    $serverName = "192.168.2.4";
    $connectionInfo = array('Database' => 'SMSEXPV17', "UID" => 'sa', "PWD" => 'sa@123456');
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    if ($conn) {

    } else {
        echo "Something went wrong while connecting to MSSQL.<br />";
        die(print_r(sqlsrv_errors(), true));
    }

try {
    $dns = 'mysql:host='.'192.168.2.3'.';dbname='.'smdesk';

    //create pdo instanse
    $mainpdo = new PDO($dns,'anton','258963');
    $mainpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mainpdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
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
        //echo $err = $e->getMessage();
        //die();
    }

try {
    $dsn = "sqlsrv:Server=192.168.2.4,1433;Database=SMSEXP_REST";
    $conn_rest = new PDO($dsn, "sa", "sa@123456");
    $conn_rest->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
//    $conn_rest->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL);
} catch(PDOException $e) {
    echo $err = $e->getMessage() . "\n SMSEXP_REST";
    die();
}

    $serverName = "192.168.2.4";
    $connectionInfo = array('Database' => 'SMSEXPV17', "UID" => 'sa', "PWD" => 'sa@123456');
    $mycom = sqlsrv_connect($serverName, $connectionInfo);
    if ($mycom) {

    } else {
        echo "Something went wrong while connecting to MSSQL.<br />";
        die(print_r(sqlsrv_errors(), true));
    }

    // spintext pos
//    $dsn = "sqlsrv:Server=192.168.1.2,1433;Database=POSDBV4";
//    $spin_retail = new PDO($dsn, "sa", "sa@123456");
//    $spin_retail->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );







