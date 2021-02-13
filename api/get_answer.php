<?php
	require_once(__DIR__."/../server-libs/db.php");
	require_once(__DIR__."/../server-libs/validation.php");

	session_start();

	$db = new DBConnection();

	$session_id = session_id();

	$call_rows = Validation::getCallByUserSID($session_id);
	if($call_rows){
		$call_row = $call_rows[0];
		if($call_row["answer"]){
			die($call_row["answer"]);
		}else if(time()-strtotime($call_row["date"]) < 50){
			die("waiting");
		}else{
			$db->delete("calls", array(
				":id" => $call_row["id"]
			));
			die("timeout");
		}
	}else{
		die("false");
	}
?>