<?php
	require_once(__DIR__."/server-libs/website.php");
	require_once(__DIR__."/server-libs/db.php");
	require_once(__DIR__."/server-libs/mail.php");
	require_once(__DIR__."/server-libs/validation.php");
	if(!session_save_path()){
		session_save_path(sys_get_temp_dir());
	}

	function randomStr(int $length, string $alphabet="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"){
		$result = "";
		for ($i=0; $i < $length; $i++) { 
			$result .= $alphabet[random_int(0, strlen($alphabet)-1)];
		}
		return $result;
	}

	session_start();

	$db = new DBConnection();

	
	if(isset($_POST["id"], $_POST["pass"])){
		$id = $_POST["id"];
		$password = $_POST["pass"];

		$resetpass_requests = $db->select("resetpass", array(":id" => $id));

		if(count($resetpass_requests) >= 1){
			$resetpass_request = $resetpass_requests[0];

			if(time()-strtotime($resetpass_request["date"]) >= 60*15){
				$db->delete("resetpass", array(":id" => $id));
				Website::redirect("resetlogin");
			}

			if(!Validation::validPassword($password)){
				Website::deploy("resetpass", array(
					"id" => $_GET["id"],
					"error" => "Invalid password"
				));
			}

			$db->delete("resetpass", array(":id" => $id));
			$db->update("accounts", array(
				":pass" => password_hash($password, PASSWORD_DEFAULT)
			), array(
				":email" => $resetpass_request["email"]
			));


			session_destroy();

			$sessionNames = scandir(session_save_path());

			foreach ($sessionNames as $sessionName) {
				$sessionName = str_replace("sess_", "", $sessionName);
				if (strpos($sessionName, ".") === false) {
					session_id($sessionName);
					session_start();
					if(isset($_SESSION["email"]) && strtolower($_SESSION["email"]) == strtolower($resetpass_request["email"])){
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
			Website::redirect("resetlogin");
		}
	}else if(isset($_GET["id"])){
		$id = $_GET["id"];
		$resetpass_requests = $db->select("resetpass", array(":id" => $id));

		if(count($resetpass_requests) >= 1){
			$resetpass_request = $resetpass_requests[0];

			if(time()-strtotime($resetpass_request["date"]) >= 60*15){
				$db->delete("resetpass", array(":id" => $id));
				Website::redirect("resetlogin");
			}

			Website::deploy("resetpass", array(
				"id" => $_GET["id"]
			));
		}else{
			Website::redirect("resetlogin");
		}
	}else{
		Website::redirect("resetlogin");
	}
?>