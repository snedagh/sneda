<?php
    require '../../include/core.php';

    $serverName = "192.168.2.4\motor";
    $connectionInfo = array('Database' => 'PROC_CMMS_V1', "UID" => 'sa', "PWD" => 'sa@123456');
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    if ($conn) {

    } else {
        echo "Something went wrong while connecting to MSSQL.<br />";
        die(print_r(sqlsrv_errors(), true));
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['function']))
        {
            $function = post('function');
            if($function === 'stock_sync') // sync stock
            {
                // delete existing
//                $deleteAll = "DELETE FROM `nia_stock_filtered` WHERE id > 0";
//                $pdo->exec($deleteAll);

                // sync new
                $serverName = "192.168.2.4";
                $connectionInfo = array('Database' => 'SMSEXPV17', "UID" => 'sa', "PWD" => 'sa@123456');
                $conn = sqlsrv_connect($serverName, $connectionInfo);
                if ($conn) {

                } else {
                    echo "Something went wrong while connecting to MSSQL.<br />";
                    die(print_r(sqlsrv_errors(), true));
                }

                $sql = "SELECT * FROM `nia_stock` WHERE `synced` = 0";
                $stmt = $pdo->query($sql);

                while($item = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                    $barcode = trim($item['barcode']);
                    $description = $item['description'];


                    $xsql = $pdo->query("select sum(counted) as total from nia_stock_multi_count where barcode = '$barcode'");
                    if($xsql->rowCount() > 0)
                    {
                        $milti = $xsql->fetch(PDO::FETCH_ASSOC);
                        $adon = $milti['total'];
                        if(strlen($adon < 1))
                        {
                            $final_add = $item['phy_qty'];
                        }
                        else
                        {
                            $final_add = $item['phy_qty'] + $adon;
                        }
                    }

                    $phy_qty = $final_add;

                    // check if item exist in stock
                    // get item code
                    $query = "select * from prod_mast where barcode = '$barcode'";
                    $params = array();
                    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $result = sqlsrv_query($conn, $query,$params,$options);
                    $rowcount = sqlsrv_num_rows($result);



                    if($rowcount > 0)
                    {
                        // get details
                        $itemFromMain = sqlsrv_fetch_array($result,2);
                        $item_code = $itemFromMain['item_code'];
                        $description = $itemFromMain['item_des'];




                        // get stock
                        $stockQuery = "select * from stock where loc_id = 202 and item_code = '$item_code'";
                        $stockQueryParams = array();
                        $stockQueryOptions =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                        $stockQueryResult = sqlsrv_query($conn, $stockQuery,$stockQueryParams,$stockQueryOptions);
                        $stockRowcount = sqlsrv_num_rows($stockQueryResult);
                        if($stockRowcount > 0)
                        {
                            $stockDetails = sqlsrv_fetch_array($stockQueryResult,2);

                            $dbStock = intval($stockDetails['qty']);
                        }
                        else
                        {
                            $dbStock = 'not_found';
                        }

                        if(is_numeric($dbStock) && is_numeric($phy_qty))
                        {



                            if(intval($dbStock) > intval($phy_qty))
                            {
                                $diff = number_format( intval($dbStock) - intval($phy_qty));
                            }
                            else
                            {
                                $diff = number_format( intval($phy_qty) - intval($dbStock));
                            }

                        }
                        else
                        {
                            $diff = 'cant calcuate';
                        }

                        if($rowcount > 0 )
                        {
                            $ip = $_SERVER['REMOTE_ADDR'];
                            // insert into new db
                            $insertSql = "INSERT INTO `nia_stock_filtered`(`barcode`, `item_code`, `description`, `db_stock`, `physical`,`diff`,`ip_add`) 
                                                                                    VALUES ('$barcode','$item_code','$description','$dbStock','$phy_qty','$diff','$ip')";
                            if(rowsOf('nia_stock_filtered',"`barcode` = '$barcode'",$pdo) < 1)
                            {
                                if($pdo->exec($insertSql))
                                {
                                    echo 'Inserted';
                                    // update
                                    $pdo->exec("UPDATE `nia_stock` SET `synced` = 1 WHERE `barcode` = '$barcode'");
                                }
                                else
                                {
                                    echo 'failied';
                                }

                            }
                            else{
                                $pdo->exec("UPDATE `nia_stock` SET `synced` = 1 WHERE `barcode` = '$barcode'");
                            }
                        }



                    }
                    else
                    {
                        $item_code = 'none';
                    }
                }

                echo 'done';

            }

            elseif ($function === 'search_stock')
            {
                $query = post('q');
                // search for stock
                // get item code
                $query = "select TOP(1) * from prod_mast where barcode LIKE '%$query%' OR item_des LIKE '%$query%'";
                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $result = sqlsrv_query($conn, $query,$params,$options);
                $rowcount = sqlsrv_num_rows($result);
                if($rowcount > 0)
                {
                    // get details
                    $itemFromMain = sqlsrv_fetch_array($result,2);
                    $item_code = $itemFromMain['item_code'];
                    $description = $itemFromMain['item_des'];
                    $barcode = $itemFromMain['barcode'];

                    // get stock
                    $stockQuery = "select * from stock where loc_id = 202 and item_code = '$item_code'";
                    $stockQueryParams = array();
                    $stockQueryOptions =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                    $stockQueryResult = sqlsrv_query($conn, $stockQuery,$stockQueryParams,$stockQueryOptions);
                    $stockRowcount = sqlsrv_num_rows($stockQueryResult);
                    if($stockRowcount > 0)
                    {
                        $stockDetails = sqlsrv_fetch_array($stockQueryResult,2);

                        $dbStock = $stockDetails['qty'];
                    }
                    else
                    {
                        $dbStock = 'not_found';
                    }


                    $js_id = 'cust'.$barcode;

                    if(rowsOf('nia_stock',"`barcode` = '$barcode'",$pdo) > 0)
                    {
                        $Csn = 0;
                        $rows = '';
                        $countSql = $pdo->query("select * from `nia_stock_multi_count` where `barcode` = '$barcode'");
                        while($ct = $countSql->fetch(PDO::FETCH_ASSOC))
                        {
                            $Csn ++;
                            $Cbarcode = $ct['barcode'];
                            $cCount = $ct['counted'];
                            $ap_count = "
                                <tr>
                                    <td>$Csn</td>
                                    <td colspan='2'>$Cbarcode</td>
                                    <td>$cCount</td>
                                </tr>
                            ";
                            $rows .= $ap_count;
                        }
                        $form = "
                            <input type='hidden' value='$barcode' name='barcode'>
                            <input type='hidden' value='$description' name='description'>
                            <input type='hidden' value='$item_code' name='item_code'>
                            <input type='hidden' value='$dbStock' name='db_stock'>
                            <input type=\"hidden\" name=\"function\" value=\"update_count\">
                            
                            <tr>
                                <td>$barcode</td>
                                <td>$description</td>
                                <td><input type='text' required class='form-control-sm' autofocus autocomplete='off' name='new_quantity' placeholder='new quantity' value=''></td>
                                <td><button type='submit' class='btn btn-warning btn-sm''>NEW UPDATE</button></td>
                            </tr>";
                        echo $rows . $form;
                    }
                    else
                    {
                        echo "
                            <input type='hidden' value='$barcode' name='barcode'>
                            <input type='hidden' value='$description' name='description'>
                            <input type='hidden' value='$item_code' name='item_code'>
                            <input type='hidden' value='$dbStock' name='db_stock'>
                            <input type=\"hidden\" name=\"function\" value=\"add_counted\">
                            
                            <tr>
                                <td>$barcode</td>
                                <td>$description</td>
                                <td><input type='text' required class='form-control-sm' autofocus autocomplete='off' name='counted_qty' placeholder='counted' value=''></td>
                                <td><button type='submit' class='btn btn-info btn-sm''>SUBMIT</button></td>
                            </tr>";
                    }

                }
                else
                {
                    echo "<kbd class='bg-danger'>NOT FOUND</kbd>";
                }

            }

            elseif ($function === 'add_counted')
            {
                $counted_qty = post('counted_qty');
                $barcode = post('barcode');
                $description = post('description');
                $item_code = post('item_code');
                $db_stock = post('db_stock');
                $physical = $counted_qty;
                if(is_numeric($db_stock) && is_numeric($physical))
                {
                    $diff = $db_stock - $physical;
                }
                else
                {
                    $diff = 'unknown';
                }
                $sql = "INSERT INTO `nia_stock`(`barcode`, `description`, `phy_qty`) VALUES ('$barcode','$description','$physical')";
                if(rowsOf('nia_stock',"`barcode` = '$barcode'",$pdo) < 1)
                {
                    $pdo->exec($sql);
                    echo 'done';
                }
                else
                {
                    echo 'exist';
                }

                // insert into db

            }

            elseif ($function === 'update_count')
            {
                $new_quantity = post('new_quantity');
                $barcode = post('barcode');
                $description = post('description');
                $item_code = post('item_code');
                $db_stock = post('db_stock');

                // insert into milti
                $pdo->exec("INSERT INTO `nia_stock_multi_count` (`barcode`,`counted`) values ('$barcode','$new_quantity')");

                // insert into db
                echo 'done';

            }

            if($function === 'arrangeStock')
            {



                $item = $mainpdo->query("select * from motors_cmms_stock_compare where `checked` = 0 LIMIT 1");
                if($item->rowCount() < 1)
                {
                    // update not found
                    $mainpdo->exec("UPDATE `motors_cmms_stock_compare` SET `checked` = 0 where `found` = 0");
                    echo "> <span class='text-success'>  <i class='fa fa fa-thumbs-up'></i> nothing to do </span><br>";
                    exit();
                }


                // check

                $result = $item->fetch(PDO::FETCH_ASSOC);
                //echo strlen($result['barcode']);
                if(strlen($result['barcode']) == 7)
                {
                    $barcode = "00".$result['barcode'];
                } elseif (strlen($result['barcode']) == 8)
                {
                    $barcode = "0".$result['barcode'];
                } else
                {
                    $barcode = $result['barcode'];
                }
                
                $barcode = $result['barcode'];

//                $barcode = $result['barcode'];
                $id = $result['id'];

                // update barcode
                $mainpdo->exec("UPDATE `motors_cmms_stock_compare` SET `barcode` = '$barcode' WHERE `id` = '$id' ");

                $x_code = rtrim($barcode);
                echo "$ ";
                //echo "## <span class='text-light'>$x_code</span>";
                $phy_qty = $result['quantity'];
                $id = $result['id'];
                $rDesc = $result['desc'];

                $query = "select * from barcode where barcode = '$x_code'";

//                echo "$query > ";

                $params = array();
                $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                $result = sqlsrv_query($conn, $query,$params,$options);
                $row = sqlsrv_fetch_array( $result, 2 );
                $rowcount = sqlsrv_num_rows($result);

                if($rowcount > 0)
                {
                    $description = $row['prod_des1'];
                    // update exist
                    $mainpdo->exec("UPDATE `motors_cmms_stock_compare` SET `checked` = 1,`description` = '$description', `found` = 1 WHERE `id` = '$id'");
                    $checked = rowsOf('motors_cmms_stock_compare', "`checked` = 1",$mainpdo);
                    $unchecked = rowsOf('motors_cmms_stock_compare', "none",$mainpdo);

                    echo "> <span class='text-success'> $checked / $unchecked  <i class='fa fa fa-thumbs-up'></i> checked  ( $barcode )</span><br>";

                }
                else
                {
//                    $item_code = 'none';
                    $mainpdo->exec("UPDATE `motors_cmms_stock_compare` SET `checked` = 1, `description` = 'NOT FOUND' WHERE `id` = '$id'");
                    $checked = rowsOf('motors_cmms_stock_compare', "`checked` = 1",$mainpdo);
                    $unchecked = rowsOf('motors_cmms_stock_compare', "none",$mainpdo);

                    echo "> <span class='text-danger'> $checked / $unchecked  <i class='fa fa-thumbs-down'></i> cant find  ( $x_code )</span><br>";
                }


            }
        }


    }
