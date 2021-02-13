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

	if(isset($_POST["id"])){
		$call_rows = $db->select("calls", array(
			":id" => $_POST["id"]
		));
		if($call_rows){
			$call_row = $call_rows[0];
			if(!$call_row["answer"]){
				$db->delete("calls", array(
					":id" => $call_row["id"]
				));
				die("true");
			}else{
				die("true");
			}
		}else{
			die("false");
		}
	}

	if(isset($_SESSION["email"])){
		$call_rows = Validation::getCallByDispatcherEmail($_SESSION["email"]);
		if($call_rows){
			$call_row = $call_rows[0];
			$db->delete("calls", array(
				":id" => $call_row["id"]
			));
			die("true");
		}else{
			die("false");
		}
	}else{
		$call_rows = Validation::getCallByUserSID($session_id);
		if($call_rows){
			$call_row = $call_rows[0];
			$db->delete("calls", array(
				":id" => $call_row["id"]
			));
			die("true");
		}else{
			die("false");
		}
	}
?>