<?php
include "init.php";

$smarty->assign("title", "Contact Adimoney");

if (isset($_POST["email"], $_POST["name"], $_POST["subject"], $_POST["message"], $_POST["captcha"])) {
    $email = post("email");
    $name = post("name");
    $subject = post("subject");
    $message = post("message");
    $captcha = post("captcha");

    $errors = array();

    if (empty($email) || empty($name) || empty($subject) || empty($message))
        $errors[] = "All fields are required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid Email";
    if ($captcha != $_SESSION["captcha"]) $errors[] = "Wrong Captcha";

    if (empty($errors)) {

    }
}