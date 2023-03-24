<?php

include "init.php";

if ($user->is_user()) {
    redir($site_url . "/dashboard/index.html");
    exit();
}

$smarty->assign("title", "Log In - " . $site_name);

if (isset($_SESSION["message"])) {
    $smarty->assign("message", $_SESSION["message"]);
    unset($_SESSION["message"]);
}

if (isset($_POST["email"]) && isset($_POST["password"])) {

    $email = post("email");
    $password = post("password");

    $errors = array();

    if (empty($email) OR strlen($email) < 1) $errors[] = "Email is empty";
    if (empty($password) OR strlen($password) < 1) $errors[] = "Password is empty";
    if (!$user->login($email, $password)) $errors[] = "Email or password is wrong";


    if (empty($errors)) {
        $id = $user->gdata("id", array("email" => $email));
        $_SESSION["id"] = $id;
        redir($site_url . "/dashboard/index.html");
    } else {
        $smarty->assign("errors", $errors);
    }
}

$smarty->display("login.tpl");
		