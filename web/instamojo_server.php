<?php

include "../src/Instamojo.php";

const apiKey = "dNo97yFf7nDcq0QeYElTR5qvpnCssXpFr6BrBU2i";
const apiSecret = "WttpIlEcGCZVTmQWxkpff0ssQH2BkICAaK0ip0HoB998OKXoUMq4hJfpaccSiTkcUieMNIY826o3MOSKMgqkuspoizMTunLcLiqqgPdihQY885wafIn1JMBLzwtYPEIY";
const salt = "07c7cd4e440d4e978b9ed5f3392b2011";
$client = new Devsupport\Client\InstamojoClient(apiKey, apiSecret, salt, "http://localhost:8880/instamojo_server.php?action=redirect", "http://someplace.else:8880/instamojo_server.php?action=notify", "test");

$action = $_GET["action"];

if ($action == "new_transaction") {

  $params = array(
    'buyer_name' => $_GET["name"],
    'email' => $_GET["email"],
    'amount' => $_GET["amount"],
    'phone' => $_GET["phone"],
    'purpose' => $_GET["purpose"],
  );
//  var_dump($params);

  echo json_encode($client->NewTransaction($params));
} else if ($action == "handle_redirect") {
  echo json_encode($_REQUEST);

//  amount	9.00
//buyer	artpar@gmail.com
//currency	INR
//fees	0.17
//longurl	https://test.instamojo.com/@artpar/015d159975c0476dab162f507afa9abf
//purpose	rest
//shorturl
//status	Credit
//mac	1d2bb5e03bb645a0a03d97abdeab1f7d6863ee5a
//buyer_name	Parth
//buyer_phone	+919686989921
//payment_id	MOJO1027807067601719
//payment_request_id	015d159975c0476dab162f507afa9abf
//
  $validatedResponse = $client->validateWebHookCall($_POST);
  if ($validatedResponse) {
    if ($params["status"] == "credit") {
      echo "Payment success";
    } else {
      echo "Payment Failed";
    }
  }


}