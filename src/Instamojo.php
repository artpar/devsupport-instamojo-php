<?php

namespace Devsupport\Client;

class InstamojoClient
{
  const version = '1.0';

  private $apiKey;
  private $apiSecret;
  private $token;


  public function __construct($apiKey, $apiSecret, $redirectUri = "http://localhost:8880/instamojo_server.php?action=redirect", $notifyUri = "http://someplace.com/instamojo_server.php?action=notify", $env = "test")
  {
    $this->apiKey = $apiKey;
    $this->apiSecret = $apiSecret;
    $this->redirectUri = $redirectUri;
    $this->notifyUri = $notifyUri;
    $this->env = $env;
  }

  public function getAccessToken()
  {

    $curl = curl_init("https://" . $this->env . ".instamojo.com/oauth2/token/");
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, rawurldecode(http_build_query(array(
      'client_id' => $this->apiKey,
      'client_secret' => $this->apiSecret,
      'grant_type' => 'client_credentials'
    ))));

    $json = json_decode(curl_exec($curl));
    if (isset($json->error)) {
      echo "Error: " . $json->error;
      throw new \Exception("Error: " . $json->error);
    }

//    echo "Generated new access token: " . $json->access_token;
    $this->token = $json;
//    var_dump($json)
    return $json->access_token;
  }

  public function __destruct()
  {

  }

  private function gen_uuid()
  {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
  }


  public function NewTransaction($params)
  {
//    echo "New transaction for " . $params["email"];
    $txn = $this->NewTransactionId();
//    echo "New transaction id " . $txn;

    $token = $this->getAccessToken();
    $paymentRequest = $this->createPaymentRequest($params, $token);
    if (!isset($paymentRequest->id)) {
      throw new \Exception(json_encode($paymentRequest));
    }
    $order = $this->createOrder($paymentRequest->id, $params["buyer_name"], $params["phone"], $token);
    return array(
      "transaction_id" => $txn,
      "payment_request" => $paymentRequest,
      "order" => $order,
      "token" => $token,
    );
  }


  private function NewTransactionId()
  {
    return $this->gen_uuid();
  }

  private function createPaymentRequest($params, $token)
  {

    $params["redirect_url"] = $this->redirectUri;
    $params["webhook"] = $this->notifyUri;

    $curl = curl_init("https://" . $this->env . ".instamojo.com/v2/payment_requests/");
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "Authorization: Bearer " . $token
    ));
    curl_setopt($curl, CURLOPT_POSTFIELDS, rawurldecode(http_build_query($params)));

    $json = json_decode(curl_exec($curl));
    if (isset($json->success) && !$json->success) {
      echo "Error: " . $json->message;
      throw new \Exception("Error: " . $json->message);
    }

    return $json;
  }

  private function createOrder($paymentRequestId, $name, $phone, $token)
  {

    $curl = curl_init("https://" . $this->env . ".instamojo.com/v2/gateway/orders/payment-request/");
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      "Authorization: Bearer " . $token
    ));
    curl_setopt($curl, CURLOPT_POSTFIELDS, rawurldecode(http_build_query(array(
      'id' => $paymentRequestId,
    ))));

    $json = json_decode(curl_exec($curl));
    if (isset($json->success) && !$json->success) {
      echo "Error: " . $json->message;
      throw new \Exception("Error: " . $json->message);
    }

    return $json;
  }


}

?>