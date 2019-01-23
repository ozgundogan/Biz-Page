<?php
require ('../includes/Teknolobi.php');
require ('../includes/functions.php');

$GLOBALS['app'] = new Teknolobi();
$app->CreateView("main","main.tpl");
$app->CreateView("index","index.tpl");
$app->CreateView("ana","anasayfa.tpl");
$app->StartPage();
$app->data_connect();
if(isset($_GET["msayfa"])){
  $msayfa=$_GET["msayfa"];

  switch ($msayfa) {

    case 'anasayfa':

    $deg=$app->data_get("select * from slider");

    while($slider=$app->data_fetch_array($deg))
    {
      $app->Views['ana']->assign('sliderid',$slider["slider_id"]);
      $app->Views['ana']->assign('slidersira',$slider["slider_sira"]);
      $app->Views['ana']->assign('sliderresim',$slider["slider_resim"]);

      $app->Views['ana']->assign('resimgoster',$slider['slider_id']);
      $app->Views['ana']->parse("main.satirlar");
    }
    $app->Views['ana']->parse('main');
    $app->Views['main']->assign('content',$app->Views['ana']->text('main'));
    break;
    case 'hizmetler':
    $app->Views['hizmetler']->parse('main');
    $app->Views['main']->assign('content',$app->Views['hizmetler']->text('main'));
    break;
    case 'calismalar':
    $app->Views['calismalar']->parse('main');
    $app->Views['main']->assign('content',$app->Views['calismalar']->text('main'));
    break;
    case 'blog':
    $app->Views['blog']->assign('blogdetay',"/".seo("see more details")."/"."3");
    $app->Views['blog']->parse('main');
    $app->Views['main']->assign('content',$app->Views['blog']->text('main'));
    break;
    case 'iletisim':
    $app->Views['iletisim']->parse('main');
    $app->Views['main']->assign('content',$app->Views['iletisim']->text('main'));
    break;
    case 'blogdetail':
    $app->Views['detail']->parse('main');
    $app->Views['main']->assign('content',$app->Views['detail']->text('main'));
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

  $sliderresimyol=$uploads_dir."/".$resim_name;

  @move_uploaded_file($temp_name,'$uploads_dir/$resim_name');

  $slidersira=$_POST["slidersira"];
  $kaydet=$app->data_run("update slider slider_sira=$slidersira, slider_resim='$sliderresim' where slider_id=$id");


  header("location:madmin/?msayfa=anasayfa");
}

if(isset($_POST["sliderduzenle"])){

  $id=$_POST["id"];
  $gorselname=$_POST["gorselname"];
  $siraname=$_POST["siraname"];
  $sliderorder=$_POST["$siraname"];
  $uploads_dir="images/uploads";

  $tmp_name=$_FILES["$gorselname"]["tmp_name"];
  $resim_name=$_FILES["$gorselname"]["name"];

  $sliderresimyol=$uploads_dir."/".$resim_name;

  @move_uploaded_file($temp_name,"$uploads_dir/$resim_name");

  $kaydet=$app->data_run("update slider set slider_sira='".$sliderorder."' , slider_resim='".$sliderresimyol."' where slider_id='".$id."'");

  if($kaydet){
    $durum="ok";

  } else{
    $durum="no";
  }
  echo $durum;
  /*echo $id.",".$customname.",".$uploads_dir.",".$tmp_name.",".$resim_name.",".$sliderresimyol.",".$slidersira."--bitti";*/
}
if(isset($_POST["sid"])){

$sil=$app->data_run("delete from slider where slider_id=".$_POST['sid']."");
if($sil){
  echo "silindi";
}

}
$app->EndPage();
$app->Views['main']->parse('main.content');
$app->Views['main']->parse('main');
$app->Views['main']->out('main');
