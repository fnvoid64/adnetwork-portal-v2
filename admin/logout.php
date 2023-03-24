<?php

	include "init.php";
	
	if(isset($_SESSION["adminid"])){
		unset($_SESSION["adminid"]);
		$_SESSION["message"]=$_LANG["logout"];
		redir("login.php");
	}
	else {
		redir("login.php");
	}
	
?>