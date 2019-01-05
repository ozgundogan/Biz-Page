<?php
session_start();
error_reporting(E_ALL);
require "../includes/data.php";
require("../includes/function.parse.php");
require("../includes/language.parse.php");
require(__DIR__ . '/bootstrap.php');

$debug = "false";



if($debug == "true") {
	require __DIR__ . '/common.php';
}

if(!isset($_POST['payment_method'])) {
	echo "error";
	exit();
}


use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

$payer = new Payer();



$shipping = 0;
$subtotal = 0;
if(count($_SESSION['cart'])>0) {
	$qty = 0;
	foreach($_SESSION['cart'] as $key=>$val) {
		$sorgu = "SELECT kayitlar.*, katisimtr,katisimen FROM kayitlar,kategoriler where kaytur='Ürün' and kaykategori=katid and kayid='".$val['postdata']['kayid']."'";
		$row = mysql_fetch_array(mysql_query($sorgu));
		$subtotal = $subtotal + ($val['qty'] * ($val['price'] + $val['optprice']));
	}
}


$vergi = 0;
$grandtotal = $subtotal + $vergi + $shipping;




$ordsecret = createhash(20);
$ordhash = createhash(12);

$vars = $_POST;

mysql_query("INSERT INTO orders (orddate,ordshippingdate,ordcusid,ordamount,ordtax,ordship,ordfreeship,ordcoupon,ordcouponamount,ordisguest,ordemail,ordname,ordaddress1,ordaddress2,ordphone,ordcity,ordzipcode,ordstate,ordcountry,ordhash,ordstatus,ordsecret,ordpaymenttype,ordpaymentrequest,ordpaymentresponse,ordpaymentkey,ordcardnumber,ordcardname,ordcarddate,ordcardcvc,ordtracking,orduserip)
	values (
		NOW(),
		NOW(),
		".(isset($_SESSION['userdata']) ? $_SESSION['userdata']['cusid'] : 0).",
		'".$subtotal."',
		'".$vergi."',
		'".$shipping."',
		0,
		'',
		0,
		".(isset($_SESSION['userdata'])?0:1).",
		'".$vars['ordemail']."',
		'".$vars['ordname']."',
		'".$vars['ordaddress1']."',
		'".$vars['ordaddress2']."',
		'".$vars['ordphone']."',
		'".$vars['ordcity']."',
		'".$vars['ordzipcode']."',
		'".$vars['ordstate']."',
		'".$vars['ordcountry']."',
		'".$ordhash."',
		'0',
		'".$ordsecret."',
		'".$vars['payment_method']."',
		'',
		'',
		'',
		'".$vars['Cardnumber']."',
		'".$vars['Cardname']."',
		'".$vars['Cardexpiry']."',
		'".$vars['Cardcvc']."',
		'".createrackingcode()."',
		'".get_client_ip()."'
	)") or die(mysql_error());
	
	$ordid = mysql_insert_id();
	
	foreach($_SESSION['cart'] as $key=>$val) {
		$sorgu = "SELECT kayitlar.*, katisimtr,katisimen FROM kayitlar,kategoriler where kaykategori=katid and kayid='".$val['postdata']['kayid']."'";
		$row = mysql_fetch_array(mysql_query($sorgu));
		
		$opsiyonlar = explode(",", $GLOBALS['ceviriler']['cevurunlisteopsiyon']);
		$fiyatlar = explode("|", $row['kayek2en']);
		$seciliopsiyonlar = explode(",", $row['kayek2tr']);
		$opsiyonarr = array();
		for($i=0;$i<count($seciliopsiyonlar);$i++) {
			if($seciliopsiyonlar[$i] <> "") {
				$opsiyonarr[] = $seciliopsiyonlar[$i];
			}
		}
		$fiyat = ($val['price'] + $val['optprice']);
		
		
		
		$sorgux = "INSERT INTO orderproducts (orpordid,orpordhash,orphash,orpcadid,orpcamid,orpprice,orpqty,orpshipping,orpdata,orpuseid,orpcolor)
		values(
			'".$ordid."',
			'".$ordhash."',
			'".$val['postdata']['kayid']."',
			'".$row['kayid']."',
			'0',
			'".$fiyat."',
			'".$val['qty']."',
			'0',
			'".json_encode($val)."',
			'0',
			'0'
		)";
		mysql_query($sorgux);
		
		$xitem = new Item();
		$xitem->setName($row['kaybaslik1'.$_SESSION['lang']])
			->setCurrency("USD")
			->setQuantity($val['qty'])
			->setSku($key)
			->setPrice($fiyat);
		$cartitems[] = $xitem;
}
	
$consql = mysql_fetch_array(mysql_query("select concode from countries where conname='".$vars['ordcountry']."'"));
$GLOBALS['countrycode'] = $consql['concode'];


/*
 ->setNumber("4669424246660779")
    ->setExpireMonth("11")
    ->setExpireYear("2019")
	->setCvv2("012")
*/


if($vars['payment_method'] == "card") {
	$tmpname = explode(" ", $vars['Cardname']);
	if(count($tmpname)==1) {
		$tmpname[1] = "null";
	}
	$tmpdate = explode("/", str_replace(" ", "", $vars['Cardexpiry']));
	$card = new PaymentCard();
	$card->setType("visa")
		->setNumber(str_replace(" " , "", trim($vars['Cardnumber'])))
		->setExpireMonth($tmpdate[0])
		->setExpireYear($tmpdate[1])
		->setCvv2($vars['Cardcvc'])
		->setFirstName($tmpname[0])
		->setLastName($tmpname[1])
		->setBillingCountry($GLOBALS['countrycode']);
		
		
	$fi = new FundingInstrument();
	$fi->setPaymentCard($card);

	$payer->setPaymentMethod("credit_card")
    ->setFundingInstruments(array($fi));
	
} else {
	$payer->setPaymentMethod("paypal");
}



$itemList = new ItemList();
$itemList->setItems($cartitems);	





$details = new Details();
$details->setShipping($shipping)
    ->setTax($vergi)
    ->setSubtotal($subtotal);
$amount = new Amount();
$amount->setCurrency("USD")
    ->setTotal($grandtotal)
    ->setDetails($details);
	

$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription("Order:".$ordsecret)
    ->setInvoiceNumber($ordsecret);		
	
$GLOBALS['CancelUrl'] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].'/p/'.$_SESSION['lang'].'/'.$GLOBALS['specialpage']['checkout']."/".seolink($GLOBALS['sayfalar'][$GLOBALS['specialpage']['checkout']]['kaybaslik2']);
$GLOBALS['ReturnUrl'] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].'/p/'.$_SESSION['lang'].'/'.$GLOBALS['specialpage']['checkoutdone']."/".seolink($GLOBALS['sayfalar'][$GLOBALS['specialpage']['checkoutdone']]['kaybaslik2']).'?o='.$ordhash.'&s='.$ordsecret;
	$baseUrl = getBaseUrl();
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl($GLOBALS['ReturnUrl'])
    ->setCancelUrl($GLOBALS['CancelUrl']."?e=c");
$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));

	
$request = clone $payment;





if($debug == "true") {
	try {
		$payment->create($apiContext);
	} catch (Exception $ex) {
		ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
		exit(1);
	}
	$approvalUrl = $payment->getApprovalLink();
	 ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);
	return $payment;
} else {
	try {
		$payment->create($apiContext);
		mysql_query("UPDATE orders set ordpaymentrequest='".mysql_real_escape_string(serialize($request))."', ordpaymentresponse='".mysql_real_escape_string(serialize($payment))."' where ordid='".$ordid."'");
	} catch (Exception $ex) {
		echo "ERROR";
		print_r($ex);
		mysql_query("UPDATE orders set ordpaymentrequest='".mysql_real_escape_string(serialize($request))."', ordpaymentresponse='".mysql_real_escape_string(serialize($ex))."' where ordid='".$ordid."'");
		print_r($request);
		print_r($payment);
		exit();
	}



	
	






	$approvalUrl = $payment->getApprovalLink();

	$token = "";
	function getpaylik($py, $paytype, $ordid) {
		if($paytype == "paypal") {   //paypal
			foreach($py->links as $val) {
				if($val->rel == "approval_url") {
					$vx = explode("token=", $val->href);
					$token = $vx[1];
					mysql_query("UPDATE orders set ordpaymentkey='".mysql_real_escape_string($token)."' where ordid='".$ordid."'");
					header("location: ".$val->href);
				}
			}
		}
		if($paytype == "card") { //Kredi karti
			if($py->state == "approved") {
				header("Location: ".$GLOBALS['ReturnUrl']);
			} else {
				header("Location: ".$GLOBALS['CancelUrl']."/?e=e");
			}
		}
	}




	getpaylik($payment,$vars['payment_method'],$ordid);
}
