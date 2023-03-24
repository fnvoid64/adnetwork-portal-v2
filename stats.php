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

$smarty->assign("title", "Reports");

$type = get("type");

// Sites & Ads
$u_sites = $db->select("sites", "name,id", array("userid" => $user->id));
$u_ads = $db->select("ads", "name,id", array("userid" => $user->id));

if ($u_sites->num_rows != 0) {
    $us_sites = array();

    while ($r = $u_sites->fetch_assoc()) {
        $us_sites[] = $r;
    }

    $smarty->assign("sites", $us_sites);
}

if ($u_ads->num_rows != 0) {
    $us_ads = array();

    while ($r = $u_ads->fetch_assoc()) {
        $us_ads[] = $r;
    }

    $smarty->assign("ads", $us_ads);
}

// Page
$page = get("page");
if (empty($page)) $page = 1;
$start = ($page - 1) * 30;
$end = 30;

$con = $db->getConnection();
// Stats
if ($type == "site" && isset($_GET["site_id"])) {
    $site_id = get("site_id");

    if (isset($_GET["date"])) {
        $date_range = get("date");
        $date_range = explode("/", $date_range);
        $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE (date BETWEEN '$date_range[0]' AND '$date_range[1]') AND uid = '$user->id' AND sid = '$site_id' ORDER BY date DESC LIMIT $start,$end");
    } else {
        $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE uid = '$user->id' AND sid = '$site_id' ORDER BY date DESC LIMIT $start,$end");
    }

    $s_dates = array();
    $s_clicks = array();
    $s_impressions = array();
    $s_earnings = array();
    $s_ctr = array();

    if ($dates->num_rows == 0) {
        $smarty->assign("no_stats", "true");
    } else {
        while ($row = $dates->fetch_array()) {
            $s_dates[] = $row["date"];

            $clicks = $db->select("clicks", "vclicks", array("date" => $row["date"], "uid" => $user->id, "sid" => $site_id));
            $impressions = $db->select("impressions", "impressions", array("date" => $row["date"], "uid" => $user->id, "sid" => $site_id));
            $earnings = $db->select("earnings", "earnings", array("date" => $row["date"], "uid" => $user->id, "sid" => $site_id));

            $c = $i = $e = 0;

            if ($clicks->num_rows != 0) {
                while ($r = $clicks->fetch_assoc()) {
                    $c = $c + $r["vclicks"];
                }

                while ($r = $impressions->fetch_assoc()) {
                    $i = $i + $r["impressions"];
                }

                while ($r = $earnings->fetch_assoc()) {
                    $e = $e + $r["earnings"];
                }
            }

            $s_clicks[] = $c;
            $s_earnings[] = $e;
            $s_impressions[] = $i;
            $s_ctr[] = round(($c / $i) * 100, 2);
        }

        $smarty->assign("dates", $s_dates);
        $smarty->assign("clicks", $s_clicks);
        $smarty->assign("earnings", $s_earnings);
        $smarty->assign("impressions", $s_impressions);
        $smarty->assign("ctr", $s_ctr);
        $init->paging($page, $dates->num_rows, 30);
    }
} elseif ($type == "ad" && isset($_GET["ad_id"])) {
    $ad_id = get("ad_id");

    if (isset($_GET["date"])) {
        $date_range = get("date");
        $date_range = explode("/", $date_range);

        $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE (date BETWEEN '$date_range[0]' AND '$date_range[1]') AND uid = '$user->id' AND aid = '$ad_id' ORDER BY date DESC LIMIT $start,$end");
    } else {
        $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE uid = '$user->id' AND aid = '$ad_id' ORDER BY date DESC LIMIT $start,$end");
    }

    $s_dates = array();
    $s_clicks = array();
    $s_impressions = array();
    $s_earnings = array();
    $s_ctr = array();

    if ($dates->num_rows == 0) {
        $smarty->assign("no_stats", "true");
    } else {
        while ($row = $dates->fetch_array()) {
            $s_dates[] = $row["date"];

            $clicks = $db->select("clicks", "vclicks", array("date" => $row["date"], "uid" => $user->id, "aid" => $ad_id));
            $impressions = $db->select("impressions", "impressions", array("date" => $row["date"], "uid" => $user->id, "aid" => $ad_id));

            $c = $i = $e = 0;

            if ($clicks->num_rows != 0) {
                while ($r = $clicks->fetch_assoc()) {
                    $c = $c + $r["vclicks"];
                }

                while ($r = $impressions->fetch_assoc()) {
                    $i = $i + $r["impressions"];
                }

                $ad = new Pranto\Ads();
                $e = $e + ($c * $ad->details($ad_id, "camount"));
            }

            $s_clicks[] = $c;
            $s_earnings[] = $e;
            $s_impressions[] = $i;
            $s_ctr[] = round(($c / $i) * 100, 2);
        }

        $smarty->assign("dates", $s_dates);
        $smarty->assign("clicks", $s_clicks);
        $smarty->assign("earnings", $s_earnings);
        $smarty->assign("impressions", $s_impressions);
        $smarty->assign("ctr", $s_ctr);
        $smarty->assign("itsad", 1);
        $init->paging($page, $dates->num_rows, 30);
    }
} else {
    if (isset($_GET["date"])) {
        $date_range = get("date");
        $date_range = explode("/", $date_range);
        $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE (date BETWEEN '$date_range[0]' AND '$date_range[1]') AND uid = '$user->id' ORDER BY date DESC LIMIT $start,$end");
    } else {
        $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE uid = '$user->id' ORDER BY date DESC LIMIT $start,$end");
    }

    $s_dates = array();
    $s_clicks = array();
    $s_impressions = array();
    $s_earnings = array();
    $s_ctr = array();

    if ($dates->num_rows == 0) {
        $smarty->assign("no_stats", "true");
    } else {
        while ($row = $dates->fetch_array()) {
            $s_dates[] = $row["date"];

            $clicks = $db->select("clicks", "vclicks", array("date" => $row["date"], "uid" => $user->id));
            $impressions = $db->select("impressions", "impressions", array("date" => $row["date"], "uid" => $user->id));
            $earnings = $db->select("earnings", "earnings", array("date" => $row["date"], "uid" => $user->id));

            $c = $i = $e = 0;

            if ($clicks->num_rows != 0) {
                while ($r = $clicks->fetch_assoc()) {
                    $c = $c + $r["vclicks"];
                }

                while ($r = $impressions->fetch_assoc()) {
                    $i = $i + $r["impressions"];
                }

                while ($r = $earnings->fetch_assoc()) {
                    $e = $e + $r["earnings"];
                }
            }

            $s_clicks[] = $c;
            $s_earnings[] = $e;
            $s_impressions[] = $i;
            $s_ctr[] = round(($c / $i) * 100, 2);
        }

        $smarty->assign("dates", $s_dates);
        $smarty->assign("clicks", $s_clicks);
        $smarty->assign("earnings", $s_earnings);
        $smarty->assign("impressions", $s_impressions);
        $smarty->assign("ctr", $s_ctr);
        $init->paging($page, $dates->num_rows, 30);
    }
}

$smarty->display("stats.tpl");