<?php
include 'init.php';
$register = new Pranto\Register();


if (isset($_GET["act"]) && $_GET["act"] == "reset") {

    $smarty->assign("title", "Reset Password " . $site_name);


    if (isset($_GET["email"]) && isset($_GET["token"])) {

        $errors = array();

        $email = get("email");
        $token = get("token");


        if (!$register->is_email($email)) $errors[] = "Email not found!";
        if (empty($email) OR strlen($email) < 1) $errors[] = "Email was empty";
        $code = $db->select("users", "token", array("email" => $email));
        $result = $code->fetch_assoc();
        if (empty($token) OR strlen($token) < 1 OR !is_numeric($token)) $errors[] = "Token was empty!";
        if ($token != $result["token"]) $errors[] = "Invalid Reset Token";

        if (empty($errors)) {
            $token2 = rand(1, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
            $smarty->assign("reset", 1);

            if (isset($_POST["password"]) && isset($_POST["cpassword"])) {


                $password = post("password");
                $cpassword = post("cpassword");

                if (empty($password) && strlen($password) < 1) $errors2[] = "Password was empty";
                if ($password != $cpassword) $errors2[] = "Password and confirm password did not match";

                if (empty($errors2)) {
                    if ($db->update("users", array("password" => md5($password)), array("email" => $email))) {
                        $db->update("users", array("token" => $token2), array("email" => $email));
                        $_SESSION["message"] = "Password reset successful!";
                        $code = $db->select("users", "id", array("email" => $email));
                        $result = $code->fetch_assoc();
                        $_SESSION["id"] = $result["id"];
                        redir($site_url . "/dashboard/index.html");
                    } else {
                        echo "Unknown Error!";
                    }
                } else {
                    $smarty->assign("reset_pw_error", $errors2);
                }
            }


            $form = '<form method="POST">
<div class="form-group">
<input type="password" class="form-control" name="password" placeholder="Password"/>
</div>
<div class="form-group">
<input type="password" class="form-control" name="cpassword" placeholder="Confirm Password"/>
</div>
<div class="form-group">
<button type="submit" class="btn btn-success">
Change Password
</button>
</div>
</form>';
            $smarty->assign("reset_pw_form", $form);

        } else {
            $smarty->assign("lp_reset_error", $errors);
        }
    } else {
        $form = '<form method="GET">
			<div class="form-group">
			<input type="text" name="email" class="form-control" placeholder="Email"/>
			</div>
			<div class="form-group">
			<input type="text" name="token" class="form-control" placeholder="Token"/>
			</div>
			<div class="form-group">
			<button type="submit" class="btn btn-success">Next</button></div></form>';
        $smarty->assign("lp_form", $form);
    }


    $smarty->display("lp_reset.tpl");

} else {
    $smarty->assign("title", "Lost Password");

    if (isset($_SESSION["message"])) {
        $smarty->assign("message", $_SESSION["message"]);
        unset($_SESSION["message"]);
    }

    if (isset($_POST["email"])) {

        $email = post("email");

        $errors = array();

        if (empty($email) OR strlen($email) < 1) $errors[] = "Email was empty";
        if (!$register->is_email($email)) $errors[] = "Email not found!";

        if (empty($errors)) {

            $token = rand(1, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
            if ($db->update("users", array("token" => $token), array("email" => $email))) {

                $mailBody = $init->mail("pwreset");
                $mailBody = str_replace("%username%", $user->gdata("username", array("email" => $email)), $mailBody);
                $mailBody = str_replace("%date%", $date, $mailBody);
                $mailBody = str_replace("%reset_link1%", $site_url . "/reset_password.html?email=$email&token=$token", $mailBody);
                $subject = "Reset Password";

                if ($user->mail($subject, $mailBody, $email)) {
                    $_SESSION["message"] = "Password Reset Instructions Sent To " . $email;
                    redir($site_url . "/lost_password.html");
                } else {
                    echo "unknown Error";
                }

            } else {
                echo "unknown error";
            }
        } else {
            $smarty->assign("lp_error", $errors);
        }

    }

    $form = '<form method="post">
		<div class="form-group">
		<input type="text" name="email" class="form-control" placeholder="Email"/>
		</div>
		<div class="form-group">
		<button type="submit" class="btn btn-success">Reset</button></div></form>';
    $smarty->assign("lp_form", $form);

    $smarty->display("lp.tpl");
}
			
			