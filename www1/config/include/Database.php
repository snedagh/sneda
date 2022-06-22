<?php



class db
{

    /**
     * @var false|mixed
     */
    private $debug;
    /**
     * @var string
     */
    private $sql_db;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $app;
    /**
     * @var string
     */
    private $database;

    public function __construct($hos,$debug = false)
    {

        if($hos === 'test1.sneda.gh')
        {
            $this->app = 'http://test1.sneda.gh';
            $this->host = "192.168.2.3";
            $this->user ="anton";
            $this->password = "258963";
            $this->database = "xxx";
            $this->sql_db = 'UAT_INV';
        }
        else
        {
            $this->app = 'http://www1.sneda.gh';
            $this->host = "192.168.2.3";
            $this->user ="anton";
            $this->password = "258963";
            $this->database = "xxx";
            $this->sql_db = 'SMSEXPV17';

        }

    }

    //connect to database
    public function connect()
    {

        try {
            $dns = 'mysql:host=' .$this->host . ';dbname=' . $this->database;
            $pdo = new PDO($dns,$this->user,$this->password);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (\Throwable $err)
        {
            return $err;
        }
    }

    //fetch row
    public function getRow($table, $condition): array
    {
        if($condition === 'none')
        {
            $sql = "SELECT * FROM `$table`";
        }
        else
        {
            $sql = "SELECT * FROM `$table` WHERE $condition LIMIT 1";
        }

        try
        {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        catch (\Throwable $err)
        {
            if($this->debug === true)
            {
                echo $err;
                die();
            }
            else
            {
                return false;
            }
        }

    }

    //insert
    public function insert($table, $column, $values)
    {
        $sql = "INSERT INTO `$table` ($column) VALUES ($values)";

        try
        {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();

            if($this->debug === true)
            {
                echo 'inserted';
            }
            return true;

        }
        catch (\Throwable $err)
        {
            if($this->debug === true)
            {
                echo $err;
                die();
            }
            else
            {
                return false;
            }
        }
    }

    // delete
    function delete($table, $condition):bool
    {
        ($condition === 'none') ?
            $sql = "DELETE FROM `$table`" : $sql = "DELETE FROM `$table` WHERE $condition" ;

        try {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            if($this->debug === true)
            {
                echo 'Deleted';
            }
            return true;
        } catch (\Throwable $err)
        {
            if($this->debug === true)
            {
                echo $err;
                die();
            }
            else
            {
                return false;
            }
        }
    }

    // update
    public function update($table, $set, $condition = 'none'): bool
    {
        if($condition === 'none')
        {
            $sql = "UPDATE `$table` SET $set";
        }
        else
        {
            $sql = "UPDATE `$table` SET $set WHERE $condition";
        }

        try
        {
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return true;
        }
        catch (\Throwable $err)
        {

            if($this->debug === true)
            {
                echo $err;
                die();
            }
            else
            {
                return false;
            }
        }
    }
}