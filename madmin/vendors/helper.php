<?php
require ('../../includes/Teknolobi.php');
include ('../../includes/functions.php');

$GLOBALS['app'] = new Teknolobi();
$app->data_connect();
if(isset($_POST["status"])){
    $id=$_GET['id'];
    $status=$_POST["status"];
    $status=($status== 0 ? 1 : 0);
    $update=$app->data_run("update menuler set status='".$status."' where id='".$id."'");
    if($update){
        $result["code"]=true;
    }
    else{
        $result["code"]=false;
    }
    echo json_encode($result);
}
