<?php

include "init.php";

if (!$user->is_user()) {
    redir($site_url . "/dashboard/login.html");
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

$smarty->assign("title", "Change Password");

if (isset($_GET["sendPin"]) && $_GET["sendPin"] == "new") {

    if ($user->pin()) {
        $_SESSION["message"] = "New PIN has been sent to your Email";

        if (isset($_GET["payment"])) {
            redir($site_url . "/payout");
        } else {
            redir($site_url . "/dashboard/change_password.html");
        }
    } else {
        echo "CRITICAL_DB_ERROR!";
    }
} else {

    if (isset($_POST["old_password"]) && isset($_POST["new_password1"]) && isset($_POST["new_password2"]) && isset($_POST["pin"])) {

        $oldPassword = post("old_password");
        $newPassword1 = post("new_password1");
        $newPassword2 = post("new_password2");
        $pin = post("pin");

        $errors = array();

        if ($user->data("password") != md5($oldPassword)) $errors[] = "Old password is wrong";
        if (empty($newPassword1) OR strlen($newPassword1) < 1) $errors[] = "Password left empty";
        if ($newPassword1 != $newPassword2) $errors[] = "New pasword and confirm password did not match";
        if (empty($pin) OR strlen($pin) < 1) $errors[] = "PIN was empty";
        if ($pin != $user->data("pin")) $errors[] = "Invalid PIN";
        if (empty($errors)) {

            $code = $db->update("users", array("password" => md5($newPassword1)), array("id" => $user->id));
            if ($code) {
                $_SESSION["message"] = "Password Changed Successfully!";
                unset($_SESSION["id"]);
                redir($site_url . "/dashboard/login.html");
            } else {
                echo "CRITICAL_DB_ERROR!";
                exit;
            }
        } else {
            $smarty->assign("change_pw_error", $errors);
        }
    }

    if (isset($_SESSION["message"])) {
        $smarty->assign("message", $_SESSION["message"]);
        unset($_SESSION["message"]);
    }

    $form = '<form method="POST">
		<div class="form-group">
		<label>Old Password</label>
		<input type="password" name="old_password" class="form-control">
		</div>
		<div class="form-group">
		<label>New Password</label>
		<input type="password" name="new_password1" class="form-control">
		</div>
		<div class="form-group">
		<label>Confirm new password</label>
		<input type="password" name="new_password2" class="form-control">
		</div>
		<div class="form-group">
		<label>PIN (<a href="?sendPin=new">Send New PIN</a>)</label>
		<input type="password" name="pin" class="form-control">
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success">Change Password</button>
		</div></form>';
    $smarty->assign("change_pw_form", $form);
    $smarty->display("change_password.tpl");
}