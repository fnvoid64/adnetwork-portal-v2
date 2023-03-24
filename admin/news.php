<?php

include "init.php";

if (!$admin->is_admin()) {
    redir("login.php");
    exit();
}

$act = get("act");

if ($act == "add") {

    $admin->head("Add News");
    echo '<section class="content-header">';
    echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Add News</h2></div>
		<div class="panel-body">';

    if (isset($_POST["title"], $_POST["message"])) {

        $title = post("title");
        $message = post("message");

        $errors = array();

        if (empty($title) OR strlen($title) < 1) $errors[] = "Title is Empty!";
        if (strlen($title) > 200) $errors[] = "Title must < 200 chars";
        if (empty($message) OR strlen($message) < 1) $errors[] = "Message is Empty!";

        if (empty($errors)) {

            if ($db->insert("news", array("adminid" => $admin->id, "title" => $title, "message" => $message, "date" => $date, "views" => 0))) {
                $_SESSION["message"] = "News Added Successfully";
                redir("news.php");
            }
        } else {
            $admin->error($errors);
        }
    }

    echo '<form method="post">
		<div class="form-group">
		<label for="title">News Title</label>
		<input type="text" name="title" class="form-control"/>
		</div>
		<div class="form-group">
		<label for="message">News Body</label>
		<textarea name="message" class="form-control"></textarea><br/>Use HTML Codes in News Body
		</div>
		<div class="form-group">
		<button type="submit" class="btn btn-success">Add News</button>
		</div>
		</form>
		</div></div></div>';

} elseif ($act == "edit" && $_GET["id"]) {
    $id = get("id");
    $newsExists = $db->select("news", "title,message", array("id" => $id));
    if ($newsExists->num_rows < 1) {
        echo "News Not Found!";
        exit;
    }

    $admin->head("Edit News");
    echo '<section class="content-header">';
    echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Edit News</h2></div>
		<div class="panel-body">';

    if (isset($_POST["title"], $_POST["message"])) {

        $title = post("title");
        $message = post("message");

        $errors = array();

        if (empty($title) OR strlen($title) < 1) $errors[] = "Title is Empty!";
        if (strlen($title) > 200) $errors[] = "Title must < 200 chars";
        if (empty($message) OR strlen($message) < 1) $errors[] = "Message is Empty!";

        if (empty($errors)) {

            if ($db->update("news", array("title" => $title, "message" => $message), array("id" => $id))) {
                $_SESSION["message"] = "News Edit Successfully";
                redir("news.php");
            }
        } else {
            $admin->error($errors);
        }
    }
    $news = $newsExists->fetch_assoc();

    echo '<form method="post">
		<div class="form-group">
		<label for="title">News Title</label>
		<input type="text" name="title" class="form-control" value="' . $news["title"] . '"/>
		</div>
		<div class="form-group">
		<label for="message">News Body</label>
		<textarea name="message" class="form-control">' . str_replace('\r\n', "\r\n", $news["message"]) . '</textarea><br/>Use HTML Codes in News Body
		</div>
		<div class="form-group">
		<button type="submit" class="btn btn-success">Update News</button>
		</div>
		</form>
		</div></div></div>';
} elseif ($act == "details" && isset($_GET["id"])) {
    $id = get("id");
    $newsExists = $db->select("news", "*", array("id" => $id));
    if ($newsExists->num_rows < 1) {
        echo "News Not Found!";
        exit();
    }
    $news = $newsExists->fetch_assoc();
    $admin->head($news["title"]);
    echo '<section class="content-header">';
    echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><span><b class="panel-title">' . $news["title"] . '</b><br/>Created: ' . $news["date"] . ' | Views: ' . $news["views"] . '</span>
		<a href="news.php?act=edit&id=' . $id . '" class="btn btn-info btn-xs pull-right"><i class="fa fa-edit"></i> Edit</a>
		<div class="clearfix"></div></div>
		<div class="panel-body">';

    $admin->message();

    echo '<p>' . str_replace("rn", "<br/>", stripslashes(htmlspecialchars_decode($news["message"]))) . '</p>';

    $replys = $db->select("nreply", "*", array("nid" => $id));
    if ($replys->num_rows < 1) {
        echo '<p class="text-center" style="border-top: 1px solid #ececec; margin-top: 20px; padding-top: 20px">
			<span class="text-muted text-muted"><i>No Reply Found!</i></span></p>';
    } else {
        echo '<p class="text-center" style="border-top: 1px solid #ececec; margin-top: 20px; padding-top: 20px">
			<table class="table table-striped">';
        while ($reply = $replys->fetch_assoc()) {
            echo '<tr><td>' . $reply["reply"];
            if (preg_match('/admin-/', $reply["userid"])) {
                $userid = str_replace("admin-", null, $reply["userid"]);
                $userId = '<font color="green"><i>' . $admin->gdata(array("id" => $userid), "admin") . '</i></font>';
            } else {
                $userId = $user->gdata("name", array("id" => $reply["userid"]));
            }
            echo '<br/>Reply By: ' . $userId . ' at ' . $reply["date"] . '</td></tr>';
        }
        echo '</table></p>';
    }
    echo '<div class="row" style="margin-top: 20px"><div class="col-md-6 col-md-offset-3">
		<form method="post" action="news.php?act=reply&id=' . $id . '">
		<div class="form-group">
		<textarea name="reply" placeholder="Reply .." class="form-control"></textarea>
		</div>
		<div class="form-group">
		<button type="submit" class="btn btn-primary">Reply</button>
		</div></form></div></div>';
} elseif ($act == "reply" && isset($_GET["id"])) {
    $id = get("id");
    $newsExists = $db->select("news", "*", array("id" => $id));
    if ($newsExists->num_rows < 1) {
        echo "News Not Found!";
        exit;
    }
    if (isset($_POST["reply"])) {
        $reply = post("reply");

        if (empty($reply) OR strlen($reply) < 1) {
            echo "Error";
            exit;
        }
        $db->insert("nreply", array("nid" => $id, "reply" => $reply, "userid" => "admin-" . $admin->id, "date" => $date));
        $_SESSION["message"] = "Reply Added Successfully!";
        redir("news.php?act=details&id=$id");
    }
} else {
    $admin->head("News List");
    echo '<section class="content-header">';
    echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">News List</h2></div>
		<div class="panel-body">';

    $admin->message();
    $page = get("page");
    if (empty($page)) $page = 1;
    $start = ($page - 1) * 30;
    $end = 30;

    $news = $db->select("news", "id,title,views,date", array(), "id DESC", "$start,$end");

    if ($news->num_rows < 1) {
        echo '<p class="text-cener"><i>No News Added!</i></p>';
    } else {

        echo '<table class="table table-bordered table-striped table-hover">
			<th>ID #</th>
			<th>Title</th>
			<th>Views</th>
			<th>Date</th>';
        while ($result = $news->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $result["id"] . '</td>';
            echo '<td><a href="news.php?act=details&id=' . $result["id"] . '">' . $result["title"] . '</a></td>';
            echo '<td>' . $result["views"] . '</td>';
            echo '<td>' . $result["date"] . '</td>';
            echo '</tr>';
        }
        echo '</table></div></div></div></div>';
        $init->paging_admin($page, $news->num_rows, 30);
    }
}
$admin->foot();

	
		
				