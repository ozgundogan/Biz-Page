<?php
require_once __DIR__ . '/includes/plugins/vendor/autoload.php';   //composer dump-autoload
require ('includes/Teknolobi.php');
require ('includes/functions.php');

$GLOBALS['app'] = new Teknolobi();
$app->CreateView("main","main.tpl");
$app->CreateView("page","index.tpl");
$app->data_connect();
$app->StartPage();


$app->EndPage();
$app->Views['page']->parse('main');
$app->Views['main']->assign('content',$app->Views['page']->text('main'));
$app->Views['main']->parse('main');
$app->Views['main']->out('main');
