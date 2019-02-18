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
if(isset($_POST["slogan"])){

    $slogan=$_POST["slogan"];
    print_r($_POST["file_"]);
    exit();
    if ($_POST["file_"]) {
        $img = $_POST["file_"];
        $uri = explode(',', $img);
        $ini =substr($uri[0], 11);
        $type = explode(';', $ini);
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace('data:image/jpg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = 'images/uploads/'.slug($name.$editid).'.'.$type[0];
        $success = file_put_contents($file, $data);
        $avatar=slug($name.$editid).'.'.$type[0];
    }
    $sorgu="update genel set slogan='".$slogan."'";
    if($avatar){
        $sorgu.=",logo_image='".$avatar."'";
    }
    $sorgu.=" where id=1";

    $update=$app->data_run($sorgu);
    if($update){
        $result["code"]=true;
    }
    else{
        $result["code"]=false;
    }
    echo json_encode($result);
}
if(isset($_POST['nestable'])){
    $table=$app->data_get("select * from menuler");
    while($row=$app->data_fecth_array($table)){

    }

}
