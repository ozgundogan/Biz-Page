<?php
require ('../includes/Teknolobi.php');
require ('../includes/functions.php');

$GLOBALS['app'] = new Teknolobi();

$app->CreateView("main","main.tpl");
$app->CreateView("index","index.tpl");
$app->CreateView("ana","anasayfa.tpl");
$app->CreateView("user","user/users.tpl");
$app->CreateView("edit","user/edit.tpl");
$app->CreateView("register","user/register.tpl");
$app->CreateView("blog","blog.tpl");
$app->CreateView("menu","menu.tpl");
$app->CreateView("createm","create-menu.tpl");

$app->data_connect();
//seo fonksiyonu
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

if(isset($_GET["msayfa"])){

    $msayfa=$_GET["msayfa"];

    switch ($msayfa) {
        case 'anasayfa':
        $deg=$app->data_get("select * from slider order by slider_id asc");
        while($slider=$app->data_fetch_array($deg))
        {
            $app->Views['ana']->assign('sliderid',$slider["slider_id"]);
            $app->Views['ana']->assign('slidersira',$slider["slider_sira"]);
            $app->Views['ana']->assign('resimgoster',$slider['slider_id']);
            $app->Views['ana']->assign('defaultGorsel',$slider['slider_resim']);
            $app->Views['ana']->assign('resimyolsil',$slider['slider_resim']);
            $app->Views['ana']->parse("main.satirlar");
        }


        $app->Views['ana']->parse('main');
        $app->Views['main']->assign('content',$app->Views['ana']->text('main'));
        break;
        case 'users':
        $userTable=$app->data_get("select * from users order by username asc");
        while($users=$app->data_fetch_array($userTable)){
            $app->Views['user']->assign('id',$users["id"]);
            $app->Views['user']->assign('username',$users["username"]);
            $app->Views['user']->assign('eposta',$users['email']);
            $app->Views['user']->parse("main.satir");
        }
        $app->Views['user']->parse('main');
        $app->Views['main']->assign('content',$app->Views['user']->text('main'));
        break;
        case 'edit':
        $app->Views['edit']->parse('main');
        $app->Views['main']->assign('content',$app->Views['edit']->text('main'));
        break;
        case 'register':
        $app->Views['register']->parse('main');
        $app->Views['main']->assign('content',$app->Views['register']->text('main'));
        break;
        case 'menu':
        // $menus= $app->data_get("select * from menuler order by orderBy desc");
        //
        // while ($row = $app->data_fetch_array($menus)) {
        //     $menu[] = [
        //         'id' => $row["id"],
        //         'title' => $row["title"],
        //         'parent' => $row["parent"],
        //         'status' => $row["status"]
        //     ];
        // }
        // $result= menu($menu,0);
        // $app->Views['menu']->assign('menuler',$result);
        $pages=$app->data_get("select * from pages");

        while($row=$app->data_fetch_array($pages)){
            $slugsayfa=seo($row['title']);
            $app->Views['menu']->assign('sayfa',$row['title']);
            $app->Views['menu']->assign("deger",$slugsayfa);
            $app->Views['menu']->parse('main.sayfa');

        }
        $app->Views['menu']->parse('main');
        $app->Views['main']->assign('content',$app->Views['menu']->text('main'));
        break;
        case 'createm':
        $app->Views['createm']->parse('main');
        $app->Views['main']->assign('content',$app->Views['createm']->text('main'));
        break;
        default:
        $genel=$app->data_get("select * from genel where id=1");
        while($row=$app->data_fetch_array($genel))
        {
            $app->Views['index']->assign('defaultGorsel',$row["logo_image"]);
            $app->Views['index']->assign('slogan',$row["slogan"]);
        }
        $app->Views["index"]->parse("main");
        $app->Views['main']->assign("content",$app->Views["index"]->text("main"));
        break;
    }
}
else{
    $genel=$app->data_get("select * from genel where id=1");
    while($row=$app->data_fetch_array($genel))
    {
        $app->Views['index']->assign('defaultGorsel',$row["logo_image"]);
        $app->Views['index']->assign('logoName',$row["logo_image"]);
        $app->Views['index']->assign('slogan',$row["slogan"]);
        $app->Views['index']->parse("main");
    }
    $app->Views["index"]->parse("main");
    $app->Views['main']->assign("content",$app->Views["index"]->text("main"));
}
if(isset($_POST["sliderduzenle"])){

    $id=$_POST["id"];
    $gorselname=$_POST["gorselname"];
    $siraname=$_POST["siraname"];
    $sliderorder=$_POST["$siraname"];
    $uploads_dir="images/uploads";

    $tmp_name=$_FILES["$gorselname"]["tmp_name"];
    $resim_name=$_FILES["$gorselname"]["name"];
    $resim_name=seo($resim_name);

    $sliderresimyol=$uploads_dir."/".$resim_name;

    @move_uploaded_file($tmp_name,"$uploads_dir/$resim_name");

    $sorgu="update slider set slider_sira='".$sliderorder."'";
    if($resim_name!=""){
        $sorgu.= ", slider_resim='".$resim_name."'";
    }
    $sorgu.=" where slider_id='".$id."'";
    $kaydet=$app->data_run($sorgu);

    if($kaydet){
        $sonuc=true;
    } else{
        $sonuc=false;
    }
    echo $sonuc;
    /*echo $id.",".$customname.",".$uploads_dir.",".$tmp_name.",".$resim_name.",".$sliderresimyol.",".$slidersira."--bitti";*/
}
if(isset($_POST["sid"])){
    $resimyol=$_POST["resimyol"];
    $sil=$app->data_run("delete from slider where slider_id=".$_POST['sid']."");
    if ($sil) {
        unlink("$resimyol");
        $array['durum']=true;
    }
    else{
        $array['durum']=false;
    }
    echo json_encode($array) ;
    exit();
}
function sirala($list, $parent_id = 0, & $m_order = 0)
{
    $ap= new Teknolobi();
    $ap->data_connect();
    foreach($list as $item) {
        $m_order++;
        $update=$ap->data_run("update menuler set orderBy=".$m_order.", parent=".$parent_id." where id='".$item["id"]."'");
        if($update){
            if (array_key_exists("children", $item)) {
                sirala($item["children"], $item["id"], $m_order);
            }
        }
    }
}
if(isset($_POST["list"])){
    $list=$_POST["list"];
    sirala($list);
}
if(isset($_POST["menuIslem"]) && isset($_POST['page']))
{
    if($_POST["menuId"]){
        $id=$_POST["menuId"];
        $menuad=$_POST["menuAdi"];
        $page=$_POST['page'];
        $link=$page.'-'.$id.'-'.seo($menuad);
        $table=$app->data_get("select * from menuler where page='$page' and id != $id");
        if(mysqli_num_rows($table) == 0){
            $update=$app->data_run("update menuler set title='$menuad', page='$page', link='$link' where id='$id'");
            if($update){
                $result["code"]=true;
            }else{
                $result["code"]=false;
            }
        }else{
            $result['code']=false;
            $result['text']='Bu sayfa eşleştirilmiş!';
        }
        echo json_encode($result);
        exit();
    }
    else{
        $menuad=$_POST["menuAdi"];
        $sayfa=$_POST['page'];
        $table=$app->data_get("select * from menuler where page='$sayfa'");

        if(mysqli_num_rows($table) == 0)
        {
            $insert=$app->data_run("insert into menuler (title,status,orderBy,parent,link,page) values ('$menuad','1','1','0',' ','$sayfa')");
            if($insert){
                $table=$app->data_get("select * from menuler order by id desc limit 1");
                $row=$app->data_fetch_array($table);
                $id=$row['id'];
                $link=$sayfa.'-'.$id.'-'.seo($menuad);
                print_r($link);
                exit();
                $update=$app->data_run("update menuler set link='$link' where id=$id");
                $result["code"]=true;
            }else{
                $result["code"]=false;
            }
            echo json_encode($result);
            exit();
        }
        else{
            $result['code']=false;
            $result['text']='Bu sayfa eşleştirilmiş!';
            echo json_encode($result);
            exit();
        }
    }
}
if(isset($_POST['menuSil'])){
    $id=$_POST['menuSil'];
    $delete=$app->data_run("delete from menuler where id='$id'");
    if($delete){
        $messages['code']=true;
    }else{
        $messages['code']=false;;
    }
    echo json_encode($messages);
    exit();
}
$app->Views['main']->parse('main.content');
$app->Views['main']->parse('main');
$app->Views['main']->out('main');
