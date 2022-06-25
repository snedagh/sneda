<?php


    require 'db.php';
    $db = new database\db();

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);

    define('rest',$db->conn(
        'sqlsrv',
        '172.17.0.2',
        'sa',
        'Sunderland@411',
        'SMSEXP_REST_2022'));
    define('smdesk',$db->conn('mysql',
        'localhost',
        'root',
        'Sunderland@411',
        'smdesk'));






