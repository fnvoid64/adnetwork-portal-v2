<?php

	ini_set('MEMORY_LIMIT',-1);
	ini_set('MAX_EXECUTION_TIME',800);

	include "init.php";
	
	if(!$admin->is_admin()){
		redir("login.php");
		exit;
	}
	
	include (ROOT."/includes/phpmailer/PHPMailerAutoload.php");
	$mail = new PHPMailer();
	
	$act = get("act");
	
	if($act == "user" && isset($_GET["username"])){
	
		$admin->head("Send Email to ".get("username"));
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Send Email to '.get("username").'</h2></div>
		<div class="panel-body">';
	
		$username = get("username");
		$checkUserName = $db->select("users","email",array("username"=>$username));
		if($checkUserName->num_rows<1){
			echo "User not found";
			exit;
		}
		$userMail = $checkUserName->fetch_assoc();
		
		if(isset($_POST["subject"],$_POST["message"])){
		
			$subject = post("subject");
			$message = post("message");
			
			if(empty($subject) OR empty($message)){
				echo "Must not empty";
				exit;
			}
			
			$subject = str_replace("%username%",$username,$subject);
			$message = str_replace("%username%",$username,$message);
			$subject = str_replace("%date%",$date,$subject);
			$message = str_replace("%date%",$date,$message);
			
			$subject = stripslashes($subject);
			$message = stripslashes($message);
			
		
		
			$Mail = $db->select("mail","","");
			$mails = $Mail->fetch_assoc();
			$mail->setFrom($mails["noreply"],$mails["name"]);
			$mail->addReplyTo($mails["noreply"],$mails["name"]);
			$mail->addAddress($userMail["email"]);
			$mail->Subject = $subject;
			$mail->msgHTML($message);
			$mail->AltBody = $message;
			
			if(!$mail->send()){
				echo '<div class="error">Email is not sent! '.$mail->ErrorInfo.'</div>';
			}
			else {
				echo '<div class="alert alert-success">Email Successfully Sent!</div>';
			}
		}
		
		echo '<form method="post">';
		echo '<div class="form-group"><label for="user">To User</label><input type="text" value="'.$username.'" readonly="readonly" class="form-control"/></div>';
		echo '<div class="form-group"><label for="subject">Subject</label><input type="text" name="subject" class="form-control"/></div>';
		echo '<div class="form-group"><label for="message">Message</label><textarea name="message" class="form-control"></textarea></div>';
		echo '<div class="form-group"><button type="submit" class="btn btn-success">Send Mail</button></div></form>';
		echo '<div class="alert alert-info">Available Variables:<br/> %username% = username<br/>%date% = Current Date</div>';
		echo '</div></div></div></div>';
		
	}
	elseif($act == "all"){
	
		$users = $db->select("users","email",array("status"=>1));
		
		$admin->head("Send Email to All User");
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Send Email to All Users</h2></div>
		<div class="panel-body">';
		
		if(isset($_POST["subject"],$_POST["message"])){
		
			$subject = post("subject");
			$message = post("message");
			
			if(empty($subject) OR empty($message)){
				echo "Must not empty";
				exit;
			}
			
			$subject = str_replace("%username%",$username,$subject);
			$message = str_replace("%username%",$username,$message);
			$subject = str_replace("%date%",$date,$subject);
			$message = str_replace("%date%",$date,$message);
			
			$subject = stripslashes($subject);
			$message = stripslashes($message);
			
		
		
			$Mail = $db->select("mail","","");
			$mails = $Mail->fetch_assoc();
			$mail->setFrom($mails["noreply"],$mails["name"]);
			$mail->addReplyTo($mails["noreply"],$mails["name"]);
			$mail->Subject = $subject;
			$mail->msgHTML($message);
			$mail->AltBody = $message;
			
			while($userMail=$users->fetch_assoc()){
			
				$mail->addAddress($userMail["email"]);
			
				if(!$mail->send()){
					echo '<font color="#ff5500">Email Not Sent To '.$userMail["email"].' ('.$mail->ErrorInfo.')</font><br/>';
				}
				else {
					echo '<font color="green">Email Sent To '.$userMail["email"].'</font><br/>';
				}
				$mail->clearAddresses();
			}
			
		}
		else {
		echo '<form method="post">';
		echo '<div class="form-group"><label for="user">To User</label><input type="text" value="All" readonly="readonly" class="form-control"/></div>';
		echo '<div class="form-group"><label for="subject">Subject</label><input type="text" name="subject" class="form-control"/></div>';
		echo '<div class="form-group"><label for="message">Message</label><textarea name="message" class="form-control"></textarea></div>';
		echo '<div class="form-group"><button type="submit" class="btn btn-success">Send Mail</button></div></form>';
		echo '<div class="alert alert-info">Available Variables:<br/> %username% = username<br/>%date% = Current Date</div>';
		echo '</div></div></div></div>';
			
		}	
	}
	else {
	
		$admin->head("Send Mail");
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Send Email</h2></div>
		<div class="panel-body">';
		echo '<form method="get" action="mail.php">
		<input type="hidden" name="act" value="user">
		<div class="form-group">
		<label for="username">Send Mail To User By Username</label>
		<input type="text" name="username" class="form-control"/>
		</div>
		<div class="form-group"><button type="submit" class="btn btn-success">Send Email</button></div></form>';
		echo '<div style="border-top: 1px solid #ddd;margin-top: 10px; padding: 8px"><a href="?act=all">Send Mail To All Users</a></div>';
		echo '</div></div></div></div>';
	}
	
	$admin->foot();
	
?>
		
		
		
	
		