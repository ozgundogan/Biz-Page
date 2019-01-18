<?php
require ('../includes/Teknolobi.php');
require ('../includes/functions.php');

$GLOBALS['app'] = new Teknolobi();
$app->CreateView("main","main.tpl");
$app->CreateView("index","index.tpl");
$app->StartPage();
$app->Views["index"]->parse("main");
$app->Views['main']->assign("content",$app->Views["index"]->text("main"));

$app->EndPage();
$app->Views['main']->parse('main.content');
$app->Views['main']->parse('main');
$app->Views['main']->out('main');
