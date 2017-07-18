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