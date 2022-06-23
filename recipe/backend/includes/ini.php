<?php


    require 'db.php';
    $db = new database\db();

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    define('rest',$db->conn(
        'sqlsrv',
        '192.168.2.4',
        'sa',
        'sa@123456',
        'SMSEXP_REST_2022'));
    define('smdesk',$db->conn('mysql',
        '192.168.2.3',
        'anton',
        '258963',
        'smdesk'));






