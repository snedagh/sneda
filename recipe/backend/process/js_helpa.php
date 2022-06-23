<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include '../includes/ini.php';



if(isset($_POST['function']))
{

    $function = $_POST['function'];

    if($function === 'row_count')
    {

        $query = $_POST['query'];
        $stmt = sqlsrv_query(rest, $query, array(), array( "Scrollable" => 'static' ));
        $row_count = sqlsrv_num_rows($stmt);
        echo $row_count;

    }

    elseif ($function === 'get_row')
    {



        $table = $_POST['table'];
        $condition = $_POST['condition'];
        $limit = $_POST['limit'];

        if($limit == '0')
        {
            $top = '';
        } else
        {
            $top = "TOP($limit)";
        }

        if($condition !== 'none')
        {
            $cond = "WHERE $condition";
        } else
        {
            $cond = "";
        }

        $query = "SELECT $top * FROM $table $cond ";
        $stmt = sqlsrv_query(rest, $query, array(), array( "Scrollable" => 'static' ));
        $row_count = sqlsrv_num_rows($stmt);
//            print_r($query);
        if($row_count > 0)
        {

//                header('Content-Type: application/json');
            $rows =[];
            while($row = sqlsrv_fetch_array($stmt,2))
            {
                $rows[] = $row;
            }
            echo(json_encode($rows));

        } else
        {
            echo "no_data%%";
        }
    }

    elseif ($function === 'fetch')
    {
        $query = $_POST['query'];
        $database = $_POST['db'];

        echo $db->fetch($database,$query);


    }

    elseif ($function === 'query_row')
    {
        $query = $_POST['query'];
        $database = $_POST['db'];
        echo $db->rows($database,$query);

    }

    elseif ($function === 'exec')
    {
        $query = $_POST['query'];
        $database = $_POST['db'];
        $db->exec($db,$query);
    }

}