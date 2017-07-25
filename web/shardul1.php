<?php

$secret_key = 'becec050531547703395a6f2c43c7cf7e34bb74f';
$access_key = 'M9OZT7LFHPCK91UDVJC8';

function elig_sign($email, $mobile, $amount) {
 $hash_before = $email . $mobile . $amount . 'INR';
 $signature = hash_hmac('sha1', $hash_before, $GLOBALS['secret_key']);
 echo $signature;
}

function initpay_sign($txnid, $amount) {
$hash_before = 'merchantAccessKey=' . $GLOBALS['access_key'] . '&' . 'transactionId=' . $txnid . "&"
                . "amount=" . $amount;
$signature = hash_hmac('sha1', $hash_before, $GLOBALS['secret_key']);
 echo $signature;
}

function auto_debitsign($txnid, $amount) {
  initpay_sign($txnid, $amount);
}

function otpsign($txReferencenum) {
$hash_before = 'merchantAccessKey=' . $GLOBALS['access_key'] . '&' . 'txnRefNo=' . $txReferencenum;
$signature = hash_hmac('sha1', $hash_before, $GLOBALS['secret_key']);
echo $signature;
}

$sign_type = $_POST['type'];

if ($sign_type == 'elligibility') {
$amount = $_POST['amount'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
elig_sign($email, $mobile, $amount);
}

else if ($sign_type == 'initpay') {
  $txnId = $_POST['txnId'];
  $amount = $_POST['amount'];
  initpay_sign($txnId, $amount);
}

else if ($sign_type == 'autodebit') {
  $txnId = $_POST['txnId'];
  $amount = $_POST['amount'];
  auto_debitsign($txnId, $amount);
}

else if ($sign_type == 'otpsign') {
  $txnref = $_POST['txnref'];
  otpsign($txnref);
}
else {
 echo 'Wrong signature type';
}

?>