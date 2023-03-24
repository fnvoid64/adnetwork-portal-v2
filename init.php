<?php

ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define("PRANTO", true);
define("ROOT", dirname(__FILE__));

include ROOT . "/config.php";

/**
 * @param $c
 */
function load_core($c)
{
    if (preg_match('#Pranto\\\#i', $c)) {
        include ROOT . "/pranto/" . strtolower(str_replace('Pranto\\', null, $c)) . ".class.php";
    }
}

spl_autoload_register(__NAMESPACE__ . 'load_core');

try {
    $db = Pranto\Database::getInstance($db_host, $db_user, $db_pass, $db_name);
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}

include ROOT . "/pranto/functions.php";

require_once ROOT . "/pranto/smarty/Autoloader.php";
Smarty_Autoloader::register();
$smarty = new Smarty();
$smarty->setTemplateDir(ROOT . "/templates");
$smarty->setCompileDir(ROOT . "/smarty/compile");
$smarty->setCacheDir(ROOT . "/smarty/cache");

$smarty->assign("site_url", $site_url);
$smarty->assign("root", $site_url . "/templates");

$user = new \Pranto\User();
$init = new \Pranto\Init();
$date = date("Y-m-d");

if (isset($_SESSION["language"])) {
    $language = $_SESSION["language"];
} else {
    $language = "en";
}
$_LANG = array();
include(ROOT . "/pranto/languages/language." . $language . ".php");

$smarty->assign("sitename", $site_name);

if ($user->is_user()) {
    $smarty->assign("name", $user->data("name"));
    $smarty->assign("pbalance", $user->data("pbal"));
    $smarty->assign("email", $user->data("email"));
}