<?php

namespace database;

class db
{
    public $driver;
    public $server;
    public $db_user;
    public $db_pass;
    public $database;



    public function conn($driver,$server,$db_user,$db_pass,$database)
    {
        if($driver === 'sqlsrv')
        {
            // sql srv connect

            $connectionInfo = array('Database' => $database, "UID" => $db_user, "PWD" => $db_pass);
            $conn = sqlsrv_connect($server, $connectionInfo);
            if($conn)
            {
                return $conn;
            }
            else
            {
                echo "Cannot connect to $server with $driver";
            }
        }
        elseif ($driver === 'mysql')
        {
            //set DSN
            $dns = 'mysql:host='.$server.';dbname='.$database;

            //create pdo instanse
            $pdo = new \PDO($dns,$db_user,$db_pass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            return $pdo;
        }
        else
        {
            return false;
        }
    }

    public function fetch($db,$query) // fetch table into json
    {
        // get drivers
        $rows = [];
        if($db === 'rest')
        {

            $stmt = sqlsrv_query(rest, $query, array(), array( "Scrollable" => 'static' ));
            $row_count = sqlsrv_num_rows($stmt);

            while($row = sqlsrv_fetch_array($stmt,2))
            {
                $rows[] = $row;
            }

        }
        elseif ($db === 'smdesk')
        {
            $stmt = smdesk->query($query);
            header('Content-Type: application/json');
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return json_encode($rows);
    }

    public function rows($db,$query)
    {
        $row = 0;
        switch ($db){
            case 'rest':
                $stmt = sqlsrv_query(rest, $query, array(), array( "Scrollable" => 'static' ));
                $row = sqlsrv_num_rows($stmt);

                break;
            case 'smdesk':
                $stmt = smdesk->query($query);
                $row = $stmt->rowCount();
                break;
        }
        return $row;
    }

    public function exec($db,$query)
    {
        echo $db;
        if($db === 'smdesk')
        {
            try{
                smdesk->query($query);
            } catch (\PDOException $e)
            {
                echo $e->getMessage();
            }
        }
    }


}
