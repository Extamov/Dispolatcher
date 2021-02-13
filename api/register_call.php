<?php
	require_once(__DIR__."/../server-libs/db.php");
	require_once(__DIR__."/../server-libs/validation.php");

	function randomStr(int $length, string $alphabet="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"){
		$result = "";
		for ($i=0; $i < $length; $i++) { 
			$result .= $alphabet[random_int(0, strlen($alphabet)-1)];
		}
		return $result;
	}

	session_start();

	$db = new DBConnection();

	if(!isset($_POST["type"], $_POST["rtc_offer"])){
		die("false");
	}

	$type = $_POST["type"];
	$rtc_offer = $_POST["rtc_offer"];
	$session_id = session_id();
	$ip = $_SERVER['REMOTE_ADDR'];
	
	if(!preg_match("/^(1|2|3)$/", $type)){
		die("false");
	}

	$call_rows = Validation::getCallByUserSID($session_id);
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

	$db->insert("calls", array(
		":id" => randomStr(12),
		":caller_session_id" => $session_id,
		":caller_ip" => $ip,
		":type" => $type,
		":date" => date('Y-m-d H:i:s', time()),
		":offer" => $rtc_offer
	));

	die("true");
?>