<?php
	require_once("server-libs/website.php");
	require_once("server-libs/validation.php");

	session_start();

	if(isset($_SESSION["email"])){
		$user_row = Validation::getUser($_SESSION["email"]);
		if(!$user_row){
			session_destroy();
			Website::redirect("login");
		}
		
		Website::deploy("panel", array(
			"level" => $user_row["level"]
		));
	}else{
		Website::redirect("login");
	}
?>