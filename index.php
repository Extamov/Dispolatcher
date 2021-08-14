<?php
	require_once(__DIR__."/server-libs/website.php");
	require_once(__DIR__."/server-libs/validation.php");

	session_start();

	if(isset($_SESSION["email"])){
		$user_row = Validation::getUser($_SESSION["email"]);
		if(!$user_row){
			session_destroy();
			Website::deploy("index");
		}

		Website::deploy("panel");
	}

	Website::deploy("index");
?>