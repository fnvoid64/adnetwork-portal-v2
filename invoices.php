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
	
	$act = get("act");
	$id = get("id");
	
	if($act == "cancel" && isset($_GET["id"])){
		if(!invoice_exists($id)){
			echo "Invoice Doesn't Exists!";
			exit;
		}
		if(invoice_info($id,"status")==1 OR invoice_info($id,"status")==3){
			echo "Not happening bro!";
			exit;
		}
		
		if($_GET["confirm"]=="yes"){
		
			if($db->update("invoices",array("status"=>3),array("id"=>$id))){
				$_SESSION["message"]=$_LANG["invoice_canceled"];
				redir($setting["siteurl"]."/invoices");
			}
			else {
				echo "CRITICAL_DB_ERROR!";
			}
		}
	}
	elseif($act == "details" && isset($_GET["id"])){
		if(!invoice_exists($id)){
			echo "Invoice Doesn't Exists!";
			exit;
		}
		
		$code = $db->select("invoices","",array("id"=>$id,"userid"=>$user->id));
		$fetch = $code->fetch_assoc();
		$smarty->assign("title","Invoice #".$fetch["invoice"]);

		if ($fetch["method"] == "Bank") {
			$get_bank = $db->select("bank_accounts", "account_number,bank_name", array("userid" => $user->id, "id" => $fetch["via"]));
			$get_bank = $get_bank->fetch_assoc();
			$fetch["via"] = $get_bank["bank_name"] . " (" . $get_bank["account_number"] . ")";
		}

		$smarty->assign("invoice",$fetch);
		$smarty->assign("username", $user->data("username"));
		$smarty->assign("name", $user->data("name"));
		$smarty->assign("email", $user->data("email"));
		
		if (isset($_GET["print"])) {
			template("invoice_print.tpl");
		} else {
			template("invoice_details.tpl");
		}
	}
	else {
		$smarty->assign("title","Invoices - ".$setting["sitename"]);
		$page = get("page");

		if(empty($page)) 	$page = 1;

		$start = ($page-1) * 10;
		$end = 10;

		if(isset($_SESSION["message"])){
			$smarty->assign("message",$_SESSION["message"]);
			unset($_SESSION["message"]);
		}
	
		$code = $db->select("invoices","id,invoice,amount,status,method",array("userid"=>$user->id), "id DESC", "$start, $end");
		$invoice = array();
		while($result=$code->fetch_assoc()){
			$invoice[] = $result;
		}
		$smarty->assign("invoices",$invoice);
		$init->paging($page, $code->num_rows, 10);
		template("invoices.tpl");
		
	}
	
	function invoice_exists($id){
		
		global $db,$user;
		
		$code = $db->select("invoices","amount",array("id"=>$id,"userid"=>$user->id));
		
		if($code->num_rows<1){
			return false;
		}
		else {
			return true;
		}
	}
	function invoice_info($id,$var){
	
		global $db,$user;
		$code = $db->select("invoices",$var,array("id"=>$id,"userid"=>$user->id));
		$fetch = $code->fetch_assoc();
		return $fetch[$var];
		
	}
	
?>	