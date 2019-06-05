<?php

namespace Ferelli\ERP;
use Exception;

class SAAS {

    private $urlStore = "http://csaldivar.saasmexico.net/";
    private $timeout = 30;

    //Credenciales
    private $companyNumber = "128";
    private $saasUser = "administracion";
    private $saasPassword = "12345";

    public function addCustomer($customer){

        $firstName  = isset($customer['customer_firstname'])            ? $customer['customer_firstname'] : NULL;
        $lastName   = isset($customer['customer_lastname'])             ? $customer['customer_lastname'] : NULL;
        $custEmail  = isset($customer['customer_email'])                ? $customer['customer_email'] : NULL;
        $custRFC    = isset($customer['billing_address']['vat_id'])     ? $customer['billing_address']['vat_id'] : ' - ';
        $city       = isset($customer['billing_address']['city'])       ? $customer['billing_address']['city'] : "N/A";
        $state      = isset($customer['billing_address']['region'])     ? $customer['billing_address']['region'] : "N/A";
        $country    = isset($customer['billing_address']['country_id']) ? $customer['billing_address']['country_id'] : "N/A";
        $postalCode = isset($customer['billing_address']['postcode'])   ? $customer['billing_address']['postcode'] : "N/A";
        $street1    = isset($customer['billing_address']['street'][0])  ? $customer['billing_address']['street'][0] : "N/A";
        $street2    = isset($customer['billing_address']['street'][1])  ? $customer['billing_address']['street'][1] : "N/A";
        $street3    = isset($customer['billing_address']['street'][2])  ? $customer['billing_address']['street'][2] : "N/A";
        $address    = implode(', ',array($street1, $street2, $street3));

        if( !isset($firstName) ) throw new Exception('El cliente no tiene nombre, Orden '.$customer['increment_id']);
        if( !isset($lastName ) ) throw new Exception('El cliente no tiene apellido, Orden '.$customer['increment_id']);
        if( !isset($custEmail) ) throw new Exception('El cliente no tiene email, Orden '.$customer['increment_id']);
        /* if( !isset($custRFC) ) throw new Exception('La orden no tiene RFC'); */

        $service = "modules/api/customers/";
        $method = "POST";
        $headers = array(
            "Company:".$this->companyNumber,
            "Password:".$this->saasPassword,
            "User:".$this->saasUser);

        $postData = array(
            'custname' => $firstName.' '.$lastName,
            'cust_ref' => $custEmail,
            'address' => $address,
            'tax_id' => $custRFC,
            'curr_code' => 'MXN',
            'credit_status' => '1',
            'payment_terms' => '4',
            'discount' => '0',
            'pymt_discount' => '0',
            'credit_limit' => '10000',
            'sales_type' => '1',
            'cfdi_street' => $street1,
            'cfdi_street_number' => $street3,
            'cfdi_district' => $street2,
            'cfdi_postal_code' => $postalCode,
            'cfdi_city' => $city,
            'cfdi_state' => $state,
            'cfdi_country' => $country,
            'client_no' => '0');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->urlStore.$service,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => $headers));

        $response = curl_exec($curl);
        $jsonResponse = json_decode($response,true); //Convierte el response a un array o de no serlo, a un String

        if( is_array($jsonResponse) ){

            if( isset($jsonResponse['debtor_no']) ) return $jsonResponse['debtor_no'];
            else throw new Exception('El usuario con email '.$customer['customer_email'].'no pudo registrarse : '.$jsonResponse['msg'].', Orden '.$customer['increment_id']);

        } else throw new Exception('La respuesta del SAAS no es valida, Orden '.$customer['increment_id'].' Response : '.print_r($jsonResponse));


    }

    public function getBranch($idSAAS,$incrementalOrderId){

        $service = "modules/api/customers/".$idSAAS."/branches/";
        $method = "GET";
        $headers = array(
            "Company:".$this->companyNumber,
            "Password:".$this->saasPassword,
            "User:".$this->saasUser);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->urlStore.$service,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers));

        $response = curl_exec($curl);
        $jsonResponse = json_decode($response,true); //Convierte el response a un array o de no serlo, a un String

        if( is_array($jsonResponse) ){

            if( isset($jsonResponse[0]['branch_code']) ) return $jsonResponse[0]['branch_code'];
            else throw new Exception('No se pudo obtener el branch ID del usuario '.$idSAAS.' : '.$jsonResponse['msg'].' Orden '.$incrementalOrderId);

        } else throw new Exception('No se recibio una respuesta valida del SAAS, Orden : '.$incrementalOrderId.' Response : '.print_r($jsonResponse));

    }

    public function addSale($order, $idUserSAAS, $idUserBranchSAAS){

        $firstName  = isset($order['customer_firstname'])            ? $order['customer_firstname'] : NULL;
        $lastName   = isset($order['customer_lastname'])             ? $order['customer_lastname'] : NULL;
        $custEmail  = isset($order['customer_email'])                ? $order['customer_email'] : NULL;
        $shipAmount = isset($order['shipping_amount'])               ? $order['shipping_amount'] : 0;
        $shipId     = isset($order['increment_id'])                  ? $order['increment_id'] : "N/A";
        $phone      = isset($order['billing_address']['telephone'])  ? $order['billing_address']['telephone'] : "N/A";
        $street1    = isset($order['billing_address']['street'][0])  ? $order['billing_address']['street'][0] : "N/A";
        $street2    = isset($order['billing_address']['street'][1])  ? $order['billing_address']['street'][1] : "N/A";
        $street3    = isset($order['billing_address']['street'][2])  ? $order['billing_address']['street'][2] : "N/A";
        $orderDate  = isset($order['created_at'])                    ? date_format(date_create($order['created_at']), 'Y-m-d') : "N/A";
        $address    = implode(', ',array($street1, $street2, $street3));
        
        if( !isset($firstName) ) throw new Exception('El cliente no tiene nombre, Orden '.$order['increment_id']);
        if( !isset($lastName ) ) throw new Exception('El cliente no tiene apellido, Orden '.$order['increment_id']);
        if( !isset($custEmail) ) throw new Exception('El cliente no tiene email, Orden '.$order['increment_id']);

        $service = "modules/api/sales/";
        $method = "POST";
        $headers = array(
            "Company:".$this->companyNumber,
            "Password:".$this->saasPassword,
            "User:".$this->saasUser);

        $items = array();

        foreach( $order['items'] as $item ){

            $itemArray = array(
                'stock_id' => $item['sku'],
                'qty' => $item['qty_ordered'],
                'price' => $item['price'],
                'discount' => $item['discount_amount']/100,
                'description'=> $item['name'] );

            array_push($items,$itemArray);
        }

        $postData = array(
            'trans_type' => '30',
            'ref' => 'auto',
            'comments' => $shipId,
            'order_date' => $orderDate,
            'delivery_date' => $orderDate,
            'cust_ref' => 'F800',
            'deliver_to' => $firstName.' '.$lastName,
            'deliver_address' => $address,
            'phone' =>  $phone,
            'ship_via' => '1',
            'location' => 'DEF',
            'freight_cost' => $shipAmount,
            'email' => $custEmail,
            'customer_id' => $idUserSAAS,
            'branch_id' => $idUserBranchSAAS,
            'sales_type' => '1',
            'dimension_id' => 0,
            'dimension2_id' => 0,
            'payment' => '4',
            'items' => $items
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->urlStore.$service,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => $headers));

        $response = curl_exec($curl);
        $jsonResponse = json_decode($response,true); //Convierte el response a un array o de no serlo, a un String

        if( is_array($jsonResponse) ) throw new Exception("La orden ".$order['increment_id']." no pudo darse de alta en el SAAS : ".$jsonResponse['msg']);
        else {
            $numberOrder = preg_replace('/\D/', '', $response);

            if( is_numeric($numberOrder) ) return $numberOrder;
            else throw new Exception('No se recibio el ID del a orden '.$order['increment_id'].' ante el SAAS : '.$response);
        }

    } 
}

?>