<?php

require_once("application/controller/Application.php");
require_once("common/view/PageView.php");
require_once("common/view/DebugView.php");
require_once 'login/model/LoginDAL.php';
require_once 'login/model/LoginDBModel.php';

session_start();

\Debug::log("session started ");

$app = new \application\controller\Application();
$pageView = new \common\view\PageView();
$debug = new \common\view\DebugView();

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$page = $app->doFrontPage();
$debugDump =  $debug->getDebugData();
$page->body .= $debugDump;

echo $pageView->GetXHTML10StrictPage($page);
