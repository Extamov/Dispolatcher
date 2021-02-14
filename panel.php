<?php
	require_once(__DIR__."/server-libs/website.php");
	require_once(__DIR__."/server-libs/validation.php");
	require_once(__DIR__."/server-libs/db.php");

	$db = new DBConnection();

	session_start();

	$user_row = "";

	if(isset($_SESSION["email"])){
		$user_row = Validation::getUser($_SESSION["email"]);
		if(!$user_row){
			session_destroy();
			Website::redirect("login");
		}
	}else{
		Website::redirect("login");
	}

	if(isset($_POST["old_pass"], $_POST["new_pass"])){
		$email = $_SESSION["email"];
		$old_pass = $_POST["old_pass"];
		$new_pass = $_POST["new_pass"];
		if(Validation::authenticate($email, $old_pass)){
			if(Validation::validPassword($new_pass)){
				$db->update("accounts", array(
					":pass" => password_hash($new_pass, PASSWORD_DEFAULT)
				), array(
					":email" => $email
				));

				session_destroy();

				$sessionNames = scandir(session_save_path());

				foreach ($sessionNames as $sessionName) {
					$sessionName = str_replace("sess_", "", $sessionName);
					if (strpos($sessionName, ".") === false) {
						session_id($sessionName);
						session_start();
						if(strtolower($_SESSION["email"]) == strtolower($email)){
							session_destroy();
						}else{
							session_abort();
						}
					}
				}

				session_id(session_create_id());
				session_start();

				Website::redirect("login");
			}else{
				Website::deploy("panel", array(
					"error" => "Invalid password",
					"level" => $user_row["level"]
				));
			}
		}else{
			Website::deploy("panel", array(
				"error" => "Incorrect password",
				"level" => $user_row["level"]
			));
		}
	}else{
		Website::deploy("panel", array(
			"level" => $user_row["level"]
		));
	}
?>