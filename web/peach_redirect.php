<?php

const apiKey = "undefined";
const apiSecret = "undefined";
const salt = "788239714ee94f2aaa5d85fc609eaa00";


class InstamojoClient
{
    const version = '1.0';
    private $apiKey;
    private $apiSecret;
    private $salt;
    private $token;

    public function __construct($apiKey, $apiSecret, $salt, $env = "test")
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->salt = $salt;
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

    public function validateWebHookCall($data)
    {

        if (!array_key_exists("mac", $data)) {
            return false;
        }

        $mac_provided = $data['mac'];  // Get the MAC from the POST data
        unset($data['mac']);  // Remove the MAC key from the data.
        $ver = explode('.', phpversion());
        $major = (int)$ver[0];
        $minor = (int)$ver[1];
        if ($major >= 5 and $minor >= 4) {
            ksort($data, SORT_STRING | SORT_FLAG_CASE);
        } else {
            uksort($data, 'strcasecmp');
        }
// You can get the 'salt' from Instamojo's developers page(make sure to log in first): https://www.instamojo.com/developers
// Pass the 'salt' without <>
        $mac_calculated = hash_hmac("sha1", implode("|", $data), $this->salt);
//    echo "Given hash: " . $mac_provided . "\n";
//    echo "Calculated hash: " . $mac_calculated . "\n";
        return $mac_provided == $mac_calculated;
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


$client = new InstamojoClient(apiKey, apiSecret, salt, "api");

$params = $_POST;
$validatedResponse = $client->validateWebHookCall($params);
if ($validatedResponse) {
    if ($_POST["status"] == "credit") {
        echo "Payment success";
    } else {
        echo "Payment Failed";
    }
} else {
}


