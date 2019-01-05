<?php
session_start();
error_reporting(E_ALL);
require "../includes/data.php";
require("../includes/function.parse.php");
require("../includes/language.parse.php");
require('stripe/Stripe.php');






$params = array(
	"testmode"   => "off",
	"private_live_key" => "sk_live_X1OQq3uC6MyDvdLpTiHvKRwa",
	"public_live_key"  => "pk_live_Nb9MJKEuKH29BmrH6Wgzw54t",
	"private_test_key" => "sk_test_hhcZUSzc0b2MGowEyDXUOuuq",
	"public_test_key"  => "pk_test_sIjPOYXbJxlmbb3ISA2LP8SF"
);

if ($params['testmode'] == "on") {
	 ::setApiKey($params['private_test_key']);
	$pubkey = $params['public_test_key'];
} else {
	Stripe::setApiKey($params['private_live_key']);
	$pubkey = $params['public_live_key'];
}

if(!isset($_POST['payment_method'])) {
	echo "error";
	exit();
}


if(isset($_POST['stripeToken']))
{
	
	
	
	
	
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
	}
		
	$consql = mysql_fetch_array(mysql_query("select concode from countries where conname='".$vars['ordcountry']."'"));
	$GLOBALS['countrycode'] = $consql['concode'];
	$GLOBALS['CancelUrl'] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].'/p/'.$_SESSION['lang'].'/'.$GLOBALS['specialpage']['checkout']."/".seolink($GLOBALS['sayfalar'][$GLOBALS['specialpage']['checkout']]['kaybaslik2']);
	$GLOBALS['ReturnUrl'] = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].'/p/'.$_SESSION['lang'].'/'.$GLOBALS['specialpage']['checkoutdone']."/".seolink($GLOBALS['sayfalar'][$GLOBALS['specialpage']['checkoutdone']]['kaybaslik2']).'?o='.$ordhash.'&s='.$ordsecret;
	
	
	
	
	
	
	
	
	
	
	if(count(explode('.',$grandtotal)) == 1) {
		$grandtotal = $grandtotal.".00";
	}
	$amount_cents = str_replace(".","",$grandtotal);  // Chargeble amount
	$description = "Invoice #" . $ordsecret;
	
	try {

		$charge = Stripe_Charge::create(array(		 
			  "amount" => $amount_cents,
			  "currency" => "usd",
			  "source" => $_POST['stripeToken'],
			  "description" => $description)			  
		);

		if ($charge->card->address_zip_check == "fail") {
			throw new Exception("zip_check_invalid");
		} else if ($charge->card->address_line1_check == "fail") {
			throw new Exception("address_check_invalid");
		} else if ($charge->card->cvc_check == "fail") {
			throw new Exception("cvc_check_invalid");
		}
		// Payment has succeeded, no exceptions were thrown or otherwise caught				

		$result = "success";

	} catch(Stripe_CardError $e) {			

	$error = $e->getMessage();
	$result = "declined";

	} catch (Stripe_InvalidRequestError $e) {
		$result = "declined";		  
	} catch (Stripe_AuthenticationError $e) {
		$result = "declined";
	} catch (Stripe_ApiConnectionError $e) {
		$result = "declined";
	} catch (Stripe_Error $e) {
		$result = "declined";
	} catch (Exception $e) {

		if ($e->getMessage() == "zip_check_invalid") {
			$result = "declined";
		} else if ($e->getMessage() == "address_check_invalid") {
			$result = "declined";
		} else if ($e->getMessage() == "cvc_check_invalid") {
			$result = "declined";
		} else {
			$result = "declined";
		}		  
	}
	mysql_query("UPDATE orders set ordpaymentrequest='', ordpaymentresponse='".mysql_real_escape_string(serialize($charge))."' where ordid='".$ordid."'");
	if($result == "success") {
		header("Location: ".$GLOBALS['ReturnUrl']);
	} else {
		header("Location: ".$GLOBALS['CancelUrl']."?e=c");
	}
}
?>