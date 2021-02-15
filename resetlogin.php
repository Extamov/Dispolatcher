<?php
	require_once(__DIR__."/server-libs/website.php");
	require_once(__DIR__."/server-libs/db.php");
	require_once(__DIR__."/server-libs/mail.php");
	require_once(__DIR__."/server-libs/validation.php");

	function randomStr(int $length, string $alphabet="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"){
		$result = "";
		for ($i=0; $i < $length; $i++) { 
			$result .= $alphabet[random_int(0, strlen($alphabet)-1)];
		}
		return $result;
	}

	session_start();

	$db = new DBConnection();

	if(isset($_POST["email"])){
		$email = $_POST["email"];

		if(!Validation::validEmail($email)){
			Website::deploy("resetlogin", array(
				"error" => "Invalid email."
			));
		}else if(!Validation::getUser($email)){
			Website::deploy("resetlogin", array(
				"error" => "If your email exists, a message was sent, go to your inbox."
			));
		}

		$reset_id = randomStr(24);

		$resetpass_requests = $db->select("resetpass", array(":email" => $email));

		if(count($resetpass_requests) >= 1){
			$resetpass_request = $resetpass_requests[0];
			if(time()-strtotime($resetpass_request["date"]) < 60*15){
				Website::deploy("resetlogin", array(
					"error" => "Ay slow down homie, you are sending way too fast, wait 15 minutes before trying again."
				));
			}else{
				$db->update("resetpass", array(
					":id" => $reset_id,
					":date" => date('Y-m-d H:i:s', time())
				), array(
					":email" => $email
				));
			}
		}else{
			$db->insert("resetpass", array(
				":id" => $reset_id,
				":date" => date('Y-m-d H:i:s', time()),
				":email" => $email
			));
		}


		$mailsystem = new MailSystem();

		$status = $mailsystem->sendEmail($email, "Password reset", "
Hello ".$email."

You recently requested to reset your password for your Dispatcher account.
Click the link below to reset your password.

https://".$_SERVER["SERVER_NAME"]."/resetpass?id=".$reset_id."

If you did not request a password reset, please ignore this email or reply to let us know.
The password reset is only valid for the next 15 minutes.

Thanks.
- Dispolatcher Team.");

		if($status){
			Website::deploy("resetlogin", array(
				"error" => "If your email exists, a message was sent, go to your inbox."
			));
		}else{
			Website::deploy("resetlogin", array(
				"error" => "An error has occoured while trying to send an email, try again."
			));
		}
	}else{
		Website::deploy("resetlogin");
	}
?>