<?php

namespace Devsupport\Tests;


/**
 * User: artpar
 * Date: 7/18/17
 * Time: 5:45 PM
 */
use Devsupport\Client\InstamojoClient;
use PHPUnit\Framework\TestCase;

class DevsupportInstamojoTest extends TestCase
{


  /**
   * @test
   */
  public function testGetAccessToken()
  {
    $client = new InstamojoClient(
      "Mze9rTwbtR7HVDjMD9LXJiTXwSKiA09w0MpfOkZA",
      "qLc7tBpbqoxbjdZ9UiZ1oa2ZObKSHDPENXzI1e5ePxo67HWcg4Z62jiOTaE6tVDWJ2cCbdqZz8fvByFm0yQltr7QYT4t2NMm4WKb6KEi7qIpNAzmXEjcfClGyOeGamtL",
      "api"
    );

    $token = $client->getAccessToken();
    $this->assertNotEquals("", $token, "Failed to generate access token");
  }


  public function testCreatePaymentRequest()
  {
    $client = new InstamojoClient(
      "Mze9rTwbtR7HVDjMD9LXJiTXwSKiA09w0MpfOkZA",
      "qLc7tBpbqoxbjdZ9UiZ1oa2ZObKSHDPENXzI1e5ePxo67HWcg4Z62jiOTaE6tVDWJ2cCbdqZz8fvByFm0yQltr7QYT4t2NMm4WKb6KEi7qIpNAzmXEjcfClGyOeGamtL",
      "api"
    );

    $params = array(
      "amount" => "9",
      "email" => "artpar@gmail.com",
      "purpose" => "test case",
      "redirect_url" => "http://localhost:8880/handle_redirect.php",
      "send_email" => true,
      "phone" => "9686989921",
      "name" => "Parth",
    );


    $paymentResponse = $client->NewTransaction($params);
    var_dump($paymentResponse);
    $this->assertNotEquals($paymentResponse["payment_request"]->id, "", "New payment request failed");
  }


}
