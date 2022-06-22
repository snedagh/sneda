<?php
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    include '../includes/motors_database.php';

    if(isset($_POST['function']))
    {
        $function = $_POST['function'];

        if($function === 'job_card')
        {
            // get car number
            $car_number = $_POST['car_number'];
            $query = "select TOP(1)  * from asset_mast where asset_no = '$car_number'";
            $stmt = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' ));
            $row_count = sqlsrv_num_rows($stmt);

            if($row_count > 0)
            {
                $row = sqlsrv_fetch_array($stmt);
                $customer_code = $row['cust_code'];
                $asset_code = $row['ASSET_CODE'];

                // get last w_req
                $wo_sql = "select TOP(1) * from wo_hd  where asset_code = '$asset_code' order by wo_date desc";
                $wo_stmt = sqlsrv_query($conn,$wo_sql,array(),array( "Scrollable" => 'static' ));

                if(sqlsrv_num_rows($wo_stmt) > 0 )
                {
                    $wo_row = sqlsrv_fetch_array($wo_stmt);
                    $wo_date = $wo_row['wo_date'];
                    $wo_number = $wo_row['wo_no'];

                    // get invoice details
                    $inv_sql = "SELECT * FROM invoice_hd WHERE wo_no = '$wo_number'";

                }
//                print_r($row);
            }



        }

    }