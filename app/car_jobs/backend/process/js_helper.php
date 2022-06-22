<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    include '../includes/motors_database.php';



    if(isset($_POST['function']))
    {
        $function = $_POST['function'];

        if($function === 'row_count')
        {

            $query = $_POST['query'];
            $stmt = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
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

            $query = "SELECT $top * FROM $table $cond";
            $stmt = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
            $row_count = sqlsrv_num_rows($stmt);
            if($row_count > 0)
            {
                header('Content-Type: application/json');
                $rows = sqlsrv_fetch_array($stmt,2);
                print_r(json_encode($rows));

            } else
            {
                echo "no_data%%";
            }
        }

    }