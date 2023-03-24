<?php
include "init.php";

if (!$admin->is_admin()) {
    redir("login.php");
    exit();
}

$admin->head("Statistics", false, true);
echo '<section class="content-header">';

// User, Sites & Ads
$users = $db->select("users", "id,name,email", array());
$sites = $db->select("sites", "id,url", array());
$ads = $db->select("ads", "id,url", array());
$con = $db->getConnection();

echo <<<HTML
<div class="row">
<div class="col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-3">
					<h3 class="panel-title">Statistics</h3>
				</div>
				<div class="col-md-2">
					<select name="user_id" onchange="changeCat('?type=user&user_id=' + this.value)" class="form-control">
						<option>Select User</option>
HTML;
while ($r = $users->fetch_assoc()) {
    echo '<option value="' . $r["id"] . '">' . $r["name"] . ' (' . $r["email"] . ')</option>';
}
echo '</select></div>';

echo '<div class="col-md-2">
<select name="site_id" onchange="changeCat(\'?type=site&site_id=\' + this.value)" class="form-control">
	<option>Select Site</option>';
while ($r = $sites->fetch_assoc()) {
    echo '<option value="' . $r["id"] . '">' . $r["url"] . '</option>';
}
echo '</select></div>';

echo '<div class="col-md-2">
<select name="ad_id" onchange="changeCat(\'?type=ad&ad_id=\' + this.value)" class="form-control">
	<option>Select Ad</option>';
while ($r = $ads->fetch_assoc()) {
    echo '<option value="' . $r["id"] . '">' . $r["url"] . '</option>';
}
echo '</select></div>';

echo <<<HTML
<div class="col-md-3">
	<button class="btn btn-default" id="daterange-btn">
        <i class="fa fa-calendar"></i> Select Statistics Date 
        <i class="fa fa-caret-down"></i>
    </button>
</div>
</div>
</div>
HTML;

$stat = get("stat");

switch ($stat) {
    case 'deep':
        $deep = get("deep");
        $dbr = get_remote_db_instance();

        if (isset($_GET["user_id"])) {
            $id = get("user_id");
            $datas = $dbr->query("SELECT DISTINCT country FROM clicks WHERE date = '$deep' AND uid = '$id'");
            $g_add = "&user_id=" . $id;
        } elseif (isset($_GET["site_id"])) {
            $id = get("site_id");
            $datas = $dbr->query("SELECT DISTINCT country FROM clicks WHERE date = '$deep' AND sid = '$id'");
            $g_add = "&site_id=" . $id;
        } elseif (isset($_GET["ad_id"])) {
            $id = get("ad_id");
            $datas = $dbr->query("SELECT DISTINCT country FROM clicks WHERE date = '$deep' AND aid = '$id'");
            $g_add = "&ad_id=" . $id;
        } else {
            $datas = $dbr->query("SELECT DISTINCT country FROM clicks WHERE date = '$deep'");
            $g_add = '';
        }

        if ($datas->num_rows == 0) {
            echo '<div class="panel-body"><p><i>Ooops, Sorry! Looks like no statistics is available!</i></p>';
        } else {
            echo '<div class="panel-body">';
            echo '<table class="table table-hover table-bordered table-striped">
			<th class="text-center">Country</th>
			<th class="text-center">Valid Clicks</th>
			<th class="text-center">Invalid Clicks</th>';
            echo '<th class="text-center">Earnings ($)</th>';

            while ($r = $datas->fetch_assoc()) {
                echo '<tr class="dateClick" align="center" data-href="?stat=deeper&country=' . $r["country"] . $g_add . '&date=' . $deep . '" style="cursor: pointer">';
                echo '<td>' . $r["country"] . '</td>';

                if (isset($_GET["user_id"])) {
                    $vclicks = $dbr->query("SELECT COUNT(id) FROM clicks WHERE type = 1 AND country = '" . $r["country"] . "' AND date = '$deep' AND uid = '$id'");
                    $iclicks = $dbr->query("SELECT COUNT(id) FROM clicks WHERE type = 0 AND country = '" . $r["country"] . "' AND date = '$deep' AND uid = '$id'");
                    $e = $dbr->query("SELECT amount FROM clicks WHERE type = 1 AND country = '" . $r["country"] . "' AND date = '$deep' AND uid = '$id'");
                } elseif (isset($_GET["site_id"])) {
                    $vclicks = $dbr->query("SELECT COUNT(id) FROM clicks WHERE type = 1 AND country = '" . $r["country"] . "' AND date = '$deep' AND sid = '$id'");
                    $iclicks = $dbr->query("SELECT COUNT(id) FROM clicks WHERE type = 0 AND country = '" . $r["country"] . "' AND date = '$deep' AND sid = '$id'");
                    $e = $dbr->query("SELECT amount FROM clicks WHERE type = 1 AND country = '" . $r["country"] . "' AND date = '$deep' AND sid = '$id'");
                } elseif (isset($_GET["ad_id"])) {
                    $vclicks = $dbr->query("SELECT COUNT(id) FROM clicks WHERE type = 1 AND country = '" . $r["country"] . "' AND date = '$deep' AND aid = '$id'");
                    $iclicks = $dbr->query("SELECT COUNT(id) FROM clicks WHERE type = 0 AND country = '" . $r["country"] . "' AND date = '$deep' AND aid = '$id'");
                    $e = $dbr->query("SELECT amount FROM clicks WHERE type = 1 AND country = '" . $r["country"] . "' AND date = '$deep' AND aid = '$id'");
                } else {
                    $vclicks = $dbr->query("SELECT COUNT(id) FROM clicks WHERE type = 1 AND country = '" . $r["country"] . "' AND date = '$deep'");
                    $iclicks = $dbr->query("SELECT COUNT(id) FROM clicks WHERE type = 0 AND country = '" . $r["country"] . "' AND date = '$deep'");
                    $e = $dbr->query("SELECT amount FROM clicks WHERE type = 1 AND country = '" . $r["country"] . "' AND date = '$deep'");
                }

                $vclicks = $vclicks->fetch_row();
                $vclicks = $vclicks[0];

                $iclicks = $iclicks->fetch_row();
                $iclicks = $iclicks[0];

                $earnings = "0.00";
                while ($s = $e->fetch_assoc()) {
                    $earnings = $earnings + $s["amount"];
                }

                echo "<td>$vclicks</td>
				<td>$iclicks</td>
				<td>$earnings</td></tr>";
            }

            echo '</table></div>';
            $dbr->close();
        }
        break;

    case 'deeper':
        $country = get("country");
        $deep = get("date");
        $dbr = get_remote_db_instance();
        $page = get("page");
        if (empty($page)) $page = 1;
        $start = ($page - 1) * 50;
        $end = 50;

        if (isset($_GET["user_id"])) {
            $id = get("user_id");
            $datas = $dbr->query("SELECT ip,ua,ref,uid,sid,aid,time,amount,type FROM clicks WHERE date = '$deep' AND country = '$country' AND uid = '$id' ORDER BY id DESC LIMIT $start, $end");
        } elseif (isset($_GET["site_id"])) {
            $id = get("site_id");
            $datas = $dbr->query("SELECT ip,ua,ref,uid,sid,aid,time,amount,type FROM clicks WHERE date = '$deep' AND country = '$country' AND sid = '$id' ORDER BY id DESC LIMIT $start, $end");
        } elseif (isset($_GET["ad_id"])) {
            $id = get("ad_id");
            $datas = $dbr->query("SELECT ip,ua,ref,uid,sid,aid,time,amount,type FROM clicks WHERE date = '$deep' AND country = '$country' AND aid = '$id' ORDER BY id DESC LIMIT $start, $end");
        } else {
            $datas = $dbr->query("SELECT ip,ua,ref,uid,sid,aid,time,amount,type FROM clicks WHERE date = '$deep' AND country = '$country' ORDER BY id DESC LIMIT $start, $end");
        }

        if ($datas->num_rows == 0) {
            echo '<div class="panel-body"><p><i>Ooops, Sorry! Looks like no statistics is available!</i></p>';
        } else {
            echo '<div class="panel-body">';
            echo '<table class="table table-hover table-bordered table-striped">
			<th class="text-center">IP</th>
			<th class="text-center">UA</th>
			<th class="text-center">Referer</th>
			<th class="text-center">Time (s)</th>';
            echo '<th class="text-center">User</th>
			<th class="text-center">Site</th>
			<th class="text-center">Ad</th>
			<th class="text-center">Amount ($)</th>
			<th class="text-center">Type</th>';

            $site = new Pranto\Site();
            $ads = new Pranto\Ads();

            while ($r = $datas->fetch_assoc()) {
                echo '<tr align="center">';
                echo "<td>" . $r["ip"] . "</td>
				<td>" . $r["ua"] . "</td>
				<td>" . $r["ref"] . "</td>
				<td>" . $r["time"] . "</td>
				<td><a href=\"users.php?act=details&id=" . $r["uid"] . "\">" . $user->gdata("name", array("id" => $r["uid"])) . "</a></td>
				<td><a href=\"sites.php?act=details&id=" . $r["sid"] . "\">" . $site->details($r["sid"], "url") . "</a></td>
				<td><a href=\"ads.php?act=details&id=" . $r["aid"] . "\">" . $ads->details($r["aid"], "url") . "</a></td>
				<td>" . $r["amount"] . "</td><td>";
                echo $r["type"] == 1 ? "Valid" : "Invalid" . '</td>';
                echo '</tr>';
            }

            echo '</table></div></div>';

            $init->paging_admin($page, $datas->num_rows, 50);
        }
        break;
    default:
        $type = get("type");
        $page = get("page");
        if (empty($page)) $page = 1;
        $start = ($page - 1) * 30;
        $end = 30;

        // Stats
        if ($type == "user" && isset($_GET["user_id"])) {
            $user_id = get("user_id");

            if (isset($_GET["date"])) {
                $date_range = get("date");
                $date_range = explode("/", $date_range);

                $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE (date BETWEEN '$date_range[0]' AND '$date_range[1]') AND uid = '$user_id' ORDER BY date DESC LIMIT $start,$end");
            } else {
                $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE uid = '$user_id' ORDER BY date DESC LIMIT $start,$end");
            }

            $s_dates = array();
            $s_vclicks = array();
            $s_iclicks = array();
            $s_impressions = array();
            $s_earnings = array();
            $s_ctr = array();
            $s_vcr = array();

            if ($dates->num_rows == 0) {
                echo '<div class="panel-body"><p><i>Ooops, Sorry! Looks like no statistics is available!</i></p>';
            } else {
                while ($row = $dates->fetch_array()) {
                    $s_dates[] = $row["date"];

                    $clicks = $db->select("clicks", "vclicks, iclicks", array("date" => $row["date"], "uid" => $user_id));
                    $impressions = $db->select("impressions", "impressions", array("date" => $row["date"], "uid" => $user_id));
                    $earnings = $db->select("earnings", "earnings", array("date" => $row["date"], "uid" => $user_id));

                    $cv = $ci = $i = $e = 0;

                    if ($clicks->num_rows != 0) {
                        while ($r = $clicks->fetch_assoc()) {
                            $cv = $cv + $r["vclicks"];
                            $ci = $ci + $r["iclicks"];
                        }

                        while ($r = $impressions->fetch_assoc()) {
                            $i = $i + $r["impressions"];
                        }

                        while ($r = $earnings->fetch_assoc()) {
                            $e = $e + $r["earnings"];
                        }
                    }

                    $s_vclicks[] = $cv;
                    $s_iclicks[] = $ci;
                    $s_earnings[] = $e;
                    $s_impressions[] = $i;
                    $s_ctr[] = round(($cv / $i) * 100, 2);
                    $s_vcr[] = round(($cv / $ci) * 100, 2);
                }

                show_stats($s_dates, $s_vclicks, $s_iclicks, $s_impressions, $s_earnings, $s_ctr, $s_vcr);
                $init->paging_admin($page, $dates->num_rows, 30);
            }
        } elseif ($type == "site" && isset($_GET["site_id"])) {
            $site_id = get("site_id");

            if (isset($_GET["date"])) {
                $date_range = get("date");
                $date_range = explode("/", $date_range);

                $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE (date BETWEEN '$date_range[0]' AND '$date_range[1]') AND sid = '$site_id' ORDER BY date DESC LIMIT $start,$end");
            } else {
                $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE sid = '$site_id' ORDER BY date DESC LIMIT $start,$end");
            }

            $s_dates = array();
            $s_vclicks = array();
            $s_iclicks = array();
            $s_impressions = array();
            $s_earnings = array();
            $s_ctr = array();
            $s_vcr = array();

            if ($dates->num_rows == 0) {
                echo '<div class="panel-body"><p><i>Ooops, Sorry! Looks like no statistics is available!</i></p>';
            } else {
                while ($row = $dates->fetch_array()) {
                    $s_dates[] = $row["date"];

                    $clicks = $db->select("clicks", "vclicks, iclicks", array("date" => $row["date"], "sid" => $site_id));
                    $impressions = $db->select("impressions", "impressions", array("date" => $row["date"], "sid" => $site_id));
                    $earnings = $db->select("earnings", "earnings", array("date" => $row["date"], "sid" => $site_id));

                    $cv = $ci = $i = $e = 0;

                    if ($clicks->num_rows != 0) {
                        while ($r = $clicks->fetch_assoc()) {
                            $cv = $cv + $r["vclicks"];
                            $ci = $ci + $r["iclicks"];
                        }

                        while ($r = $impressions->fetch_assoc()) {
                            $i = $i + $r["impressions"];
                        }

                        while ($r = $earnings->fetch_assoc()) {
                            $e = $e + $r["earnings"];
                        }
                    }

                    $s_vclicks[] = $cv;
                    $s_iclicks[] = $ci;
                    $s_earnings[] = $e;
                    $s_impressions[] = $i;
                    $s_ctr[] = round(($cv / $i) * 100, 2);
                    $s_vcr[] = round(($cv / $ci) * 100, 2);
                }

                show_stats($s_dates, $s_vclicks, $s_iclicks, $s_impressions, $s_earnings, $s_ctr, $s_vcr);
                $init->paging_admin($page, $dates->num_rows, 30);
            }
        } elseif ($type == "ad" && isset($_GET["ad_id"])) {
            $ad_id = get("ad_id");

            if (isset($_GET["date"])) {
                $date_range = get("date");
                $date_range = explode("/", $date_range);

                $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE (date BETWEEN '$date_range[0]' AND '$date_range[1]') AND aid = '$ad_id' ORDER BY date DESC LIMIT $start,$end");
            } else {
                $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE aid = '$ad_id' ORDER BY date DESC LIMIT $start,$end");
            }

            $s_dates = array();
            $s_vclicks = array();
            $s_iclicks = array();
            $s_impressions = array();
            $s_earnings = array();
            $s_ctr = array();
            $s_vcr = array();

            if ($dates->num_rows == 0) {
                echo '<div class="panel-body"><p><i>Ooops, Sorry! Looks like no statistics is available!</i></p>';
            } else {
                while ($row = $dates->fetch_array()) {
                    $s_dates[] = $row["date"];

                    $clicks = $db->select("clicks", "vclicks, iclicks", array("date" => $row["date"], "aid" => $ad_id));
                    $impressions = $db->select("impressions", "impressions", array("date" => $row["date"], "aid" => $ad_id));

                    $cv = $ci = $i = $e = 0;

                    if ($clicks->num_rows != 0) {
                        while ($r = $clicks->fetch_assoc()) {
                            $cv = $cv + $r["vclicks"];
                            $ci = $ci + $r["iclicks"];
                        }

                        while ($r = $impressions->fetch_assoc()) {
                            $i = $i + $r["impressions"];
                        }

                        $ad = new Pranto\Ads();
                        $e = $e + ($cv * $ad->details($ad_id, "camount"));
                    }

                    $s_vclicks[] = $cv;
                    $s_iclicks[] = $ci;
                    $s_earnings[] = $e;
                    $s_impressions[] = $i;
                    $s_ctr[] = round(($cv / $i) * 100, 2);
                    $s_vcr[] = round(($cv / $ci) * 100, 2);
                }

                show_stats($s_dates, $s_vclicks, $s_iclicks, $s_impressions, $s_earnings, $s_ctr, $s_vcr, true);
                $init->paging_admin($page, $dates->num_rows, 30);
            }
        } else {
            if (isset($_GET["date"])) {
                $date_range = get("date");
                $date_range = explode("/", $date_range);

                $dates = $con->query("SELECT DISTINCT date FROM clicks WHERE (date BETWEEN '$date_range[0]' AND '$date_range[1]') ORDER BY date DESC LIMIT $start,$end");
            } else {
                $dates = $con->query("SELECT DISTINCT date FROM clicks ORDER BY date DESC LIMIT $start,$end");
            }

            $s_dates = array();
            $s_vclicks = array();
            $s_iclicks = array();
            $s_impressions = array();
            $s_earnings = array();
            $s_ctr = array();
            $s_vcr = array();

            if ($dates->num_rows == 0) {
                echo '<div class="panel-body"><p><i>Ooops, Sorry! Looks like no statistics is available!</i></p>';
            } else {
                while ($row = $dates->fetch_array()) {
                    $s_dates[] = $row["date"];

                    $clicks = $db->select("clicks", "vclicks, iclicks", array("date" => $row["date"]));
                    $impressions = $db->select("impressions", "impressions", array("date" => $row["date"]));
                    $earnings = $db->select("earnings", "earnings", array("date" => $row["date"]));

                    $cv = $ci = $i = $e = 0;

                    if ($clicks->num_rows != 0) {
                        while ($r = $clicks->fetch_assoc()) {
                            $cv = $cv + $r["vclicks"];
                            $ci = $ci + $r["iclicks"];
                        }

                        while ($r = $impressions->fetch_assoc()) {
                            $i = $i + $r["impressions"];
                        }

                        while ($r = $earnings->fetch_assoc()) {
                            $e = $e + $r["earnings"];
                        }
                    }

                    $s_vclicks[] = $cv;
                    $s_iclicks[] = $ci;
                    $s_earnings[] = $e;
                    $s_impressions[] = $i;
                    $s_ctr[] = round(($cv / $i) * 100, 2);
                    $s_vcr[] = round(($cv / $ci) * 100, 2);
                }

                show_stats($s_dates, $s_vclicks, $s_iclicks, $s_impressions, $s_earnings, $s_ctr, $s_vcr);
                $init->paging_admin($page, $dates->num_rows, 30);
            }
        }
        break;
}

function show_stats($dts, $cv, $ci, $im, $e, $ct, $vc, $a = false)
{
    echo '<div class="panel-body">';
    echo '<table class="table table-hover table-bordered table-striped">
	<th class="text-center">Date</th>
	<th class="text-center">Valid Clicks</th>
	<th class="text-center">Invalid Clicks</th>
	<th class="text-center">Impressions</th>';

    if ($a === TRUE) {
        echo '<th class="text-center">Spent (Estimated)[$]</th>';
    } else {
        echo '<th class="text-center">Earnings ($)</th>';
    }

    echo '<th class="text-center">CTR (%)</th>';
    echo '<th class="text-center">VCR (%)</th>';

    foreach ($dts as $i => $dt) {
        if (isset($_GET["type"])) {
            if (isset($_GET["user_id"])) {
                $g_add = "&user_id=" . $_GET["user_id"];
            } elseif (isset($_GET["site_id"])) {
                $g_add = "&site_id=" . $_GET["site_id"];
            } else {
                $g_add = "&ad_id=" . $_GET["ad_id"];
            }
        } else {
            $g_add = '';
        }

        echo '<tr class="dateClick" align="center" data-href="?stat=deep&deep=' . $dt . $g_add . '" style="cursor: pointer">';
        echo "<td>$dt</td>
		<td>$cv[$i]</td>
		<td>$ci[$i]</td>
		<td>$im[$i]</td>
		<td>$e[$i]</td>
		<td>$ct[$i]</td>
		<td>$vc[$i]</td>";
        echo '</tr>';
    }

    echo '</table></div></div>';
}

function get_remote_db_instance()
{
    return new mysqli("localhost", "root", "", "adzdollar_clicks");
}

$script = <<<HTML
<script src="js/moment.min.js"></script>
<script src="js/daterangepicker.js"></script>
<script>
$(".dateClick").click(function() {
    window.location = $(this).data("href");
});
$('#daterange-btn').daterangepicker(
	{
	    ranges: {
	        'Today': [moment(), moment()],
	        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	        'This Month': [moment().startOf('month'), moment().endOf('month')],
	        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	    },
	    startDate: moment().subtract(29, 'days'),
	    endDate: moment(),
	    format: 'DD/MM/YYYY',
	    separator: ' to '
	},
	function (start, end) {
		location.href = URL_add_parameter(location.href, 'date', start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD'));
	    //$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	}
);
function changeCat(value) {
	location.href = value;
}
function URL_add_parameter(url, param, value){
    var hash       = {};
    var parser     = document.createElement('a');

    parser.href    = url;

    var parameters = parser.search.split(/\?|&/);

    for(var i=0; i < parameters.length; i++) {
        if(!parameters[i])
            continue;

        var ary      = parameters[i].split('=');
        hash[ary[0]] = ary[1];
    }

    hash[param] = value;

    var list = [];  
    Object.keys(hash).forEach(function (key) {
        list.push(key + '=' + hash[key]);
    });

    parser.search = '?' + list.join('&');
    return parser.href;
}
</script>
HTML;
$admin->foot($script);