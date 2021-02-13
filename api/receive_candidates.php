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

	if(isset($_SESSION["email"])){
		$call_rows = $db->select("calls", array(
			":dispatcher_email" => $_SESSION["email"]
		));
		if($call_rows){
			$call_row = $call_rows[0];
			if(time()-strtotime($call_row["date"]) > 50){
				$db->delete("calls", array(
					":id" => $call_row["id"]
				));
				die("false");
			}

			if(!$call_row["caller_candidates"]){
				die("waiting");
			}else{
				die($call_row["caller_candidates"]);
			}
		}else{
			die("false");
		}
	}else{
		$call_rows = $db->select("calls", array(
			":caller_session_id" => $session_id
		));
		if($call_rows){
			$call_row = $call_rows[0];
			if(time()-strtotime($call_row["date"]) > 50){
				$db->delete("calls", array(
					":id" => $call_row["id"]
				));
				die("timeout");
			}

			if(!$call_row["dispatcher_candidates"]){
				die("waiting");
			}else{
				$db->delete("calls", array(
					":id" => $call_row["id"]
				));
				die($call_row["dispatcher_candidates"]);
			}
		}else{
			die("false");
		}
	}
?>