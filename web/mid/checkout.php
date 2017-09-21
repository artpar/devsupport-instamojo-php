<?php
require_once(dirname(__FILE__) . '/Veritrans.php');
//Set Your server key
Veritrans_Config::$serverKey = "VT-server-jxeozomsTmDnLuRQ2ZQdeNv6";
// Uncomment for production environment
// Veritrans_Config::$isProduction = true;
Veritrans_Config::$isSanitized = Veritrans_Config::$is3ds = true;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {


// Required
$transaction_details = array(
  'order_id' => rand(),
  'gross_amount' => $_REQUEST["amount"], // no decimal allowed for creditcard
);


// Fill transaction details
$transaction = array(
  'transaction_details' => $transaction_details,
);

$snapToken = Veritrans_Snap::getSnapToken($transaction);
$response = Array();
$response["token"] = $snapToken;
echo json_encode($response);
} else {
?>

<!DOCTYPE html>
<html>
  <body>
    <button id="pay-button">Pay!</button>
<!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="VT-client-zqEBdN6gR8txZevh"></script>
    <script type="text/javascript">

      function getToken(amount, callback)
      {
          var formData = new FormData();
          formData.append("amount", amount);
          var xmlHttp = new XMLHttpRequest();
              xmlHttp.onreadystatechange = function()
              {
                  if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
                  {
                      callback(xmlHttp.responseText);
                  }
              }
              xmlHttp.open("post", "checkout.php");
              xmlHttp.send(formData);
      }


      document.getElementById('pay-button').onclick = function(){
        // SnapToken acquired from previous step
        getToken("900", function(response){
          console.log("new token response", response);
        response = JSON.parse(response);
        snap.pay(response.token);
        })
      };
    </script>
  </body>
</html>
<? }
