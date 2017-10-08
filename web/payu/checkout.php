<?php
// Merchant key here as provided by Payu
$MERCHANT_KEY = "gtKFFx"; //Please change this value with live key for production
   $hash_string = '';
// Merchant Salt as provided by Payu
$SALT = "eCwWELxi";    //Please change this value with live salt for production

// End point - change to https://secure.payu.in for LIVE mode
$PAYU_BASE_URL = "https://test.payu.in";

$action = '';

$posted = array();
if(!empty($_POST)) {
    //print_r($_POST);
  foreach($_POST as $key => $value) {    
    $posted[$key] = $value; 
 
  }
}

$formError = 0;

if(empty($posted['txnid'])) {
   // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['pg'])
          || empty($posted['bankcode'])
          || empty($posted['ccnum'])
          || empty($posted['ccname'])
          || empty($posted['ccvv'])
          || empty($posted['ccexpmon'])
          || empty($posted['ccexpyr'])
         
  ) {
    $formError = 1;
  } else {
    
 $hashVarsSeq = explode('|', $hashSequence);
 
 foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

    $hash_string .= $SALT;


    $hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}
?>
<html>
  <head>
  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
  </head>
  <body onload="submitPayuForm()">
    <h2>PayU Seamless integration</h2>
    <br/>
    <?php if($formError) { ?>
      <span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" name="payuForm" >
      <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
      <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
  
     <input type="hidden" name="surl" value="http://frozen-reef-67391.herokuapp.com/payu/response.php" />   <!--Please change this parameter value with your success page absolute url like http://mywebsite.com/response.php. -->
    <input type="hidden" name="furl" value="http://frozen-reef-67391.herokuapp.com/payu/response.php" /><!--Please change this parameter value with your failure page absolute url like http://mywebsite.com/response.php. -->
   
      <table>
        <tr>
          <td><b>Mandatory Parameters</b></td>
        </tr>
        <tr>
          <td>Amount: </td>
          <td><input name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>" /></td>
          <td>First Name: </td>
          <td><input name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" /></td>
        </tr>
        <tr>
          <td>Email: </td>
          <td><input name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>" /></td>
          <td>Phone: </td>
          <td><input name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>" /></td>
        </tr>
        <tr>
          <td>Product Info: </td>
          <td colspan="3"><textarea name="productinfo"><?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?></textarea></td>
        </tr>
        

         <tr>
          <td><b>Payment Details</b></td>
        </tr>
        <tr>
          <td>pg: </td>
          <td>
            <select name="pg">


                <option  <?php echo ($posted['pg'] == 'CC') ? 'selected' : '' ?> value="CC">Credit Card</option>
              <option  <?php echo ($posted['pg'] == 'DC') ? 'selected' : '' ?> value="DC">Debit Card</option>
              <option  <?php echo ($posted['pg'] == 'CASH') ? 'selected' : '' ?> value="CASH">Cash</option>
              <option  <?php echo ($posted['pg'] == 'EMI') ? 'selected' : '' ?> value="EMI">EMI</option>
              <option  <?php echo ($posted['pg'] == 'NB') ? 'selected' : '' ?> value="NB">Net banking</option>


              </select>
          </td>
          <td>bankcode: </td>
          <td>
            <select name="bankcode">

<option  <?php echo ($posted['bankcode'] == '162B') ? 'selected' : '' ?> value="162B">162 Bank</option>
<option  <?php echo ($posted['bankcode'] == 'AXIB') ? 'selected' : '' ?> value="AXIB">Axis Bank</option>
<option  <?php echo ($posted['bankcode'] == 'BOIB') ? 'selected' : '' ?> value="BOIB">Bank of India</option>
<option  <?php echo ($posted['bankcode'] == 'CABB') ? 'selected' : '' ?> value="CABB">CAB</option>
<option  <?php echo ($posted['bankcode'] == 'CBIB') ? 'selected' : '' ?> value="CBIB">Central bank of India</option>
<option  <?php echo ($posted['bankcode'] == 'CITNB') ? 'selected' : '' ?> value="CITNB">Citi Bank</option>
<option  <?php echo ($posted['bankcode'] == 'DSHB') ? 'selected' : '' ?> value="DSHB">DSHB</option>
<option  <?php echo ($posted['bankcode'] == 'ICIB') ? 'selected' : '' ?> value="ICIB">ICICI Bank</option>
<option  <?php echo ($posted['bankcode'] == 'INIB') ? 'selected' : '' ?> value="INIB">INI Bank</option>
<option  <?php echo ($posted['bankcode'] == 'JAKB') ? 'selected' : '' ?> value="JAKB">Jammu and Kashmir Bank</option>
<option  <?php echo ($posted['bankcode'] == 'KRKB') ? 'selected' : '' ?> value="KRKB">KRK Bank</option>
<option  <?php echo ($posted['bankcode'] == 'KRVB') ? 'selected' : '' ?> value="KRVB">Karur Vysasya Bank</option>
<option  <?php echo ($posted['bankcode'] == 'PNBB') ? 'selected' : '' ?> value="PNBB">Punjab National Bank</option>
<option  <?php echo ($posted['bankcode'] == 'SBBJB') ? 'selected' : '' ?> value="SBBJB">SBB JB</option>
<option  <?php echo ($posted['bankcode'] == 'SBIB') ? 'selected' : '' ?> value="SBIB">State Bank Of India</option>
<option  <?php echo ($posted['bankcode'] == 'SBTB') ? 'selected' : '' ?> value="SBTB">State Bank of Travancore</option>
<option  <?php echo ($posted['bankcode'] == 'SOIB') ? 'selected' : '' ?> value="SOIB">SOI Bank</option>
<option  <?php echo ($posted['bankcode'] == 'UBIB') ? 'selected' : '' ?> value="UBIB">UBI Bank</option>
<option  <?php echo ($posted['bankcode'] == 'UNIB') ? 'selected' : '' ?> value="UNIB">Union Bank of India</option>
<option  <?php echo ($posted['bankcode'] == 'VJYB') ? 'selected' : '' ?> value="VJYB">Vijaya Bank</option>
<option  <?php echo ($posted['bankcode'] == 'YESB') ? 'selected' : '' ?> value="YESB">Yes Bank</option>

              </select>


          </td>
        </tr><tr>
          <td>ccname: </td>
          <td><input name="ccname" value="<?php echo (empty($posted['ccname'])) ? '' : $posted['ccname'] ?>" /></td>
        </tr>
        <tr>
          <td>ccnum: </td>
          <td><input name="ccnum" id="ccnum" value="<?php echo (empty($posted['ccnum'])) ? '' : $posted['ccnum']; ?>" /></td>
          <td>ccvv: </td>
          <td><input name="ccvv" value="<?php echo (empty($posted['ccvv'])) ? '' : $posted['ccvv']; ?>" /></td>
        </tr>
        <tr>
          <td>ccexpmon: </td>
          <td><input name="ccexpmon" id="ccexpmon" value="<?php echo (empty($posted['ccexpmon'])) ? '' : $posted['ccexpmon']; ?>" /></td>
          <td>ccexpyr: </td>
          <td><input name="ccexpyr" value="<?php echo (empty($posted['ccexpyr'])) ? '' : $posted['ccexpyr']; ?>" /></td>
        </tr>

         

        <tr>
          <td><b>Optional Parameters</b></td>
        </tr>
        <tr>
          <td>Last Name: </td>
          <td><input name="lastname" id="lastname" value="<?php echo (empty($posted['lastname'])) ? '' : $posted['lastname']; ?>" /></td>
          <td>Cancel URI: </td>
          <td><input name="curl" value="" /></td>
        </tr>
        <tr>
          <td>Address1: </td>
          <td><input name="address1" value="<?php echo (empty($posted['address1'])) ? '' : $posted['address1']; ?>" /></td>
          <td>Address2: </td>
          <td><input name="address2" value="<?php echo (empty($posted['address2'])) ? '' : $posted['address2']; ?>" /></td>
        </tr>
        <tr>
          <td>City: </td>
          <td><input name="city" value="<?php echo (empty($posted['city'])) ? '' : $posted['city']; ?>" /></td>
          <td>State: </td>
          <td><input name="state" value="<?php echo (empty($posted['state'])) ? '' : $posted['state']; ?>" /></td>
        </tr>
        <tr>
          <td>Country: </td>
          <td><input name="country" value="<?php echo (empty($posted['country'])) ? '' : $posted['country']; ?>" /></td>
          <td>Zipcode: </td>
          <td><input name="zipcode" value="<?php echo (empty($posted['zipcode'])) ? '' : $posted['zipcode']; ?>" /></td>
        </tr>
        <tr>
          <td>UDF1: </td>
          <td><input name="udf1" value="<?php echo (empty($posted['udf1'])) ? '' : $posted['udf1']; ?>" /></td>
          <td>UDF2: </td>
          <td><input name="udf2" value="<?php echo (empty($posted['udf2'])) ? '' : $posted['udf2']; ?>" /></td>
        </tr>
        <tr>
          <td>UDF3: </td>
          <td><input name="udf3" value="<?php echo (empty($posted['udf3'])) ? '' : $posted['udf3']; ?>" /></td>
          <td>UDF4: </td>
          <td><input name="udf4" value="<?php echo (empty($posted['udf4'])) ? '' : $posted['udf4']; ?>" /></td>
        </tr>
        <tr>
          <?php if(!$hash) { ?>
            <td colspan="4"><input type="submit" value="Submit" /></td>
          <?php } ?>
        </tr>
      </table>
    </form>
  </body>
</html>