<?php
include "init.php";

if (!$admin->is_admin()) {
    redir("login.php");
    exit();
}

$act = get("act");
$con = $db->getConnection();

if ($act == "search" && isset($_GET["q"])) {
    $admin->head("Search Users");
    echo '<section class="content-header">';
    $q = get("q");

    if (empty($q) OR strlen($q) < 1) {
        echo "QueryStringIsEmpty!";
        exit();
    }

    $page = get("page");

    if (empty($page)) $page = 1;

    $start = ($page - 1) * 30;
    $end = 30;
    $search = $con->query("SELECT id,name,email,pbal,status FROM users WHERE  name LIKE '%$q%' OR email LIKE '%$q%' LIMIT $start,$end");

    if ($search->num_rows > 0) {
        echo '<div class="row"><div class="col-lg-12"><div class="box box-default">
		<div class="box-header with-border">
		<h3 class="panel-title">Search Result For ' . $q . '</h3>
		</div><div class="box-body">';
        echo '<table class="table table-striped table-hover table-bordered">';
        echo '<th class="text-center">ID #</th>
<th class="text-center">Name</th>
<th class="text-center">E-Mail</th>
<th class="text-center">Balance</th>
<th class="text-center">Status</th> ';

        while ($result = $search->fetch_assoc()) {
            // Status
            if ($result["status"] == 1) {
                $status = '<span class="label label-success">Active</span>';
            } elseif ($result["status"] == 2) {
                $status = '<span class="label label-warning">Unverified</span>';
            } else {
                $status = '<span class="label label-success">Active</span>';
            }

            echo '<tr class="text-center">
			<td>' . $result["id"] . '</td>
			<td><a href="users.php?act=details&id=' . $result["id"] . '">' . $result["name"] . '</a></td>
			<td>' . $result["email"] . '</td>
			<td>$' . round($result["pbal"], 2) . '</td>
			<td>' . $status . '</td>
			</tr>';
        }

        echo '</table></div></div>';
        $init->paging_admin($page, $search->num_rows, 30);
    } else {
        echo '<div class="row"><div class="col-lg-12"><div class="box box-default">
			<div class="box-header with-border">
			<h3 class="panel-title">Search Result For ' . $q . '</h3>
			</div><div class="panel-body">No User Found For "' . $q . '"</div></div>';
    }

    echo '</div></div>';
} elseif ($act == "edit" && isset($_GET["id"])) {
    $admin->head("Edit User");
    echo '<section class="content-header">';

    $id = get("id");

    if (!$user->exists($id)) {
        echo "UserNotFound!";
        exit();
    }

    echo '<div class="row">
	<div class="col-md-12">
	<div class="panel panel-default">
	<div class="panel-heading">
	<h3 class="panel-title">Edit User</h3>
	</div>
	<div class="panel-body">';

    if (isset($_POST["pbal"], $_POST["abal"], $_POST["pin"])) {
        $pbal = post("pbal");
        $abal = post("abal");
        $pin = post("pin");

        $errors = array();

        if (empty($pbal) OR strlen($pbal) < 1 OR !is_numeric($pbal)) $errors[] = "Publisher Balance Invalid!";
        if (empty($abal) OR strlen($abal) < 1 OR !is_numeric($abal)) $errors[] = "Advertiser Balance Invalid!";
        if (empty($pin) OR strlen($pin) < 6 OR strlen($pin) > 6 OR !is_numeric($pin)) $errors[] = "PIN must be 6 char Number!";

        if (empty($errors)) {
            $update = $db->update("users", array("pbal" => $pbal, "abal" => $abal, "pin" => $pin), array("id" => $id));

            if ($update) {
                $_SESSION["message"] = "User " . $user->gdata("name", array("id" => $id)) . " Updated Successfully!";
                redir("users.php?act=details&id=" . $id);
            } else {
                echo "UnknownError~DbError";
            }
        } else {
            $admin->error($errors);
        }
    }

    echo '<form method="post">
	<div class="form-group">
	<label for="name">Name</label>
	<input type="text" readonly="readonly" value="' . $user->gdata("name", array("id" => $id)) . '" class="form-control"/>
	</div>
	<div class="form-group">
	<label for="email">E-mail</label>
	<input type="text" readonly="readonly" value="' . $user->gdata("email", array("id" => $id)) . '" class="form-control"/>
	</div>
	<div class="form-group">
	<label for="pbal">Publisher Balance</label>
	<div class="input-group">
	<div class="input-group-addon">$</div>
	<input type="text" name="pbal" value="' . $user->gdata("pbal", array("id" => $id)) . '" class="form-control"/>
	</div></div>
	<div class="form-group">
	<label for="pbal">Advertiser Balance</label>
	<div class="input-group">
	<div class="input-group-addon">$</div>
	<input type="text" name="abal" value="' . $user->gdata("abal", array("id" => $id)) . '" class="form-control"/>
	</div></div>
	<div class="form-group">
	<label for="pin">User PIN</label>
	<div class="row">
		<div class="col-md-8"><input type="text" name="pin" id="pin" value="' . $user->gdata("pin", array("id" => $id)) . '" class="form-control"/></div>
		<div class="col-md-4"><a class="btn btn-primary" onclick="generatePin();"><i class="fa fa-refresh"></i> Generate</a></div>
	</div>
	</div>
	<div class="form-group">
	<button type="submit" class="btn btn-success">Save</button>
	</div></form></div></div></div></div>
	<script>
	function generatePin()
	{
		var pin = Math.floor(Math.random()*900000) + 100000;
		document.getElementById("pin").value = pin;
	}
	</script>';
} elseif ($act == "del" && isset($_GET["id"])) {
    $admin->head("Delete User");
    $id = get("id");
    if (!$user->exists($id)) {
        echo "UserNotFound!";
        exit();
    }
    if ($_GET["confirm"] == "yes") {

        $delete = $con->query("DELETE FROM users WHERE id='$id'");
        if ($delete) {
            $_SESSION["message"] = "User " . $user->gdata("username", array("id" => $id)) . " deleted successfully!";
            redir("users.php");
        } else {
            echo "UnknownError~DbError";
        }
    } else {

        echo '<div class="title">Delete ' . $user->gdata("username", array("id" => $id)) . '?</div>';
        echo '<div class="menu" align="center">Are you Sure ??<br/><a href="users.php?act=del&id=' . $id . '&confirm=yes">Yes, Delete</a> - <a href="users.php?act=details&id=' . $id . '">No, Go Back</a></div>';

    }
} elseif ($act == "block" && isset($_GET["id"])) {
    $id = get("id");

    if (!$user->exists($id)) {
        echo "UserNotFound!";
        exit();
    }

    $db->update("users", array("status" => 0), array("id" => $id));
    $_SESSION["message"] = $user->gdata("name", array("id" => $id)) . " Blocked Successfully!";
    $mailBody = $init->mail("userblock");
    $mailBody = str_replace("%date%", $date, $mailBody);
    $mailBody = str_replace("%username%", $user->gdata("name", array("id" => $id)), $mailBody);
    $subject = "Account Blocked";
    $user->mail($subject, $mailBody, $user->gdata("email", array("id" => $id)));
    redir("users.php?act=details&id=" . $id);
} elseif ($act == "activate" && isset($_GET["id"])) {
    $id = get("id");

    if (!$user->exists($id)) {
        echo "UserNotFound!";
        exit();
    }

    $db->update("users", array("status" => 1), array("id" => $id));
    $_SESSION["message"] = $user->gdata("name", array("id" => $id)) . " Activated Successfully!";
    redir("users.php?act=details&id=" . $id);
} elseif ($act == "details" && isset($_GET["id"])) {
    $admin->head("User Details");
    $id = get("id");

    if (!$user->exists($id)) {
        echo "UserNotFound!";
        exit();
    }

    echo '<section class="content-header">';
    echo '<div class="row">
	<div class="col-lg-12">
	<div class="box box-primary">
	<div class="box-header with-border"><h3 class="panel-title">' . $user->gdata("name", array("id" => $id)) . '\'s Details</h3></div>
	<div class="box-body">';

    $admin->message();

    $detail = $db->select("users", "*", array("id" => $id));
    $details = $detail->fetch_assoc();
    $country = ucwords(strtolower(country_convert($details["country"], "two", "full")));

    if ($details["status"] == 1) {
        $status = '<span class="label label-success">Active</span>';
    } elseif ($details["status"] == 2) {
        $status = '<span class="label label-warning">Unverified</span>';
    } else {
        $status = '<span class="label label-danger">Blocked</span>';
    }

    $click_stats = $db->select("clicks", "vclicks", array("date" => $date, "uid" => $id));
    $today_clicks = 0;

    if ($click_stats->num_rows != 0) {
        while ($r = $click_stats->fetch_assoc()) {
            $today_clicks = $today_clicks + $r["vclicks"];
        }
    }

    $click_statst = $db->select("clicks", "vclicks", array("uid" => $id));
    $total_clicks = 0;

    if ($click_statst->num_rows != 0) {
        while ($r = $click_statst->fetch_assoc()) {
            $total_clicks = $total_clicks + $r["vclicks"];
        }
    }

    $earn_stats = $db->select("earnings", "earnings", array("date" => $date, "uid" => $id));
    $today_earnings = "0.00";

    if ($earn_stats->num_rows != 0) {
        while ($r = $earn_stats->fetch_assoc()) {
            $today_earnings = $today_earnings + $r["earnings"];
        }
    }

    $earn_statst = $db->select("earnings", "earnings", array("uid" => $id));
    $total_earnings = "0.00";

    if ($earn_statst->num_rows != 0) {
        while ($r = $earn_statst->fetch_assoc()) {
            $total_earnings = $total_earnings + $r["earnings"];
        }
    }

    $sites = $db->select("sites", "id", array("userid" => $id));
    $ads = $db->select("ads", "id", array("userid" => $id));
    $invoices = $db->select("invoices", "id", array("userid" => $id));
    $referral = $db->select("refer", "id", array("userid" => $id));
    $pbal = round($details["pbal"], 2);
    $abal = round($details["abal"], 2);

    echo <<<HTML
<div class="row">
<div class="col-md-6">
<ul class="list-group">
<li class="list-group-item"><b>Name:</b> {$details["name"]}</li>
<li class="list-group-item"><b>E-Mail:</b> {$details["email"]}</li>
<li class="list-group-item"><b>Publisher Balance:</b> <span class="label label-success">\${$pbal}</span></li>
<li class="list-group-item"><b>Advertiser Balance:</b> <span class="label label-info">\${$abal}</span></li>
<li class="list-group-item"><b>Address:</b> {$details["address"]}</li>
<li class="list-group-item"><b>City:</b> {$details["city"]}</li>
<li class="list-group-item"><b>Country:</b> {$country}</li>
<li class="list-group-item"><b>PIN:</b> {$details["pin"]}</li>
<li class="list-group-item"><b>Status:</b> {$status}</li>
<li class="list-group-item"><b>Mobile Number:</b> {$details["mobile"]}</li>
<li class="list-group-item">
<a href="users.php?act=edit&id={$id}" class="btn btn-app"><i class="fa fa-edit"></i> Edit User</a>
<a href="#" class="btn btn-app" data-toggle="modal" data-target="#delUser"><i class="fa fa-trash-o"></i> Delete User</a>
<a href="mail.php?act=user&username={$id}" class="btn btn-app"><i class="fa fa-envelope-o"></i> Mail User</a>
<a href="notifications.php?act=user&username={$id}" class="btn btn-app"><i class="fa fa-bell-o"></i> Notify User</a>
HTML;

    if ($user->gdata("status", array("id" => $id)) != 1) {
        echo '<a href="users.php?act=activate&id=' . $id . '" class="btn btn-app"><i class="fa fa-check"></i> Activate User</a>';
    } else {
        echo '<a href="users.php?act=block&id=' . $id . '" class="btn btn-app"><i class="fa fa-ban"></i> Block User</a>';
    }

    echo <<<HTML
</li>
</ul>
<div class="modal fade" tabindex="-1" role="dialog" id="delUser" aria-labelledby="delUserLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete User?</h4>
      </div>
      <div class="modal-body">
        <p>Delete User {$details["name"]} ({$details["email"]})</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <a href="users.php?act=del&id={$id}&confirm=yes"><button type="button" class="btn btn-danger">Delete</button></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<div class="col-md-6">
<div class="row">
<a href="stats.php?type=user&user_id={$id}&date={$date}/{$date}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua text-center">
        <div class="inner">
            <h3>{$today_clicks}</h3>
            <p>Today Clicks</p>
        </div>
    </div>
</div>
</a>
<a href="stats.php?type=user&user_id={$id}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-red text-center">
        <div class="inner">
            <h3>{$total_clicks}</h3>
            <p>Total Clicks</p>
        </div>
    </div>
</div>
</a>
<a href="stats.php?type=user&user_id={$id}&date={$date}/{$date}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green text-center">
        <div class="inner">
            <h3>\${$today_earnings}</h3>
            <p>Today Earns</p>
        </div>
    </div>
</div>
</a>
<a href="stats.php?type=user&user_id={$id}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-yellow text-center">
        <div class="inner">
            <h3>\${$total_earnings}</h3>
            <p>Total Earn</p>
        </div>
    </div>
</div>
</a>
</div>
<div class="row">
<a href="sites.php?uid={$id}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green text-center">
        <div class="inner">
            <h3>{$sites->num_rows}</h3>
            <p>Sites</p>
        </div>
    </div>
</div>
</a>
<a href="invoices.php?uid={$id}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-yellow text-center">
        <div class="inner">
            <h3>{$invoices->num_rows}</h3>
            <p>Invoices</p>
        </div>
    </div>
</div>
</a>
<a href="ads.php?uid={$id}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua text-center">
        <div class="inner">
            <h3>{$ads->num_rows}</h3>
            <p>Advertises</p>
        </div>
    </div>
</div>
</a>
</div>
HTML;
} else {
    $type = get("type");
    $page = get("page");

    if (empty($page)) $page = 1;

    $start = ($page - 1) * 30;
    $end = 30;

    if ($type == "active") {
        $users = $db->select("users", "id,name,email,pbal,status", array("status" => 1), "", "$start,$end");
    } elseif ($type == "blocked") {
        $users = $db->select("users", "id,name,email,pbal,status", array("status" => 0), "", "$start,$end");
    } elseif ($type == "pending") {
        $users = $db->select("users", "id,name,email,pbal,status", array("status" => 2), "", "$start,$end");
    } elseif ($type == "top_balance") {
        $users = $db->select("users", "id,name,email,pbal,status", array(), "pbal DESC", "$start,$end");
    } else {
        $users = $db->select("users", "id,name,email,pbal,status", array(), "id DESC", "$start,$end");
    }

    $admin->head("Users");
    echo '<section class="content-header">';
    echo '<div class="row">
	<div class="col-lg-12">
	<div class="box box-primary">
	<div class="box-header with-border">
	<div class="row">
	<div class="col-md-4">
	<h2 class="panel-title">' . ucwords(str_replace("_", " ", $type)) . ' Users</h2>
	</div>
	<div class="col-md-4">
	<select class="form-control" onchange="changeCat(this.value);">
	<option selected="selected">Select Type</option>
	<option value="active">Active Users</option>
	<option value="pending">Pending Users</option>
	<option value="blocked">Blocked Users</option>
	<option value="top_balance">Top Balance Users</option>
	</select>
	</div>
	<div class="col-md-4">
	<form method="get">
	<input type="hidden" name="act" value="search"/>
	<div class="input-group">
	<input type="text" name="q" placeholder="Search Users" class="form-control"/>
	<div class="input-group-btn">
	<button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
	</div></div></form>
	</div></div></div>
	<div class="box-body">';

    if ($users->num_rows == 0) {
        echo 'No User Found in Database!';
    } else {
        echo '<table class="table table-striped table-hover table-bordered">';
        echo '<th class="text-center">ID #</th><th class="text-center">Name</th>
<th class="text-center">E-Mail</th>
<th class="text-center">Balance</th>
<th class="text-center">Status</th>
';

        while ($result = $users->fetch_assoc()) {
            // Status
            if ($result["status"] == 1) {
                $status = '<span class="label label-success">Active</span>';
            } elseif ($result["status"] == 2) {
                $status = '<span class="label label-warning">Unverified</span>';
            } else {
                $status = '<span class="label label-success">Active</span>';
            }

            echo '<tr class="text-center">
			<td>' . $result["id"] . '</td>
			<td><a href="users.php?act=details&id=' . $result["id"] . '">' . $result["name"] . '</a></td>
			<td>' . $result["email"] . '</td>
			<td>$' . round($result["pbal"], 2) . '</td>
			<td>' . $status . '</td>
			</tr>';
        }

        echo '</table></div></div>';
        $init->paging_admin($page, $users->num_rows, 30);
    }
}
$script = <<<HTML
<script>
function changeCat(cat)
{
	window.location.href = "?type=" + cat;
}
</script>
HTML;
$admin->foot($script);

