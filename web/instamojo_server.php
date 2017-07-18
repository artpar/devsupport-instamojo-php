<?php

include "../src/Instamojo.php";

const apiKey = "dNo97yFf7nDcq0QeYElTR5qvpnCssXpFr6BrBU2i";
const apiSecret = "WttpIlEcGCZVTmQWxkpff0ssQH2BkICAaK0ip0HoB998OKXoUMq4hJfpaccSiTkcUieMNIY826o3MOSKMgqkuspoizMTunLcLiqqgPdihQY885wafIn1JMBLzwtYPEIY";


$action = $_GET["action"];

if ($action == "new_transaction") {
  $client = new Devsupport\Client\InstamojoClient(apiKey, apiSecret, "http://localhost:8880/instamojo_server.php?action=redirect", "http://someplace.else:8880/instamojo_server.php?action=notify", "test");

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
}