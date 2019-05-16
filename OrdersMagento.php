<?php

include_once('Magento.php');
include_once('DataBaseConnection.php');
include_once('SASS.php');

use Ferelli\ERP\Magento as Magento;
use Ferelli\ERP\DataBaseConnection as DataBaseConnection;
use Ferelli\ERP\SASS as SASS;


$magento = new Magento();
$databaseConnection = new DataBaseConnection();
$sass = new SASS();

try{
    $running = $databaseConnection -> initProccess(); //Cambia el flag running en la DB de 0 a 1, regresa la cantidad de filas afectadas. 
    $configuration = $databaseConnection -> getConfigurationData(); //Hace un select a los datos de la DB (username, password, last run)

   /*  if( !$running ) throw new ErrorException('Ya existe un proceso corriendo'); */

    if( !isset($configuration) )  throw new Exception('No se encontro la configuracion para iniciar');
    if( !is_array($configuration) ) throw new Exception('No se recibio una configuracion valida');
    
    $username = key_exists('username',$configuration) ? $configuration['username'] : NULL;
    $password = key_exists('password',$configuration) ? $configuration['password'] : NULL;
    $lastRun  = key_exists('last_run',$configuration) ? $configuration['last_run'] : NULL;

    if( !isset($username) ) throw new Exception('No se especifico un username');
    if( !isset($password) ) throw new Exception('No se especifico un password');
    if( !isset($lastRun) ) throw new Exception('Ultima fecha no es valida');

    $token =  $magento -> getToken($username, $password); //Obtiene el token de autenticacion para obtener las ordenes de la tienda
    $orders = $magento -> getOrders($token, $lastRun); //Realiza la consulta a Magento de las ordenes.

    if( !isset($orders) ) throw new Exception('No se recibio respuesta de la tienda');
    if( !is_array($orders) ) throw new Exception('No se recibio una respuesta valida');
    if( !key_exists('items',$orders) ) throw new Exception('No se regresaron items');

    foreach( $orders['items'] as $item){
        try{

            if( !isset($item['entity_id']) ) throw new Exception('La orden no tiene un ID');
            $order = $databaseConnection -> checkOrder($item['entity_id']);

            if( !isset($order) ) {
                echo "REGISTRAR ORDEN<br>";

                $userMagentoId = $item['customer_id'];
                $userSASS = $databaseConnection -> checkUser($userMagentoId);

                if( !isset($userSASS) ) {

                    $sassUserId = $sass -> addCustomer($item);
                    if( !isset($sassUserId) ) throw new Exception('No se pudo registrar al usuario con ID '.$userMagentoId);
                    $successRegister = $databaseConnection->addUser($userMagentoId, $sassUserId);

                    $userSASS = $sassUserId;

                }else{ $userSASS = $userSASS['id_sass']; }
                
                //Aqui se debe registrar ya la orden

            }else{
               echo "La orden ".$item['entity_id']." ya esta registrada"; 
            }

            echo '<br><br><br>';
            
        }catch(Exception $e){ echo "EXCEPTION : "; echo $e->getMessage(); echo " [Enviar correo] <br><br>"; }
    }


}catch(ErrorException $e){ echo $e->getMessage(); }
catch(Exception $e){ echo $e->getMessage(); $databaseConnection->finishProccess(); }



