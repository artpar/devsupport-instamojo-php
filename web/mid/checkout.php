<?php
require_once(dirname(__FILE__) . '/Veritrans.php');
//Set Your server key
Veritrans_Config::$serverKey = "VT-server-jxeozomsTmDnLuRQ2ZQdeNv6";
// Uncomment for production environment
// Veritrans_Config::$isProduction = true;
Veritrans_Config::$isSanitized = Veritrans_Config::$is3ds = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    "authorization: Basic VlQtc2VydmVyLWp4ZW96b21zVG1Ebkx1UlEyWlFkZU52Njo=",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 6657b615-d58c-bb7a-8aa1-2527a839d582"
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
