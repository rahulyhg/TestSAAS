<?php

namespace Ferelli\ERP;
use Exception;

class Magento {

    private $urlStore = "http://beautyangelsstore.mx/";
    //private $urlStore = "http://54.202.124.81/";
    private $timeout = 30;

    public function getToken($user, $password){
        $service = "rest/V1/integration/admin/token/";
        $method = "POST";
        $tokenData = array(
            'username' => $user,
            'password' => $password);
        $headers = array(
            "Content-Type : application/json");

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->urlStore.$service,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $tokenData,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers));
    
        $response = curl_exec($curl);
        $jsonResponse = json_decode($response,true); //Convierte el response a un array o de no serlo, a un String

        if( is_array($jsonResponse) ) throw new Exception($jsonResponse['message']);
        else return $jsonResponse;

        /* return is_array($jsonResponse) ? throw new Exception($jsonResponse['message']) : $jsonResponse; */
    }

    public function getOrders($token, $lastDate){
        $service = "rest/V1/orders?";
        $method = "GET";
        $headers = array(
            "Content-Type : application/json",
            "Authorization: Bearer ".$token);
        $queryData = array(
            'searchCriteria[filterGroups][0][filters][0][field]' => 'status',
            'searchCriteria[filterGroups][0][filters][0][value]' => 'processing',
            'searchCriteria[filterGroups][0][filters][0][condition_type]' => 'eq',
            'searchCriteria[filterGroups][0][filters][1][field]' => 'status',
            'searchCriteria[filterGroups][0][filters][1][value]' => 'complete',
            'searchCriteria[filterGroups][0][filters][1][condition_type]' => 'eq',
            'searchCriteria[filterGroups][1][filters][0][field]' => 'updated_at',
            'searchCriteria[filterGroups][1][filters][0][value]' => $lastDate,
            'searchCriteria[filterGroups][1][filters][0][condition_type]' => 'gteq');
        $httpQuery = http_build_query($queryData);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->urlStore.$service.$httpQuery,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers));

        $response = curl_exec($curl);
        $jsonResponse = json_decode($response,true); //Convierte el response a un array o de no serlo, a un String

        if(key_exists('message',$jsonResponse)) throw new Exception($jsonResponse['message']);
        else return $jsonResponse;

    }

}

?>