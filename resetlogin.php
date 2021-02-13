<?php
	require_once(__DIR__."/server-libs/website.php");
	require_once(__DIR__."/server-libs/db.php");

	session_start();

	$db = new DBConnection();

	if(isset($_POST["email"])){
		Website::deploy("resetlogin");
	}else{
		Website::deploy("resetlogin");
	}
?>