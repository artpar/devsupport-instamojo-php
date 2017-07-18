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
  private $apiKey = "dNo97yFf7nDcq0QeYElTR5qvpnCssXpFr6BrBU2i";
  private $apiSecret = "WttpIlEcGCZVTmQWxkpff0ssQH2BkICAaK0ip0HoB998OKXoUMq4hJfpaccSiTkcUieMNIY826o3MOSKMgqkuspoizMTunLcLiqqgPdihQY885wafIn1JMBLzwtYPEIY";

  private $salt = "07c7cd4e440d4e978b9ed5f3392b2011";

  public function testWebHookResponse()
  {
    $client = new InstamojoClient($this->apiKey, $this->apiSecret, $this->salt, "http://localhost:8880/instamojo_server.php?action=redirect", "http://someplace.else:8880/instamojo_server.php?action=notify", "test");

    $params = array(
      "amount" => "9.00",
      "buyer" => "artpar@gmail.com",
      "currency" => "INR",
      "fees" => "0.17",
      "longurl" => "https://test.instamojo.com/@artpar/015d159975c0476dab162f507afa9abf",
      "purpose" => "rest",
      "shorturl" => "",
      "status" => "Credit",
      "mac" => "ad2a6ad9961ba0c0d14cc072fbe8010af040739b",
      "buyer_name" => "Parth",
      "buyer_phone" => "+919686989921",
      "payment_id" => "MOJO9989784500079690",
      "payment_request_id" => "015d159975c0476dab162f507afa9abf",
    );
    $this->assertTrue($client->validateWebHookCall($params), "Hash check failed");
  }


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
