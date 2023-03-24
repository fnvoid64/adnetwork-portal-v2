<?php

	include "init.php";

	if(!$user->is_user()){
		redir($setting["siteurl"]."/login");
		exit();
	}
	
	if($setting["news"]==1){
	
		if(isset($_GET["news"])){
		
			$smarty->assign("title","News Details - ".$setting["sitename"]);
		
			$news = get("news");
			
			$newsExists = $db->select("news","",array("id"=>$news));
			
			if($newsExists->num_rows<1){
				$smarty->assign("nonews",1);
			}
			else {
			
				if(isset($_SESSION["message"])){
					$smarty->assign("message",$_SESSION["message"]);
					unset($_SESSION["message"]);
				}
			
				$fetchNews = $newsExists->fetch_assoc();
				
				$smarty->assign("newsTitle",$fetchNews["title"]);
				$smarty->assign("newsBody",str_replace("rn", "<br/>", stripslashes(htmlspecialchars_decode($fetchNews["message"]))));
				$smarty->assign("newsCreated",$fetchNews["date"]);
				
				$admins = $db->select("admins","admin",array("id"=>$fetchNews["adminid"]));
				$admin = $admins->fetch_assoc();
				$smarty->assign("newsBy",$admin["admin"]);
				$smarty->assign("newsViews",$fetchNews["views"]);
				
				$replys = $db->select("nreply","reply,userid,date",array("nid"=>$news),"id DESC");
				
				$reply = array();
				$replyDate = array();
				$replyBy = array();
				
				while($result = $replys->fetch_array()){
					$reply[] = $result["reply"];
					$replyDate[] = $result["date"];
					if(preg_match('/admin-/',$result["userid"])){
						$replyby = str_replace('admin-',null,$result["userid"]);
						$adminId = $db->select("admins","admin",array("id"=>$replyby));
						$AdminId = $adminId->fetch_assoc();
						$replyBy[] = '<i><font color="green">'.$AdminId["admin"].'</font></i>';
					}
					else {
						$replyBy[] = $user->gdata("username",array("id"=>$result["userid"]));
					}
				}
				
				$smarty->assign("replys",$reply);
				$smarty->assign("replyDate",$replyDate);
				$smarty->assign("replyBy",$replyBy);
				
				$views = $fetchNews["views"];
				if(empty($views)) $views = 0;
				$views++;
				$db->update("news",array("views"=>$views),array("id"=>$news));
				if($user->is_user()){
					$form = '<div class="row" style="margin-top: 20px"><div class="col-md-6 col-md-offset-3"><form method="post" action="'.$setting["siteurl"].'/news?reply='.$news.'">
					<div class="form-group"><textarea name="reply" placeholder="Reply .." class="form-control"></textarea></div>
					<div class="form-group">
					<button type="submit" class="btn btn-primary">Reply</button>
					</div></form></div>';
				}
				$smarty->assign("newsform",$form);
				
			}
			template("news_details.tpl");
		}
		elseif(isset($_GET["reply"])){
			
			
	
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
		
			$news = get("reply");
			
			$newsExists = $db->select("news","adminid",array("id"=>$news));
			
			if($newsExists->num_rows<1){
				redir($setting["siteurl"]."/news");
			}
			else {
		
				
				if(isset($_POST["reply"])){
					
					$reply = post("reply");
					
					$errors = array();
					
					if(empty($reply) OR strlen($reply)<1) 	$errors[] = "Reply left Empty!";
					if(!empty($setting["spamdomains"])){
						$spamdomains = explode(",",$setting["spamdomains"]);
						foreach($spamdomains as $domain){
							if(preg_match('/([a-zA-Z0-9\-]+)\.'.$domain.'/',$reply)) 	$errors[] = "SPAM Detected!";
						}
					}
					
					if(empty($errors)){
						
						$insert = $db->insert("nreply",array("nid"=>$news,"userid"=>$user->id,"reply"=>$reply,"date"=>$date),"");
						if($insert){
							$_SESSION["message"]="Reply Added Successfully!";
							redir($setting["siteurl"]."/news/$news");
						}
						else {
							echo "Unknown Errror";
						}
					}
					else {
						redir($setting["siteurl"]."/news/$news");
					}
				}
				
			
			}
			
		}
		else {
			/**
			$smarty->assign("title","News ".$setting["sitename"]);
			
			$page = get("page");
			if(empty($page)) 	$page=1;
			$sql=$db->link->query("SELECT count(id) FROM news");
			$coun=$sql->fetch_array();
			$count=$coun[0];
			$start=($page-1)*10;
			$end=10;
			
			$news = $db->select("news","id,title,views,date","","id DESC","$start,$end");
			
			if($news->num_rows<1){
				$smarty->assign("nonews",1);
			}
			else {
				
				$News = array();
				while($result = $news->fetch_array()){
					$News[] = $result;
				}
				$smarty->assign("news",$News);
				
				$init->paging($start,$count,10,$setting["siteurl"]."/news.html?");
				
			}
			template("news.tpl");
			**/
		}
	}
	else {
		echo "News System is disbled by Admin";
	}
?>
	
		
				