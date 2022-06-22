<?php
    $serverName = "192.168.2.4\MOTOR"; //serverName\instanceName

    // Since UID and PWD are not specified in the $connectionInfo array,
    // The connection will be attempted using Windows Authentication.
    $myUser = "sa";
    $myPass = "sa@123456";
    $myDB = "PROC_CMMS_V1";
    $connectionInfo = array("Database"=>$myDB, "UID" => $myUser, "PWD" => $myPass);
    $conn = sqlsrv_connect( $serverName, $connectionInfo);

    if( !$conn ) {
        echo "Connection could not be established.<br />";
        die( print_r( sqlsrv_errors(), true));
    }