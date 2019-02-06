<?php
require ('../includes/Teknolobi.php');
include ('../includes/functions.php');

$GLOBALS['app'] = new Teknolobi();

$app->CreateView("main","main.tpl");
$app->CreateView("index","index.tpl");
$app->CreateView("ana","anasayfa.tpl");
$app->CreateView("user","user/users.tpl");
$app->CreateView("edit","user/edit.tpl");
$app->CreateView("register","user/register.tpl");
$app->CreateView("blog","blog.tpl");
$app->CreateView("menu","menu.tpl");
$app->StartPage();
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
        $categories= $app->data_get("select * from menuler order by orderBy desc");

        while ($row = $app->data_fetch_array($categories)) {
            $menu[] = [
                'id' => $row["id"],
                'title' => $row["title"],
                'parent' => $row["parent"],
                'status' => $row["status"]
            ];
        }

        $result= menu($menu,0);
        $app->Views['menu']->assign('menuler',$result);
        $app->Views['menu']->parse('main');
        $app->Views['main']->assign('content',$app->Views['menu']->text('main'));
        break;
        default:
        $app->Views["index"]->parse("main");
        $app->Views['main']->assign("content",$app->Views["index"]->text("main"));
        break;
    }
}
else{
    $app->Views["index"]->parse("main");
    $app->Views['main']->assign("content",$app->Views["index"]->text("main"));
}
if(isset($_POST["sliderkaydet"])){

    $id=$_POST["sliderid"];
    $uploads_dir="images/uploads";
    $temp_name=$_FILES['slidergorsel']['tmp_name'];
    $resim_name=$_FILES['slidergorsel']['name'];
    $slidersira=$_POST["slidersira"];
    $resim_name=seo($resim_name);
    $sliderresimyol=$uploads_dir."/".$resim_name;

    @move_uploaded_file($temp_name,'$uploads_dir/$resim_name');

    $slidersira=$_POST["slidersira"];
    $kaydet=$app->data_run("update slider set slider_sira=$slidersira, slider_resim='$sliderresim' where slider_id=$id");

    header("location:madmin/?msayfa=anasayfa");
}

if(isset($_POST["sliderduzenle"])){
    $array=array();
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
        $sorgu.= ", slider_resim='".$sliderresimyol."'";
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
    $slidersil = mysqli_query($baglanti, "delete from slider where slider_id='" . $_GET["slidesid"] . "'");
    if ($sil) {
        unlink("$resimyol");
        $array["durum"]=true;
    }
    else{
        $array["durum"]=false;
    }
    echo json_encode($array);
}
function sirala($list, $parent_id = 0, & $m_order = 0)
{
    $ap= new Teknolobi();
    $ap->data_connect();
    foreach($list as $item) {
        $m_order++;
        $update=$ap->data_run("update menuler set orderBy=".$m_order.", parent=".$parent_id." where id='".$item["id"]."'");
        if($update){
            exit();
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

$app->EndPage();
$app->Views['main']->parse('main.content');
$app->Views['main']->parse('main');
$app->Views['main']->out('main');
