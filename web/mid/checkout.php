<?php

  $transaction = file_get_contents('php://input');

  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://app.sandbox.midtrans.com/snap/v1/transactions",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $transaction,
  CURLOPT_HTTPHEADER => array(
    "accept: application/json",
    "Authorization: Basic VlQtc2VydmVyLWp4ZW96b21zVG1Ebkx1UlEyWlFkZU52Njo=", 
"cache-control: no-cache",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}