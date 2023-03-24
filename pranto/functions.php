<?php

function get($var)
{
    if (isset($_GET[$var])) {
        return sanitize($_GET[$var]);
    } else {
        return null;
    }
}

function post($var)
{
    if (isset($_POST[$var])) {
        return sanitize($_POST[$var]);
    } else {
        return null;
    }
}


function sanitize($var)
{
    global $db;
    $con = $db->getConnection();

    return addslashes($con->real_escape_string(htmlspecialchars(trim($var))));
}

function redir($to)
{
    header('Location:' . $to);
}

function get_two_country()
{
    $file = file_get_contents(ROOT . "/pranto/country/ct.php");
    $country = explode("\n", $file);
    return $country;
}

function get_three_country()
{
    $file = file_get_contents(ROOT . "/pranto/country/cty.php");
    $country = explode("\n", $file);
    return $country;
}

function get_full_country()
{
    $file = file_get_contents(ROOT . "/pranto/country/country.php");
    $country = explode("\n", $file);
    return $country;
}

function country_convert($name, $from, $to)
{
    $file = file_get_contents(ROOT . "/pranto/country/ct.php");
    $file2 = file_get_contents(ROOT . "/pranto/country/cty.php");
    $file3 = file_get_contents(ROOT . "/pranto/country/country.php");
    $ct = explode("\n", $file);
    $cty = explode("\n", $file2);
    $country = explode("\n", $file3);

    if ($from == "two" && $to == "full") {
        foreach ($ct as $i => $ct2) {
            if ($name == trim($ct2)) {
                return $country[$i];
            }
        }
    }
    if ($from == "three" && $to == "full") {
        foreach ($cty as $i => $ct2) {
            if ($name == trim($ct2)) {
                return $country[$i];
            }
        }
    }
    if ($from == "full" && $to == "two") {
        foreach ($country as $i => $ct2) {
            if ($name == trim($ct2)) {
                return $ct[$i];
            }
        }
    }
    if ($from == "two" && $to == "three") {
        foreach ($ct as $i => $ct2) {
            if ($name == trim($ct2)) {
                return $cty[$i];
            }
        }
    }

}

function device_code()
{
    $file = file_get_contents(ROOT . "/pranto/country/dev.php");
    $country = explode("\n", $file);
    return $country;
}

function device_full()
{
    $file = file_get_contents(ROOT . "/pranto/country/device.php");
    $country = explode("\n", $file);
    return $country;
}

function get_device_code()
{
    foreach (device_code() as $ua) {
        if (stristr(get_ua(), $ua) === TRUE) {
            return $ua;
            break;
        } else {
            return "unknown";
        }
    }
}

// User Agent
function get_ua()
{
    if (isset($_SERVER["X_OPERAMINI_PHONE_UA"])) {
        return $_SERVER["X_OPERAMINI_PHONE_UA"];
    } else {
        return $_SERVER["HTTP_USER_AGENT"];
    }
}

function get_captcha()
{
    return strtolower(post("captcha"));
}
