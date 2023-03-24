<?php
include 'init.php';
$site = new \Pranto\Site();
$act = get("act");

if (!$user->is_user()) {
    redir($$site_url . "/dashboard/login.html");
    exit();
}

if ($user->data("status") == 2) {
    $smarty->assign("title", "Account Unverified " . $site_name);
    $smarty->display("user_unverified.tpl");
    exit();
}
if ($user->data("status") == 0) {
    $smarty->assign("title", "Account Blocked " . $site_name);
    $smarty->display("user_blocked.tpl");
    exit();
}

if ($act == "add") {

    echo "Deprecated!";
    exit();

} elseif ($act == "edit" && $_GET["id"]) {

    echo "Deprecated!";
    exit();

} elseif ($act == "del" && $_GET["id"]) {

    $id = get("id");

    if (!$site->own_site($id, $user->id)) exit("Site not found!");

    if ($_GET["c"] == "y") {

        if ($db->delete("sites", array("id" => $id))) {
            $_SESSION["message"] = "Site deleted successfully";
            redir($site_url . "/dashboard/index.html");
        } else {
            echo "Unknown Error!";
        }
    }
} elseif ($act == "details" && $_GET["id"]) {
    echo "Deprecated!";
    exit();

} else {

    echo "Deprecated!";
    exit();
}
		
			
		
		
		
		