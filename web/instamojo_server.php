<?php

include "../src/Instamojo.php";

const apiKey = "Mze9rTwbtR7HVDjMD9LXJiTXwSKiA09w0MpfOkZA";
const apiSecret = "qLc7tBpbqoxbjdZ9UiZ1oa2ZObKSHDPENXzI1e5ePxo67HWcg4Z62jiOTaE6tVDWJ2cCbdqZz8fvByFm0yQltr7QYT4t2NMm4WKb6KEi7qIpNAzmXEjcfClGyOeGamtL";
const salt = "7c9c3a0b92f14ae791a27912cc6ae63e";
$client = new Devsupport\Client\InstamojoClient(apiKey, apiSecret, salt, "https://frozen-reef-67391.herokuapp.com/instamojo_server.php?action=handle_redirect", "https://frozen-reef-67391.herokuapp.com/instamojo_server.php?action=handle_redirect", "api");

$action = $_GET["action"];

if ($action == "new_transaction") {

  $params = array(
    'buyer_name' => $_GET["name"],
    'email' => $_GET["email"],
    'amount' => $_GET["amount"],
    'phone' => $_GET["phone"],
    'purpose' => $_GET["purpose"],
  );
  echo json_encode($client->NewTransaction($params));
} else if ($action == "handle_redirect") {
  $params = $_POST;
  $validatedResponse = $client->validateWebHookCall($params);
  if ($validatedResponse) {
    if ($_POST["status"] == "credit") {
      echo "Payment success";
    } else {
      echo "Payment Failed";
    }
  }


}