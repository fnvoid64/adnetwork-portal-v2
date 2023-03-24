<?php
include "init.php";

$site = new Pranto\Site();

if (!$admin->is_admin()) {
    redir("login.php");
    exit();
}

$act = get("act");
$con = $db->getConnection();

if ($act == "search" && isset($_GET["q"])) {
    $admin->head("Search Sites");
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
    $search = $con->query("SELECT id,name,url,userid,status FROM sites WHERE name LIKE '%$q%' OR url LIKE '%$q%' LIMIT $start,$end");

    if ($search->num_rows > 0) {
        echo '<div class="row"><div class="col-lg-12"><div class="box box-primary">
		<div class="box-header with-border">
		<h3 class="panel-title">Search Result For ' . $q . '</h3>
		</div><div class="box-body">';
        echo '<table class="table table-striped table-hover table-bordered">';
        echo '<th class="text-center">ID #</th><th class="text-center">Name</th><th class="text-center">URL</th>
<th class="text-center">Status</th>
<th class="text-center">Owner</th>';

        while ($result = $search->fetch_assoc()) {
            if ($result["status"] == 1) {
                $status = '<span class="label label-success">Active</span>';
            } elseif ($result["status"] == 2) {
                $status = '<span class="label label-warning">Unverified</span>';
            } else {
                $status = '<span class="label label-danger">Blocked</span>';
            }

            echo '<tr class="text-center">
			<td>' . $result["id"] . '</td>
			<td><a href="sites.php?act=details&id=' . $result["id"] . '">' . $result["name"] . '</a></td>
			<td><a href="' . $result["url"] . '">' . $result["url"] . '</a></td>
			<td>' . $status . '</td>
			<td><a href="users.php?act=details&id=' . $result["userid"] . '">' . $user->gdata("name", array("id" => $result["userid"])) . '</a></td>
			</tr>';
        }

        echo '</table></div></div>';
        $init->paging_admin($page, $search->num_rows, 30);
    } else {
        echo '<div class="row"><div class="col-lg-12"><div class="box box-primary">
			<div class="box-header with-border">
			<h3 class="panel-title">Search Result For ' . $q . '</h3>
			</div><div class="box-body">No Sites Found For "' . $q . '"</div></div>';
    }

    echo '</div></div>';
} elseif ($act == "edit" && isset($_GET["id"])) {
    $admin->head("Edit Site");
    echo '<section class="content-header">';

    $id = get("id");

    if (!$site->exists($id)) {
        echo "SiteNotFound!";
        exit();
    }

    echo '<div class="row">
	<div class="col-md-12">
	<div class="box box-primary">
	<div class="box-header with-border">
	<h3 class="panel-title">Edit Site</h3>
	</div>
	<div class="box-body">';

    if (isset($_POST["name"], $_POST["description"], $_POST["category"])) {
        $name = post("name");
        $description = post("description");
        $category = post("category");

        $errors = array();

        if (empty($name) OR strlen($name) < 1) $errors[] = "Sitename cannot be empty!";
        if (empty($description)) $description = "Free Mobile Downloads";
        if (empty($category)) $category = "Entertainment";

        if (empty($errors)) {
            if ($db->update("sites", array("name" => $name, "description" => $description, "category" => $category), array("id" => $id))) {
                $_SESSION["message"] = "Site successfully edited!";
                redir("sites.php?act=details&id=$id");
            } else {
                exit('Unknown Error!');
            }
        } else {
            $admin->error($errors);
        }
    }

    $form = '<form method="post">
	<div class="form-group">
	<label for="name">Site Name</label>
	<input type="text" name="name" class="form-control" value="' . $site->details($id, "name") . '"/>
	</div>
	<div class="form-group">
	<label for="name">Site URL</label>
	<input type="text" class="form-control" value="' . $site->details($id, "url") . '" readonly="readonly"/>
	</div>
	<div class="form-group">
	<label for="description">Site Description</label>
	<textarea name="description" class="form-control">' . $site->details($id, "description") . '</textarea>
	</div>
	<div class="form-group">
	<label for="name">Site Category</label>
	<select name="category" class="form-control">';

    $cats = explode("[%,%]", $init->settings("sitecats"));

    foreach ($cats as $myCat) {
        $form .= '<option value="' . $myCat . '">' . $myCat . '</option>';
    }

    $form .= '</select></div><div class="form-group"><button type="submit" class="btn btn-primary">Save</button></div></form></div></div></div>';
    echo $form;
} elseif ($act == "del" && isset($_GET["id"])) {
    $admin->head("Delete Site");
    $id = get("id");
    if (!$site->exists($id)) {
        echo "SiteNotFound!";
        exit;
    }
    if ($_GET["confirm"] == "yes") {

        $delete = $con->query("DELETE FROM sites WHERE id='$id'");
        if ($delete) {
            $_SESSION["message"] = "Site " . $site->details($id, "name") . " deleted successfully!";
            redir("sites.php");
        } else {
            echo "UnknownError~DbError";
        }
    } else {

        echo '<div class="title">Delete ' . $site->details($id, "name") . '?</div>';
        echo '<div class="menu" align="center">Are you Sure ??<br/><a href="sites.php?act=del&id=' . $id . '&confirm=yes">Yes, Delete</a> - <a href="sites.php?act=details&id=' . $id . '">No, Go Back</a></div>';

    }
} elseif ($act == "block" && isset($_GET["id"])) {

    $id = get("id");
    if (!$site->exists($id)) {
        echo "SiteNotFound!";
        exit;
    }

    $db->update("sites", array("status" => 0), array("id" => $id));
    $_SESSION["message"] = $site->details($id, "name") . " Blocked Successfully!";
    redir("sites.php?act=details&id=" . $id);
} elseif ($act == "reject" && isset($_GET["id"])) {

    $id = get("id");
    if (!$site->exists($id)) {
        echo "SiteNotFound!";
        exit;
    }

    $db->update("sites", array("status" => 3), array("id" => $id));
    $_SESSION["message"] = $site->details($id, "name") . " Rejected Successfully!";
    $mailBody = $init->mail("sitereject");
    $mailBody = str_replace("%date%", $date, $mailBody);
    $mailBody = str_replace("%username%", $user->gdata("name", array("id" => $site->details($id, "userid"))), $mailBody);
    $mailBody = str_replace("%sitename%", $site->details($id, "name"), $mailBody);
    $mailBody = str_replace("%siteurl%", $site->details($id, "url"), $mailBody);
    $subject = "Site Rejected";
    $user->mail($subject, $mailBody, $user->gdata("email", array("id" => $site->details($id, "userid"))));
    redir("sites.php?act=details&id=" . $id);
} elseif ($act == "activate" && isset($_GET["id"])) {

    $id = get("id");
    if (!$site->exists($id)) {
        echo "SiteNotFound!";
        exit;
    }

    $db->update("sites", array("status" => 1), array("id" => $id));
    $_SESSION["message"] = $site->details($id, "name") . " Activated Successfully!";
    $mailBody = $init->mail("siteactive");
    $mailBody = str_replace("%date%", $date, $mailBody);
    $mailBody = str_replace("%username%", $user->gdata("name", array("id" => $site->details($id, "userid"))), $mailBody);
    $mailBody = str_replace("%sitename%", $site->details($id, "name"), $mailBody);
    $mailBody = str_replace("%siteurl%", $site->details($id, "url"), $mailBody);
    $subject = "Site Active";
    $user->mail($subject, $mailBody, $user->gdata("email", array("id" => $site->details($id, "userid"))));
    redir("sites.php?act=details&id=" . $id);
} elseif ($act == "details" && isset($_GET["id"])) {
    $admin->head("Site Details");
    $id = get("id");

    if (!$site->exists($id)) {
        echo "Site Not Found!";
        exit();
    }

    $detail = $db->select("sites", "*", array("id" => $id));
    $details = $detail->fetch_assoc();

    echo '<section class="content-header">';
    echo '<div class="row">
	<div class="col-lg-12">
	<div class="box box-primary">
	<div class="box-header with-border"><h3 class="panel-title">' . $details["url"] . '\'s Details</h3></div>
	<div class="box-body">';

    $admin->message();

    if ($details["status"] == 1) {
        $status = '<span class="label label-success">Active</span>';
    } elseif ($details["status"] == 2) {
        $status = '<span class="label label-warning">Unverified</span>';
    } else {
        $status = '<span class="label label-danger">Blocked</span>';
    }

    $click_stats = $db->select("clicks", "vclicks", array("date" => $date, "sid" => $id));
    $today_clicks = 0;

    if ($click_stats->num_rows != 0) {
        while ($r = $click_stats->fetch_assoc()) {
            $today_clicks = $today_clicks + $r["vclicks"];
        }
    }

    $click_statst = $db->select("clicks", "vclicks", array("sid" => $id));
    $total_clicks = 0;

    if ($click_statst->num_rows != 0) {
        while ($r = $click_statst->fetch_assoc()) {
            $total_clicks = $total_clicks + $r["vclicks"];
        }
    }

    $earn_stats = $db->select("earnings", "earnings", array("date" => $date, "sid" => $id));
    $today_earnings = "0.00";

    if ($earn_stats->num_rows != 0) {
        while ($r = $earn_stats->fetch_assoc()) {
            $today_earnings = $today_earnings + $r["earnings"];
        }
    }

    $earn_statst = $db->select("earnings", "earnings", array("sid" => $id));
    $total_earnings = "0.00";

    if ($earn_statst->num_rows != 0) {
        while ($r = $earn_statst->fetch_assoc()) {
            $total_earnings = $total_earnings + $r["earnings"];
        }
    }

    $username = $user->gdata("name", array("id" => $details["userid"]));

    echo <<<HTML
<div class="row">
<div class="col-md-5">
<ul class="list-group">
<li class="list-group-item"><b>Name:</b> {$details["name"]}</li>
<li class="list-group-item"><b>URL:</b> {$details["url"]}</li>
<li class="list-group-item"><b>Category:</b> {$details["category"]}</li>
<li class="list-group-item"><b>Owner:</b> <a href="users.php?act=details&id={$details["userid"]}">{$username}</a></li>
<li class="list-group-item"><b>Status:</b> {$status}</li>
<li class="list-group-item">
<a href="sites.php?act=edit&id={$id}" class="btn btn-app"><i class="fa fa-edit"></i> Edit Site</a>
<a href="#" class="btn btn-app" data-toggle="modal" data-target="#delUser"><i class="fa fa-trash-o"></i> Delete Site</a>
HTML;

    if ($details["status"] != 1) {
        echo '<a href="sites.php?act=activate&id=' . $id . '" class="btn btn-app"><i class="fa fa-check"></i> Activate Site</a>';
    } else {
        echo '<a href="sites.php?act=reject&id=' . $id . '" class="btn btn-app"><i class="fa fa-ban"></i> Reject Site</a>';
    }

    echo <<<HTML
<a href="sites.php?act=block&id={$id}" class="btn btn-app"><i class="fa fa-ban"></i> Block Site</a>
</li>
</ul>
<div class="modal fade" tabindex="-1" role="dialog" id="delUser" aria-labelledby="delUserLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete Site?</h4>
      </div>
      <div class="modal-body">
        <p>Delete Site {$details["name"]} (http://{$details["url"]})</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <a href="sites.php?act=del&id={$id}&confirm=yes"><button type="button" class="btn btn-danger">Delete</button></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<div class="col-md-7">
<div class="row">
<a href="stats.php?type=site&site_id={$id}&date={$date}/{$date}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua text-center">
        <div class="inner">
            <h3>{$today_clicks}</h3>
            <p>Today Clicks</p>
        </div>
    </div>
</div>
</a>
<a href="stats.php?type=site&site_id={$id}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-red text-center">
        <div class="inner">
            <h3>{$total_clicks}</h3>
            <p>Total Clicks</p>
        </div>
    </div>
</div>
</a>
<a href="stats.php?type=site&site_id={$id}&date={$date}/{$date}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green text-center">
        <div class="inner">
            <h3>\${$today_earnings}</h3>
            <p>Today Earns</p>
        </div>
    </div>
</div>
</a>
<a href="stats.php?type=site&site_id={$id}">
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
HTML;
} else {
    $type = get("type");
    $page = get("page");

    if (empty($page)) $page = 1;

    $start = ($page - 1) * 30;
    $end = 30;

    if (isset($_GET["uid"])) {
        $uid = get("uid");

        if (!$user->exists($uid)) {
            echo "UserNotFound~!";
            exit();
        }

        if ($type == "active") {
            $sites = $db->select("sites", "id,name,url,userid,status", array("status" => 1, "userid" => $uid), "id DESC", "$start,$end");
        } elseif ($type == "blocked") {
            $sites = $db->select("sites", "id,name,url,userid,status", array("status" => 0, "userid" => $uid), "id DESC", "$start,$end");
        } elseif ($type == "pending") {
            $sites = $db->select("sites", "id,name,url,userid,status", array("status" => 2, "userid" => $uid), "id DESC", "$start,$end");
        } elseif ($type == "rejected") {
            $sites = $db->select("sites", "id,name,url,userid,status", array("status" => 3, "userid" => $uid), "id DESC", "$start,$end");
        } else {
            $sites = $db->select("sites", "id,name,url,userid,status", array("userid" => $uid), "id DESC", "$start,$end");
        }
    } else {
        if ($type == "active") {
            $sites = $db->select("sites", "id,name,url,userid,status", array("status" => 1), "id DESC", "$start,$end");
        } elseif ($type == "blocked") {
            $sites = $db->select("sites", "id,name,url,userid,status", array("status" => 0), "id DESC", "$start,$end");
        } elseif ($type == "pending") {
            $sites = $db->select("sites", "id,name,url,userid,status", array("status" => 2), "id DESC", "$start,$end");
        } elseif ($type == "rejected") {
            $sites = $db->select("sites", "id,name,url,userid,status", array("status" => 3), "id DESC", "$start,$end");
        } else {
            $sites = $db->select("sites", "id,name,url,userid,status", array(), "id DESC", "$start,$end");
        }
    }

    $admin->head("Sites");
    echo '<section class="content-header">';
    echo '<div class="row">
	<div class="col-lg-12">
	<div class="box box-primary">
	<div class="box-header with-border">
	<div class="row">
	<div class="col-md-4">
	<h2 class="panel-title">' . ucwords(str_replace("_", " ", $type)) . ' Sites</h2>
	</div>
	<div class="col-md-4">
	<select class="form-control" onchange="changeCat(this.value);">
	<option selected="selected">Select Type</option>
	<option value="active">Active Sites</option>
	<option value="pending">Unverified Sites</option>
	<option value="blocked">Blocked Sites</option>
	</select>
	</div>
	<div class="col-md-4">
	<form method="get">
	<input type="hidden" name="act" value="search"/>
	<div class="input-group">
	<input type="text" name="q" placeholder="Search Sites" class="form-control"/>
	<div class="input-group-btn">
	<button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
	</div></div></form>
	</div></div></div>
	<div class="box-body">';

    if ($sites->num_rows == 0) {
        echo 'No Site Found in Database!';
    } else {
        echo '<table class="table table-striped table-hover table-bordered">';
        echo '<th class="text-center">ID #</th><th class="text-center">Name</th><th class="text-center">URL</th>
<th class="text-center">Status</th>
<th class="text-center">Owner</th>';

        while ($result = $sites->fetch_assoc()) {
            if ($result["status"] == 1) {
                $status = '<span class="label label-success">Active</span>';
            } elseif ($result["status"] == 2) {
                $status = '<span class="label label-warning">Unverified</span>';
            } else {
                $status = '<span class="label label-danger">Blocked</span>';
            }

            echo '<tr class="text-center">
			<td>' . $result["id"] . '</td>
			<td><a href="sites.php?act=details&id=' . $result["id"] . '">' . $result["name"] . '</a></td>
			<td><a href="' . $result["url"] . '">' . $result["url"] . '</a></td>
			<td>' . $status . '</td>
			<td><a href="users.php?act=details&id=' . $result["userid"] . '">' . $user->gdata("name", array("id" => $result["userid"])) . '</a></td>
			</tr>';
        }

        echo '</table></div></div>';
        $init->paging_admin($page, $sites->num_rows, 30);
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

