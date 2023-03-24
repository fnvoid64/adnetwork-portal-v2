<?php
include 'init.php';
$ads = new Pranto\Ads();

$act = get("act");

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

if ($act == "add") {
    $type = get("type");

    if ($type == "banner") {
        $smarty->assign("title", "Create Banner Campaign");
        $smarty->assign("type", "Banner");

        if (isset($_POST["name"], $_POST["adult"], $_FILES["banners"], $_POST["url"], $_POST["amount"])) {
            $name = post("name");
            $adult = post("adult");
            $url = post("url");
            $amount = post("amount");

            $errors = array();

            if (empty($name) OR strlen($name) < 1) $errors[] = "Campaign name left empty";
            if (empty($adult)) $adult = 0;

            $url = str_replace("http://", null, $url);
            $cset = $dset = 0;
            if (isset($_POST["countrys"])) $cset = 1;
            if (isset($_POST["devices"])) $dset = 1;

            if (!preg_match('/([a-zA-Z0-9\-\.]+)\.([a-zA-Z0-9\-\.]+)/', $url)) $errors[] = "URL empty or invalid";
            if (empty($amount) OR strlen($amount) < 1 OR !is_numeric($amount)) $errors[] = "Invalid amount";
            if ($amount < $init->settings("minimumcpc")) $errors[] = "CPC is too low";
            if (count($_FILES["banners"]["tmp_name"]) < 1) $errors[] = "No banner uploaded";

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

                $code = $db->insert("ads", array("name" => $name, "url" => $url, "type" => 1, "adult" => $adult, "banners" => $Banner, "cset" => $cset, "dset" => $dset, "countrys" => $countrylist, "devices" => $devicelist, "status" => 2, "date" => $date, "amount" => $amount, "userid" => $user->id));

                if ($code) {
                    $_SESSION["message"] = "Campaign created successfully";
                    $mailBody = $init->mail("adsadd");
                    $mailBody = str_replace("%date%", $date, $mailBody);
                    $mailBody = str_replace("%username%", $user->data("name"), $mailBody);
                    $mailBody = str_replace("%adname%", $name, $mailBody);
                    $mailBody = str_replace("%adurl%", $url, $mailBody);

                    $subject = "Advertise Created";
                    $user->mail($subject, $mailBody);
                    redir($site_url . "/dashboard/campaigns.html");
                } else {
                    echo "CRITICAL_DB_ERROR!";
                    exit();
                }
            } else {
                $smarty->assign("ads_add_error", $errors);
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
				<lable for="amount">Advertisement CPC (' . $_LANG["minimumcpc_low"] . ')</label>
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

        $smarty->assign("ads_add_form", $form);
        $smarty->display("ads_add.tpl");
    } elseif ($type == "text") {
        $smarty->assign("title", "Create Text Campaign");
        $smarty->assign("type", "Text");

        if (isset($_POST["name"], $_POST["adult"], $_POST["titles"], $_POST["url"], $_POST["amount"])) {
            $name = post("name");
            $adult = post("adult");
            $url = post("url");
            $amount = post("amount");
            $titles = post("titles");

            $errors = array();

            if (empty($name) OR strlen($name) < 1) $errors[] = "Campaign name was empty";
            if (empty($adult)) $adult = 0;

            $url = str_replace("http://", null, $url);
            $cset = $dset = 0;
            if (isset($_POST["countrys"])) $cset = 1;
            if (isset($_POST["devices"])) $dset = 1;

            if (!preg_match('/([a-zA-Z0-9\-\.]+)\.([a-zA-Z0-9\-\.]+)/', $url)) $errors[] = "URL is empty or invalid";
            if (empty($amount) OR strlen($amount) < 1 OR !is_numeric($amount)) $errors[] = "Invalid amount";
            if ($amount < $init->settings("minimumcpc")) $errors[] = "CPC is too low";
            if (empty($titles) OR strlen($titles) < 1) $errors[] = "Title is empty";

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

                $code = $db->insert("ads", array("name" => $name, "url" => $url, "type" => 2, "adult" => $adult, "titles" => $titles, "cset" => $cset, "dset" => $dset, "countrys" => $countrylist, "devices" => $devicelist, "status" => 2, "date" => $date, "amount" => $amount, "userid" => $user->id));

                if ($code) {
                    $_SESSION["message"] = "Campaign created successfully";
                    redir("/dashboard/campaigns.html");
                } else {
                    echo "CRITICAL_DB_ERROR!";
                    exit();
                }
            } else {
                $smarty->assign("ads_add_error", $errors);
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
					<lable for="amount">Advertisement CPC (' . $_LANG["minimumcpc_low"] . ')</label>
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
        $smarty->assign("ads_add_form", $form);
        $smarty->display("ads_add.tpl");
    } else {
        $smarty->assign("title", "Create Campaign");
        $smarty->display("ads_add_list.tpl");

    }
} elseif ($act == "edit" && isset($_GET["id"])) {

    $id = get("id");

    if (!$ads->exists($id)) {
        echo "Advertise Doesn't Exit!";
        exit();
    }

    $smarty->assign("title", "Edit Campaign");

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

            if (empty($name) OR strlen($name) < 1) $errors[] = "Campaign name was empty";

            $url = str_replace("http://", null, $url);
            $cset = $dset = 0;
            if (isset($_POST["countrys"])) $cset = 1;
            if (isset($_POST["devices"])) $dset = 1;

            if (!preg_match('/([a-zA-Z0-9\-\.]+)\.([a-zA-Z0-9\-\.]+)/', $url)) $errors[] = "URL was empty or invalid";
            if (empty($adult)) $adult = 0;
            if (empty($amount) OR strlen($amount) < 1 OR !is_numeric($amount)) $errors[] = "Invalid amount";
            if ($amount < $init->settings("minimumcpc")) $errors[] = "CPC is too low";
            if (count($_FILES["banners"]["tmp_name"]) < 1) $errors[] = "No Banner uploaded";

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
                    $_SESSION["message"] = "Campaign successfully edited";
                    redir("{$site_url}/dashboard/campaigns/details.html?id=$id");
                } else {
                    echo "CRITICAL_DB_ERROR!";
                    exit();
                }
            } else {
                $smarty->assign("ads_edit_error", $errors);
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
				<lable for="amount">Advertisement CPC (' . $_LANG["minimumcpc_low"] . ')</label>
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

        $smarty->assign("ads_edit_form", $form);
        $smarty->display("ads_edit.tpl");
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
                    $_SESSION["message"] = "Campaign edited successfully!";
                    redir("{$site_url}/dashboard/campaigns/details.html?id=$id");
                } else {
                    echo "CRITICAL_DB_ERROR!";
                    exit;
                }
            } else {
                $smarty->assign("ads_edit_error", $errors);
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
				<lable for="amount">Advertisement CPC (' . $_LANG["minimumcpc_low"] . ')</label>
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

        $smarty->assign("ads_edit_form", $form);
        $smarty->display("ads_edit.tpl");
    }
} elseif ($act == "change" && isset($_GET["id"])) {

    $id = get("id");

    if (!$ads->exists($id)) {
        echo "Advertise Doesn't Exit!";
        exit();
    }

    $status = $ads->details($id, "status");


    if ($status == 2 OR $status == 0) {
        echo "Not happening";
        exit();
    }

    if ($status == 1) {
        $db->update("ads", array("status" => 3), array("id" => $id));
        $_SESSION["message"] = "Advertise Successfully Paused!";
        redir($site_url . "/dashboard/campaigns.html");
    } else {
        $db->update("ads", array("status" => 2), array("id" => $id));
        $_SESSION["message"] = "Advertise is Pending for Admin Actions!";
        redir($site_url . "/dashboard/campaigns.html");
    }
} elseif ($act == "del" && isset($_GET["id"])) {

    $id = get("id");

    if (!$ads->exists($id)) {
        echo "Advertise Doesn't Exit!";
        exit();
    }

    if ($_GET["confirm"] == "yes") {

        if ($db->delete("ads", array("id" => $id))) {
            $_SESSION["message"] = "Advertise Deleted Successfully!";
            redir($site_url . "/dashboard/campaigns.html");
        } else {
            echo "Unknown Error!";
        }
    }
} elseif ($act == "details" && isset($_GET["id"])) {

    $id = get("id");

    if (!$ads->exists($id)) {
        echo "Advertise Doesn't Exit!";
        exit();
    }

    $smarty->assign("title", "Campaign Details");

    $code = $db->select("ads", "*", array("id" => $id));
    $fetch = $code->fetch_assoc();
    $smarty->assign("ads", $fetch);
    $banner = explode(",", $fetch["banners"]);
    $smarty->assign("banners", $banner);

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

    $today_earnings = round($today_clicks * $fetch["camount"], 2);
    $total_earnings = round($total_clicks * $fetch["camount"], 2);

    $smarty->assign("today_clicks", $today_clicks);
    $smarty->assign("total_clicks", $total_clicks);
    $smarty->assign("today_earn", $today_earnings);
    $smarty->assign("total_earn", $total_earnings);
    $smarty->assign("date", $date);
    $smarty->display("ads_details.tpl");
} else {

    $smarty->assign("title", "Campaigns");
    $page = get("page");

    if (empty($page)) $page = 1;

    $start = ($page - 1) * 10;
    $end = 10;

    if (isset($_SESSION["message"])) {

        $smarty->assign("message", $_SESSION["message"]);
        unset($_SESSION["message"]);

    }

    $code = $db->select("ads", "id,name,url,status,type,amount", array("userid" => $user->id), "id DESC", "$start,$end");
    $fetch = array();
    while ($result = $code->fetch_assoc()) {
        $fetch[] = $result;
    }
    $smarty->assign("adslist", $fetch);
    $init->paging($page, $code->num_rows, 10);
    $smarty->display("ads.tpl");
}