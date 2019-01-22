<?php
require ('../includes/Teknolobi.php');
require ('../includes/functions.php');

$GLOBALS['app'] = new Teknolobi();
$app->CreateView("main","main.tpl");
$app->CreateView("index","index.tpl");
$app->CreateView("ana","anasayfa.tpl");

if(isset($_GET["msayfa"])){
  $msayfa=$_GET["msayfa"];
  switch ($msayfa) {
    case 'anasayfa':
    $app->data_connect();
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
  $temp_name=$_FILES['slidergorsel']['temp_name'];
  $resim_name=$_FILES['slidergorsel']['name'];
  $sliderresimyol=$uploads_dir."/".$resim_name;
  print_r($resim_name);
  exit();
  @move_uploaded_files($temp_name,"$uploads_dir/$resim_name");

  $slidersira=$_POST["slidersira"];
  $kaydet=$app->data_run("update slider slider_sira=$slidersira, slider_resim='$sliderresim' where slider_id=$id");

  if(mysqli_affected_rows()){
    header("location:madmin?msayfa=anasayfa");
  }

}

$app->Views['main']->parse('main.content');
$app->Views['main']->parse('main');
$app->Views['main']->out('main');
