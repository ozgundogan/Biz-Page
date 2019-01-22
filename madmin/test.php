<?php
require ('../includes/Teknolobi.php');
require ('../includes/functions.php');


 $sqlType = "mysqli"; // Options: "mysql", "mysqli" , "mssql", "pgsql"
 $sqlServer = "127.0.0.1"; // Options: SQL Server name/IP
 $sqlUsername = "root"; // Sql username
 $sqlPassword = "";
 $sqlDatabase = "huris";
 $sqlPort="3306";

$GLOBALS['app'] = new Teknolobi();
$app->CreateView("main","main.tpl");
$app->CreateView("index","index.tpl");
$app->CreateView("ana","anasayfa.tpl");
$app->Views["index"]->parse("main");
$app->Views['main']->assign("content",$app->Views["index"]->text("main"));
if(isset($_GET["msayfa"])){
  $msayfa=$_GET["msayfa"];
  switch ($msayfa) {
    case 'anasayfa':
    $baglanti=mysqli_connect($sqlServer, $sqlUsername, $sqlPassword, $sqlDatabase, $sqlPort);
     $deg=mysqli_query($baglanti,'select * from slider');

    while($slider=mysqli_fetch_assoc($deg))
    {
     $app->Views['ana']->assign('sliderid',$slider["slider_id"]);
     $app->Views['ana']->assign('slidersira',$slider["slider_sira"]);
     $app->Views['ana']->assign('sliderresim',$slider["slider_resim"]);
     $app->Views['ana']->parse("main.sliders");
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
$app->Views['main']->parse('main.content');
$app->Views['main']->parse('main');
$app->Views['main']->out('main');
