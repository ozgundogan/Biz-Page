<?php
session_start();
if($configloaded <> "ok") {
	@mysql_connect("localhost","root","", "printgo2018");
	mysql_select_db("printgo2018");
	mysql_query("SET NAMES 'utf8'");
	mysql_query("SET CHARACTER SET utf8");
	/*
	$lngdatax = mysql_query("select * from languagevars2 where lnvlngcode='".$_SESSION['lngcode']."'");
	unset($_SESSION['lngdata']);
	while($row = mysql_fetch_array($lngdatax)) {
		$_SESSION['lngdata'][] = $row;
	}
	*/
	function ispanel() {
		$veri = explode("/", strtolower($_SERVER['REQUEST_URI']));
		if($veri[1] == "panel") {
			return true;
		} else {
			return false;
		}
	}
	
	if(!isset($_SESSION['activelng'])) {
		$languages = mysql_query("select * from languages where lngactive=1");
		while($row = mysql_fetch_array($languages)) {		
			if($row['lngdefault'] == 1) {
				$_SESSION['activelng'] = $row;
				$_SESSION['lngcode'] = $row['lngcode'];
			}		
		}		
	}
	if(!isset($_SESSION['activelngadmin'])) {
		$languages = mysql_query("select * from languages where lngadmin=1 order by lngid desc limit 0,1");
		while($row = mysql_fetch_array($languages)) {		
			if($row['lngdefault'] == 1) {
				$_SESSION['activelngadmin'] = $row;
				$_SESSION['lngcodeadmin'] = $row['lngcode'];
			}		
		}		
	}	


	if(ispanel()) {
		$lngdatax = mysql_query("select * from languagevars where lnvlngcode='".$_SESSION['lngcodeadmin']."'");
		unset($_SESSION['lngdataadmin']);
		while($row = mysql_fetch_array($lngdatax)) {
			$_SESSION['lngdataadmin'][] = $row;
			if($row['lnvdatatype'] == 'sistem') {
				$_SESSION['sistemmmesage'][] = $row;
			}
		}
	} else {
		$lngdatax = mysql_query("select * from languagevars where lnvlngcode='".$_SESSION['lngcode']."' and lnvtype !='panel'");
		unset($_SESSION['lngdata']);
		while($row = mysql_fetch_array($lngdatax)) {
			$_SESSION['lngdata'][] = $row;
			if($row['lnvdatatype'] == 'sistem') {
				$_SESSION['sistemmmesage'][] = $row;
			}
		}
	}

	$seodatax = mysql_query("select * from languagevars where lnvdatatype='seo'");
	unset($_SESSION['seodata']);
	while($row = mysql_fetch_array($seodatax)) {
		$_SESSION['seodata'][] = $row;
	}
	

	$setdatax = mysql_query("select * from settings");
	unset($_SESSION['setdata']);
	while($row = mysql_fetch_array($setdatax)) {
		$_SESSION['setdata'][] = $row;
	}
	

	$pricedata = mysql_query("select * from productprices");
	unset($_SESSION['setprice']);
	while($row = mysql_fetch_array($pricedata)) {
		$_SESSION['setprice'][$row['prpcode']] = $row;
	}


	function getself() {
		$self = explode('?', $_SERVER['REQUEST_URI']);
		return $self[0];
	}
	
	$colors = array('000000','FFFFFF', 'fe0000', 'ffff01', '84fd08', '0001fc', 'ac10d3', 'fa4a09', '5a3d1d');


	$productcodes = array(
	'roller' => 'stor-perde',
	'zebra' => 'zebra-perde',
	'vertical' => 'dikey-perde',
	'japan' => 'japon-perde',
	'pano' => 'pano-perde',
	'panel' => 'panel-perde',
	'pillow' => 'yastik',
	'mop' => 'paspas'
	);
	$GLOBALS['productcodes'] = $productcodes;

	$_SESSION['sociallinks'] = array(
		array('url'=>'social-facebook', 'icon'=>'facebook'),
		array('url'=>'social-youtube', 'icon'=>'youtube'),
		array('url'=>'social-instagram', 'icon'=>'instagram'),
		array('url'=>'social-twitter', 'icon'=>'twitter'),
		array('url'=>'social-google', 'icon'=>'gplus'),
		array('url'=>'social-pinterest', 'icon'=>'pinterest'),
		array('url'=>'social-likedin', 'icon'=>'linkedin')
	);



	$configloaded = "ok";
}

$odemetipleri = array("1"=>"paypal","2"=>"havale","3"=>"fatura","4"=>"kredikarti");
$durumtipleri = array("1"=>"onay-bekliyor","2"=>"uretimde","3"=>"kargoya-verildi","4"=>"tamamlandi","5"=>"sorunlu");
$durumtipleriico = array("1"=>"fa-clock-o text-primary","2"=>"fa-cog text-warning","3"=>"fa-ruck text-info","4"=>"fa-check text-success","5"=>"fa-exclamation-triangle text-danger");
$fotoliadil = array('fr'=>'1','en'=>'2','en'=>'3','de'=>'4','es'=>'5','it'=>'6','pt'=>'7','br'=>'8','jp'=>'9','pl'=>'11','ru'=>'12','cn'=>'13','tr'=>'14','kp'=>'15','nl'=>'22','se'=>'23');


$_SESSION['isdebug'] = true;
$_SESSION['fabricprices']['roller'] = array(58.5,58.5,65,65,65,104);
$_SESSION['fabricprices']['zebra'] = array(58.5,58.5,65,65,65,104);
$_SESSION['fabricprices']['japan'] = array(72.5,72.5,80,80,80,119);
$_SESSION['fabricprices']['vertical'] = array(68.5,68.5,75,75,75,114);
$_SESSION['fabricprices']['panel'] = array(68.5,68.5,75,75,75,114);
$_SESSION['fabricprices']['fon'] = array(68.5,68.5,75,75,75,114);
?>