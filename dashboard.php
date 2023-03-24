<?php

include 'init.php';

if ($user->is_user()) {

    $smarty->assign("title", "Dashboard - " . $site_name);

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

    if (isset($_SESSION["message"])) {
        $smarty->assign("message", $_SESSION["message"]);
        unset($_SESSION["message"]);
    }
    $smarty->assign("publisher_balance", round($user->data("pbal"), 2));
    $smarty->assign("advertiser_balance", round($user->data("abal"), 2));
    $smarty->assign("username", $user->data("name"));

    $click_stats = $db->select("clicks", "vclicks", array("date" => $date, "uid" => $user->id));
    $today_clicks = 0;

    if ($click_stats->num_rows != 0) {
        while ($r = $click_stats->fetch_assoc()) {
            $today_clicks = $today_clicks + $r["vclicks"];
        }
    }

    $click_statst = $db->select("clicks", "vclicks", array("uid" => $user->id));
    $total_clicks = 0;

    if ($click_statst->num_rows != 0) {
        while ($r = $click_statst->fetch_assoc()) {
            $total_clicks = $total_clicks + $r["vclicks"];
        }
    }

    $earn_stats = $db->select("earnings", "earnings", array("date" => $date, "uid" => $user->id));
    $today_earnings = "0.00";

    if ($earn_stats->num_rows != 0) {
        while ($r = $earn_stats->fetch_assoc()) {
            $today_earnings = $today_earnings + $r["earnings"];
        }
    }

    $earn_statst = $db->select("earnings", "earnings", array("uid" => $user->id));
    $total_earnings = "0.00";

    if ($earn_statst->num_rows != 0) {
        while ($r = $earn_statst->fetch_assoc()) {
            $total_earnings = $total_earnings + $r["earnings"];
        }
    }

    $smarty->assign("today_clicks", $today_clicks);
    $smarty->assign("total_clicks", $total_clicks);
    $smarty->assign("today_earnings", round($today_earnings, 2));
    $smarty->assign("total_earnings", round($total_earnings, 2));

    for ($iDay = 6; $iDay >= 0; $iDay--) {
        $aDays[7 - $iDay] = $currDate = date('Y-m-d', strtotime("-" . $iDay . " day"));

        $click_data = $db->select("clicks", "vclicks", array("date" => $currDate, "uid" => $user->id));

        if ($click_data->num_rows == 0) {
            $clicks_stats[7 - $iDay] = 0;
        } else {
            $t_clicks = 0;

            while ($r = $click_data->fetch_assoc()) {
                $t_clicks = $t_clicks + $r["vclicks"];
            }

            $clicks_stats[7 - $iDay] = $t_clicks;
        }
    }

    $total_dates = implode("\",\"", $aDays);
    $total_clicks = implode(",", $clicks_stats);

    $smarty->assign("g_total_dates", $total_dates);
    $smarty->assign("g_total_clicks", $total_clicks);

    $notifications = $db->select("notifications", "id,subject", array("userid" => $user->id, "status" => 1));
    if ($notifications->num_rows == 1) {
        $result = $notifications->fetch_assoc();
        $smarty->assign("notifications", '<a href="' . $site_url . '/notifications/' . $result["id"] . '">' . $result["subject"] . '</a>');
    } elseif ($notifications->num_rows > 1) {
        $smarty->assign("notifications", '<a href="' . $site_url . '/notifications">You have ' . $notifications->num_rows . ' Notifications!</a>');
    } else {
    }

    $news = $db->select("news", "id,title,date", array(), "id DESC", "0,3");

    if ($news->num_rows != 0) {
        $n = array();

        while ($r = $news->fetch_assoc()) {
            $n[] = $r;
        }

        $smarty->assign("news", $n);
    }

    // Site List
    $sites = $db->select("sites", "id,name,url,category,status", array("userid" => $user->id));

    if ($sites->num_rows > 0) {
        $sitelist = array();

        while ($r = $sites->fetch_assoc()) {
            $sitelist[] = $r;
        }

        $smarty->assign("sites", $sitelist);
    }

    $smarty->display("dashboard.tpl");
} else {
    redir($site_url . "/dashboard/login.html");
}

