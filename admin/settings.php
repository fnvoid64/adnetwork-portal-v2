<?php

	include "init.php";

	if(!$admin->is_admin()){
		redir("login.php");
		exit;
	}

	$module = get("module");

	if($module == "sites"){

		if($admin->data("role")==3){
			$admin->head("Access Denied");
			echo '<section class="content-header">';
			echo '<div class="row">
			<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-heading"><h2 class="panel-title">Access Denied</h2></div>
			<div class="panel-body"><div class="alert alert-danger">Why are you here?!</div>
			</div></div></div></div>';
			$admin->foot();
			exit();
		}

		$admin->head("Sites Settings");
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Site Settings</h2></div>
		<div class="panel-body">';

		if(isset($_POST["sitecats"])){

			$siteCats = post("sitecats");

			if(empty($siteCats)){
				echo "Empty Categorys";
				exit;
			}

			if(preg_match('/,/',$siteCats)){
				if(substr($siteCats,-1)==","){
					$siteCats = substr($siteCats,0,-1);
				}
				$cats = str_replace(",","[%,%]",$siteCats);
			}
			else {
				$cats = $siteCats;
			}

			$update = $db->update("settings",array("sitecats"=>$cats),"");

			if($update){
				echo '<div class="alert alert-success">Successfully Updated!</div>';
			}
			else { echo "CRITICAL_DB_ERROR~!"; }
		}
		echo '<form method="post">
		<div class="form-group">
		<textarea name="sitecats" class="form-control">'.str_replace("[%,%]",",",$setting["sitecats"]).'</textarea>
		<br/>Each Category Separated by comma(,)
		</div>
		<div class="form-group">
		<button type="submit" class="btn btn-success">Update</button>
		</div></form>
		</div></div></div></div>';
	}
	elseif($module == "ads"){

		if($admin->data("role")==3){
			$admin->head("Access Denied!");
			echo '<div class="menu">Sorry but you are not allowed to access this page!</div>';
			$admin->foot();
			exit;
		}

		$admin->head("Ads Settings");
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Ads Settings</h2></div>
		<div class="panel-body">';

		if(isset($_POST["cpc"],$_POST["banners"])){

			$cpc = post("cpc");
			$banners = post("banners");

			$errors = array();

			if(empty($cpc) OR strlen($cpc)<1 OR !is_numeric($cpc)) 	$errors[] = "CPC Invalid";
			if(empty($banners) OR strlen($banners)<1 OR !is_numeric($banners)) 	$errors[] = "Banners Invalid";

			if(empty($errors)){

				$update = $db->update("settings",array("minimumcpc"=>$cpc,"adbanners"=>$banners),"");
				if($update){
					echo '<div class="alert alert-success">Successfully Updated!</div>';
				}
				else {
					echo "CRITICAL_DB_ERROR~!";
				}
			}
			else {
				$admin->error($errors);
			}
		}

		echo '<form method="post">';
		echo '<div class="form-group">
		<label for="cpc">Minimum CPC</label>
		<div class="input-group">
		<span class="input-group-addon">$</span>
		<input type="text" name="cpc" value="'.$setting["minimumcpc"].'" class="form-control"/>
		</div><br/>(Minimum CPC for Creating Ad)</div>';
		echo '<div class="form-group">
		<label for="banners">Banners in Form</label>
		<input type="text" name="banners" value="'.$setting["adbanners"].'" class="form-control"/><br/>(Banners for Creating Ad)
		</div>';
		echo '<div class="form-group"><button type="submit" class="btn btn-success">Update</button></div></form>';
		echo '</div></div></div></div>';

	}
	elseif($module == "clicks"){

		if($admin->data("role")==3){
			$admin->head("Access Denied!");
			echo '<div class="menu">Sorry but you are not allowed to access this page!</div>';
			$admin->foot();
			exit;
		}

		$admin->head("Clicks & Impressions Settings");
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Clicks & Impressions Settings</h2></div>
		<div class="panel-body">';

		if(isset($_POST["invalid_clicks"], $_POST["impressions"], $_POST["htmlads"], $_POST["reftrack"])){

			$redirurl = post("redirurl");
			$redirurl = str_replace('http://',null,$redirurl);

			if(empty($redirurl) OR strlen($redirurl)<1){
				echo "URL EMPTY!~~~!";
				exit;
			}

			$update = $db->update("settings",array("impressions"=>post("impressions"),"invalid_clicks"=>post("invalid_clicks"),"redirurl"=>$redirurl,"htmlads"=>post("htmlads"),"reftrack"=>post("reftrack")),"");
			if($update){
				echo '<div class="alert alert-success">Successfully Updated!</div>';
			}
			else {
				echo "CRITICAL_DB_ERROR~!";
			}
		}
		echo '<form method="post">';
		echo '<div class="form-group">
		<label for="invalid_clicks">Invalid Clicks Counting</label>
		<select name="invalid_clicks" class="form-control">';
		if($setting["invalid_clicks"]==1){
			echo '<option value="1" selected="selected">On</option>';
			echo '<option value="0">Off</option>';
		}
		else {
			echo '<option value="1">On</option>';
			echo '<option value="0" selected="selected">Off</option>';
		}
		echo '</select></div>';
		echo '<div class="form-group">
		<label for="impressions">Impressions</label>
		<select name="impressions" class="form-control">';
		if($setting["impressions"]==1){
			echo '<option value="1" selected="selected">On</option>';
			echo '<option value="0">Off</option>';
		}
		else {
			echo '<option value="1">On</option>';
			echo '<option value="0" selected="selected">Off</option>';
		}
		echo '</select></div>';

		echo '<div class="form-group">
		<label for="reftrack">Referer Tracking (<i>Click Counts Only If Site URL matches in Referer</i>)</label>
		<select name="reftrack" class="form-control">';
		if($setting["reftrack"]==1){
			echo '<option value="1" selected="selected">On</option>';
			echo '<option value="0">Off</option>';
		}
		else {
			echo '<option value="1">On</option>';
			echo '<option value="0" selected="selected">Off</option>';
		}
		echo '</select></div>';

		echo '<div class="form-group">
		<label for="htmlads">HTML Ads Type</label>
		<select name="htmlads" class="form-control">';

		if($setting["htmlads"]==1){
			echo '<option value="1" selected="selected">Listing All Ads</option>';
			echo '<option value="2">1 Link Random Ads</option>';
		}
		else {
			echo '<option value="1">Listing All Ads</option>';
			echo '<option value="2"  selected="selected">1 Link Random Ads</option>';
		}
		echo '</select></div>';


		echo '<div class="form-group">
		<label for="redirurl">Invalid Clicks Redirect URL</label>
		<input type="text" name="redirurl" value="http://'.$setting["redirurl"].'" class="form-control"/>
		<br/>(%ad_link% to redirect to active ad)</div>';

		echo '<div class="form-group">
		<button type="submit" class="btn btn-success">Update</button>
		</div></form>';
		echo '</div></div></div></div>';

	}
	elseif($module == "global"){

		if($admin->data("role")==3){
			$admin->head("Access Denied!");
			echo '<div class="menu">Sorry but you are not allowed to access this page!</div>';
			$admin->foot();
			exit;
		}

		$admin->head("Global Settings");
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Global Settings</h2></div>
		<div class="panel-body">';

		if(isset($_POST["sitename"],$_POST["sitetitle"],$_POST["siteurl"],$_POST["news"])){

			$sitename = post("sitename");
			$siteurl = post("siteurl");
			$siteurl = str_replace("http://",null,$siteurl);
			$sitetitle = post("sitetitle");
			$news = post("news");

			$errors = array();

			if(empty($sitename) OR strlen($sitename)<1) 	$errors[] = "Site name is empty!";
			if(empty($siteurl) OR strlen($siteurl)<1) 	$errors[] = "Site URL is empty!";
			if(empty($sitetitle) OR strlen($sitetitle)<1) 	$errors[] = "Site title is empty!";
			if(empty($news) OR strlen($news)<1) 	$errors[] = "News is empty!";

			if(empty($errors)){

				$update = $db->update("settings",array("sitename"=>$sitename,"siteurl"=>"http://".$siteurl,"sitetitle"=>$sitetitle,"news"=>$news),"");
				if($update){
					echo '<div class="alert alert-success">Updated Successfully!</div>';
				}
				else {
					echo "Unnn";
					exit;
				}
			}
		}

		echo '<form method="post">';
		echo '<div class="form-group">
		<label for="sitename">Site name</label>
		<input type="text" name="sitename" value="'.$setting["sitename"].'" class="form-control"/>
		</div>';
		echo '<div class="form-group">
		<label for="siteurl">Site URL</label>
		<input type="text" name="siteurl" value="'.$setting["siteurl"].'" class="form-control"/>
		</div>';
		echo '<div class="form-group">
		<label for="sitetitle">Home Page Site Title</label>
		<input type="text" name="sitetitle" value="'.$setting["sitetitle"].'" class="form-control"/>
		</div>';
		echo '<div class="form-group">
		<label for="sitetitle">News System</label>
		<select name="news" class="form-control">';
		if($setting["news"]==1){
			echo '<option value="1" selected="selected">On</option>';
			echo '<option value="0">Off</option>';
		}
		else {
			echo '<option value="1">On</option>';
			echo '<option value="0" selected="selected">Off</option>';
		}
		echo '</select></div>';
		echo '<div class="form-group">
		<button type="submit" class="btn btn-success">Update</button>
		</div></form>';
		echo '</div></div></div></div>';
	}
	elseif($module == "admins"){

		if($admin->data("role")!=1){
			$admin->head("Access Denied!");
			echo '<div class="menu">Sorry but you are not allowed to access this page!</div>';
			$admin->foot();
			exit;
		}

		$act = get("act");

		if($act == "add"){

			$admin->head("Add Admin");
			echo '<section class="content-header">';
			echo '<div class="row">
			<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-heading"><h2 class="panel-title">Add Admin</h2></div>
			<div class="panel-body">';

			if(isset($_POST["admin"],$_POST["password"],$_POST["cpassword"],$_POST["email"],$_POST["role"])){

				$adminu = post("admin");
				$password = post("password");
				$cpassword = post("cpassword");
				$email = post("email");
				$role = post("role");

				$errors = array();

				if(empty($adminu) OR strlen($adminu)<1) 			$errors[] = "Admin Username Cannot be Empty!";
				if(empty($password) OR strlen($password)<1) 	$errors[] = "Admin Password Cannot be Empty!";
				if(empty($email) OR strlen($email)<1) 			$errors[] = "Admin Email Cannot be Empty!";
				if(!$admin->valid($adminu)) 						$errors[] = "Admin Username is not valid";
				if($password!=$cpassword) 						$errors[] = "Password & Confirm Password didn't macth";
				if(!$admin->email($email)) 						$errors[] = "Email is not Valid!";
				if($role>3 OR $role<2 OR !is_numeric($role)) 	$errors[] = "Admin Role is Invalid!";
				if($admin->exists(array("admin"=>$adminu))) 		$errors[] = "Admin Already Exists!";

				if(empty($errors)){

					$insert = $db->insert("admins",array("admin"=>$adminu,"password"=>md5($password),"email"=>$email,"role"=>$role),"");

					if($insert){
						echo '<div class="alert alert-success">Admin Added Successfully!</div>';
					}
					else {
						echo "Unknown ERROR!@@@$###";
					}
				}
				else {
					$admin->error($errors);
				}
			}
			echo '<form method="post">
			<div class="form-group">
			<label for="admin">Admin Username</label>
			<input type="text" name="admin" class="form-control"/>
			</div>
			<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" class="form-control">
			</div>
			<div class="form-group">
			<label for="cpassword">Confirm Password</label>
			<input type="password" name="cpassword" class="form-control"/>
			</div>
			<div class="form-group">
			<label for="email">Email</label>
			<input type="text" name="email" class="form-control"/>
			</div>
			<div class="form-group">
			<label for="role">Admin Role</label>
			<select name="role" class="form-control">
			<option value="2">Second Admin</option>
			<option value="3">Third Admin</option>
			</select>
			<br/>Second Admin: Can do everything except Admin Functions.
			<br/>Third Admin: Can do everything except settings.
			</div>
			<div class="form-group">
			<button type="submit" class="btn btn-success">Add Admin</button>
			</div></form>';
			echo '</div></div></div></div>';
		}
		elseif($act == "edit" && isset($_GET["id"])){

			if($admin->gdata(array("id"=>$id),"role")==1){
				echo "Head Admin Cannot be edited!";
				exit();
			}
			$admin->head("Edit Admin");
			echo '<section class="content-header">';
			echo '<div class="row">
			<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-heading"><h2 class="panel-title">Edit Admin</h2></div>
			<div class="panel-body">';

			$id = get("id");
			if(!$admin->exists(array("id"=>$id))){
				echo "Admin Not Found!";
				exit;
			}

			if(isset($_POST["password"],$_POST["cpassword"],$_POST["email"],$_POST["role"])){

				$password = post("password");
				$cpassword = post("cpassword");
				$email = post("email");
				$role = post("role");

				$errors = array();


				if(empty($password) OR strlen($password)<1) 	$errors[] = "Admin Password Cannot be Empty!";
				if(empty($email) OR strlen($email)<1) 			$errors[] = "Admin Email Cannot be Empty!";
				if($password!=$cpassword) 						$errors[] = "Password & Confirm Password didn't macth";
				if(!$admin->email($email)) 						$errors[] = "Email is not Valid!";
				if($role>3 OR $role<2 OR !is_numeric($role)) 	$errors[] = "Admin Role is Invalid!";

				if(empty($errors)){

					$update = $db->update("admins",array("password"=>md5($password),"email"=>$email,"role"=>$role),array("id"=>$id));

					if($update){
						echo '<div class="success">'.$admin->gdata(array("id"=>$id),"admin").' Updated	Successfully!</div>';
					}
					else {
						echo "Unknown ERROR!@@@$###";
					}
				}
				else {
					$admin->error($errors);
				}
			}
			echo '<form method="post">
			<div class="form-group">
			<label for="admin">Admin Username</label>
			<input type="text" value="'.$admin->gdata(array("id" => $id), "admin").'" readonly="readonly" class="form-control"/>
			</div>
			<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" class="form-control">
			</div>
			<div class="form-group">
			<label for="cpassword">Confirm Password</label>
			<input type="password" name="cpassword" class="form-control"/>
			</div>
			<div class="form-group">
			<label for="email">Email</label>
			<input type="text" value="'.$admin->gdata(array("id" => $id), "email").'" readonly="readonly" class="form-control"/>
			</div>
			<div class="form-group">
			<label for="role">Admin Role</label>
			<select name="role" class="form-control">
			<option value="2">Second Admin</option>
			<option value="3">Third Admin</option>
			</select>
			<br/>Second Admin: Can do everything except Admin Functions.
			<br/>Third Admin: Can do everything except settings.
			</div>
			<div class="form-group">
			<button type="submit" class="btn btn-success">Update Admin</button>
			</div></form>';
			echo '</div></div></div></div>';
		}
		elseif($act == "del" && isset($_GET["id"])){

			$admin->head("Delete Admin");
			echo '<div class="title">Delete Admin</div>';

			$id = get("id");
			if(!$admin->exists(array("id"=>$id))){
				echo "Admin Not Found!";
				exit;
			}
			if($admin->gdata(array("id"=>$id),"role")==1){
				echo "Head Admin Cannot be deleted!";
				exit;
			}

			if($_GET["confirm"]=="yes"){

				$delete = $db->link->query("DELETE FROM admins WHERE id='$id'");
				if($delete){
					$_SESSION["message"]="Admin ".$admin->gdata(array("id"=>$id),"admin")." deleted successfully!";
					redir("settings.php?module=admins");
				}
				else {
					echo "UnknownError~DbError";
				}
			}
			else {

				echo '<div class="title">Delete '.$admin->gdata(array("id"=>$id),"admin").'?</div>';
				echo '<div class="menu" align="center">Are you Sure ??<br/><a href="settings.php?module=admins&act=del&id='.$id.'&confirm=yes">Yes, Delete</a> - <a href="settings.php?module=admins&act=details&id='.$id.'">No, Go Back</a></div>';
			}
		}
		elseif($act == "details" && isset($_GET["id"])){

			$admin->head("Admin Details");
			echo '<section class="content-header">';
			echo '<div class="row">
			<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-heading"><h2 class="panel-title">Admin Details</h2></div>
			<div class="panel-body">';

			$id = get("id");
			if(!$admin->exists(array("id"=>$id))){
				echo "Admin Not Found!";
				exit();
			}

			if($admin->gdata(array("id"=>$id),"role")==1){
				$role = '<span class="label label-danger">Head Admin</span>';
			}
			elseif($admin->gdata(array("id"=>$id),"role")==2){
				$role = '<span class="label label-info">Second Admin</span>';
			}
			else {
				$role = '<span class="label label-success">Third Admin</span>';
			}

			echo '<ul class="list-group">
			<li class="list-group-item"><b>Admin Username:</b> '.$admin->gdata(array("id" => $id), "admin").'</li>
			<li class="list-group-item"><b>Admin Email:</b> '.$admin->gdata(array("id" => $id), "email").'</li>
			<li class="list-group-item"><b>Admin Role:</b> '.$role.'</li>';

			if ($admin->gdata(array("id"=>$id), "role") !=1 ){
				echo '<li class="list-group-item">';
				echo '<a href="settings.php?module=admins&act=edit&id='.$id.'" class="btn btn-app"><i class="fa fa-edit"></i> Edit Admin</a>';
				echo '<a href="#" class="btn btn-app" data-toggle="modal" data-target="#delUser"><i class="fa fa-trash-o"></i> Delete Admin</a>';
				echo '</li>';
			}

			echo '</ul>';

			$adminu = $admin->gdata(array("id" => $id), "admin");
			$admine = $admin->gdata(array("id" => $id), "email");

			echo '</ul>';
			echo '<div class="modal fade" tabindex="-1" role="dialog" id="delUser" aria-labelledby="delUserLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete Admin?</h4>
      </div>
      <div class="modal-body">
        <p>Delete User {$adminu} ({$admine})</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <a href="settings.php?module=admins&act=del&id={$id}&confirm=yes"><button type="button" class="btn btn-danger">Delete</button></a>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
			echo '</div></div></div></div>';

		}
		else {

			$admin->head("Admins");
			echo '<section class="content-header">';
			echo '<div class="row">
			<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-heading"><h2 class="panel-title">Admins</h2></div>
			<div class="panel-body">';

			$admins = $db->select("admins","id,admin,role,email","");
			echo '<table class="table table-bordered table-striped table-hover">
			<tr>
				<th>ID #</th>
				<th>Admin Username</th>
				<th>Admin Role</th>
				<th>Admin Email</th>
			</tr>';
			while($result=$admins->fetch_assoc()){
				echo '<tr>';
				echo '<td>'.$result["id"].'</td>';
				echo '<td><a href="settings.php?module=admins&act=details&id='.$result["id"].'"><b>'.$result["admin"].'</b></a></td>';
				if($result["role"]==1){
					$role = "Head Admin";
				}
				elseif($result["role"]==2){
					$role = "Second Admin";
				}
				else {
					$role = "Third Admin";
				}
				echo '<td>'.$role.'</td><td>'.$result["email"].'</td></tr>';
			}
			echo '</table>';
			echo '</div></div></div></div>';
		}
	}
	elseif($module == "refer"){

		if($admin->data("role")==3){
			$admin->head("Access Denied!");
			echo '<div class="menu">Sorry but you are not allowed to access this page!</div>';
			$admin->foot();
			exit;
		}

		$act = get("act");


			$admin->head("Referral Settings");
			echo '<section class="content-header">';
			echo '<div class="row">
			<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-heading"><h2 class="panel-title">Referral Settings</h2></div>
			<div class="panel-body">';

			if(isset($_POST["refer"],$_POST["refamount"])){

				$refer = post("refer");
				$refamount = post("refamount");
				if(empty($refamount) OR strlen($refamount)<1 OR !is_numeric($refamount)){
					echo "Invalid amount";
					exit;
				}
				$update = $db->update("settings",array("refer"=>$refer),"");
				if($update){
					echo '<div class="success">Updated Successfully!</div>';
				}
				else {
					echo "UuUUu";
					exit;
				}
			}

			echo '<form method="post">
			<div class="form-group">
			<label for="refer">Referral Type</label>
			<select name="refer" class="form-control">';

			for($i=1;$i<=2;$i++){
				if($i==$setting["refer"]){
					echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
				}
				else {
					echo '<option value="'.$i.'">'.$i.'</option>';
				}
			}
			echo '</select><br/>';

			echo '
			<b>1</b> - Pay Per Valid Registration.<br/>
			<b>2</b> - Pay After Referred User Earns.
			<div class="form-group">
			<label for="refamount">User Benifits</label>
			<div class="input-group">
			<span class="input-group-addon">$</span>
			<input type="text" name="refamount" value="'.$setting["refamount"].'" class="form-control"/>
			</div></div>
			<div class="form-group">
			<button type="submit" class="btn btn-success">Update</button>
			</div>
			</form>';
			echo '</div></div></div></div>';
	}
	elseif($module == "mail"){

		if($admin->data("role")==3){
			$admin->head("Access Denied!");
			echo '<div class="menu">Sorry but you are not allowed to access this page!</div>';
			$admin->foot();
			exit;
		}

		$admin->head("Mail Settings");
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Mail Settings</h2></div>
		<div class="panel-body">';

		if(isset($_POST["noreply"],$_POST["register"],$_POST["siteadd"],$_POST["adsadd"],$_POST["siteactive"],$_POST["adsactive"],$_POST["sitereject"],$_POST["adreject"],$_POST["userblock"],$_POST["invoicec"],$_POST["invoicep"],$_POST["name"],$_POST["pin"],$_POST["pwreset"])){

			$noReply = post("noreply");
			$register = post("register");
			$siteAdd = post("siteadd");
			$adsAdd = post("adsadd");
			$siteActive = post("siteactive");
			$adsActive = post("adsactive");
			$siteReject = post("sitereject");
			$adReject = post("adreject");
			$userBlock = post("userblock");
			$invoiceC = post("invoicec");
			$invoiceP = post("invoicep");

			$update = $db->update("mail",array("noreply"=>$noReply,"register"=>$register,"siteadd"=>$siteAdd,"adsadd"=>$adsAdd,"siteactive"=>$siteActive,"adsactive"=>$adsActive,"sitereject"=>$siteReject,"adreject"=>$adReject,"userblock"=>$userBlock,"invoicec"=>$invoiceC,"invoicep"=>$invoiceP,"name"=>post("name"),"pwreset"=>post("pwreset"),"pin"=>post("pin")),"");

			if($update){
				echo '<div class="alert alert-success">Mail Templates Updated Successfully!</div>';
			}
			else {
				echo "U";
			}
		}

		$mailS = $db->select("mail","","");
		$mails = $mailS->fetch_assoc();

		echo '<form method="post">';
		echo '<div class="form-group">
		<label for="noreply">Email Sender</label>
		<input type="text" name="noreply" value="'.$mails["noreply"].'" class="form-control"/>
		</div>';
		echo '<div class="form-group">
		<label for="name">Email Sender Name: (Ex. My Adnetwork)</label>
		<input type="text" name="name" value="'.$mails["name"].'" class="form-control"/>
		</div>';
		echo '<div class="form-group">
		<label for="register">Register Email Template</label>
		<textarea name="register" class="form-control">'.$mails["register"].'</textarea>
		<br/>%username% = Username, %date% = Date, %verify_link% = Verify Link
		</div>';
		echo '<div class="form-group">
		<label for="pwreset">Password Reset Email Template</label>
		<textarea name="pwreset" class="form-control">'.$mails["pwreset"].'</textarea>
		<br/>%username% = Username, %date% = Date, %reset_link% = Reset Password Link
		</div>';
		echo '<div class="form-group">
		<label for="pin">New PIN Email Template</label>
		<textarea name="pin" class="form-control">'.$mails["pin"].'</textarea>
		<br/>%username% = Username, %date% = Date, %pin% = New PIN
		</div>';
		echo '<div class="form-group">
		<label for="siteadd">Site Added Email Template</label>
		<textarea name="siteadd" class="form-control">'.$mails["siteadd"].'</textarea>
		<br/>%username% = Username, %date% = Date,%sitename% = Site Name, %siteurl% = Site URL
		</div>';
		echo '<div class="form-group">
		<label for="adsadd">Advertise Added Email Template</label>
		<textarea name="adsadd" class="form-control">'.$mails["adsadd"].'</textarea>
		<br/>%username% = Username, %date% = Date,%adname% = Advertise Name, %adurl% = Advertise URL
		</div>';
		echo '<div class="form-group">
		<label for="siteactive">Site Active Email Template</label>
		<textarea name="siteactive" class="form-control">'.$mails["siteactive"].'</textarea>
		<br/>%username% = Username, %date% = Date,%sitename% = Site Name, %siteurl% = Site URL
		</div>';
		echo '<div class="form-group">
		<label for="adsactive">Advertise Active Email Template</label>
		<textarea name="adsactive" class="form-control">'.$mails["adsactive"].'</textarea>
		<br/>%username% = Username, %date% = Date,%adname% = Ad Name, %adurl% = Ad URL
		</div>';
		echo '<div class="form-group">
		<label for="sitereject">Site Rejected Email Template</label>
		<textarea name="sitereject" class="form-control">'.$mails["sitereject"].'</textarea>
		<br/>%username% = Username, %date% = Date,%sitename% = Site Name, %siteurl% = Site URL
		</div>';
		echo '<div class="form-group">
		<label for="adreject">Advertise Rejected Email Template</label>
		<textarea name="adreject" class="form-control">'.$mails["adreject"].'</textarea>
		<br/>%username% = Username, %date% = Date,%adname% = Ad Name, %adurl% = Ad URL
		</div>';
		echo '<div class="form-group">
		<label for="userblock">User Blocked Email Template</label>
		<textarea name="userblock" class="form-control">'.$mails["userblock"].'</textarea>
		<br/>%username% = Username, %date% = Date
		</div>';
		echo '<div class="form-group">
		<label for="invoicec">Invoice Created Email Template</label>
		<textarea name="invoicec" class="form-control">'.$mails["invoicec"].'</textarea>
		<br/>%username% = Username, %date% = Date,%invoice% = Invoice
		</div>';
		echo '<div class="form-group">
		<label for="invoicep">Invoice Paid Email Template</label>
		<textarea name="invoicep" class="form-control">'.$mails["invoicep"].'</textarea>
		<br/>%username% = Username, %date% = Date,%invoice% = Invoice
		</div>';

		echo '<div class="form-group">
		<button type="submit" class="btn btn-success">Update</button>
		</div>
		</form>';

		echo '</div></div></div></div>';
	}
	elseif($module == "changePassword"){

		$admin->head("Change Password");
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Change Password</h2></div>
		<div class="panel-body">';

		if(isset($_POST["oldpassword"],$_POST["newpassword"],$_POST["newpassword2"])){

			$oldpassword = post("oldpassword");
			$newpassword = post("newpassword");
			$newpassword2 = post("newpassword2");

			$errors = array();

			if($admin->data("password")!=md5($oldpassword)) 	$errors[] = "Old Password is Invalid!";
			if(empty($newpassword) OR strlen($newpassword)<1) 	$errors[] = "New Password Can't be Empty!";
			if($newpassword!=$newpassword2) 	$errors[] = "Passwords didn't match!";

			if(empty($errors)){

				if($db->update("admins",array("password"=>md5($newpassword)),array("id"=>$admin->id))){
					$_SESSION["message"]="Password Changed Successfully!";
					unset($_SESSION["adminid"]);
					redir("login.php");
				}
				else {
					echo "Unnn";
				}
			}
			else {
				$admin->error($errors);
			}
		}

		echo '<form method="post">';
		echo '<div class="form-group">
		<label for="oldpassword">Old Password</label>
		<input type="password" name="oldpassword" class="form-control"/>
		</div>
		<div class="form-group">
		<label for="newpassword">New Password</label>
		<input type="password" name="newpassword" class="form-control"/>
		</div>
		<div class="form-group">
		<label for="newpassword2">Verify New Password</label>
		<input type="password" name="newpassword2" class="form-control"/>
		</div>
		<div class="form-group"><button type="submit" class="btn btn-success">Update Password</button></div>
		</form>';

		echo '</div></div></div></div>';
	}
	elseif($module == "styles"){

		if($admin->data("role")==3){
			$admin->head("Access Denied!");
			echo '<div class="menu">Sorry but you are not allowed to access this page!</div>';
			$admin->foot();
			exit;
		}

		$admin->head("Template & Styles");
		echo '<section class="content-header">';
		echo '<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div class="panel-heading"><h2 class="panel-title">Template & Styles Settings</h2></div>
		<div class="panel-body">';

		$admin->message();

		if(isset($_POST["template"],$_POST["webtemp"],$_POST["mweb"])){

		$template = post("template");
		$webtemp = post("webtemp");
		$mweb = post("mweb");

		if(!$admin->template_exists($template)){
			echo "Template Not Found!";
			exit();
			}
			if(!$admin->template_exists($webtemp)){
				echo "Template Not Found!";
				exit;
			}

			if($db->update("settings",array("template"=>$template,"mweb"=>$mweb,"webtemp"=>$webtemp),"")){
				$_SESSION["message"]="Template Updated Successfully!";
				redir("settings.php?module=styles");
			}
			else {
				echo 'Wrororor';
				exit;
			}
			}
			echo '<form method="post">';
			echo '<div class="form-group">
			<label for="mweb">Web & Mobile Detected Template</label>
			<select name="mweb" class="form-control" onchange="showExtra(this.value);">';
			if($setting["mweb"]==1){
				echo '<option value="1" selected="selected">On</option>';
				echo '<option value="0">Off</option>';
			}
			else {
				echo '<option value="1">On</option>';
				echo '<option value="0" selected="selected">Off</option>';
			}
			echo '</select></div>';
			echo '<div class="form-group">
			<label for="webtemp">Web Template</label>
			<select name="webtemp" class="form-control">';

			foreach(glob(ROOT."/templates/*",GLOB_ONLYDIR) as $templ){
				if($setting["webtemp"]==basename($templ)){
					echo '<option value="'.basename($templ).'" selected="selected">'.basename($templ).'</option>';
				}
				else {
					echo '<option value="'.basename($templ).'">'.basename($templ).'</option>';
				}
			}

			echo '</select></div>';
			if ($setting["mweb"] == 1) {
				echo '<div class="form-group" id="mobile">';
			} else {
				echo '<div class="form-group" style="display: none;" id="mobile">';
			}
			echo '<label for="template">Mobile Template</label>
			<select name="template" class="form-control">';
			foreach(glob(ROOT."/templates/*",GLOB_ONLYDIR) as $templ){
				if($setting["template"]==basename($templ)){
					echo '<option value="'.basename($templ).'" selected="selected">'.basename($templ).'</option>';
				}
				else {
					echo '<option value="'.basename($templ).'">'.basename($templ).'</option>';
				}
			}
			echo '</select></div>';
			echo '<div class="form-group">
			<button type="submit" class="btn btn-success">Update</button>
			</div></form>';

			echo '</div></div></div></div>';

			echo <<<HTML
<script>
function showExtra(value)
{
	if (value == 1) {
		document.getElementById("mobile").style = "";
	} else {
		document.getElementById("mobile").style = "display: none;";
	}
}
</script>
HTML;

	}
	else {
		header('Location: ./');
	}

	$admin->foot();

?>
