<?php

	include "init.php";
	
	if(!$user->is_user()){
		redir($setting["siteurl"]."/login");
		exit;
	}
	
	if($user->data("status")==2){
			$smarty->assign("title","Account Unverified ".$setting["sitename"]);
			template("user_unverified.tpl");
			exit;
		}
	if($user->data("status")==0){
		$smarty->assign("title","Account Blocked ".$setting["sitename"]);
		template("user_blocked.tpl");
		exit;
	}
	
	$smarty->assign("title","Notifications - ".$setting["sitename"]);
	
	if(isset($_GET["id"])){
	
		$id = get("id");
		
		$noti = $db->select("notifications","message,subject,date",array("userid"=>$user->id,"id"=>$id));
		
		if($noti->num_rows<1){
			
			redir($setting["siteurl"]."/dashboard");
		}
		else {
			
			$notif = $noti->fetch_assoc();
			$db->update("notifications",array("status"=>2),array("id"=>$id));
			$smarty->assign("notify",$notif);
			template("read_notification.tpl");
			
		}
	}
	else {		
		$smarty->assign("title","Notifications - ".$setting["sitename"]);
		$page = get("page");

		if(empty($page)) 	$page = 1;

		$start = ($page-1) * 10;
		$end = 10;
		$noti = $db->select("notifications","id,subject,message,date,status",array("userid"=>$user->id),"id DESC","$start,$end");
		if($noti->num_rows != 0){
			$notify = array();
			while($notif = $noti->fetch_assoc()){
				$notify[] = $notif;
			}
			$smarty->assign("notify",$notify);
			$init->paging($page, $noti->num_rows, 10);
			template("notifications.tpl");
		}
	}
	
			
?>
	
		
				