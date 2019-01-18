<?php
require ('includes/Teknolobi.php');
require ('includes/functions.php');

$GLOBALS['app'] = new Teknolobi();
$app->CreateView("main","main.tpl");

$app->CreateView("404","404.tpl");
$app->StartPage();


$app->EndPage();
$app->Views['404']->parse('main');
$app->Views['404']->out('main');
