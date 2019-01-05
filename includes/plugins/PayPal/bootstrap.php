<?php
/*
 * Sample bootstrap file.
 */

// Include the composer Autoloader
// The location of your project's vendor autoloader.
$composerAutoload = __DIR__ . '/PayPal-PHP-SDK/autoload.php';
if (!file_exists($composerAutoload)) {
    //If the project is used as its own project, it would use rest-api-sdk-php composer autoloader.
    $composerAutoload = __DIR__ . '/PayPal-PHP-SDK/autoload.php';


    if (!file_exists($composerAutoload)) {
        echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
		echo __DIR__ . '/paypal/PayPal-PHP-SDK/autoload.php';
        exit(1);
    }
}




require $composerAutoload;
//require __DIR__ . '/common.php';

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

// Suppress DateTime warnings, if not set already
date_default_timezone_set(@date_default_timezone_get());

/*
// Adding Error Reporting for understanding errors properly
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/
// Replace these values by entering your own ClientId and Secret by visiting https://developer.paypal.com/developer/applications/

$xmode = "sandbox"; //live, sandbox

if($xmode == "sandbox") {
	$clientId 		= 'AeJzwWTJF-UHWY1hm6NwlCb3V8dh0iA0KnBasZjoVAv-ddVRzgwR877jOkPyw76CDgFFewIc6tNbn7KP';
	$clientSecret 	= 'EEGT761Clyvm5nVM-W4PRGJ7YreUWh_PloHOhVaWt89DdjTBhJNE8vpCfHNZy30WwaUMtO2Z5xU0amlJ';
} else {
	
	$clientId 		= 'AfWKU2lQzpSqmK2vk1gdinm7SliXXMcIX2ciaj_xMYy1Kjr9z7QBMPxCkA7d-E1Ws6_1M2EAINarW3Mn';
	$clientSecret 	= 'EGjRYTcUKXbGoC0AbZIC97bLeU7SFGD0rU2eiMl-kcNS1KQh0hIi94NgGEaMKJzqQXxNyTRbNllmPBis';
}

/**
 * All default curl options are stored in the array inside the PayPalHttpConfig class. To make changes to those settings
 * for your specific environments, feel free to add them using the code shown below
 * Uncomment below line to override any default curl options.
 */
//PayPalHttpConfig::$defaultCurlOptions[CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;


/** @var \Paypal\Rest\ApiContext $apiContext */
$apiContext = getApiContext($clientId, $clientSecret, $xmode);

return $apiContext;
/**
 * Helper method for getting an APIContext for all calls
 * @param string $clientId Client ID
 * @param string $clientSecret Client Secret
 * @return PayPal\Rest\ApiContext
 */
 
function getApiContext($clientId, $clientSecret, $xmode)
{

    // #### SDK configuration
    // Register the sdk_config.ini file in current directory
    // as the configuration source.
    /*
    if(!defined("PP_CONFIG_PATH")) {
        define("PP_CONFIG_PATH", __DIR__);
    }
    */


    // ### Api context
    // Use an ApiContext object to authenticate
    // API calls. The clientId and clientSecret for the
    // OAuthTokenCredential class can be retrieved from
    // developer.paypal.com

    $apiContext = new ApiContext(
        new OAuthTokenCredential(
            $clientId,
            $clientSecret
        )
    );

    // Comment this line out and uncomment the PP_CONFIG_PATH
    // 'define' block if you want to use static file
    // based configuration

    $apiContext->setConfig(
        array(
            'mode' => $xmode,
            'log.LogEnabled' => true,
            'log.FileName' => '../PayPal.log',
            'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'cache.enabled' => true,
            // 'http.CURLOPT_CONNECTTIMEOUT' => 30
            // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
            //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
        )
    );

    // Partner Attribution Id
    // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
    // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
    // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');

    return $apiContext;
}

/*
function getBaseUrl()
{
    if (PHP_SAPI == 'cli') {
        $trace=debug_backtrace();
        $relativePath = substr(dirname($trace[0]['file']), strlen(dirname(dirname(__FILE__))));
        echo "Warning: This sample may require a server to handle return URL. Cannot execute in command line. Defaulting URL to http://localhost$relativePath \n";
        return "http://localhost" . $relativePath;
    }
    $protocol = 'http';
    if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')) {
        $protocol .= 's';
    }
    $host = $_SERVER['HTTP_HOST'];
    $request = $_SERVER['PHP_SELF'];
    return dirname($protocol . '://' . $host . $request);
}
*/