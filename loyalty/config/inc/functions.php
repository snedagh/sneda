<?php
    @!session_start();
    require 'db.php';

    function srcData($path)
    {
        // Read image path, convert to base64 encoding
        $imageData = base64_encode(file_get_contents($path));

        // Format the image SRC:  data:{mime};base64,{data};
        return 'data: '.mime_content_type($path).';base64,'.$imageData;
    }

    function form_data($method,$name)
    {
        if($method === 'POST')
        {
            if(isset($_POST[$name]))
            {
                if(strlen($res = $_POST[$name]) > 0)
                {
                    $res = $_POST[$name];
                }
                else
                {
                    $res = 'NOT SET';
                }
            }
            else
            {
                $res = "not available";
            }
        }
        elseif ($method === 'GET')
        {
            if(isset($_GET[$name]))
            {
                if(strlen($res = $_GET[$name]) > 0)
                {
                    $res = $_GET[$name];
                }
                else
                {
                    $res = 'NOT SET';
                }
            }
            else
            {
                $res = "not available";
            }
        }
        else
        {
            echo "UNKNOWN METHOD";
            die();
        }

        return $res;
    }

    function getValue($session_variable)
    {
        if(isset($_SESSION["$session_variable"]))
        {
            echo $_SESSION["$session_variable"];
        }
        else
        {
            echo '';
        }
    }

    function connectDb($database)
    {
        $host = 'localhost';
        $user = 'root';
        $password = 'Sunderland@411';
        //set DSN
        $dns = 'mysql:host='.$host.';dbname='.$database;

        //create pdo instanse
        $pdo = new PDO($dns,$user,$password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }

    function checkPermission(array $permission)
    {
        $access_granted = 0;
        foreach ($permission as $key => $value)
        {
            if (isset($_SESSION[$value]) && $_SESSION[$value] === true)
            {
                $access_granted ++;
            }

            if ($access_granted > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }


    ##FUNCTION TO COUNT NUMBER OF ROWS
    function rowsOf($table , $condition , $connection)
    {
        if ($condition === 'none')
        {
            $rcSql = "SELECT * FROM $table";

        }
        else
        {
            $rcSql = "SELECT * FROM $table WHERE $condition";
        }
        $rcStmt = $connection->prepare($rcSql);
        $rcStmt->execute();
        return $rcStmt->rowCount();
    }

    ##date difference
    function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
    {

        //////////////////////////////////////////////////////////////////////
        //PARA: Date Should In YYYY-MM-DD Format
        //RESULT FORMAT:
        // '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
        // '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
        // '%m Month %d Day'                                            =>  3 Month 14 Day
        // '%d Day %h Hours'                                            =>  14 Day 11 Hours
        // '%d Day'                                                        =>  14 Days
        // '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
        // '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
        // '%h Hours                                                    =>  11 Hours
        // '%a Days                                                        =>  468 Days
        //////////////////////////////////////////////////////////////////////

        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);

    }

    ## time difference
    function timeDifference($start , $end)
    {
        $time1 = strtotime($start);
        $time2 = strtotime($end);
        return round(abs($time2 - $time1) / 3600,2);
    }

    function br($str = "none",$str_color = 'none')
    {
        echo '<span style="color: red">'.date("Y:m:d H:i:s") . ' # </span>' . $str . "<br>";
    }
    function time_stamp_msg($str = "none",$str_color = 'none'): string
    {
        return '<span style="color: red">'.date("Y:m:d H:i:s") . ' # </span>' . $str . "<br>";
    }

    ##fetch from table
    function fetchFunc($table , $condition, $connection)
    {
        if($condition === "none")
        {
            $sql = "SELECT * FROM $table LIMIT 1";
        }
        else
        {
            $sql = "SELECT * FROM $table WHERE $condition LIMIT 1";
        }
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }




    ##column sum
    function getSumOfColumn($table , $column , $condition  , $connection , $currency = 0)
    {
        if ($condition != 'none')
        {
            $sql = "select SUM($column) from $table WHERE $condition";
        }
        else
        {
            $sql = "SELECT SUM($column) from `$table`";
        }
        $stmt  = $connection->prepare($sql);
        $stmt->execute();
        $stmt_res = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = $stmt_res['SUM('.$column.')'];
        if ($result === NULL)
        {
            $x = number_format(0.00);
        }
        else
        {
            //explode result
            $x = number_format($result,2);

        }

        if ($currency === 0)
        {
            return $x;
        }
        else
        {
            return $_SESSION['currency'].' '.$x;
        }

    }

    ##CHECK IF RECORD EXIST
    function ifRecordExist($table , $column , $record , $connection)
    {
        $ifRecordExistsql = "SELECT * FROM `$table` WHERE `$column` = $record";
        $ifRecordExiststmt = $connection->prepare($ifRecordExistsql);
        $ifRecordExiststmt->execute();

        if ($ifRecordExiststmt->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }



    ##SET SESSION INFO
    function info($info)
    {
        $_SESSION['info'] = $info;
    }

    ##insert into database
    function insertIntoDatabase($table , $culumns , $values , $connection)
    {
        try {
            $sql = "INSERT INTO `$table` ($culumns) VALUES ($values)";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            return true;
        } catch (Throwable $e)
        {
            br($e);
            return false;
        }
    }

    ##update_record
    function updateRecord ($table , $set , $condition , $conn)
    {
        if ($condition === 'none')
        {
            $updateSql = "UPDATE `$table` SET $set";
        }
        else
        {
            $updateSql = "UPDATE `$table` SET $set WHERE $condition";
        }
        $updateStmt = $conn->prepare($updateSql);
        if($updateStmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    ##get next function
    function nextPrevious($table , $condition , $conn)
    {
        $sql = "SELECT * FROM `$table` WHERE $condition LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    ##is condition true
    function isConditionTrue ($table , $column , $condition , $tcolumn , $connection)
    {
        $sql = "SELECT `$column` FROM $table WHERE $condition";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $stmt_result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = $stmt_result[$tcolumn];
        return $result;
    }

    ## delete row
    function deleteRow($table , $condition , $connection)
    {
        $sql = "DELETE FROM $table WHERE $condition";
        $stmt = $connection->prepare($sql);
        if ($stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    ##mavigate url
    function gb()
    {
        header("Location:".$_SERVER['HTTP_REFERER']);
    }
    function redirect($url)
    {
        header("Location:".$url);
    }

    ##check user task
    function task($username,$conn)
    {
        try
        {
            $sql = "SELECT * FROM `user_task` WHERE `user` = ? AND `task_status` = '1' LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$username]);
            return $task_res = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $error)
        {
            echo $error->getMessage();
        }
    }

    ##password validate
    function validateKey($user_id , $key , $connection)
    {
        $sql = "SELECT `password` FROM `users` WHERE `id` = $user_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $result['password'];
        if (password_verify($key, $hashed_password))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    function done()
    {
        echo "OK";
    }

    function reload()
    {
        echo '<script>location.reload()</script>';
    }

    ## go back
    function back()
    {
        $url = $_SERVER['HTTP_REFERER'];
        header("Location:".$_SERVER['HTTP_REFERER']);
        exit();
    }

    function error($message)
    {
        $_SESSION['error'] = $message;
    }

    function alert($message)
    {
        echo "<script>alert('".$message."')</script>";
    }

    ##logout
    function logout()
    {
        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session.
        session_destroy();


        // Redirect to login page
        header("location: ../../");
        exit;
    }

    ## SEND MESSAGE USING
    function sms($api,$key,$from,$to,$msg,$exp_ressult): bool
    {
        if($api === 'mnotify')
        {
            // mnotify messaging
            $api_key = 'VE36nLRD9rCWroMvHE6i2C097';

            $url = "https://apps.mnotify.net/smsapi?key=$api_key&to=$to&msg=".$msg."&sender_id=$from";
            $response = file_get_contents(urldecode($url));
            $result = json_decode($response);

            $code = $result->code;
            if($code === $exp_ressult)
            {
                return '1';
            }
            else
            {
//                br("Code : ".$code);
                return $code;
            }
        }
    }

    ## set session
    function setSession($variable, $value)
    {
        $_SESSION[$variable] = $value;

    }

    function getSession($variable)
    {
        if(isset($_SESSION[$variable]))
        {
            return $_SESSION[$variable];
        }

    }

    function unsetSession($arr = [])
    {
        foreach ($arr as $key => $v)
        {
            if(isset($_SESSION[$v]))
            {
                unset($_SESSION[$v]);
            }
        }
    }
