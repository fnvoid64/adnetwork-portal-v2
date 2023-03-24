<?php

include "init.php";

if ($admin->is_admin()) {
    redir("index.php");
    exit();
}

//$admin->head("prAdmin Login");

if (isset($_POST["admin"], $_POST["password"])) {

    $admins = post("admin");
    $password = post("password");

    $errors = array();

    if (empty($admins) OR strlen($admins) < 1) $errors[] = "Admin Field Empty!";
    if (empty($password) OR strlen($password) < 1) $errors[] = "Password Field Empty!";
    if (!$admin->login($admins, $password)) $errors[] = "Admin Data Incorrect!";

    if (empty($errors)) {

        $_SESSION["adminid"] = $admin->gdata(array("admin" => $admins), "id");
        redir("index.php");

    }
}

echo <<<HTML
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>AdzDollar AdminCP Login</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/AdminLTE.min.css">
</head>
<body class="hold-transition register-page" style="background: #454E59;">
<div class="register-box">
	<div class="register-logo">
		<a href="/"><img src="css/adzdollar.png" width="250px"/></a>
	</div>
	<div class="register-box-body">
		<p class="login-box-msg">Login to dashboard</p>
HTML;
if (!empty($errors))   $admin->error($errors);
$admin->message();
echo <<<HTML
		<form method="post" id="login">
			<div class="form-group has-feedback">
				<input type="text" name="admin" class="form-control" placeholder="Admin" required>
				<span class="glyphicon glyphicon-user form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" name="password" class="form-control" placeholder="Password" required>
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-info btn-block btn-flat">Login</button>
			</div>
		</form>
	</div>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
HTML;

	