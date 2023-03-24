<?php
include "init.php";

$ads = new Pranto\Ads();

$act = get("act");
$con = $db->getConnection();

if (!$admin->is_admin()) {
    redir("login.php");
    exit();
}

if ($act == "search" && isset($_GET["q"])) {
    $admin->head("Search Ads");
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
    $search = $con->query("SELECT id,name,url,userid,status FROM ads WHERE name LIKE '%$q%' OR url LIKE '%$q%' LIMIT $start,$end");

    if ($search->num_rows > 0) {
        echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">
		<div class="panel-heading">
		<h3 class="panel-title">Search Result For ' . $q . '</h3>
		</div><div class="panel-body">';
        echo '<table class="table table-striped table-hover table-bordered">';
        echo '<th class="text-center">ID #</th><th class="text-center">Name</th><th class="text-center">URL</th>
<th class="text-center">Status</th>
<th class="text-center">Owner</th>';

        while ($result = $search->fetch_assoc()) {
            if ($result["status"] == 1) {
                $status = '<span class="label label-success">Running</span>';
            } elseif ($result["status"] == 2) {
                $status = '<span class="label label-warning">Pending</span>';
            } elseif ($result["status"] == 3) {
                $status = '<span class="label label-danger">Paused</span>';
            } elseif ($result["status"] == 4) {
                $status = '<span class="label label-danger">Rejected</span>';
            } else {
                $status = '<span class="label label-danger">Blocked</span>';
            }

            $username = ($result["userid"] == 0) ? "<i>ADMIN</i>" : $user->gdata("name", array("id" => $result["userid"]));
            echo '<tr class="text-center">
			<td>' . $result["id"] . '</td>
			<td><a href="ads.php?act=details&id=' . $result["id"] . '">' . $result["name"] . '</a></td>
			<td><a href="http://' . $result["url"] . '">http://' . $result["url"] . '</a></td>
			<td>' . $status . '</td>
			<td><a href="users.php?act=details&id=' . $result["userid"] . '">' . $username . '</a></td>
			</tr>';
        }

        echo '</table></div></div></div>';
        $init->paging_admin($page, $search->num_rows, 30);
    } else {
        echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">
			<div class="panel-heading">
			<h3 class="panel-title">Search Result For ' . $q . '</h3>
			</div><div class="panel-body">No Ads Found For "' . $q . '"</div></div>';
    }

    echo '</div></div>';
} elseif ($act == "add") {
    $type = get("type");

    if ($type == "banner") {
        $admin->head("Create Banner Advertisement", true);
        echo '<section class="content-header">';
        echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">
		<div class="panel-heading">
		<h3 class="panel-title">Create Banner Advertisement</h3>
		</div><div class="panel-body">';

        if (isset($_POST["name"], $_POST["adult"], $_FILES["banners"], $_POST["url"], $_POST["amount"])) {
            $name = post("name");
            $adult = post("adult");
            $url = post("url");
            $countrys = post("countrys");
            $devices = post("devices");
            $amount = post("amount");

            $errors = array();

            if (empty($name) OR strlen($name) < 1) $errors[] = $_LANG["ads_name_empty"];
            if (empty($adult)) $adult = 0;

            $url = str_replace("http://", null, $url);
            $cset = $dset = 0;
            if (isset($_POST["countrys"])) $cset = 1;
            if (isset($_POST["devices"])) $dset = 1;

            if (!preg_match('/([a-zA-Z0-9\-\.]+)\.([a-zA-Z0-9\-\.]+)/', $url)) $errors[] = $_LANG["url_empty"];
            if (empty($amount) OR strlen($amount) < 1 OR !is_numeric($amount)) $errors[] = $_LANG["invalid_amount"];
            if ($amount < $init->settings("minimumcpc")) $errors[] = $_LANG["minimumcpc_low"];
            if (count($_FILES["banners"]["tmp_name"]) < 1) $errors[] = $_LANG["banner_empty"];

            $banner = array();

            foreach ($_FILES["banners"]["error"] as $i => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $maxSize = 6 * 1024 * 1024;

                    if ($_FILES["banners"]["size"][$i] > $maxSize) {
                        $errors[] = "Max Upload Limit (6MB) for single file has reached!";
                    }

                    $fileTypes = array("image/gif", "image/jpeg", "image/png", "image/bmp", "image/jpg");

                    foreach ($fileTypes as $fileType) {
                        if ($_FILES["banners"]["type"][$i] == $fileType) {
                            $found = true;
                        }
                    }

                    if (!$found) $errors[] = "Invalid File Type! (Allowed Only .gif,.jpg,.png,.bmp)";

                    $fileName = md5(microtime());

                    if (move_uploaded_file($_FILES["banners"]["tmp_name"][$i], ROOT . "/uploads/" . $fileName . ".gif")) {
                        $banner[] = $fileName;
                    } else {
                        echo "FILE_IS_INVALID($i)!";
                        exit();
                    }
                } else {
                    if ($i == 0) {
                        $errors[] = "At least 1 banner should be uploaded!";
                    }
                }
            }

            if (empty($errors)) {
                if (count($banner) > 1) {
                    $Banner = implode(",", $banner);
                } else {
                    $Banner = $banner[0];
                }

                if ($cset == 1) {
                    $countrylist = implode(",", $_POST["countrys"]);
                    $countrylist = sanitize($countrylist);
                } else {
                    $countrylist = "Worldwide";
                }

                if ($dset == 1) {
                    $devicelist = implode(",", $_POST["devices"]);
                    $devicelist = sanitize($devicelist);
                } else {
                    $devicelist = "All";
                }

                $code = $db->insert("ads", array("name" => $name, "url" => $url, "type" => 1, "adult" => $adult, "banners" => $Banner, "cset" => $cset, "dset" => $dset, "countrys" => $countrylist, "devices" => $devicelist, "status" => 2, "date" => $date, "amount" => $amount, "userid" => 0), "");

                if ($code) {
                    $_SESSION["message"] = $_LANG["ads_created"];
                    redir("ads.php");
                } else {
                    echo "CRITICAL_DB_ERROR!";
                    exit();
                }
            } else {
                $admin->error($errors);
            }
        }

        $form = '<form method="post" enctype="multipart/form-data">
		<div class="form-group">
			<lable for="name">Advertisement Name</label>
			<input type="text" name="name" class="form-control">
		</div>
		<div class="form-group">
			<lable for="url">Advertisement URL</label>
			<input type="text" name="url" value="http://" class="form-control">
		</div>
		<div class="form-group">
			<lable for="banners">Advertisement Banners</label>
			<input type="file" name="banners[]" class="form-control">
		</div>
		<div id="addBanners"></div>
		<a href="#" onclick="addBanners();"><i class="fa fa-plus"></i> Add More Banners</a>
		<div class="form-group">
			<lable for="name">Advertisement Type</label>
			<select name="adult" class="form-control"><option value="0">Non Adult</option><option value="1">Adult</option></select>
		</div>
		<div class="form-group">
			<lable for="countrys">Advertisement Target Countrys</label>
			<select name="countrys[]" class="form-control select2" multiple="multiple" data-placeholder="Select Country" style="width: 100%;">';
        $countri = get_full_country();
        foreach (get_two_country() as $i => $ct) {
            $form .= '<option value="' . trim($ct) . '">' . ucwords(strtolower(trim($countri[$i]))) . '</option>';
        }
        $form .= '
		</select>
		</div>
		<div class="form-group">
			<lable for="devices">Advertisement Target Devices</label>
			<select name="devices[]" class="form-control select2" multiple="multiple" data-placeholder="Select Device" style="width: 100%;">';
        $countr = device_full();
        foreach (device_code() as $i => $ccode) {
            $form .= '<option value="' . trim($ccode) . '">' . ucwords(strtolower(trim($countr[$i]))) . '</option>';
        }
        $form .= '
		</select>
		</div>
		<div class="form-group">
			<lable for="amount">Advertisement CPC (' . $_LANG["minimumcpc_low"] . ' <a href="settings.php?module=ads">Change</a>)</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" name="amount" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success">Create Advertisement</button>
		</div>
		</form>
		</div></div></div>
		<script>
		function addBanners()
		{
			var div = document.createElement("DIV");
			div.className = "form-group";
			div.innerHTML = \'<input type="file" name="banners[]" class="form-control">\';
			document.getElementById("addBanners").appendChild(div);
		}
		</script>';

        echo $form;
    } elseif ($type == "text") {
        $admin->head("Create Text Advertisement", true);
        echo '<section class="content-header">';
        echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">
		<div class="panel-heading">
		<h3 class="panel-title">Create Text Advertisement</h3>
		</div><div class="panel-body">';

        if (isset($_POST["name"], $_POST["adult"], $_POST["titles"], $_POST["url"], $_POST["amount"])) {
            $name = post("name");
            $adult = post("adult");
            $url = post("url");
            $amount = post("amount");
            $titles = post("titles");

            $errors = array();

            if (empty($name) OR strlen($name) < 1) $errors[] = $_LANG["ads_name_empty"];
            if (empty($adult)) $adult = 0;

            $url = str_replace("http://", null, $url);
            $cset = $dset = 0;
            if (isset($_POST["countrys"])) $cset = 1;
            if (isset($_POST["devices"])) $dset = 1;

            if (!preg_match('/([a-zA-Z0-9\-\.]+)\.([a-zA-Z0-9\-\.]+)/', $url)) $errors[] = $_LANG["url_empty"];
            if (empty($amount) OR strlen($amount) < 1 OR !is_numeric($amount)) $errors[] = $_LANG["invalid_amount"];
            if ($amount < $init->settings("minimumcpc")) $errors[] = $_LANG["minimumcpc_low"];
            if (empty($titles) OR strlen($titles) < 1) $errors[] = $_LANG["ads_title_empty"];

            if (empty($errors)) {
                if ($cset == 1) {
                    $countrylist = implode(",", $_POST["countrys"]);
                    $countrylist = sanitize($countrylist);
                } else {
                    $countrylist = "Worldwide";
                }

                if ($dset == 1) {
                    $devicelist = implode(",", $_POST["devices"]);
                    $devicelist = sanitize($devicelist);
                } else {
                    $devicelist = "All";
                }

                $code = $db->insert("ads", array("name" => $name, "url" => $url, "type" => 2, "adult" => $adult, "titles" => $titles, "cset" => $cset, "dset" => $dset, "countrys" => $countrylist, "devices" => $devicelist, "status" => 2, "date" => $date, "amount" => $amount, "userid" => 0), "");

                if ($code) {
                    $_SESSION["message"] = $_LANG["ads_created"];
                    redir("ads.php");
                } else {
                    echo "CRITICAL_DB_ERROR!";
                    exit();
                }
            } else {
                $admin->error($errors);
            }
        }

        $form = '<form method="post">
		<div class="form-group">
			<lable for="name">Advertisement Name</label>
			<input type="text" name="name" class="form-control">
		</div>
		<div class="form-group">
			<lable for="url">Advertisement URL</label>
			<input type="text" name="url" value="http://" class="form-control">
		</div>
		<div class="form-group">
			<lable for="banners">Advertisement Titles (Seperated By Comma)</label>
			<input type="text" name="titles" class="form-control">
		</div>
		<div class="form-group">
			<lable for="name">Advertisement Type</label>
			<select name="adult" class="form-control"><option value="0">Non Adult</option><option value="1">Adult</option></select>
		</div>
		<div class="form-group">
			<lable for="countrys">Advertisement Target Countrys</label>
			<select name="countrys[]" class="form-control select2" multiple="multiple" data-placeholder="Select Country" style="width: 100%;">';
        $countri = get_full_country();
        foreach (get_two_country() as $i => $ct) {
            $form .= '<option value="' . trim($ct) . '">' . ucwords(strtolower(trim($countri[$i]))) . '</option>';
        }
        $form .= '
		</select>
		</div>
		<div class="form-group">
			<lable for="devices">Advertisement Target Devices</label>
			<select name="devices[]" class="form-control select2" multiple="multiple" data-placeholder="Select Device" style="width: 100%;">';
        $countr = device_full();
        foreach (device_code() as $i => $ccode) {
            $form .= '<option value="' . trim($ccode) . '">' . ucwords(strtolower(trim($countr[$i]))) . '</option>';
        }
        $form .= '
		</select>
		</div>
		<div class="form-group">
			<lable for="amount">Advertisement CPC (' . $_LANG["minimumcpc_low"] . ' <a href="settings.php?module=ads">Change</a>)</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" name="amount" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success">Create Advertisement</button>
		</div>
		</form>
		</div></div></div>';

        echo $form;
    } else {
        $admin->head("Create Advertisement");
        echo '<section class="content-header">';
        echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">
		<div class="panel-heading">
		<h3 class="panel-title">Create Advertisement</h3>
		</div><div class="panel-body text-center">
		<a href="ads.php?act=add&type=text" class="btn btn-success btn-lg"><i class="fa fa-pencil fa-3x"></i><br/>Create Text Advertisement</a>
		<a href="ads.php?act=add&type=banner" class="btn btn-info btn-lg"><i class="fa fa-photo fa-3x"></i><br/>Create Banner Advertisement</a>
		</div></div></div>';
    }
} elseif ($act == "edit" && isset($_GET["id"])) {
    $admin->head("Edit Advertisement", true);
    echo '<section class="content-header">';
    echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">
	<div class="panel-heading">
	<h3 class="panel-title">Edit Advertisement</h3>
	</div><div class="panel-body">';

    $id = get("id");

    if (!$ads->ad_exists($id)) {
        echo "AdNotFound!";
        exit();
    }

    $type = $ads->details($id, "type");

    if ($type == 1) {
        if (isset($_POST["name"], $_POST["url"], $_POST["adult"], $_FILES["banners"], $_POST["amount"])) {
            $name = post("name");
            $url = post("url");
            $adult = post("adult");
            $countrys = post("countrys");
            $devices = post("devices");
            $amount = post("amount");

            $errors = array();

            if (empty($name) OR strlen($name) < 1) $errors[] = $_LANG["ads_name_empty"];

            $url = str_replace("http://", null, $url);
            $cset = $dset = 0;
            if (isset($_POST["countrys"])) $cset = 1;
            if (isset($_POST["devices"])) $dset = 1;

            if (!preg_match('/([a-zA-Z0-9\-\.]+)\.([a-zA-Z0-9\-\.]+)/', $url)) $errors[] = $_LANG["url_empty"];
            if (empty($adult)) $adult = 0;
            if (empty($amount) OR strlen($amount) < 1 OR !is_numeric($amount)) $errors[] = $_LANG["invalid_amount"];
            if ($amount < $init->settings("minimumcpc")) $errors[] = $_LANG["minimumcpc_low"];
            if (count($_FILES["banners"]["tmp_name"]) < 1) $errors[] = $_LANG["banner_empty"];

            $banner = array();

            foreach ($_FILES["banners"]["error"] as $i => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $maxSize = 6 * 1024 * 1024;

                    if ($_FILES["banners"]["size"][$i] > $maxSize) {
                        $errors[] = "Max Upload Limit (6MB) for single file has reached!";
                    }

                    $fileTypes = array("image/gif", "image/jpeg", "image/png", "image/bmp", "image/jpg");

                    foreach ($fileTypes as $fileType) {
                        if ($_FILES["banners"]["type"][$i] == $fileType) {
                            $found = true;
                        }
                    }

                    if (!$found) $errors[] = "Invalid File Type! (Allowed Only .gif,.jpg,.png,.bmp)";

                    $fileName = md5(microtime());

                    if (move_uploaded_file($_FILES["banners"]["tmp_name"][$i], ROOT . "/uploads/" . $fileName . ".gif")) {
                        $banner[] = $fileName;
                    } else {
                        echo "FILE_IS_INVALID($i)!";
                        exit();
                    }
                } else {
                    if ($i == 0) {
                        $errors[] = "At least 1 banner should be uploaded!";
                    }
                }
            }

            if (empty($errors)) {
                if (count($banner) > 1) {
                    $Banner = implode(",", $banner);
                } else {
                    $Banner = $banner[0];
                }

                if ($cset == 1) {
                    $countrylist = implode(",", $_POST["countrys"]);
                    $countrylist = sanitize($countrylist);
                } else {
                    $countrylist = "Worldwide";
                }

                if ($dset == 1) {
                    $devicelist = implode(",", $_POST["devices"]);
                    $devicelist = sanitize($devicelist);
                } else {
                    $devicelist = "All";
                }

                $code = $db->update("ads", array("name" => $name, "url" => $url, "banners" => $Banner, "adult" => $adult, "cset" => $cset, "dset" => $dset, "countrys" => $countrylist, "devices" => $devicelist, "amount" => $amount), array("id" => $id));

                if ($code) {
                    $_SESSION["message"] = $_LANG["ads_edited"];
                    redir("ads.php?act=details&id=$id");
                } else {
                    echo "CRITICAL_DB_ERROR!";
                    exit;
                }
            } else {
                $admin->error($errors);
            }
        }

        $form = '<form method="post" enctype="multipart/form-data">
		<div class="form-group">
			<lable for="name">Advertisement Name</label>
			<input type="text" name="name" class="form-control" value="' . $ads->details($id, "name") . '">
		</div>
		<div class="form-group">
			<lable for="url">Advertisement URL</label>
			<input type="text" name="url" class="form-control" value="http://' . $ads->details($id, "url") . '">
		</div>
		<div class="form-group">
			<lable for="banners">Advertisement Banners</label>
			<input type="file" name="banners[]" class="form-control">
		</div>
		<div id="addBanners"></div>
		<a href="#" onclick="addBanners();"><i class="fa fa-plus"></i> Add More Banners</a>
		<div class="form-group">
			<lable for="name">Advertisement Type</label>
			<select name="adult" class="form-control"><option value="0">Non Adult</option><option value="1">Adult</option></select>
		</div>
		<div class="form-group">
			<lable for="countrys">Advertisement Target Countrys</label>
			<select name="countrys[]" class="form-control select2" multiple="multiple" data-placeholder="Select Country" style="width: 100%;">';
        $countri = get_full_country();
        foreach (get_two_country() as $i => $ct) {
            $form .= '<option value="' . trim($ct) . '">' . ucwords(strtolower(trim($countri[$i]))) . '</option>';
        }
        $form .= '
		</select>
		</div>
		<div class="form-group">
			<lable for="devices">Advertisement Target Devices</label>
			<select name="devices[]" class="form-control select2" multiple="multiple" data-placeholder="Select Device" style="width: 100%;">';
        $countr = device_full();
        foreach (device_code() as $i => $ccode) {
            $form .= '<option value="' . trim($ccode) . '">' . ucwords(strtolower(trim($countr[$i]))) . '</option>';
        }
        $form .= '
		</select>
		</div>
		<div class="form-group">
			<lable for="amount">Advertisement CPC (' . $_LANG["minimumcpc_low"] . ' <a href="settings.php?module=ads">Change</a>)</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" name="amount" class="form-control" value="' . $ads->details($id, "amount") . '">
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success">Edit Advertisement</button>
		</div>
		</form>
		</div></div></div>
		<script>
		function addBanners()
		{
			var div = document.createElement("DIV");
			div.className = "form-group";
			div.innerHTML = \'<input type="file" name="banners[]" class="form-control">\';
			document.getElementById("addBanners").appendChild(div);
		}
		</script>';

        echo $form;
    } elseif ($type == 2) {


        if (isset($_POST["name"], $_POST["url"], $_POST["adult"], $_POST["titles"], $_POST["amount"])) {

            $name = post("name");
            $url = post("url");
            $adult = post("adult");
            $countrys = post("countrys");
            $devices = post("devices");
            $amount = post("amount");
            $titles = post("titles");

            $errors = array();
            $cset = $dset = 0;
            if (isset($_POST["countrys"])) $cset = 1;
            if (isset($_POST["devices"])) $dset = 1;

            if (empty($name) OR strlen($name) < 1) $errors[] = $_LANG["ads_name_empty"];
            $url = str_replace("http://", null, $url);
            if (!preg_match('/([a-zA-Z0-9\-\.]+)\.([a-zA-Z0-9\-\.]+)/', $url)) $errors[] = $_LANG["url_empty"];
            if (empty($adult)) $adult = 0;
            if (empty($amount) OR strlen($amount) < 1 OR !is_numeric($amount)) $errors[] = $_LANG["invalid_amount"];
            if ($amount < $init->settings("minimumcpc")) $errors[] = $_LANG["minimumcpc_low"];
            if (empty($titles) OR strlen($titles) < 1) $errors[] = $_LANG["ads_title_empty"];

            if (empty($errors)) {

                if ($cset == 1) {
                    $countrylist = implode(",", $_POST["countrys"]);
                    $countrylist = sanitize($countrylist);
                } else {
                    $countrylist = "Worldwide";
                }

                if ($dset == 1) {
                    $devicelist = implode(",", $_POST["devices"]);
                    $devicelist = sanitize($devicelist);
                } else {
                    $devicelist = "All";
                }

                $code = $db->update("ads", array("name" => $name, "url" => $url, "titles" => $titles, "adult" => $adult, "cset" => $cset, "dset" => $dset, "countrys" => $countrylist, "devices" => $devicelist, "amount" => $amount), array("id" => $id));

                if ($code) {
                    $_SESSION["message"] = $_LANG["ads_edited"];
                    redir("ads.php");
                } else {
                    echo "CRITICAL_DB_ERROR!";
                    exit;
                }
            } else {
                $admin->error($errors);
            }
        }

        $form = '<form method="post">
		<div class="form-group">
			<lable for="name">Advertisement Name</label>
			<input type="text" name="name" class="form-control" value="' . $ads->details($id, "name") . '">
		</div>
		<div class="form-group">
			<lable for="url">Advertisement URL</label>
			<input type="text" name="url" class="form-control" value="http://' . $ads->details($id, "url") . '">
		</div>
		<div class="form-group">
			<lable for="banners">Advertisement Titles (Seperated By Comma)</label>
			<input type="text" name="titles" class="form-control" value="' . $ads->details($id, "titles") . '">
		</div>
		<div class="form-group">
			<lable for="name">Advertisement Type</label>
			<select name="adult" class="form-control"><option value="0">Non Adult</option><option value="1">Adult</option></select>
		</div>
		<div class="form-group">
			<lable for="countrys">Advertisement Target Countrys</label>
			<select name="countrys[]" class="form-control select2" multiple="multiple" data-placeholder="Select Country" style="width: 100%;">';
        $countri = get_full_country();
        foreach (get_two_country() as $i => $ct) {
            $form .= '<option value="' . trim($ct) . '">' . ucwords(strtolower(trim($countri[$i]))) . '</option>';
        }
        $form .= '
		</select>
		</div>
		<div class="form-group">
			<lable for="devices">Advertisement Target Devices</label>
			<select name="devices[]" class="form-control select2" multiple="multiple" data-placeholder="Select Device" style="width: 100%;">';
        $countr = device_full();
        foreach (device_code() as $i => $ccode) {
            $form .= '<option value="' . trim($ccode) . '">' . ucwords(strtolower(trim($countr[$i]))) . '</option>';
        }
        $form .= '
		</select>
		</div>
		<div class="form-group">
			<lable for="amount">Advertisement CPC (' . $_LANG["minimumcpc_low"] . ' <a href="settings.php?module=ads">Change</a>)</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input type="text" name="amount" class="form-control" value="' . $ads->details($id, "amount") . '">
			</div>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-success">Edit Advertisement</button>
		</div>
		</form>
		</div></div></div>';

        echo $form;
    }
} elseif ($act == "del" && isset($_GET["id"])) {
    $admin->head("Delete Ads");
    $id = get("id");
    if (!$ads->ad_exists($id)) {
        echo "AdsNotFound!";
        exit;
    }
    if ($_GET["confirm"] == "yes") {

        $delete = $con->query("DELETE FROM ads WHERE id='$id'");
        if ($delete) {
            $_SESSION["message"] = "Ad " . $ads->details($id, "name") . " deleted successfully!";
            redir("ads.php");
        } else {
            echo "UnknownError~DbError";
        }
    } else {

        echo '<div class="title">Delete ' . $ads->details($id, "name") . '?</div>';
        echo '<div class="menu" align="center">Are you Sure ??<br/><a href="ads.php?act=del&id=' . $id . '&confirm=yes">Yes, Delete</a> - <a href="ads.php?act=details&id=' . $id . '">No, Go Back</a></div>';

    }

} elseif ($act == "block" && isset($_GET["id"])) {

    $id = get("id");
    if (!$ads->ad_exists($id)) {
        echo "AdsNotFound!";
        exit;
    }

    $db->update("ads", array("status" => 0), array("id" => $id));
    $_SESSION["message"] = $ads->details($id, "name") . " Blocked Successfully!";
    redir("ads.php?act=details&id=" . $id);
} elseif ($act == "reject" && isset($_GET["id"])) {

    $id = get("id");
    if (!$ads->ad_exists($id)) {
        echo "AdsNotFound!";
        exit;
    }

    $db->update("ads", array("status" => 4), array("id" => $id));
    $_SESSION["message"] = $ads->details($id, "name") . " Rejected Successfully!";
    $mailBody = $init->mail("adreject");
    $mailBody = str_replace("%date%", $date, $mailBody);
    $mailBody = str_replace("%username%", $user->gdata("name", array("id" => $ads->details($id, "userid"))), $mailBody);
    $mailBody = str_replace("%adname%", $ads->details($id, "name"), $mailBody);
    $mailBody = str_replace("%adurl%", $ads->details($id, "url"), $mailBody);

    $subject = "Advertise Rejected";
    if ($ads->details($id, "userid") != 0) {
        $user->mail($subject, $mailBody, $user->gdata("email", array("id" => $ads->details($id, "userid"))));
    }
    redir("ads.php?act=details&id=" . $id);
} elseif ($act == "run" && isset($_GET["id"])) {

    $id = get("id");
    if (!$ads->ad_exists($id)) {
        echo "AdsNotFound!";
        exit;
    }

    $admin->head("Run Advertisement");
    echo '<section class="content-header">';
    echo '<div class="row"><div class="col-lg-12"><div class="panel panel-default">
		<div class="panel-heading">
		<h3 class="panel-title">Run Advertisement</h3>
		</div><div class="panel-body">';

    if (isset($_POST["camount"])) {
        $camount = post("camount");
        if (empty($camount) OR strlen($camount) < 1 OR !is_numeric($camount)) {
            echo "Invalid Amount!";
            exit;
        }


        $db->update("ads", array("status" => 1, "camount" => $camount), array("id" => $id));
        $_SESSION["message"] = $ads->details($id, "name") . " Running Successfully!";
        $mailBody = $init->mail("adsactive");
        $mailBody = str_replace("%date%", $date, $mailBody);
        $mailBody = str_replace("%username%", $user->gdata("name", array("id" => $ads->details($id, "userid"))), $mailBody);
        $mailBody = str_replace("%adname%", $ads->details($id, "name"), $mailBody);
        $mailBody = str_replace("%adurl%", $ads->details($id, "url"), $mailBody);
        $subject = "Advertise Running";
        if ($ads->details($id, "userid") != 0) {
            $user->mail($subject, $mailBody, $user->gdata("email", array("id" => $ads->details($id, "userid"))));
        }
        redir("ads.php?act=details&id=" . $id);
    } else {
        echo '<form method="post">
			<div class="form-group">
				<label for="camount">Cut From Advertiser (Per Click)</label>
				<div class="input-group">
					<span class="input-group-addon">$</span>
					<input type="text" name="camount" class="form-control"/>
				</div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-success">Run Advertisement</button>
			</div>
			</form></div></div></div>';
    }
} elseif ($act == "pause" && isset($_GET["id"])) {

    $id = get("id");
    if (!$ads->ad_exists($id)) {
        echo "AdsNotFound!";
        exit;
    }

    $db->update("ads", array("status" => 3), array("id" => $id));
    $_SESSION["message"] = $ads->details($id, "name") . " Paused Successfully!";
    redir("ads.php?act=details&id=" . $id);
} elseif ($act == "details" && isset($_GET["id"])) {
    $admin->head("Advertisement Details");
    $id = get("id");

    if (!$ads->ad_exists($id)) {
        echo "Advertisement Not Found!";
        exit();
    }

    $detail = $db->select("ads", "*", array("id" => $id));
    $details = $detail->fetch_assoc();

    echo '<section class="content-header">';
    echo '<div class="row">
	<div class="col-lg-12">
	<div class="panel panel-default">
	<div class="panel-heading"><h3 class="panel-title">' . $details["name"] . '\'s Details</h3></div>
	<div class="panel-body">';

    $admin->message();

    if ($details["status"] == 1) {
        $status = '<span class="label label-success">Running</span>';
    } elseif ($details["status"] == 2) {
        $status = '<span class="label label-warning">Pending</span>';
    } elseif ($details["status"] == 3) {
        $status = '<span class="label label-danger">Paused</span>';
    } elseif ($details["status"] == 4) {
        $status = '<span class="label label-danger">Rejected</span>';
    } else {
        $status = '<span class="label label-danger">Blocked</span>';
    }

    $click_stats = $db->select("clicks", "vclicks", array("date" => $date, "aid" => $id));
    $today_clicks = 0;

    if ($click_stats->num_rows != 0) {
        while ($r = $click_stats->fetch_assoc()) {
            $today_clicks = $today_clicks + $r["vclicks"];
        }
    }

    $click_statst = $db->select("clicks", "vclicks", array("aid" => $id));
    $total_clicks = 0;

    if ($click_statst->num_rows != 0) {
        while ($r = $click_statst->fetch_assoc()) {
            $total_clicks = $total_clicks + $r["vclicks"];
        }
    }

    $today_earnings = round($today_clicks * $details["camount"], 2);
    $total_earnings = round($total_clicks * $details["camount"], 2);

    if ($details["userid"] == 0) {
        $username = '<span class="label label-success">Admin</span>';
    } else {
        $username = $user->gdata("name", array("id" => $details["userid"]));
    }

    $type = ($details["type"] == 1) ? "Banner" : "Text";
    $adult = ($details["adult"] == 1) ? "Adult" : "Non-Adult";

    echo <<<HTML
<div class="row">
<div class="col-md-5">
<ul class="list-group">
<li class="list-group-item"><b>Name:</b> {$details["name"]}</li>
<li class="list-group-item"><b>URL:</b> http://{$details["url"]}</li>
<li class="list-group-item"><b>Type:</b> {$type}</li>
<li class="list-group-item"><b>Adult:</b> {$adult}</li>
<li class="list-group-item"><b>Owner:</b> <a href="users.php?act=details&id={$details["userid"]}">{$username}</a></li>
<li class="list-group-item"><b>Status:</b> {$status}</li>
HTML;

    if ($details["type"] == 1) {
        if (strstr($details["banners"], ",") !== FALSE) {
            echo '<li class="list-group-item"><b>Banners</b>';
            foreach (explode(",", $details["banners"]) as $banner) {
                echo '<img src="' . $site_url . '/uploads/' . $banner . '.gif" class="thumbnail" alt="Banner"/>';
            }
        } else {
            echo '<li class="list-group-item"><b>Banner</b>
		<br/><img src="' . $site_url . '/uploads/' . $details["banners"] . '.gif" class="thumbnail" alt="Banner"/>';
        }
    } else {
        echo '<li class="list-group-item"><b>Titles:</b> ' . $details["titles"];
    }

    echo '</li>';

    echo <<<HTML
<li class="list-group-item"><b>Countrys:</b> {$details["countrys"]}</li>
<li class="list-group-item"><b>Devices:</b> {$details["devices"]}</li>
<li class="list-group-item"><b>CPC:</b> <span class="text-success text-bold">\${$details["amount"]}</span></li>
HTML;

    echo '<li class="list-group-item">
<a href="ads.php?act=edit&id=' . $id . '" class="btn btn-app"><i class="fa fa-edit"></i> Edit Ad</a>
<a href="#" class="btn btn-app" data-toggle="modal" data-target="#delUser"><i class="fa fa-trash-o"></i> Delete Ad</a>';

    if ($details["status"] != 2) {
        if ($details["status"] == 1) {
            echo '<a href="ads.php?act=pause&id=' . $id . '" class="btn btn-app"><i class="fa fa-pause"></i> Pause Ad</a>';
        } else {
            echo '<a href="ads.php?act=run&id=' . $id . '" class="btn btn-app"><i class="fa fa-play"></i> Run Ad</a>';
        }
    } else {
        echo '<a href="ads.php?act=reject&id=' . $id . '" class="btn btn-app"><i class="fa fa-ban"></i> Reject Ad</a>';
        echo '<a href="ads.php?act=run&id=' . $id . '" class="btn btn-app"><i class="fa fa-play"></i> Run Ad</a>';
    }

    echo <<<HTML
<a href="ads.php?act=block&id={$id}" class="btn btn-app"><i class="fa fa-ban"></i> Block Ad</a>
</li>
</ul>
<div class="modal fade" tabindex="-1" role="dialog" id="delUser" aria-labelledby="delUserLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete Advertisement?</h4>
      </div>
      <div class="modal-body">
        <p>Delete Advertisement {$details["name"]} (http://{$details["url"]})</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <a href="ads.php?act=del&id={$id}&confirm=yes"><button type="button" class="btn btn-danger">Delete</button></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<div class="col-md-7">
<div class="row">
<a href="stats.php?type=ad&ad_id={$id}&date={$date}/{$date}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua text-center">
        <div class="inner">
            <h3>{$today_clicks}</h3>
            <p>Today Clicks</p>
        </div>
    </div>
</div>
</a>
<a href="stats.php?type=ad&ad_id={$id}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-red text-center">
        <div class="inner">
            <h3>{$total_clicks}</h3>
            <p>Total Clicks</p>
        </div>
    </div>
</div>
</a>
<a href="stats.php?type=ad&ad_id={$id}&date={$date}/{$date}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green text-center">
        <div class="inner">
            <h3>\${$today_earnings}</h3>
            <p>Today Spent</p>
        </div>
    </div>
</div>
</a>
<a href="stats.php?type=ad&ad_id={$id}">
<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-yellow text-center">
        <div class="inner">
            <h3>\${$total_earnings}</h3>
            <p>Total Spent</p>
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

        if ($type == "running") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 1, "userid" => $uid), "id DESC", "$start,$end");
        } elseif ($type == "blocked") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 0, "userid" => $uid), "id DESC", "$start,$end");
        } elseif ($type == "pending") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 2, "userid" => $uid), "id DESC", "$start,$end");
        } elseif ($type == "paused") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 3, "userid" => $uid), "id DESC", "$start,$end");
        } elseif ($type == "rejected") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 4, "userid" => $uid), "id DESC", "$start,$end");
        } else {
            $ads = $db->select("ads", "id,name,url,userid,status", array("userid" => $uid), "id DESC", "$start,$end");
        }
    } else {
        if ($type == "running") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 1), "id DESC", "$start,$end");
        } elseif ($type == "blocked") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 0), "id DESC", "$start,$end");
        } elseif ($type == "pending") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 2), "id DESC", "$start,$end");
        } elseif ($type == "paused") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 3), "id DESC", "$start,$end");
        } elseif ($type == "rejected") {
            $ads = $db->select("ads", "id,name,url,userid,status", array("status" => 4), "id DESC", "$start,$end");
        } else {
            $ads = $db->select("ads", "id,name,url,userid,status", array(), "id DESC", "$start,$end");
        }
    }

    $admin->head("Advertisements");
    $admin->message();
    echo '<section class="content-header">';
    echo '<div class="row">
	<div class="col-lg-12">
	<div class="panel panel-default">
	<div class="panel-heading">
	<div class="row">
	<div class="col-md-4">
	<h2 class="panel-title">' . ucwords(str_replace("_", " ", $type)) . ' Advertisements</h2>
	</div>
	<div class="col-md-4">
	<select class="form-control" onchange="changeCat(this.value);">
	<option selected="selected">Select Type</option>
	<option value="running">Running Ads</option>
	<option value="pending">Pending Ads</option>
	<option value="blocked">Blocked Ads</option>
	<option value="rejected">Rejected Ads</option>
	</select>
	</div>
	<div class="col-md-4">
	<form method="get">
	<input type="hidden" name="act" value="search"/>
	<div class="input-group">
	<input type="text" name="q" placeholder="Search Ads" class="form-control"/>
	<div class="input-group-btn">
	<button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
	</div></div></form>
	</div></div></div>
	<div class="panel-body">';

    if ($ads->num_rows == 0) {
        echo 'No Advertisement Found in Database!';
    } else {
        echo '<table class="table table-striped table-hover table-bordered">';
        echo '<th class="text-center">ID #</th><th class="text-center">Name</th><th class="text-center">URL</th>
<th class="text-center">Status</th>
<th class="text-center">Owner</th>';

        while ($result = $ads->fetch_assoc()) {
            if ($result["status"] == 1) {
                $status = '<span class="label label-success">Running</span>';
            } elseif ($result["status"] == 2) {
                $status = '<span class="label label-warning">Pending</span>';
            } elseif ($result["status"] == 3) {
                $status = '<span class="label label-danger">Paused</span>';
            } elseif ($result["status"] == 4) {
                $status = '<span class="label label-danger">Rejected</span>';
            } else {
                $status = '<span class="label label-danger">Blocked</span>';
            }

            $username = ($result["userid"] == 0) ? "<i>ADMIN</i>" : $user->gdata("name", array("id" => $result["userid"]));
            echo '<tr class="text-center">
			<td>' . $result["id"] . '</td>
			<td><a href="ads.php?act=details&id=' . $result["id"] . '">' . $result["name"] . '</a></td>
			<td><a href="http://' . $result["url"] . '">http://' . $result["url"] . '</a></td>
			<td>' . $status . '</td>
			<td><a href="users.php?act=details&id=' . $result["userid"] . '">' . $username . '</a></td>
			</tr>';
        }

        echo '</table></div></div>';
        $init->paging_admin($page, $ads->num_rows, 30);
    }
}
$script = <<<HTML
<script src="js/select2.full.min.js"></script>
<script type="text/javascript">
	$(".select2").select2();
</script>
<script>
function changeCat(cat)
{
	window.location.href = "?type=" + cat;
}
</script>
HTML;
$admin->foot($script);
?>
