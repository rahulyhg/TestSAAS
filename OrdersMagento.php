<?php

include_once 'Magento.php';
include_once 'DataBaseConnection.php';
include_once 'SASS.php';
include_once 'Email.php';

use Ferelli\ERP\Magento as Magento;
use Ferelli\ERP\DataBaseConnection as DataBaseConnection;
use Ferelli\ERP\SAAS as SAAS;
use Ferelli\ERP\Email as Email;

$magento = new Magento();
$databaseConnection = new DataBaseConnection();
$saas = new SAAS();
$email = new Email();

$newRun; //Variable que tendra la fecha de la ultima orden.
$errors; //Variable que almacenara los errores de cada orden

try{
    $running = $databaseConnection -> initProccess(); //Cambia el flag running en la DB de 0 a 1, regresa la cantidad de filas afectadas. 
    $configuration = $databaseConnection -> getConfigurationData(); //Hace un select a los datos de la DB (username, password, last run)

    if( !$running ) throw new ErrorException('Ya existe un proceso corriendo');

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

    $newRun = $lastRun; //La nueva fecha de corrida del script se inicializa con la actual.

    foreach( $orders['items'] as $item ){
        try{

            $order = $databaseConnection -> checkOrder($item['entity_id']);

            if( !isset($order) ) { /*Registrar Orden*/

                $userMagentoId = $item['customer_id'];
                $userSAAS = $databaseConnection -> checkUser($userMagentoId);
                $idUserSAAS = isset($userSAAS['id_saas']) ? $userSAAS['id_saas'] : NULL;
                $idUserBranchSAAS = isset($userSAAS['id_branch_saas']) ? $userSAAS['id_branch_saas'] : NULL;

                if( !isset($idUserSAAS) ) { /*Registrar Usuario*/

                    $responseUserSAAS = $saas -> addCustomer($item);
                    if( !isset($responseUserSAAS) ) throw new Exception("El usuario con ID ".$userMagentoId." no pudo ser registrado, Orden ".$item['increment_id']);
                    $databaseConnection->addUser($userMagentoId, $responseUserSAAS);
                    $idUserSAAS = $responseUserSAAS;

                }

                if( !isset($idUserBranchSAAS) ){ /*Registra el branch del usuario en la Base de datos*/
                    
                    $responseBranchSAAS = $saas -> getBranch($idUserSAAS, $item['increment_id']);
                    if( !isset($responseBranchSAAS) ) throw new Exception("El usuario con ID ".$userMagentoId." no tiene un branch en SAAS, Orden ".$item['increment_id']);
                    $databaseConnection -> addBranch($userMagentoId, $responseBranchSAAS);
                    $idUserBranchSAAS = $responseBranchSAAS;
                    
                }

                /*Registra la orden en la DB */ 
                $orderIdSAAS = $saas -> addSale($item, $idUserSAAS, $idUserBranchSAAS);
                if( !isset($orderIdSAAS) ) throw new Exception('No se pudo registrar la orden '.$item['increment_id'].' ante el SAAS');
                $databaseConnection -> addOrder($item['entity_id'],$item['increment_id'], $orderIdSAAS);

                if( isset($item['updated_at']) ){ //Se inicia el proceso de actualiza la fecha de corrida del script

                    $timeRun = strtotime($newRun);
                    $timeOrder = strtotime($item['updated_at']);
                    //$newRun = $timeOrder > $timeRun ? date_format( date_create($item['updated_at']) , 'Y-m-d' ) : $newRun;
                    $newRun = $timeOrder > $timeRun ? $item['updated_at'] : $newRun;

                }
                 
            }

        }catch(Exception $e){ 
            $errors = isset($errors) ? $errors : ""; 
            $errors = $errors.$e->getMessage()."\n \n";
        }

    }

    $databaseConnection -> addNewRun($newRun);
    $databaseConnection->finishProccess(); //Termina el proceso de ejecucion

    $errors = isset($errors) ? $errors : NULL;
    $email -> sendEmail($errors); //Envia un email con el resultado del script

}catch(ErrorException $e){ echo $e->getMessage(); }
catch(Exception $e){ echo $e->getMessage(); $databaseConnection->finishProccess(); }



