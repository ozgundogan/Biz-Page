<?php
function varcontrol($var) {
	foreach($var as $key=>$val) {
		if(is_array($val)) {
			$val = varcontrol($var);
		} else {
			$val = @mysql_real_escape_string($val);
		}
		$var[$key] = $val;
	}
	return $var;
}
function daytonum($day) {
	return $day * 60 * 60 * 24;
}
function money($mny) {
	return number_format($mny,2,",",".");
}
function getminprice($ptype) {
	$prices =  $_SESSION['setprice'][$ptype];
	$minval = "";
	foreach($prices as $key=>$val) {
		if(substr($key, 0,3) == "km-") {
			if($minval == "") {
				$minval = $val;
			} else {
				if($val < $minval) $minval = $val;
			}
		}

	}
	return $minval;
}
function cleanstring($vp_string){
    $vp_string = trim($vp_string);
    $vp_string = html_entity_decode($vp_string);
    $vp_string = strip_tags($vp_string);
	$vp_string = str_replace(array("Ç","ç","Ş","ş","Ğ","ğ","ı","Ö","ö","Ü","ü","İ"),array("c","c","s","s","g","g","i","o","o","u","u","i"),$vp_string);
    $vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);
    $vp_string = preg_replace('~ ~', '-', $vp_string);
    $vp_string = preg_replace('~-+~', '-', $vp_string);
    return $vp_string;
}

function get_client_ip() {
    $ipaddress = '';
	if($_SERVER['SERVER_NAME'] == "localhost") {
		 $ipaddress = 'no';
	} else {
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
	}
    return $ipaddress;
}
if(!isset($_SESSION['logged'])) {
	$userip = get_client_ip();
	if($userip <> "no") {
		require_once 'ip2location/IP2Location.php';
		$db = new \IP2Location\Database('includes/ip2location/IP2LOCATION-LITE-DB5.BIN', \IP2Location\Database::FILE_IO);
		$records = $db->lookup($userip, \IP2Location\Database::ALL);
		$sorgu = "INSERT INTO stats (stadate,stauserip,stacountrycode,stacountry,staregion,stacity,stalat,stalong)
		values(
			NOW(),
			'".$userip."',
			'".($records['countryCode'])."',
			'".($records['countryName'])."',
			'".($records['regionName'])."',
			'".($records['cityName'])."',
			'".$records['latitude']."',
			'".$records['longitude']."'
		)";
		@mysql_query($sorgu);
		$_SESSION['logged'] = "ok";
	}
}

function cleanstr($stext,$subs="") {
	if(is_array($stext)) {
		$mainstr = "";
		if($subs <> "") {
			$subs = explode(",", $subs);
			$newarray = array();
			foreach($stext as $key=>$val) {
				if(in_array($key, $subs)) {
					$newarray[] = $val;
				}
			}
			$stext = $newarray;
		}
		for($i=0; $i<count($stext); $i++) {
			$s = strip_tags($stext[$i]);
			$tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö', 'Ç','ç','(',')','/',':',',');
			$eng = array('s','s','i','i','i','g','g','u','u','o','o', 'c','c','','','-','-','');
			$s = str_replace($tr,$eng,$s);
			$s = strtolower($s);
			$s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
			$s = preg_replace('/\s+/', '', $s);
			$s = preg_replace('|-+|', '', $s);
			$s = preg_replace('/#/', '', $s);
			$s = str_replace('.', '', $s);
			$s = trim($s, '-');
			$mainstr = $mainstr.strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '',$s));
		}
		return $mainstr;
	} else {
		$s = strip_tags($stext);
		$tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö', 'Ç','ç','(',')','/',':',',');
		$eng = array('s','s','i','i','i','g','g','u','u','o','o', 'c','c','','','-','-','');
		$s = str_replace($tr,$eng,$s);
		$s = strtolower($s);
		$s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
		$s = preg_replace('/\s+/', '', $s);
		$s = preg_replace('|-+|', '', $s);
		$s = preg_replace('/#/', '', $s);
		$s = str_replace('.', '', $s);
		$s = trim($s, '-');
		return strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '',$s));
	}

}



function getlngvalue($code) {
	if(ispanel()) {
		$ref = "lngdataadmin";
	} else {
		$ref = "lngdata";

	}
	foreach($_SESSION[$ref] as $val) {
		if("lng-".$val['lnvcode'] == $code) {
			return rlng($val['lnvdata']);
		}
	}
}
function getlngdata($code,$lng) {
	$data = mysql_fetch_array(mysql_query("select * from languagevars where lnvlngcode='".$lng."' and lnvtype='site' and lnvcode='".$code."'"));
	if(is_array($data)) {
		return $data['lnvdata'];
	} else {
		return false;
	}
}

function getmessagevalue($code) {
	foreach($_SESSION['sistemmmesage'] as $val) {
		if($val['lnvcode'] == $code) {
			return rlng($val['lnvdata']);
		}
	}
}

function getfotoliaprice($api, $fotoid, $rate, $isapikey = "0") {
	if($isapikey == "1") {
		require_once 'plugins/fotolia/fotolia-api.php';
		$api = new Fotolia_Api($api);
	}
	$sonuc = $api->getMediaData($fotoid,'110');
	for($i=0;$i<count($sonuc['licenses']);$i++) {
		if($sonuc['licenses'][$i]['name'] == "XL") {
			$photoprice = round($sonuc['licenses'][$i]['price'] * $rate);
		}
	}
	return $photoprice;
}

function sendorder($ordernum) {

}

function getplugindata($plugincode) {
	$data = array();
	$sql = mysql_query("select * from pluginsettings where plsplucode='".$plugincode."'");
	while($row = mysql_fetch_array($sql)) {
		$data[$row['plscode']] = $row['plsdata'];
	}
	return $data;
}
function getsetvalue($code) {
	foreach($_SESSION['setdata'] as $val) {
		if("set-".$val['setcode'] == $code) {
			return $val['setdata'];
		}
	}
}
function calculatecargo($country="") {
	return 5;
}
function rlng($lngval) {
	if(ispanel()) {
		$ref = "lngdataadmin";
	} else {
		$ref = "lngdata";

	}
	foreach($_SESSION[$ref] as $val) {
		$lngval = str_replace('{lng-'.$val['lnvcode']."}",$val['lnvdata'], $lngval);
	}
	return $lngval;
}


function createhash($len = 15,$isupper = false) {
	$val = bin2hex(mcrypt_create_iv($len, MCRYPT_DEV_URANDOM));
	if($isupper == true) {
		$val = strtoupper($val);
	}
	return $val;
}
function checklock() {
	if($_SESSION['islock'] == "1") {
		header("Location: lock.php");
		exit();
	}
}
function checklogin() {
	if(!isset($_SESSION['admindata'])) {
		header("Location: login.php");
		exit();
	}
}
function addlog($tip,$mesaj,$data="") {
	mysql_query("INSERT INTO sistemlog (slodate,slotype,slomessage,slodata) values
	(
		NOW(),
		'".$tip."',
		'".$mesaj."',
		'".$data."'
	)");
}
function startadminpage() {
	$languages = mysql_query("select * from languages where lngactive=1 and lngadmin=1 order by lngorder");
	while($row = mysql_fetch_array($languages)) {
		if($_SESSION['lngcodeadmin'] ==  $row['lngcode']) {
			$GLOBALS['main']->assign('sellng', "selected");
			$GLOBALS['page']->assign('sellng', "selected");
		} else {
			$GLOBALS['main']->assign('sellng', "");
			$GLOBALS['page']->assign('sellng', "");
		}
		$GLOBALS['main']->assign('lngflag', $row['lngflagcode']);
		$GLOBALS['main']->assign('lngcode', $row['lngcode']);
		$GLOBALS['main']->assign('lngname', $row['lngdetails']);
		$GLOBALS['main']->parse('main.languages');
		$GLOBALS['page']->assign('lngflag', $row['lngflagcode']);
		$GLOBALS['page']->assign('lngcode', $row['lngcode']);
		$GLOBALS['page']->assign('lngname', $row['lngdetails']);
		$GLOBALS['page']->parse('main.languages');
	}
	$GLOBALS['main']->assign('active-lngcode', $_SESSION['activelngadmin']['lngcode']);
	$GLOBALS['main']->assign('active-lngname', $_SESSION['activelngadmin']['lngdetails']);
	$GLOBALS['main']->assign('active-lngflag', $_SESSION['activelngadmin']['lngflagcode']);
	$GLOBALS['page']->assign('active-lngcode', $_SESSION['activelngadmin']['lngcode']);
	$GLOBALS['page']->assign('active-lngname', $_SESSION['activelngadmin']['lngdetails']);
	$sql = mysql_query("select * from sistemlog where slodate > '".$_SESSION['admindata']['admlastlogin']."' order by slodate desc");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_array($sql)) {
			$GLOBALS['main']->assign('slomessage',getmessagevalue($row['slomessage']));
			$GLOBALS['main']->assign('slodate',time_elapsed_string($row['slodate']));
			switch ($row['slotype']) {
			case "siparis":
				$GLOBALS['main']->assign('slourl',"order.php?o=".$row['slodata']);
				$GLOBALS['main']->assign('sloicon',"wb-order");
				$GLOBALS['main']->assign('slocolor',"red");
				break;
			case "siparis-gonderildi":
				$GLOBALS['main']->assign('slourl',"order.php?o=".$row['slodata']);
				$GLOBALS['main']->assign('sloicon',"fa-mail-forward");
				$GLOBALS['main']->assign('slocolor',"yellow");
				break;
			case "uye-kaydi":
				$GLOBALS['main']->assign('slourl',"customer.php?o=".$row['slodata']);
				$GLOBALS['main']->assign('sloicon',"wb-user-add");
				$GLOBALS['main']->assign('slocolor',"blue");
				break;
			case "siparis-kargolandi":
				$GLOBALS['main']->assign('slourl',"order.php?o=".$row['slodata']);
				$GLOBALS['main']->assign('sloicon',"fa-truck");
				$GLOBALS['main']->assign('slocolor',"green");
				break;
			case 1:
				$GLOBALS['main']->assign('slourl',"javascript:void(0)");
				break;
			}
			$GLOBALS['main']->parse('main.yessysmessage.sysmessage');
		}
		$GLOBALS['main']->parse('main.yessysmessage');
		$GLOBALS['main']->assign('sysmessagecount',mysql_num_rows($sql));
		$GLOBALS['main']->parse('main.sysmessagecount');
		$GLOBALS['main']->parse('main.sysmessagecount2');
	} else {
		$GLOBALS['main']->parse('main.nosysmessage');
	}
	$sql = mysql_query("select * from tickets where tictype=0 and ticstatus=1 and ticlastdate > '".$_SESSION['admindata']['admlastlogin']."' order by ticlastdate desc");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_array($sql)) {
			$user = mysql_fetch_array(mysql_query("select * from customers where cusid='".$row['ticcusid']."'"));
			foreach($user as $key=>$val) {
				$GLOBALS['main']->assign($key,$val);
			}
			$GLOBALS['main']->assign('ticlastdate',time_elapsed_string($row['ticlastdate']));
			$GLOBALS['main']->assign('ticsubject', substr($row['ticsubject'],0,50));
			$GLOBALS['main']->assign('ticid', $row['ticid']);
			$GLOBALS['main']->parse('main.yesticmessage.ticmessage');
		}
		$GLOBALS['main']->parse('main.yesticmessage');
		$GLOBALS['main']->assign('ticmessagecount',mysql_num_rows($sql));
		$GLOBALS['main']->parse('main.ticmessagecount');
		$GLOBALS['main']->parse('main.ticmessagecount2');
	} else {
		$GLOBALS['main']->parse('main.noticmessage');
	}
}
function startpage() {
	$languages = mysql_query("select * from languages where lngactive=1 order by lngorder");
	while($row = mysql_fetch_array($languages)) {
		$GLOBALS['main']->assign('lngflag', $row['lngflagcode']);
		$GLOBALS['main']->assign('lngcode', $row['lngcode']);
		$GLOBALS['main']->assign('lngname', $row['lngdetails']);
		$GLOBALS['main']->parse('main.languages');
	}
	if($_SESSION['activelng']['lngrtl'] == 1) {
		$GLOBALS['main']->assign('isrtlhtml','dir="rtl"');
	}
	$GLOBALS['main']->assign('active-lngname', explode(" ",$_SESSION['activelng']['lngdetails'])[0]);
	$GLOBALS['main']->assign('active-lngflag', $_SESSION['activelng']['lngflagcode']);
	$GLOBALS['main']->assign('active-lngcode', $_SESSION['activelng']['lngcode']);
}


function parselng($block) {
	if(ispanel()) {
		$ref = "lngdataadmin";
	} else {
		$ref = "lngdata";

	}
	foreach($_SESSION[$ref] as $val) {
		$block->assign('lng-'.$val['lnvcode'],$val['lnvdata']);
	}
	foreach($_SESSION['setdata'] as $val) {
		$block->assign('set-'.$val['setcode'],$val['setdata']);
	}
	return $block;
}

function getproductcode($key) {
	if($key == "roller") return "lng80";
	if($key == "zebra") return "lng81";
	if($key == "vertical") return "lng82";
	if($key == "japan") return "lng83";
	if($key == "panel") return "lng84";
	if($key == "fon") return "lng85";
}
function ispluginactive($plugincode) {
	$data = mysql_fetch_array(mysql_query("select plustatus from plugins where plucode='".$plugincode."'"));
	return $data['plustatus'];
}
function endadminpage() {
}

function endpage() {

	$sql = mysql_query("select * from productprices where prpactive='1' and prptype='1'");
	$sel = 0;
	$GLOBALS['main']->assign('catname', getlngvalue('lng-perdeler'));
	while($row = mysql_fetch_array($sql)) {
		if($sel == 0) {
			$sel = 1;
			$GLOBALS['main']->assign('procatselname', getlngvalue('lng-'.$GLOBALS['productcodes'][$row['prpcode']]));
			$GLOBALS['main']->assign('procatselcode', $row['prpcode']);
		}
		$GLOBALS['main']->assign('productname', getlngvalue('lng-'.$GLOBALS['productcodes'][$row['prpcode']]));
		$GLOBALS['main']->assign('productcode', $row['prpcode']);
		$GLOBALS['main']->parse('main.productcat.catproduct1');
		$GLOBALS['main']->parse('main.productcat.catproduct2');
	}
	$GLOBALS['main']->parse('main.productcat');
	$sql = mysql_query("select * from productprices where prpactive='1' and prptype='2'");
	$sel = 0;
	$GLOBALS['main']->assign('catname', getlngvalue('lng-ev-tekstili'));
	while($row = mysql_fetch_array($sql)) {
		if($sel == 0) {
			$sel = 1;
			$GLOBALS['main']->assign('procatselname', getlngvalue('lng-'.$GLOBALS['productcodes'][$row['prpcode']]));
			$GLOBALS['main']->assign('procatselcode', $row['prpcode']);
		}
		$GLOBALS['main']->assign('productname', getlngvalue('lng-'.$GLOBALS['productcodes'][$row['prpcode']]));
		$GLOBALS['main']->assign('productcode', $row['prpcode']);
		$GLOBALS['main']->parse('main.productcat.catproduct1');
		$GLOBALS['main']->parse('main.productcat.catproduct2');
	}
	$GLOBALS['main']->parse('main.productcat');

	if($_SESSION['basket']>0) {
		$GLOBALS['main']->assign('cartcount', count($_SESSION['basket']));
		$subtotal = 0;
		$GLOBALS['main']->parse('main.menucartyes');


		foreach($_SESSION['basket'] as $key=>$val) {
			$GLOBALS['main']->assign('prohash', $key);
			$GLOBALS['main']->assign('proname',getlngvalue('lng-'.$GLOBALS['productcodes'][$val[2]['rollertype']]));
			$GLOBALS['main']->assign('prodetails',"<span class=\"color-variations\"><span class=\"f-14\">".getlngvalue('lng-'.$GLOBALS['productcodes'][$val[2]['rollertype']])."</span><br>".$val[2]['genislik']."cm x ".$val[2]['yukseklik']."cm</span>");
			$GLOBALS['main']->assign('prorollertype',$val[2]['rollertype']);
			$GLOBALS['main']->assign('proprice', money($val[0]));
			$GLOBALS['main']->assign('proqty', $val[1]);
			$GLOBALS['main']->assign('prototalprice', money($val[1] * $val[0]));
			$GLOBALS['main']->parse('main.menucart.miniproduct');
			$subtotal = $subtotal + ($val[1] * $val[0]);
		}

		$vergi = round($subtotal * getsetvalue("set-tax")/100,2);
		$granttotal = $subtotal + $vergi + calculatecargo();
		$GLOBALS['main']->assign('pricewithoutcargo', money($subtotal + $vergi));
		$GLOBALS['main']->assign('prosubtotal', money($subtotal));
		$GLOBALS['main']->assign('procargo', money(calculatecargo()));
		$GLOBALS['main']->assign('provergi', $vergi);
		$GLOBALS['main']->assign('prograndtotal', money($granttotal));
		$GLOBALS['main']->parse('main.menucart');
	} else {
		$GLOBALS['main']->parse('main.nomenucart');
	}
	if(ispluginactive("mylivechat") == 1) {
		$data = getplugindata("mylivechat");
		$GLOBALS['main']->assign('mylivechatid', $data['id']);
		$GLOBALS['main']->parse('main.mylivechat');
	}
	if(ispluginactive("googleanalytics") == 1) {
		$data = getplugindata("googleanalytics");
		$GLOBALS['main']->assign('googleanalytics', $data['id']);
		$GLOBALS['main']->parse('main.googleanalytics');
	}
	if(ispluginactive("tawkto") == 1) {
		$data = getplugindata("tawkto");
		$GLOBALS['main']->assign('tawkto', $data['code']);
		$GLOBALS['main']->parse('main.tawkto');
	}
	if(isset($_SESSION['userdata'])) {
		$GLOBALS['main']->parse('main.usermenu');
	} else {
		$GLOBALS['main']->parse('main.guestmenu');
	}
	foreach($_SESSION['sociallinks'] as $val) {
		if(getsetvalue('set-'.$val['url']) <> "") {
			$GLOBALS['main']->assign('social-url', getsetvalue('set-'.$val['url']));
			$GLOBALS['main']->assign('social-icon', $val['icon']);
			$GLOBALS['main']->parse('main.sociallinks1');
			$GLOBALS['main']->parse('main.sociallinks2');
		}
	}
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'yil',
        'm' => 'ay',
        'w' => 'hafta',
        'd' => 'gun',
        'h' => 'saat',
        'i' => 'dakika',
        's' => 'saniye',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . getlngvalue("lng-tarih-".$v);
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . " ".getlngvalue("lng-tarih-once") : " ".getlngvalue("lng-tarih-simdi");
}
 function seo($text)
{
    $find = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '+', '#');
    $replace = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', 'plus', 'sharp');
    $text = strtolower(str_replace($find, $replace, $text));
    $text = preg_replace("@[^A-Za-z0-9\-_\.\+]@i", ' ', $text);
    $text = trim(preg_replace('/\s+/', ' ', $text));
    $text = str_replace(' ', '-', $text);
    return $text;
}

?>
