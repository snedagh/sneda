<?php

    require '../includes/core.php';



    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if(isset($_POST['function']))
        {
            $function = $anton->post('function');
            if($function === 'set_session')
            {
                // get form data
                $session_data = $_POST['session_data'];
                print_r($session_data);
                $anton->set_session($session_data);
            }

            elseif ($function === 'row_count') // count rows
            {
                $table = $anton->post('table');
                $condition = $_POST['condition'];

                echo $db->row_count("$table","$condition");

            }

            elseif ($function === 'query')
            {
                $query = $_POST['query'];
                echo($query);
                $db->db_connect()->exec($query);
            }

            elseif ($function === 'get_row')
            {
                $table = $anton->post('table');
                $condition = $_POST['condition'];
                $res =  $db->get_rows($table,$condition,'json');
                print_r($res);
            }

            elseif ($function === 'insert') // insert into table
            {
                $query = $_POST['query'];
//                echo($query);
                try {
                    $db->db_connect()->exec($query);
                    echo '1';
                } catch (PDOException $e){
                    echo $e->getMessage();
                }
            }

            elseif ($function === 'get_input_tax')
            {

                $invoice_value = $anton->post('invoice_value');
                $tax_class = $anton->post('tax_class');
                $tax_trigger = $tax_class."($invoice_value)";
                $tax_return = $taxCalc->tax_input($invoice_value,$tax_class);

                echo $tax_return;
            }

            elseif ($function === 'query_rows')
            {
                $sql = $_POST['query'];
                $stmt = $db->db_connect()->query($sql);
                header('Content-Type: application/json');
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                print_r(json_encode($res));

            }

            elseif ($function === 'row_sum') // count rows
            {
                $table = $anton->post('table');
                $column = $anton->post('column');
                $condition = $_POST['condition'];

                $sum = $db->sum($table,$column,$condition);
                echo $sum;
            }
        }
    }
