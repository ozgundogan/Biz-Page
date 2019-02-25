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
    if(isset($_POST['img'])){
        $tmp_name=$_FILES['img']['tmp_name'];
        $img_name=$_FILES['img']['name'];
        $rnd=rand(1,1000);
        $file = 'images/uploads/'.$rnd.'.'.$type[0];
        @move_uploaded_file($tmp_name, "$file");
        $slogan=$_POST["slogan"];
        $img=$_FILES["img"];
    }
    $sorgu="update genel set slogan='".$slogan."'";
    if($img_name){
        $sorgu.=", logo_image='".$img_name."'";
    }
    $sorgu.=" where id=1";
    print_r($sorgu);
    exit();
    $update=$app->data_run($sorgu);
    if($update){
        $result=true;
    }
    else{
        $result=false;
    }
    echo $result;
}

if(isset($_POST["nest"])){
    $rows=[];
    $get=$app->data_get('select * from menuler');

    while($g=$app->data_fetch_array($get))
    {
        $rows[]=[
            "title"  => $g["title"],
            "status" => $g["status"],
            "id"     => $g["id"],
            "parent" => $g["parent"],
            "orderBy"=> $g["orderBy"]
        ];
    }

    $messages['menuler']=nestable($rows,0);
    echo json_encode($messages);
}
