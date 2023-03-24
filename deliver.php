<?php

// Include Files
include "init.php";

// init
$site = new Pranto\Site();
$ads = new Pranto\Ads();
$country = new Pranto\Country();

// Set Vars
$time_limit = 4;    // The time user should spent clicking ads

/**
 * Redirection URL when click is fake
 * Set FALSE for redirecting to advertisement
 */
$fake_url = "http://fake.adzdolar.com";

/** Db Connection */
$con = $db->getConnection();

// Clicks
if (isset($_GET["pr"]) && $_GET["pr"] == "click" && isset($_GET["click_id"])) {
    $click_id = get("click_id");
    $ad_id = isset($_GET["ad_id"]) ? get("ad_id") : $_SESSION["ad_id"];
    $site_id = $_SESSION["site_id"];
    $ip = $_SESSION["ip"];
    $ua = $_SESSION["ua"];
    $ref = empty($_SESSION["ref"]) ? "Empty" : $_SESSION["ref"];
    $time = $_SESSION["time"];
    $elapsed_time = time() - $time;

    // Remote Database
    $dbr = new mysqli("localhost", "root", "", "adzdollar_clicks");

    // Check IP
    $check_ip = $dbr->query("SELECT COUNT(id) FROM clicks WHERE date = '$date' AND ip = '$ip'");
    $ip_exists = $check_ip->fetch_row();

    // Let's stop fake clicks
    $fake = false;

    if ($ip != $_SERVER["REMOTE_ADDR"]) $fake = true;
    if ($ua != sanitize(get_ua())) $fake = true;
    if (isset($_COOKIE["xU4dSkQ8y"])) $fake = true;
    if ($elapsed_time < $time_limit) $fake = true;
    if ($ip_exists[0] != 0) $fake = true;
    if (!$site->exists($site_id)) $fake = true;
    if ($site->details($site_id, "status") != 1) $fake = true;
    if (!$ads->exists($ad_id)) $fake = true;

    // Not Fake
    if ($fake === FALSE) {
        // User Balance
        $user_balance = $user->gdata("pbal", array("id" => $site->details($site_id, "userid")));
        $user_balance = $user_balance + $ads->details($ad_id, "amount");

        // Owner Balance
        $ad_owner = $ads->details($ad_id, "userid");

        if ($ad_owner != 0) {
            $owner_balance = $user->gdata("abal", array("id" => $ad_owner));

            if ($owner_balance < $ads->details($ad_id, "camount")) {
                $db->update("ads", array("status" => 2), array("id" => $ad_id));
            } else {
                $owner_balance = $owner_balance - $ads->details($ad_id, "camount");
            }
        } else {
            $owner_balance = 0;
        }

        // Clicks
        $clicks = $db->select("clicks", "id,vclicks", array("uid" => $site->details($site_id, "userid"), "date" => $date, "sid" => $site_id, "aid" => $ad_id));

        if ($clicks->num_rows == 0) {
            $do = $db->insert("clicks", array("date" => $date, "uid" => $site->details($site_id, "userid"), "sid" => $site_id, "aid" => $ad_id, "vclicks" => 1, "iclicks" => 0));
        } else {
            $clicks = $clicks->fetch_assoc();
            $clicks_id = $clicks["id"];
            $clicks = $clicks["vclicks"];
            $clicks = $clicks + 1;

            $do = $db->update("clicks", array("vclicks" => $clicks), array("id" => $clicks_id));
        }

        // Earnings
        $earnings = $db->select("earnings", "id,earnings", array("uid" => $site->details($site_id, "userid"), "date" => $date, "sid" => $site_id));

        if ($earnings->num_rows == 0) {
            $do2 = $db->insert("earnings", array("date" => $date, "uid" => $site->details($site_id, "userid"), "sid" => $site_id, "earnings" => $ads->details($ad_id, "amount")));
        } else {
            $earnings = $earnings->fetch_assoc();
            $earnings_id = $earnings["id"];
            $earnings = $earnings["earnings"];
            $earnings = $earnings + $ads->details($ad_id, "amount");

            $do2 = $db->update("earnings", array("earnings" => $earnings), array("id" => $earnings_id));
        }

        // Add
        if ($do && $do2) {
            $db->update("users", array("pbal" => $user_balance), array("id" => $site->details($site_id, "userid")));

            if ($ad_owner != 0) {
                $db->update("users", array("abal" => $owner_balance), array("id" => $ad_owner));
            }

            $dbr->query("INSERT INTO clicks (`ip`, `ua`, `ref`, `time`, `country`, `uid`, `sid`, `aid`, `date`, `amount`, `type`) VALUES ('$ip', '$ua', '$ref', '$elapsed_time', '" . $country->get_country_name() . "', '" . $site->details($site_id, "userid") . "', '$site_id', '$ad_id', '$date', '" . $ads->details($ad_id, "amount") . "', 1)");
        }

        setcookie("xXuSQkRWhQ8y", 1, time() + 86400);
        header('Location: http://' . $ads->details($ad_id, "url"));
    } else {
        // Clicks
        $clicks = $db->select("clicks", "id,iclicks", array("uid" => $site->details($site_id, "userid"), "date" => $date, "sid" => $site_id, "aid" => $ad_id));

        if ($clicks->num_rows == 0) {
            $do = $db->insert("clicks", array("date" => $date, "uid" => $site->details($site_id, "userid"), "sid" => $site_id, "aid" => $ad_id, "vclicks" => 0, "iclicks" => 1));
        } else {
            $clicks = $clicks->fetch_assoc();
            $clicks_id = $clicks["id"];
            $clicks = $clicks["iclicks"];
            $clicks = $clicks + 1;

            $do = $db->update("clicks", array("iclicks" => $clicks), array("id" => $clicks_id));
        }

        if ($do) {
            $dbr->query("INSERT INTO clicks (`ip`, `ua`, `ref`, `time`, `country`, `uid`, `sid`, `aid`, `date`, `amount`, `type`) VALUES ('$ip', '$ua', '$ref', '$elapsed_time', '" . $country->get_country_name() . "', '" . $site->details($site_id, "userid") . "', '$site_id', '$ad_id', '$date', '" . $ads->details($ad_id, "amount") . "', 0)");
        }

        if ($fake_url === FALSE) {
            header('Location: http://' . $ads->details($ad_id, "url"));
        } else {
            header('Location: ' . $fake_url);
        }
    }

    // CLose
    $dbr->close();
    $dbr = NULL;
} else {
    // Ads Type
    $type = get("type");

    switch ($type) {
        case 'link':
            if (isset($_GET["site_id"])) {
                $site_id = get("site_id");

                // Site Error
                $error = false;

                if (empty($site_id)) $error = true;
                if (!$site->exists($site_id)) $error = true;
                if ($site->details($site_id, "status") != 1) $error = true;

                /**
                 * Start <html> Here
                 */

                if ($error === FALSE) {
                    // Site Type
                    $site_type = $site->details($site_id, "adult");

                    // Country Code
                    $c_code = $country->get_country_code();

                    // Device Code
                    $d_code = get_device_code();

                    $data = $con->query("SELECT id, type, titles, banners FROM ads WHERE (CASE WHEN cset = 1 THEN countrys LIKE '%$c_code%' ELSE cset = cset END) AND (CASE WHEN dset = 1 THEN devices LIKE '%$d_code%' ELSE dset = dset END) AND status = 1 AND adult = '$site_type' ORDER BY rand()");

                    if ($data->num_rows == 0) {
                        /**
                         * No Ads Available
                         * Add Your Own Ads Here
                         */
                    } else {
                        // Click ID
                        $click_id = md5(microtime());
                        $_SESSION["click_id"] = $click_id;

                        // User Credentials
                        $_SESSION["ip"] = $_SERVER["REMOTE_ADDR"];
                        $_SESSION["ua"] = sanitize(get_ua());
                        $_SESSION["ref"] = sanitize(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "Unknown");
                        $_SESSION["time"] = time();
                        $_SESSION["site_id"] = $site_id;

                        while ($ad = $data->fetch_assoc()) {
                            if ($ad["type"] == 1) {
                                if (strstr($ad["banners"], ",") !== FALSE) {
                                    $banners = explode(",", $ad["banners"]);
                                    $r = rand(0, count($banners) - 1);
                                    $banner = $banners[$r];
                                } else {
                                    $banner = $ad["banners"];
                                }

                                echo '<div class="your_class"><a href="http://ads.adimoney.com/go?click_id=' . $click_id . '&ad_id=' . $ad["id"] . '"><img src="http://ads.adimoney.com/banner-' . $banner . '.gif" alt="Loading"></a></div>';
                            } else {
                                if (strstr($ad["titles"], ",") !== FALSE) {
                                    $titles = explode(",", $ad["titles"]);
                                    $r = rand(0, count($titles) - 1);
                                    $title = $titles[$r];
                                } else {
                                    $title = $ad["titles"];
                                }

                                echo '<div class="your_class"><a href="http://ads.adimoney.com/go?click_id=' . $click_id . '&ad_id=' . $ad["id"] . '">' . $title . '</a></div>';
                            }

                            if (is_imp($ad["id"]) === FALSE) {
                                $impressions = $db->select("impressions", "impressions", array("uid" => $site->details($site_id, "userid"), "sid" => $site_id, "aid" => $ad["id"], "date" => $date));

                                if ($impressions->num_rows == 0) {
                                    $db->insert("impressions", array("uid" => $site->details($site_id, "userid"), "sid" => $site_id, "aid" => $ad["id"], "impressions" => 1, "date" => $date));
                                } else {
                                    $impressions = $impressions->fetch_assoc();
                                    $impressions = $impressions["impressions"];
                                    $impressions = $impressions + 1;

                                    $db->update("impressions", array("impressions" => $impressions), array("uid" => $site->details($site_id, "userid"), "sid" => $site_id, "aid" => $ad["id"], "date" => $date));
                                }

                                set_imp_cookie($ad["id"]);
                            }
                        }
                    }
                } else {
                    /**
                     * Site Error
                     * Put Ads or Message to user here
                     */
                }

                /**
                 * END </html> here
                 */
            } else {
                echo '[Error] => [Missing Parameter {site_id}]';
            }

            break;
        case 'js':
            if (isset($_GET["site_id"])) {
                $site_id = get("site_id");

                // Site Error
                $error = false;

                if (empty($site_id)) $error = true;
                if (!$site->exists($site_id)) $error = true;
                if ($site->details($site_id, "status") != 1) $error = true;

                if ($error === FALSE) {
                    // Site Type
                    $site_type = $site->details($site_id, "adult");

                    // Country Code
                    $c_code = $country->get_country_code();

                    // Device Code
                    $d_code = get_device_code();

                    $data = $con->query("SELECT id, type, titles, banners FROM ads WHERE (CASE WHEN cset = 1 THEN countrys LIKE '%$c_code%' ELSE cset = cset END) AND (CASE WHEN dset = 1 THEN devices LIKE '%$d_code%' ELSE dset = dset END) AND status = 1 AND adult = '$site_type' ORDER BY rand() LIMIT 0,1");

                    if ($data->num_rows == 0) {
                        /**
                         * No Ads Available
                         * Add Your Own Ads Here
                         * Must be in document.write() format
                         */
                    } else {
                        // Click ID
                        $click_id = md5(microtime());
                        $_SESSION["click_id"] = $click_id;

                        // User Credentials
                        $_SESSION["ip"] = $_SERVER["REMOTE_ADDR"];
                        $_SESSION["ua"] = sanitize(get_ua());
                        $_SESSION["ref"] = sanitize(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "Unknown");
                        $_SESSION["time"] = time();
                        $_SESSION["site_id"] = $site_id;

                        $js_ad = '';

                        while ($ad = $data->fetch_assoc()) {
                            if ($ad["type"] == 1) {
                                if (strstr($ad["banners"], ",") !== FALSE) {
                                    $banners = explode(",", $ad["banners"]);
                                    $r = rand(0, count($banners) - 1);
                                    $banner = $banners[$r];
                                } else {
                                    $banner = $ad["banners"];
                                }

                                // Ad ID
                                $_SESSION["ad_id"] = $ad["id"];

                                $js_ad = '<a href="http://ads.adimoney.com/go?click_id=' . $click_id . '"><img src="http://ads.adimoney.com/banner-' . $banner . '.gif" alt="Loading"></a>';
                            } else {
                                if (strstr($ad["titles"], ",") !== FALSE) {
                                    $titles = explode(",", $ad["titles"]);
                                    $r = rand(0, count($titles) - 1);
                                    $title = $titles[$r];
                                } else {
                                    $title = $ad["titles"];
                                }

                                // Ad ID
                                $_SESSION["ad_id"] = $ad["id"];

                                $js_ad = '<a href="http://ads.adimoney.com/go?click_id=' . $click_id . '">' . $title . '</a>';
                            }

                            if (is_imp($ad["id"]) === FALSE) {
                                $impressions = $db->select("impressions", "impressions", array("uid" => $site->details($site_id, "userid"), "sid" => $site_id, "aid" => $ad["id"], "date" => $date));

                                if ($impressions->num_rows == 0) {
                                    $db->insert("impressions", array("uid" => $site->details($site_id, "userid"), "sid" => $site_id, "aid" => $ad["id"], "impressions" => 1, "date" => $date));
                                } else {
                                    $impressions = $impressions->fetch_assoc();
                                    $impressions = $impressions["impressions"];
                                    $impressions = $impressions + 1;

                                    $db->update("impressions", array("impressions" => $impressions), array("uid" => $site->details($site_id, "userid"), "sid" => $site_id, "aid" => $ad["id"], "date" => $date));
                                }

                                set_imp_cookie($ad["id"]);
                            }
                        }

                        // Print JS
                        echo 'document.write("' . $js_ad . '");';

                        /**
                         * Want Something to add?
                         * This is your chance
                         * Add your JS codes here
                         */
                    }
                } else {
                    /**
                     * Site Error
                     * Put Ads or Message to user here
                     * in document.write()
                     */
                }
            } else {
                echo '[Error] => [Missing Parameter {site_id}]';
            }

            break;
        default:
            echo '[Error] => [Missing Parameter {site_id, type}]';
            break;
    }
}

function is_imp($aid)
{
    $return = false;
    if (isset($_COOKIE["imp"])) {
        $imps = sanitize($_COOKIE["imp"]);
        $imps = base64_decode($imps);
        $imps = explode(",", $imps);

        foreach ($imps as $imp) {
            if ($aid == $imp) {
                $return = true;
                break;
            } else {
                $return = false;
            }
        }
    } else {
        $return = false;
    }
    return $return;
}

function set_imp_cookie($id)
{
    if (isset($_COOKIE["imp"])) {
        $imps = sanitize($_COOKIE["imp"]);
        $imps = base64_decode($imps);
        $imps .= "," . $id;
    } else {
        $imps = $id;
    }

    setcookie("imp", base64_encode($imps), time() + 86400);
}
