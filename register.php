<?php
	require_once(__DIR__."/server-libs/website.php");
	require_once(__DIR__."/server-libs/validation.php");
	require_once(__DIR__."/server-libs/db.php");

	session_start();

	$db = new DBConnection();

	$user_rows_checkifany = $db->select("accounts");

	if(count($user_rows_checkifany) == 0){
		if(isset($_POST["email"], $_POST["pass"])){
			$email = $_POST["email"];
			$password = $_POST["pass"];
	
			if(!Validation::validEmail($email)){
				Website::deploy("register", array(
					"error" => "Invalid email."
				));
			}else if(!Validation::validPassword($password)){
				Website::deploy("register", array(
					"error" => "Password's length must be between 6 and 100."
				));
			}else if(Validation::getUser($email)){
				Website::deploy("register", array(
					"error" => "Email already exists."
				));
			}else{
				$db->insert("accounts", array(
					":email" => $email,
					":pass" => password_hash($password, PASSWORD_DEFAULT),
					":level" => 2
				));
				Website::redirect("login");
			}
		}else{
			Website::deploy("register", array(
				"first_user" => true
			));
		}
	}

	if(isset($_SESSION["email"])){
		$user_row = Validation::getUser($_SESSION["email"]);
		if(!$user_row){
			session_destroy();
			Website::redirect("login");
		}
		
		if($user_row["level"] >= 1){
			if(isset($_POST["email"], $_POST["pass"])){
				$email = $_POST["email"];
				$password = $_POST["pass"];
		
				if(!Validation::validEmail($email)){
					Website::deploy("register", array(
						"error" => "Invalid email."
					));
				}else if(!Validation::validPassword($password)){
					Website::deploy("register", array(
						"error" => "Password's length must be between 6 and 100."
					));
				}else if(Validation::getUser($email)){
					Website::deploy("register", array(
						"error" => "Email already exists."
					));
				}else{
					$db->insert("accounts", array(
						":email" => $email,
						":pass" => password_hash($password, PASSWORD_DEFAULT)
					));
					Website::redirect("panel#admin");
				}
			}else{
				Website::deploy("register");
			}
		}else{
			Website::redirect("panel");
		}
	}else{
		Website::redirect("login");
	}
?>