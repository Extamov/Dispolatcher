<?php
	require_once(__DIR__."/../server-libs/db.php");
	require_once(__DIR__."/../server-libs/validation.php");

	session_start();

	$db = new DBConnection();

	$session_id = session_id();

	if(isset($_SESSION["email"])){
		$user_row = Validation::getUser($_SESSION["email"]);
		if(!$user_row){
			session_destroy();
			die("false");
		}
	}

	if(isset($_POST["id"], $_POST["answer"])){
		$call_rows = Validation::getCallByDispatcherEmail($_SESSION["email"]);
		if($call_rows){
			$call_row = $call_rows[0];
			if(time()-strtotime($call_row["date"]) > 50){
				$db->delete("calls", array(
					":id" => $call_row["id"]
				));
			}else{
				die("false");
			}
		}

		$call_rows = $db->select("calls", array(
			":id" => $_POST["id"]
		));
		if($call_rows){
			$call_row = $call_rows[0];
			$db->update("calls", array(
				":answer" => $_POST["answer"],
				":dispatcher_email" => $_SESSION["email"],
				":date" => date('Y-m-d H:i:s', time()),
			), array(
				":id" => $call_row["id"]
			));
			die("true");
		}else{
			die("false");
		}
	}
?>