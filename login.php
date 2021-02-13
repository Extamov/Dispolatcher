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
		
		Website::redirect("panel");
	}else if(isset($_POST["email"], $_POST["pass"])){
		$email = $_POST["email"];
		$password = $_POST["pass"];

		if(!Validation::validEmail($email)){
			Website::deploy("login", array(
				"error" => "Invalid email."
			));
		}else if(!Validation::validPassword($password)){
			Website::deploy("login", array(
				"error" => "Password's length must be between 6 and 100."
			));
		}else if(!Validation::authenticate($email, $password)){
			Website::deploy("login", array(
				"error" => "Authentication Failed."
			));
		}else{
			$_SESSION["email"] = $email;
			Website::redirect("panel");
		}
	}else{
		Website::deploy("login");
	}
?>