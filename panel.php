<?php
	require_once(__DIR__."/server-libs/website.php");
	require_once(__DIR__."/server-libs/validation.php");
	require_once(__DIR__."/server-libs/db.php");
	if(!session_save_path()){
		session_save_path(sys_get_temp_dir());
	}

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
						if(isset($_SESSION["email"]) && strtolower($_SESSION["email"]) == strtolower($email)){
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
	}else if(isset($_POST["unsession"])){
		$original_id = session_id();
		$email = $_SESSION["email"];
		session_abort();

		$sessionNames = scandir(session_save_path());

		foreach ($sessionNames as $sessionName) {
			$sessionName = str_replace("sess_", "", $sessionName);
			if (strpos($sessionName, ".") === false) {
				if($sessionName == $original_id){
					continue;
				}

				session_id($sessionName);
				session_start();
				if(isset($_SESSION["email"]) && strtolower($_SESSION["email"]) == strtolower($email)){
					session_destroy();
				}else{
					session_abort();
				}
			}
		}

		session_id($original_id);
		session_start();
		Website::redirect("panel#settings");
	}else{
		if($user_row["level"] >= 1){
			if(isset($_POST["email"])){
				$target = Validation::getUser($_POST["email"]);

				if($target && $user_row["level"] > $target["level"]){
					if(isset($_POST["delete"])){
						$db->delete("accounts", array(
							":email" => $target["email"]
						));

						$original_id = session_id();
						session_abort();

						$sessionNames = scandir(session_save_path());
		
						foreach ($sessionNames as $sessionName) {
							$sessionName = str_replace("sess_", "", $sessionName);
							if (strpos($sessionName, ".") === false) {
								session_id($sessionName);
								session_start();
								if(isset($_SESSION["email"]) && strtolower($_SESSION["email"]) == strtolower($target["email"])){
									session_destroy();
								}else{
									session_abort();
								}
							}
						}
		
						session_id($original_id);
						session_start();

					}else if(isset($_POST["adminize"]) && $user_row["level"] >= 2){
						$db->update("accounts", array(
							":level" => 1
						),
						array(
							":email" => $target["email"]
						));
					}else if(isset($_POST["deadminize"]) && $user_row["level"] >= 2){
						$db->update("accounts", array(
							":level" => 0
						),
						array(
							":email" => $target["email"]
						));
					}
				}
				Website::redirect("panel#admin");
			}
			$all_dispatchers_rows = $db->select("accounts", array());

			$dispatchers_html_table = '
				<a style="padding: 15px 0;border-radius:50px;" class="positive_button s-table_row" href="register">Register a new account</a>
				<div class="s-table_row s-table_header" style="padding: 15px 0;margin-bottom:20px;">
					<div class="s-table_item">Accounts</div>
					<div class="s-table_footer"></div>
				</div>';

			foreach($all_dispatchers_rows as $dispatcher_row){
				$level_str = $dispatcher_row["level"];

				if($level_str == 0){
					$level_str = "Dispatcher";
				}else if($level_str == 1){
					$level_str = "Admin";
				}else if($level_str == 2){
					$level_str = "Owner";
				}else if($level_str >= 3){
					$level_str = "Super (".$level_str.")";
				}

				$action_buttons = "";

				if($user_row["level"] > $dispatcher_row["level"]){
					$action_buttons .= '<input type="submit" class="negative_button" name="delete" value="Delete">';
					if($user_row["level"] >= 2){
						if($dispatcher_row["level"] == 1){
							$action_buttons .= '<input type="submit" class="positive_button" name="deadminize" value="Deadminize">';
						}else if($dispatcher_row["level"] == 0){
							$action_buttons .= '<input type="submit" class="positive_button" name="adminize" value="Adminize">';
						}
					}
				}

				$dispatchers_html_table .= '
					<form method="POST" class="s-table_row">
						<input type="hidden" name="email" value="'.$dispatcher_row["email"].'">
						<div class="s-table_item">
							<div>Email</div>
							<div>'.$dispatcher_row["email"].'</div>
						</div>
						<div class="s-table_item">
							<div>Level</div>
							<div>'.$level_str.'</div>
						</div>
						<div class="s-table_item">
							<div>Date</div>
							<div>'.$dispatcher_row["creation_date"].'</div>
						</div>
						<div class="s-table_footer">
							'.$action_buttons.'
						</div>
					</form>
				';
			}

			Website::deploy("panel", array(
				"level" => $user_row["level"],
				"dispatchers_html_table" => $dispatchers_html_table
			));
		}else{
			Website::deploy("panel", array(
				"level" => $user_row["level"]
			));
		}
	}
?>