<?php

ini_set('MEMORY_LIMIT', -1);
ini_set('MAX_EXECUTION_TIME', 800);

include "init.php";

if (!$admin->is_admin()) {
    redir("login.php");
    exit();
}

$act = get("act");

if ($act == "user" && isset($_GET["email"])) {

    $admin->head("Notify " . get("email"));
    echo '<section class="content-header">';
    echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Notify ' . get("email") . '</h2></div>
		<div class="panel-body">';

    $username = get("email");
    $checkUserName = $db->select("users", "id", array("email" => $username));
    if ($checkUserName->num_rows < 1) {
        echo "User not found";
        exit();
    }
    $userId = $checkUserName->fetch_assoc();

    if (isset($_POST["subject"], $_POST["message"])) {

        $subject = post("subject");
        $message = post("message");

        if (empty($subject) OR empty($message)) {
            echo "Must not empty";
            exit;
        }

        $subject = str_replace("%email%", $username, $subject);
        $message = str_replace("%email%", $username, $message);
        $subject = str_replace("%date%", $date, $subject);
        $message = str_replace("%date%", $date, $message);

        $subject = stripslashes($subject);
        $message = stripslashes($message);

        $message = str_replace("\n", "<br/>", $message);

        $notify = $db->insert("notifications", array("userid" => $userId["id"], "subject" => $subject, "message" => $message, "date" => $date, "status" => 1));

        if ($notify) {
            echo '<div class="alert alert-success">User Notified Successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Unknown Error</div>';
        }

    }

    echo '<form method="post">';
    echo '<div class="form-group"><label for="email">To User</label><input type="text" value="' . $username . '" readonly="readonly" class="form-control"/></div>';
    echo '<div class="form-group"><label for="subject">Subject</label><input type="text" name="subject" class="form-control"/></div>';
    echo '<div class="form-group"><label for="message">Message</label><textarea name="message" class="form-control"></textarea></div>';
    echo '<div class="form-group"><button type="submit" class="btn btn-success">Notify</button></div></form>';
    echo '<div class="alert alert-info">Available Variables:<br/> %email% = email<br/>%date% = Current Date</div>';
    echo '</div></div></div></div>';

} elseif ($act == "all") {

    $users = $db->select("users", "id", array("status" => 1));

    $admin->head("Notify All User");
    echo '<section class="content-header">';
    echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Notify All Users</h2></div>
		<div class="panel-body">';

    if (isset($_POST["subject"], $_POST["message"])) {

        $subject = post("subject");
        $message = post("message");

        if (empty($subject) OR empty($message)) {
            echo "Must not empty";
            exit;
        }

        $subject = str_replace("%date%", $date, $subject);
        $message = str_replace("%date%", $date, $message);

        $subject = stripslashes($subject);
        $message = stripslashes($message);
        echo '<div class="menu">';
        $message = str_replace("\n", "<br/>", $message);
        while ($userId = $users->fetch_assoc()) {

            $notify = $db->insert("notifications", array("userid" => $userId["id"], "subject" => $subject, "message" => $message, "date" => $date, "status" => 1));

            if ($notify) {
                echo '<font color="green">' . $user->gdata("name", array("id" => $userId["id"])) . ' Notified Successfully!</font><br/>';
            } else {
                echo 'Unknown Error?<br/>';
            }
        }
        echo '</div>';

    } else {
        echo '<form method="post">';
        echo '<div class="form-group"><label for="user">To User</label><input type="text" value="All" readonly="readonly" class="form-control"/></div>';
        echo '<div class="form-group"><label for="subject">Subject</label><input type="text" name="subject" class="form-control"/></div>';
        echo '<div class="form-group"><label for="message">Message</label><textarea name="message" class="form-control"></textarea></div>';
        echo '<div class="form-group"><button type="submit" class="btn btn-success">Notify</button></div></form>';
        echo '<div class="alert alert-info">Available Variables:<br/>%date% = Current Date</div>';
        echo '</div></div></div></div>';

    }
} else {

    $admin->head("Notify User");
    echo '<section class="content-header">';
    echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Notify User</h2></div>
		<div class="panel-body">';
    echo '<form method="get" action="notifications.php">
		<input type="hidden" name="act" value="user">
		<div class="form-group">
		<label for="email">Notify User By Email</label>
		<input type="text" name="email" class="form-control"/>
		</div>
		<div class="form-group"><button type="submit" class="btn btn-success">Notify</button></div></form>';
    echo '<div style="border-top: 1px solid #ddd;margin-top: 10px; padding: 8px"><a href="?act=all">Notify All Users</a></div>';
    echo '</div></div></div></div>';

}

$admin->foot();

		
		
		
	
		