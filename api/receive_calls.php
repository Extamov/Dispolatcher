<?php
	require_once(__DIR__."/../server-libs/db.php");
	require_once(__DIR__."/../server-libs/validation.php");

	session_start();

	$db = new DBConnection();

	if(isset($_SESSION["email"])){
		$user_row = Validation::getUser($_SESSION["email"]);
		if(!$user_row){
			session_destroy();
			die("false");
		}
	}

	$call_rows = $db->select("calls", array());

	$rows = [];
	foreach($call_rows as $call_row){
		if(time()-strtotime($call_row["date"]) > 50){
			$db->delete("calls", array(
				":id" => $call_row["id"]
			));
		}else if(!$call_row["answer"]){
			array_push($rows, array(
				"id" => $call_row["id"],
				"date" => $call_row["date"],
				"type" => $call_row["type"],
				"caller_ip" => $call_row["caller_ip"],
				"offer" => $call_row["offer"]
			));
		}
	}
	die(json_encode($rows));
?>