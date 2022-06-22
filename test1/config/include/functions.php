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

function connectDb($database)
{
    $host = '192.168.2.3';
    $user = 'anton';
    $password = '258963';
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
        $rcSql = "SELECT * FROM `$table` WHERE $condition";
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

function delete_file($file)
{
    if(file_exists($file) && is_file($file))
    {
        if(unlink($file))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
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

function get($variable): string
{
    if(isset($_GET["$variable"]))
    {
       return htmlentities($_GET[$variable]);
    }
    else{
        return "NONE";
    }
}

function cal_percentage($rate, $total) {
    $percentage = $rate / 100;
    $tt = $percentage * $total;
    return number_format($tt , 2);
}

function get_array($arr,$position)
{
    return $arr[$position] ?? 'not set';
}

function post($variable): string
{
    if(isset($_POST["$variable"]))
    {
        return htmlentities($_POST["$variable"]);
    }
    else
    {
            return 'NONE';
    }

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

# get file type
function file_type($ext)
{
    $file_ext = strtolower($ext);
    //if file is document
    if  (
        $file_ext === 'doc' || $file_ext === 'docx' || $file_ext === 'odt' ||
        $file_ext === 'pdf' || $file_ext === 'rtf' || $file_ext === 'tex' ||
        $file_ext === 'txt' || $file_ext === 'wpd' || $file_ext === 'ods' ||
        $file_ext === 'xls' || $file_ext === 'xlsm' || $file_ext === 'xlsx' ||
        $file_ext === 'c' || $file_ext === 'csv' || $file_ext === 'pl' ||
        $file_ext === 'class' || $file_ext === 'cpp' || $file_ext === 'cs' ||
        $file_ext === 'h' || $file_ext === 'java' || $file_ext === 'php' ||
        $file_ext === 'css' || $file_ext === 'js' || $file_ext === 'py' ||
        $file_ext === 'swift' || $file_ext === 'sh' || $file_ext === 'vb' ||
        $file_ext === 'key' || $file_ext === 'odp' || $file_ext === 'pps' ||
        $file_ext === 'ppt' || $file_ext === 'pptx' || $file_ext === 'xhtml' ||
        $file_ext === 'rss' || $file_ext === 'part' || $file_ext === 'jsp' ||
        $file_ext === 'cer' || $file_ext === 'cfm' || $file_ext === 'htm' ||
        $file_ext === 'dat' || $file_ext === 'db' || $file_ext === 'dbf' ||
        $file_ext === 'log' || $file_ext === 'mdb' || $file_ext === 'sav' ||
        $file_ext === 'sql' || $file_ext === 'xml'
    )
    {

        $dir = 'Documents';

    }

    //If File is Audio
    elseif (
        $file_ext === 'aif' || $file_ext === 'cda' || $file_ext === 'mid' ||
        $file_ext === 'midi' || $file_ext === 'mpa' || $file_ext === 'mp3' ||
        $file_ext === 'ogg' || $file_ext === 'wav' || $file_ext === 'wma' ||
        $file_ext === 'wpl'|| $file_ext === 'MP3'
    ) {
        $dir = 'Music';
    }

    //if file is compressed
    elseif (
        $file_ext === '7z' || $file_ext === 'arj' || $file_ext === 'pkg' ||
        $file_ext === 'rar' || $file_ext === 'zip' || $file_ext === 'tar' ||
        $file_ext === 'tar.gz' || $file_ext === 'gz' || $file_ext === 'iso'
    ) {
        $dir = 'Compressed';
    }

    //if file is Picture
    elseif (
        $file_ext === 'png' || $file_ext === 'ai' || $file_ext === 'gif' ||
        $file_ext === 'jpg' || $file_ext === 'jpeg' || $file_ext === 'ico' ||
        $file_ext === 'svg' || $file_ext === 'psd' || $file_ext === 'ps' ||
        $file_ext === 'tif' || $file_ext === 'tiff'
    )
    {
        $dir = 'Pictures';
    }

    //if file is an app
    elseif ($file_ext === 'exe' || $file_ext === 'rmp' || $file_ext === 'msi')
    {
        $dir = 'Applications';
    }

    // if file is video
    elseif (
        $file_ext === 'webm' || $file_ext === 'mpg' || $file_ext === 'mp2' ||
        $file_ext === 'mpeg' || $file_ext === 'mpv' || $file_ext === 'ogg' ||
        $file_ext === 'mp4' || $file_ext === 'm4p' || $file_ext === 'mv4' ||
        $file_ext === 'avi' || $file_ext === 'wmv' || $file_ext === 'mov' ||
        $file_ext === 'qt' || $file_ext === 'flv' || $file_ext === 'sqf' || $file_ext === 'avchd'
    )
    {
        $dir = 'Video';
    }

    else
    {
        $dir = 'Unknown';
    }
    return $dir;
}

##update_record
function updateRecord ($table , $set , $condition , $conn): bool
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

## drop table
function drop_table($table,$con): bool
{
    $sql = "DROP TABLE IF EXISTS $table";

    $stmt = $con->prepare($sql);
    if($stmt->execute())
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
    header("Location:".$_SERVER['HTTP_REFERER']);
    exit();
}

function error($message)
{
    $_SESSION['error'] = $message;
}

function GetIP()
{
    if ( getenv("HTTP_CLIENT_IP") ) {
        $ip = getenv("HTTP_CLIENT_IP");
    } elseif ( getenv("HTTP_X_FORWARDED_FOR") ) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
        if ( strstr($ip, ',') ) {
            $tmp = explode(',', $ip);
            $ip = trim($tmp[0]);
        }
    } else {
        $ip = getenv("REMOTE_ADDR");
    }
    return $ip;
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
            return true;
        }
        else
        {
//            br("Code : ".$code);
            return false;
        }
    }
}

## set session
function setSession($variable, $value)
{
    $_SESSION[$variable] = $value;
    return $value;
}

function getSession($variable)
{
    if(isset($_SESSION[$variable]))
    {
        return $_SESSION[$variable];
    }
}

function sess_unset($data)
{
    $exp = explode(',',$data);

    foreach ($data as $key => $value)
    {
        unset($_SESSION["$value"]);
    }

}

function download($data_file)
{
    //1- file we want to serve :

    $data_size = filesize($data_file); //Size is not zero base

    $mime = 'application/otect-stream'; //Mime type of file. to begin download its better to use this.
    $filename = basename($data_file); //Name of file, no path included

    //2- Check for request, is the client support this method?
    if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {
        $ranges_str = (isset($_SERVER['HTTP_RANGE']))?$_SERVER['HTTP_RANGE']:$HTTP_SERVER_VARS['HTTP_RANGE'];
        $ranges_arr = explode('-', substr($ranges_str, strlen('bytes=')));
        //Now its time to check the ranges
        if ((intval($ranges_arr[0]) >= intval($ranges_arr[1]) && $ranges_arr[1] != "" && $ranges_arr[0] != "" )
            || ($ranges_arr[1] == "" && $ranges_arr[0] == "")
        ) {
            //Just serve the file normally request is not valid :(
            $ranges_arr[0] = 0;
            $ranges_arr[1] = $data_size - 1;
        }
    } else { //The client dose not request HTTP_RANGE so just use the entire file
        $ranges_arr[0] = 0;
        $ranges_arr[1] = $data_size - 1;
    }

    //Now its time to serve file
    $file = fopen($data_file, 'rb');

    $start = $stop = 0;
    if ($ranges_arr[0] === "") { //No first range in array
        //Last n1 byte
        $stop = $data_size - 1;
        $start = $data_size - intval($ranges_arr[1]);
    } elseif ($ranges_arr[1] === "") { //No last
        //first n0 byte
        $start = intval($ranges_arr[0]);
        $stop = $data_size - 1;
    } else {
        // n0 to n1
        $stop = intval($ranges_arr[1]);
        $start = intval($ranges_arr[0]);
    }
    //Make sure the range is correct by checking the file

    fseek($file, $start, SEEK_SET);
    $start = ftell($file);
    fseek($file, $stop, SEEK_SET);
    $stop = ftell($file);

    $data_len = $stop - $start;

    //Lets send headers

    if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {
        header('HTTP/1.0 206 Partial Content');
        header('Status: 206 Partial Content');
    }
    header('Accept-Ranges: bytes');
    header('Content-type: ' . $mime);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header("Content-Range: bytes $start-$stop/" . $data_size );
    header("Content-Length: " . ($data_len + 1));
    ob_clean();

    //Finally serve data and done ~!
    fseek($file, $start, SEEK_SET);
    $bufsize = 2048000;

    ignore_user_abort(true);
    @set_time_limit(0);
    while (!(connection_aborted() || connection_status() == 1) && $data_len > 0) {
        echo fread($file, $bufsize);
        $data_len -= $bufsize;
        flush();
        session_write_close();
    }




    fclose($file);
}

// get sales
function get_sales($connection,$machine_number)
{
    // check if bill count exist
    $bill_count = "select COUNT(*) as row_count from bill_tran where mech_no = $machine_number;";
    $bill_count_stmt = $connection->query($bill_count);
    $bill_count_res = $bill_count_stmt->fetch(PDO::FETCH_ASSOC);
    if($bill_count_res['row_count'] < 1)
    {
        return '0';
    }
    else
    {
        $sql = "SELECT SUM( tran_amt ) AS sales  FROM bill_tran WHERE type_code In ('RS','VV','DS','PI','NF')and mech_no = $machine_number group by mech_no";
        $stmt = $connection->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result['sales'] > 0)
        {
            return $result['sales'];
        }
        else
        {
            return '0';
        }
    }



}

// get deduct
function get_deduct($connection,$machine_number)
{

    // check if bill count exist
    $bill_count = "select COUNT(*) as de_count from bill_tran where mech_no = $machine_number;";
    $bill_count_stmt = $connection->query($bill_count);
    $bill_count_res = $bill_count_stmt->fetch(PDO::FETCH_ASSOC);
    if($bill_count_res['de_count'] < 1)
    {
        return '0';
    }
    else
    {
        $sql = "SELECT mech_no, SUM( tran_amt )  AS gross_sales  FROM bill_tran  
        WHERE type_code In ('RR','RC','DR','DM','DP','MN','MP','W','DN','PO','DT') and mech_no = $machine_number group by mech_no ";
        $stmt = $connection->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['gross_sales'];
    }


}


