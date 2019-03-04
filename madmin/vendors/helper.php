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
    $image=$_FILES['img'];
    if($image){
        $tmp_name=$_FILES['img']['tmp_name'];
        $type=explode('/',$_FILES['img']['type']);
        $rnd=rand(1,1000);
        $uploads_dir="../images/uploads";
        $name=$rnd.'.'.$type[1];
        @move_uploaded_file($tmp_name, "$uploads_dir/$name");
    }
    $sorgu="update genel set slogan='".$slogan."'";
    if($image){
        $sorgu.=", logo_image='".$rnd.".".$type[1]."'";
    }
    $sorgu.=" where id=1";
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
