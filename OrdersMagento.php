<?php

include_once('Magento.php');
include_once('DataBaseConnection.php');

use Ferelli\ERP\Magento as Magento;
use Ferelli\ERP\DataBaseConnection as DataBaseConnection;

$magento = new Magento();
$databaseConnection = new DataBaseConnection();

try{
    $running = $databaseConnection->initProccess();
    $configuration = $databaseConnection->getConfigurationData();

    if( !$running ) throw new Exception('Ya existe un proceso corriendo');

    if( !isset($configuration) )  throw new Exception('No se encontro la configuracion para iniciar');
    if( !is_array($configuration) ) throw new Exception('No se recibio una configuracion valida');
    
    $username = key_exists('username',$configuration) ? $configuration['username'] : NULL;
    $password = key_exists('password',$configuration) ? $configuration['password'] : NULL;
    $lastRun  = key_exists('last_run',$configuration) ? $configuration['last_run'] : NULL;

    if( !isset($username) ) throw new Exception('No se especifico un username');
    if( !isset($password) ) throw new Exception('No se especifico un password');
    if( !isset($lastRun) ) throw new Exception('Ultima fecha no es valida');

    $token =  $magento -> getToken($username, $password);
    $orders = $magento -> getOrders($token, $lastRun);

    var_dump($orders);

    if( !isset($orders) ) throw new Exception('No se recibio respuesta de la tienda');
    if( !is_array($orders) ) throw new Exception('No se recibio una respuesta valida');
    if( !key_exists('items',$orders) ) throw new Exception('No se regresaron items');

    foreach( $orders['items'] as $item){
        try{
            if( !isset($item['customer_firstname'])) throw new Exception('No se pudo acceder al nombre del cliente');
            if( !key_exists('customer_lastname',$item)) echo "No hay apellido";
        }catch(Exception $e){ echo $e->getMessage();}
    }


}catch(Exception $e){ echo $e->getMessage(); }



