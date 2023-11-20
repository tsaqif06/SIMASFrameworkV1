<?php

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;

    private $dbh, $stmt; // dbh = database handler, stmt = statement/buat nympan query

    public function __construct($db_name)
    {
        $dsn = "mysql:host={$this->host};dbname={$db_name}";

        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $option);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($params, $value, $type = null) // binding data agar terhindar dari sql injection
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($params, $value, $type);
    }

    public function execute() // eksekusi query
    {
        $this->stmt->execute();
    }

    public function fetch($mode = PDO::FETCH_ASSOC) // mengambil satu data
    {
        $this->execute();
        return $this->stmt->fetch($mode);
    }

    public function fetchAll($mode = PDO::FETCH_ASSOC, $param = null) // mengambil banyak data
    {
        $this->execute();
        return ($param) ? $this->stmt->fetchAll($mode, $param) : $this->stmt->fetchAll($mode) ;
    }

    public function rowCount() // mengambil jumlah row
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() // mengambil id dari data yang paling terbaru
    {
        return $this->dbh->lastInsertId();
    }
}
