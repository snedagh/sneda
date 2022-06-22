<?php
    require '../includes/core.php';

    //echo $_SERVER['REQUEST_METHOD'];


    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {



        if(isset($_POST['function'])) // if we have a function from post call
        {
            $function = $anton->post('function');

            if($function === 'addItem')
            {
                $barcode = $anton->post('barcode');
                $qty = $anton->post('qty');

                // insert into database
                try {
                    $db->db_connect()->exec("INSERT INTO `sp_stock_count` (`barcode`,`quantity`,`owner`) value ('$barcode','$qty','$myName')");
                    $anton->done();
                } catch (PDOException $exception)
                {
                    $anton->err($exception->getMessage());
                }

            }

            elseif ($function === 'get_items') // get bill
            {
                // get all from bill
                $bill_query = $db->db_connect()->query(
                    "select * from sp_stock_count order by id desc LIMIT 10"
                );
                if($bill_query->rowCount() < 1)
                {
                    echo 'no_bill%%';
                    exit();
                }
                $bill_items = 'done%%';
                $sn = 0;
                while($bill = $bill_query->fetch(PDO::FETCH_ASSOC))
                {
                    ++$sn;
                    $barcode = $bill['barcode'];
                    $qty = $bill['quantity'];




                    // make bill item
                    $bill_item = "<div 
                                    class=\"d-flex flex-wrap cart_item align-content-center justify-content-between border-dotted pb-1 pt-1\"
                                    >
                                    
                                    <div class=\"75 h-100 d-flex flex-wrap align-content-center pl-1\">
                                        <p class=\"m-0 p-0\">$barcode</p>
                                    </div>
                                    
                                    <div class=\"25 h-100 d-flex flex-wrap align-content-center pl-1\">
                                        <p class=\"m-0 p-0\">$qty</p>
                                    </div>
            
                                   
                                </div>";

                    // append item to bills
                    $bill_items .= $bill_item;
                    $qty = 1; // set quantity to 1
                }
                // return bill item
                echo $bill_items;
            }


        }

    }
