<?php
require_once __DIR__ . '/autoload.php';   //composer dump-autoload
require ('../Teknolobi.php');

use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

$hasher = new ImageHash(new DifferenceHash());
$hash1 = $hasher->hash('1.jpg');

$app = new Teknolobi();
$app->StartPage();
$app->debug = true;
$app->sqlServer = "92.42.38.74";
$app->sqlUsername = "ipattern";
$app->sqlPassword = "Ipa1122!";
$app->sqlDatabase = "ipattern";
$app->data_connect();
/*
$app->data_query("UPDATE images set , hashhex='".$hash1."' where id=1");
*/

$app->StartCustom();
/*
$data = $app->data_get("select * from images");
while($row = $app->data_fetch_array($data)) {
    $app->DebugReturn[] = $row;
}
*/
$app->DebugReturn = $app->Custom->FindSmilarImages($hash1,4);

$app->debug();