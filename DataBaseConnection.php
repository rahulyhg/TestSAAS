<?php

namespace Ferelli\ERP;

class DataBaseConnection {
    
    private $server = "127.0.0.1";
    private $username = "root";
    private $password = ""; //Localhost
    //private $password = "i-037fa87410e75e2e1"; //52.89.143.217 PRODUCCION
    //private $password = "i-0b7e8b7789aad56fa"; //54.202.124.81  PRE - PRODUCCION (DEV)
    private $databaseName;
    private $tableName = "ferelli_replica";
    private $ordersTable = "ferelli_ordenes";
    private $clientsTable = "ferelli_clientes";
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

    public function getConfigurationData(){
        $resultado = null;

        $this->init();
        $query = 'SELECT * FROM '.$this->tableName;
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        mysqli_close($this->connection);

        $arrayResult = mysqli_fetch_array($resultado);

        return $arrayResult;
    }

    public function initProccess(){
        $resultado = null;

        $this->init();
        $query = 'UPDATE '.$this->tableName.' SET running = 1 WHERE running = 0';
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        $rowsAffected = mysqli_affected_rows($this->connection);
        mysqli_close($this->connection);

        return $rowsAffected;
    }

    public function checkOrder($entityIdMagento){
        $resultado = null;

        $this->init();
        $query = 'SELECT * FROM '.$this->ordersTable.' WHERE id_entity_magento='.$entityIdMagento.'';
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        mysqli_close($this->connection);

        $arrayResult = mysqli_fetch_array($resultado);

        return $arrayResult;
    }

    public function checkUser($idUserMagento){
        $resultado = null;

        $this->init();
        $query = 'SELECT * FROM '.$this->clientsTable.' WHERE id_magento='.$idUserMagento.'';
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        mysqli_close($this->connection);

        $arrayResult = mysqli_fetch_array($resultado);

        return $arrayResult;
    }

    public function addUser($idUserMagento, $idUserSAAS){
        $this->init();

        $query = 'INSERT INTO '.$this->clientsTable.' ( id_magento, id_saas ) VALUES ('.$idUserMagento.', '.$idUserSAAS.')';
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        mysqli_close($this->connection);

    }

    public function addNewRun($newDate){
        $this->init();

        $query = "UPDATE ".$this->tableName." SET last_run ='".$newDate."'";
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        mysqli_close($this->connection);

    }

    public function addBranch($idUserMagento, $idBranchSAAS){
        $this->init();

        $query = 'UPDATE '.$this->clientsTable.' SET id_branch_saas = '.$idBranchSAAS.' WHERE id_magento = '.$idUserMagento;
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        mysqli_close($this->connection);
    }

    public function addOrder($idOrderMagento, $incrementalMagentoId, $idOrderSAAS){
        $this->init();

        $query = 'INSERT INTO '.$this->ordersTable.' ( id_entity_magento, id_orden_magento, id_orden_saas ) VALUES ('.$idOrderMagento.', '.$incrementalMagentoId.', '.$idOrderSAAS.')';
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        mysqli_close($this->connection);

    }

    public function finishProccess(){
        $resultado = null;

        $this->init();
        $query = 'UPDATE '.$this->tableName.' SET running = 0 WHERE running = 1';
        $resultado = mysqli_query($this->connection , $query ) or die(mysqli_error($this->connection));
        $rowsAffected = mysqli_affected_rows($this->connection);
        mysqli_close($this->connection);
        
        return $rowsAffected;
    }

}



?>