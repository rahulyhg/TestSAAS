<?php

namespace Ferelli\ERP;
use Exception;

class SASS {

    private $urlStore = "http://csaldivar.saasmexico.net/";
    private $timeout = 30;

    //Credenciales
    private $companyNumber = "128";
    private $sassUser = "administracion";
    private $sassPassword = "12345";

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

        /* $address    = !empty(implode(', ',array($street1, $street2, $street3))) ? implode(', ',array($street1, $street2, $street3)) : "N/A"; */

        if( !isset($firstName) ) throw new Exception('No se pudo acceder al nombre del cliente');
        if( !isset($lastName ) ) throw new Exception('No se pudo acceder al apellido del cliente');
        if( !isset($custEmail) ) throw new Exception('La orden no tiene un email');
        if( !isset($custRFC) ) throw new Exception('La orden no tiene RFC');

        $service = "modules/api/customers/";
        $method = "POST";
        $headers = array(
            "Company:".$this->companyNumber,
            "Password:".$this->sassPassword,
            "User:".$this->sassUser);

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
            else throw new Exception($customer['customer_email']." no pudo registrarse : ".$jsonResponse['msg']);

        } else {
            var_dump($jsonResponse);
            throw new Exception('No se recibio una respuesta valida del SASS');
        }

    }
}

?>