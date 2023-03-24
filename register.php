<?php
include "init.php";

if (isset($_GET["act"]) && $_GET["act"] == "verify") {

    $email = get("email");
    $token = get("token");

    $errors = array();

    if (empty($email) OR strlen($email) < 1) $errors[] = "Email Empty!";
    if (empty($token) OR strlen($token) < 1) $errors[] = "Token Empty!";

    if (empty($errors)) {
        if ($user->gdata("status", array("email" => $email)) == 1) {
            redir($site_url . "/dashboard/index.html");
            exit();
        }
        if ($user->gdata("token", array("email" => $email)) == $token) {
            $db->update("users", array("status" => 1, "token" => md5(microtime())), array("email" => $email));
            $_SESSION["message"] = "Email Verified Successfully!";
            redir($site_url . "/dashboard/index.html");
        } else {
            redir($site_url . "/dashboard/index.html");
        }
    } else {
        redir($site_url . "/dashboard/index.html");
    }
} else {
    $register = new \Pranto\Register();
    $captcha = new \Pranto\Captcha($g_site_key, $g_secret_key);

    if ($user->is_user()) {
        redir($site_url . "/dashboard/index.html");
        exit;
    }


    $smarty->assign("title", "Register - " . $site_name);

    if (isset($_POST["fullname"], $_POST["email"], $_POST["password1"], $_POST["password2"], $_POST["country"], $_POST["mobile"], $_POST["g-recaptcha-response"])) {
        $fullname = post("fullname");
        $email = post("email");
        $password1 = post("password1");
        $password2 = post("password2");
        $country = post("country");
        $mobile = post("mobile");


        $errors = array();

        if (empty($fullname) || empty($email) || empty($password1) || empty($password2) || empty($country) || empty($mobile) || empty(post("g-recaptcha-response"))) {
            $errors[] = "Required fields left empty!";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email is not valid!";
        }

        if ($register->is_email($email)) {
            $errors[] = "Email is already registered!";
        }

        if ($password1 != $password2) {
            $errors[] = "Password and confirm password doesn't match!";
        }

        if (strlen($country) > 2) {
            $errors[] = "Country is not valid!";
        }

        if (!is_numeric(str_replace("+", null, $mobile))) {
            $errors[] = "Mobile number is not valid!";
        }

        if (!$captcha->verify($_POST["g-recaptcha-response"])) {
            $errors[] = "Incorrect captcha, try again!";
        }

        /**if(isset($_GET["ref"])){
         * $ref = get("ref");
         * if(empty($ref) OR strlen($ref)<1)    $errors[] = "Referer not found!";
         * if(!$user->exists($ref))    $errors[] = "User not found!";
         * }**/

        if (empty($errors)) {
            $token = md5(microtime());
            if ($db->insert("users", array("name" => ucwords($fullname), "password" => md5($password1), "email" => $email, "country" => $country, "mobile" => $mobile, "status" => 2, "pbal" => "0.00", "abal" => "0.00", "token" => $token))) {
                $_SESSION["id"] = $db->link->insert_id;

                /**
                 * if ($ref) {
                 * $userId = $user->gdata("id", array("username" => $username));
                 * $db->insert("refer", array("ref" => $userId, "userid" => $ref, "date" => $date, "status" => 2), "");
                 * }**/

                $mailBody = $init->mail("register");
                $mailBody = str_replace("%username%", $fullname, $mailBody);
                $mailBody = str_replace("%date%", $date, $mailBody);
                $mailBody = str_replace("%verify_link%", $site_url . "/register/verify?email=$email&token=$token", $mailBody);
                $subject = "Registration";

                if ($user->mail($subject, $mailBody, $email)) {
                    $_SESSION["message"] = "You have registered successfully!";
                    redir($site_url . "/dashboard/index.html");
                } else {
                    echo "mail server error:";
                }

            } else {
                echo "CRITICAL_DB_ERROR!";
            }
        } else {
            $smarty->assign("errors", $errors);
        }
    }

    $country_options = "";

    $countri = get_full_country();
    $countryy = new \Pranto\Country();
    foreach (get_two_country() as $i => $ct) {
        if ($countryy->get_country_code() == trim($ct)) {

            $country_options .= '<option value="' . trim($ct) . '" selected="selected">' . ucwords(strtolower(trim($countri[$i]))) . '</option>';
        } else {
            $country_options .= '<option value="' . trim($ct) . '">' . ucwords(strtolower(trim($countri[$i]))) . '</option>';
        }
    }

    $smarty->assign("country_options", $country_options);
    $smarty->assign("captcha", $captcha->show());
    $smarty->display("register.tpl");
}