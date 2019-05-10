<?php

include_once('Magento.php');
include_once('DataBaseConnection.php');

use Ferelli\ERP\Magento as Magento;
use Ferelli\ERP\DataBaseConnection as DataBaseConnection;

$magento = new Magento();
$databaseConnection = new DataBaseConnection();

try{

    $token =  $magento -> getToken('lmarquez','Ferelli01!');
    $orders = $magento -> getOrders($token);

    var_dump($orders);

}catch(Exception $e){ echo $e->getMessage(); }






/* $url = "http://beautyangelsstore.mx/rest/V1/orders?".http_build_query($queryData); */
/* 

    $jsonArray = json_decode($response);
    //var_dump($jsonArray);
    $items = $jsonArray->items;
    if(sizeof($items) == 0){
        echo "No hay elementos";
    }
    foreach($items as $item){
       /*  echo "entre al for";
        var_dump($item); 
        echo $item->base_currency_code;
        echo "<br>";
        echo $item->base_grand_total;
        echo "<br>";
        echo $item->base_subtotal;
        echo "<br>";
        echo $item->customer_email;
        echo "<br>";
        echo $item->status;
        echo "<br>";
        echo $item->customer_firstname;
        echo "<br>";
        echo $item->customer_lastname;
        echo "<br>";

        echo "<br><br><br>";
    }
    echo "Fin del else";
 */


