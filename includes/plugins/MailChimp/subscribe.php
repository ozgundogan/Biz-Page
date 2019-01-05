<?
session_start();

include('../../includes/config.php');
include('../../includes/functions.php');

$plugindata = getplugindata('mailchimp');


include('MailChimp.php');
//use \DrewM\MailChimp\MailChimp;

$MailChimp = new MailChimp($plugindata['key']);
$listId 	= $plugindata['list'];


if (isset($_POST['email'])) {
	$email = $_POST['email'];
} else if (isset($_GET['email'])) {
	$email = $_GET['email'];
} else {
	$email = '';
}

/*
$result = $MailChimp->get('lists');

print_r($result);

*/
$Pringtolist = new MailChimp("f73a20f0d4ce415d004d888be9bd44bb-us10");
$Pringtolist->post("lists/f31ce43719/members", [
			'email_address' => $email,
			'merge_fields'  => array('FNAME'=>'', 'LNAME'=>''), 
			'status'        => 'subscribed',
]);
		
$result = $MailChimp->post("lists/".$listId."/members", [
				'email_address' => $email,
				'merge_fields'  => array('FNAME'=>'', 'LNAME'=>''),
				'status'        => 'subscribed',
			]);
			
/*			
print_r($result);
var_dump($result);
print_r($MailChimp);
var_dump($MailChimp);
*/
//print_r($MailChimp->getLastResponse());


if ($result['id'] != '') {
	$arrResult = array('response'=>'success');	
} else {
	$arrResult = array('response'=>'error','message'=>$result['detail']);
}

echo json_encode($arrResult);
