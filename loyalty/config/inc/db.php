<?php 
    $servername = "192.168.2.3";
    $username = "anton";
    $password = "258963";
    
    try {
      $pdo = new PDO("mysql:host=$servername;dbname=smdesk", $username, $password);
      // set the PDO error mode to exception
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //   echo "Connected successfully";
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }

    try {
        $dsn = "sqlsrv:Server=192.168.2.4,1433;Database=UAT_INV";
        $conn = new PDO($dsn, "sa", "sa@123456");
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } catch(PDOException $e) {
        echo $err = $e->getMessage();
    }

?>
