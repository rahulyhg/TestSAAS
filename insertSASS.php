<?php

//Credenciales
$companyNumber = "128";
$sassUser = "administracion";
$sassPassword = "12345";

//Conexion
$urlWS = "http://csaldivar.saasmexico.net/modules/api/";
$endpoint = "sales/";
$method = "POST";
$idUser = "1271";

//Headers
$headers = array(
    "Company:".$companyNumber,
    "Password:".$sassPassword,
    "User:".$sassUser);

//Data
$postData = array(
  'trans_type' => '30',
  'ref' => 'auto',
  'comments' => 'Esta es un nuevo registro test',
  'order_date' => '2019-05-16',
  'delivery_date' => '2019-05-16',
  'cust_ref' => 'TEST',
  'deliver_to' => 'Eliezer',
  'deliver_address' => 'Test',
  'phone' => '8110111256',
  'ship_via' => '1',
  'location' => 'DEF',
  'freight_cost' => '0',
  'email' => 'eliezer.garza@ferelli.com.mx',
  'customer_id' => '1271',
  'branch_id' => '1188',
  'sales_type' => '1',
  'dimension_id' => 0,
  'dimension2_id' => 0,
  'payment' => '4',
  'items' => array(
    array(
      'stock_id' => '005',
      'qty' => '1',
      'price' => 10,
      'discount' => 0,
      'description'=>'TEST')
      )
  );

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $urlWS.$endpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_POSTFIELDS => http_build_query($postData),
    CURLOPT_HTTPHEADER => $headers)); 

// CURLOPT_POSTFIELDS => "custname=Eliezer%20Test&cust_ref=eliezer.garza%40ferelli.com.mx&address=Sierra%20de%20M%C3%A9rida%20%238008&tax_id=GAME750911QP3&curr_code=MXN&credit_status=1&payment_terms=4&discount=0&pymt_discount=0&credit_limit=10000&sales_type=1&cfdi_street=Sierra%20de%20M%C3%A9rida&cfdi_street_number=8008&cfdi_district=Sierra%20&cfdi_postal_code=67190&cfdi_city=Guadalupe&cfdi_state=Nuevo%20Leon&cfdi_country=M%C3%A9xico&client_no=1271",

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

var_dump($response);

$jsonArray2 = json_decode($response); //Regresa un null de ser un string

var_dump($jsonArray2);

/* 
if ($err) {
  echo "cURL Error #:" . $err;
} else {

  $jsonArray2 = json_decode($response,true); 
  var_dump($jsonArray2);

  echo "<br><br>NUMERO DE USER NUEVO : ";
  echo ($jsonArray2['debtor_no']);
  echo "FIN<br><br>";

  echo "<br><br>ERROR MSG : ";
  echo ($jsonArray2['msg']);
  echo "<br>FIN<br><br>";

  if($jsonArray2['success'] == 1){
    echo $jsonArray2['msg'];
    print_r($jsonArray2);
  }else{
    echo $response;
  } 

 
}*/