<?php

include "init.php";

$smarty->assign("title", $setting["sitetitle"]);

if ($user->is_user()) {

    $smarty->assign("publisher_balance", $user->data("pbal"));
    $smarty->assign("advertiser_balance", $user->data("abal"));
    $smarty->assign("logged", 1);

}

if ($setting["news"] == 1) {

    $news = $db->select("news", "id,title", "", "id DESC", "0,1");
    $News = $news->fetch_assoc();
    if ($news->num_rows > 0) {
        $smarty->assign("news", '<a href="' . $setting["siteurl"] . '/news/' . $News["id"] . '">' . $News["title"] . '</a>');
    } else {
        $smarty->assign("news", "No News Available");
    }
}

$clicks = $db->select("clicks", "id", "");
$impressions = $db->select("impressions", "id", "");
$users = $db->select("users", "id", "");
$amount = "0.00";
$Paid = $db->select("invoices", "amount", array("status" => 1));
while ($paid = $Paid->fetch_assoc()) {
    $amount = ($amount + $paid["amount"]);
}

$smarty->assign("clicks", $clicks->num_rows);
$smarty->assign("impressions", $impressions->num_rows);
$smarty->assign("users", $users->num_rows);
$smarty->assign("paid", $amount);

template("homepage.tpl");
