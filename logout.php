<?php
include "init.php";

if (isset($_SESSION["id"])) {
    unset($_SESSION["id"]);
    $_SESSION["message"] = "Ypu have logged out!";
    redir($site_url . "/dashboard/login.html");
} else {
    redir($site_url . "/dashboard/login.html");
}
