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

$smarty->assign("title", "Edit Profile");

if (isset($_POST["address"], $_POST["city"], $_POST["country"], $_POST["mobile"], $_POST["name"])) {
    $name = post("name");
    $address = post("address");
    $city = post("city");
    $country = post("country");
    $mobile = post("mobile");

    $errors = array();

    if (empty($name) || empty($address) || empty($city) || empty($country) || empty($mobile) || empty($sc_id))
        $errors[] = "All the fields are required";
    if (!is_numeric(str_replace("+", null, $mobile))) $errors[] = "Mobile number is not valid!";

    if (empty($errors)) {
        if ($db->update("users", array("name" => $name, "address" => $address, "city" => $city, "country" => $country, "mobile" => $mobile), array("id" => $user->id))) {
            $_SESSION["message"] = "Account information updated!";
            redir($site_url . "/dashboard/edit_profile.html");
        }
    } else {
        $smarty->assign("errors", $errors);
    }
}

$form = '
<form method="post">
	<div class="form-group">
		<label for="name">Full Name</label>
		<input type="text" name="name" value="' . $user->data("name") . '" class="form-control">
	</div>
	<div class="form-group">
		<label for="address">Address</label>
		<textarea name="address" class="form-control">' . $user->data("address") . '</textarea>
	</div>
	<div class="form-group">
		<label for="city">City</label>
		<input type="text" name="city" value="' . $user->data("city") . '" class="form-control">
	</div>
	<div class="form-group">
		<label for="country">Country</label>
		<select name="country" class="form-control">';
$countri = get_full_country();
foreach (get_two_country() as $i => $ct) {
    if ($user->data("country") == trim($ct)) {
        $form .= '<option value="' . trim($ct) . '" selected="selected">' . ucwords(strtolower(trim($countri[$i]))) . '</option>';
    } else {
        $form .= '<option value="' . trim($ct) . '">' . ucwords(strtolower(trim($countri[$i]))) . '</option>';
    }
}
$form .= '</select></div>
<div class="form-group">
	<label for="mobile">Mobile Number</label>
	<input type="text" name="mobile" value="' . $user->data("mobile") . '" class="form-control">
</div>
<div class="form-group">
	<button type="submit" class="btn btn-success">Update Info</button>
</div>
</form>';

$smarty->assign("form", $form);
$smarty->display("account.tpl");