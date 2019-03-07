<?php
require ('includes/Teknolobi.php');
include ('includes/functions.php');

$GLOBALS['app'] = new Teknolobi();
$app->CreateView("main","main.tpl");

$app->CreateView("hakkinda","hakkinda.tpl");
$app->CreateView("hizmetler","hizmetler.tpl");
$app->CreateView("calismalar","calismalar.tpl");
$app->CreateView("blog","blog.tpl");
$app->CreateView("iletisim","iletisim.tpl");
$app->CreateView("detail","blog-single.tpl");
$app->CreateView("page","index.tpl");
$app->CreateView("404","404.tpl");
$app->StartPage();
$app->data_connect();
if(isset($_GET['sayfa'])){
  $sayfa=current(explode('/',$_GET['sayfa']));
  //  echo $sayfa;
  switch ($sayfa) {
    case 'hakkinda':
    $app->Views['hakkinda']->parse('main');
    $app->Views['main']->assign('content',$app->Views['hakkinda']->text('main'));
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
    $app->Views['blog']->assign('blogdetay',"/".slug("see more details")."/"."3");
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
    $app->Views['page']->parse('main');
    $app->Views['main']->assign('content',$app->Views['page']->text('main'));
    break;
  }
}  else{
  $sliders=$app->data_get("select * from slider order by slider_sira asc");
  $menus=$app->data_get("select * from menuler");
  $genel=$app->data_get("select * from  genel");

  while($row=$app->data_fetch_array($genel)){
    $app->Views['page']->assign('slogan',$row['slogan']);
    $app->Views['main']->assign('logo',$row['logo_image']);
  }

  while($menu=$app->data_fetch_array($menus))
  {
    $rows[] =[
      'id'     => $menu["id"],
      'parent' => $menu["parent"],
      'title'  => $menu["title"],
      'link'   =>$menu['link'],
      'page'=>$menu['page']
    ];
  }
  $result= menuGoster($rows,0);
  // print_r($result);
  // exit();
  $app->Views['main']->assign('menuolustur',$result);
  while($slider=$app->data_fetch_array($sliders))
  {
    $app->Views['page']->assign('img',$slider['slider_resim']);
    $app->Views['page']->parse('main.slider');
  }
  $app->Views['page']->parse('main');
  $app->Views['main']->assign('content',$app->Views['page']->text('main'));
}


$app->EndPage();
$app->Views['main']->parse('main.content');
$app->Views['main']->parse('main');
$app->Views['main']->out('main');
