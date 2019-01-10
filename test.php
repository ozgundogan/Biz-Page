<?php
require ('includes/Teknolobi.php');
require ('includes/functions.php');

$GLOBALS['app'] = new Teknolobi();
$app->CreateView("main","main.tpl");
$app->CreateView("hakkinda","hakkinda.tpl");
$app->CreateView("hizmetler","hizmetler.tpl");
$app->CreateView("calismalar","calismalar.tpl");
$app->CreateView("blog","blog.tpl");
$app->CreateView("iletisim","iletisim.tpl");
$app->CreateView("detail","blog-single.tpl");
$app->CreateView("page","index.tpl");
$app->StartPage();
if(isset($_GET['sayfa'])){
  $sayfa=$_GET['sayfa'];
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
}  else{
    $app->Views['page']->parse('main');
    $app->Views['main']->assign('content',$app->Views['page']->text('main'));
  };
$app->EndPage();

$app->Views['main']->parse('main.content');
$app->Views['main']->parse('main');
$app->Views['main']->out('main');
