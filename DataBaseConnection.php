<?php

namespace Ferelli\ERP;

class DataBaseConnection {
    
    private $server = "127.0.0.1";
    private $username = "root";
    private $password = "i-037fa87410e75e2e1"; //52.89.143.217 PRODUCCION
    //private $password = "i-0b7e8b7789aad56fa"; //54.202.124.81  PRE - PRODUCCION (DEV)
    private $databaseName;
    private $tableName = "ferelli_replica";
    private $clientsTable = "clientes_sass";
    private $connection; 
    private $db;

    public function connectDB(){
        $this->connection = mysqli_connect($this->server, $this->username, $this->password) or die ("No fue posible conectarse al servidor");
    }

    public function selectDBtoWork($database){
        $this->db = mysqli_select_db($this->connection, $database) or die ("No se encontro la base de datos");
        $this->databaseName = $database;
    }

    public function init(){
        $this->connectDB();
        $this->selectDBtoWork("magento");
    }

    public function getConfigurationData($referenceCode){
        $resultado = null;
        $this->init();
        $query = 'SELECT * FROM '.$this->tableName;
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        mysqli_close($this->connection);
        $arrayResult = mysqli_fetch_array($resultado);
        return $arrayResult;
    }
}



?>