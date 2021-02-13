<?php
	require_once("server-libs/website.php");
	require_once("server-libs/db.php");

	$db = new DBConnection();

	if(isset($_POST["email"])){
		Website::deploy("resetlogin");
	}else{
		Website::deploy("resetlogin");
	}
?>