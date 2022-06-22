<?php


class db_handler extends anton
{


    public function db_connect() // connect to database
    {
        $host = "127.0.0.1";
        $user = "root";
        $password = "Sunderland@411";
        $db = "smdesk";
        //set DSN
        $dns = 'mysql:host='.$host.';dbname='.$db;

        //create pdo instanse
        $pdo = new PDO($dns,$user,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }



    public function machine_details() // get machine details
    {
        $machine_detail = $this->db_sqlite()->query("select * from machine_config");
        return $machine_detail->fetch(PDO::FETCH_ASSOC);
    }
    public function machine_number() // machine number
    {
       return $this->machine_details()['machine_number'];
    }

    function row_count($table,$condition='none') // row count of a table
    {

        if($condition === 'none')
        {
            $sql = $this->db_connect()->query("SELECT * FROM $table");
        }
        else
        {
            $sql = $this->db_connect()->query("SELECT * FROM $table WHERE $condition");
        }

        return $sql->rowCount();
    }

    function get_rows($table, $condition) // get rows from table
    {
        if($condition === 'none')
        {
            $sql = "SELECT * FROM $table";
        }
        else
        {
            $sql = "SELECT * FROM $table WHERE $condition";
        }
        $stmt = $this->db_connect()->query($sql);


        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;


    }

    public function add_item_bill($bill_number,$barcode,$qty,$myName)
    {
        //get item details
        $machine_number = $this->machine_number();
        $clerk = $_SESSION['clerk_id'];

        // get item details
        $item = $this->get_rows('prod_mast',"`barcode` = '$barcode'");
        $item_desc = $item['desc'];
        $item_retail = $item['retail'];
        $disc = $item['discount'];
//        echo $disc;


        if($item['discount'] == '1')
        {
            // calculate discount rate off

            //$retail_p = $item_retail;
            $discount_rate = $item['discount_rate'];
            $retail_p = $item_retail - $this->percentage($discount_rate,$item_retail);
        }
        else
        {
            $retail_p = $item_retail;
        }

        $bill_amt = $retail_p * $qty;
        $tax_group = $item['tax_grp'];


        // get tax rate
        $taxDetails = $this->get_rows('tax_master',"`id` = '$tax_group'");
        $rate = $taxDetails['rate'];
        $tax_description =$taxDetails['description'];
        if($taxDetails['rate'] < 1)
        {
            $taxAmount = 0.00;
        }
        else
        {
            // calculate for tax
            $taxAmount = $this->tax($rate,$bill_amt);
        }



        // add to bill in trans
        $sql = "insert into `bill_trans` 
                (`mach`,`clerk`,`bill_number`,`item_barcode`,
                 `item_desc`,`retail_price`,`item_qty`,`tax_amt`,
                 `bill_amt`,`trans_type`,`tax_grp`,`tax_rate`) value
                 ('$machine_number','$myName','$bill_number','$barcode',
                  '$item_desc','$item_retail','$qty','$taxAmount',
                  '$bill_amt','i','$tax_description','$rate')";


        if($this->db_connect()->exec($sql))
        {
            return true;
        }
        else
        {
            return false;
        }


    }

    public function sum($table,$column,$condition,$as = 'result')
    {
        $sql = $this->db_connect()->query("SELECT SUM($column) as $as FROM `$table` WHERE $condition");
        $stmt = $sql->fetch(PDO::FETCH_ASSOC);
        return $stmt["$as"];
    }


    public function uniqieStr(string $table, string $column, int $length)
    {
        $unique = $this->generateRandomString($length);

        if($this->row_count("$table","$column = '$unique'") > 0)
        {
            // repeat function
            $this->uniqieStr($table,$column,$length);
        }

        return $unique;


    }

    public function delete($table,$condition = 'none'): bool
    {
        if($condition === 'none')
        {
            $sql = "DELETE FROM $table";
        }
        else
        {
            $sql = "DELETE FROM $table WHERE $condition";
        }

        if($this->db_connect()->exec($sql))
        {
            return true;
        } else
        {
            return false;
        }
    }

    public function clerkAuth($clerk_code,$key) :bool
    {
        if($this->row_count('clerk',"`clerk_code` = '$clerk_code'") > 0)
        {
            // compare password
            $clerk = $this->get_rows('clerk',"`clerk_code` = '$clerk_code'");
            $db_key = $clerk['clerk_key'];


            // compare
            if($this->compare(md5($key),$db_key))
            {
                // login pass
                return true;
            }
            else
            {
                // wrong password
                return false;
            }
        }
        else
        {
            // no user
            return false;
        }
    }

    public function discount($bill_condition,$disc_condition)
    {
        $_SESSION['sub_total'] = 0;
        $_SESSION['disc_value'] = 0;
        $disc_rate = $this->get_rows('bill_trans',"$disc_condition")['bill_amt'];
        // calculate sub total with discount
        $items = $this->db_connect()->query("SELECT * FROM `bill_trans` WHERE $bill_condition");
        while($it = $items->fetch(PDO::FETCH_ASSOC))
        {
            $item_barcode = $it['item_barcode'];
            $item = $this->get_rows('prod_mast',"`barcode` = '$item_barcode'");

            if($item['discount'] === 1)
            {
                //dont  apply discount
                $s_toal = $it['bill_amt'];
            }
            else
            {
                //apply discount
                $b_amt = $it['bill_amt'];
                $_SESSION['disc_value'] += $this->percentage($disc_rate,$b_amt);
                $s_toal = $b_amt - $this->percentage($disc_rate,$b_amt);
            }

            $s = $_SESSION['sub_total'];
            $_SESSION['sub_total'] = $s + $s_toal;

        }

        $sub_total = $_SESSION['sub_total'];
        $dis_value = $_SESSION['disc_value'];
        unset($_SESSION['sub_total']);
        return $dis_value;
    }

}