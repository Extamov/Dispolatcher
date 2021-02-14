<?php
	require_once(__DIR__."/db.php");

	class Validation{
		private static $emailPattern = "/^[a-zA-Z0-9,.-_]{2,15}@[a-zA-Z0-9,.-_]{2,15}\\.[a-zA-Z]{2,10}$/";
		private static $passwordPattern = "/^.{6,100}$/";
		private static $db = null;

		public static function validEmail(string $email){
			return preg_match(self::$emailPattern, $email);
		}

		public static function validPassword(string $password){
			return preg_match(self::$passwordPattern, $password);
		}

		public static function getCallByUserSID(string $sid){
			if(self::$db === null){
				self::$db = new DBConnection();
			}

			$call_rows = self::$db->select("calls", array(
				":caller_session_id" => $sid
			));
			if(count($call_rows) > 0){
				return $call_rows;
			}else{
				return false;
			}
		}

		public static function getCallsByIP(string $ip){
			if(self::$db === null){
				self::$db = new DBConnection();
			}

			$call_rows = self::$db->select("calls", array(
				":caller_ip" => $ip
			));
			if(count($call_rows) > 0){
				return $call_rows;
			}else{
				return false;
			}
		}

		public static function getCallByDispatcherEmail(string $email){
			if(self::$db === null){
				self::$db = new DBConnection();
			}

			$call_rows = self::$db->select("calls", array(
				":dispatcher_email" => $email
			));
			if(count($call_rows) > 0){
				return $call_rows;
			}else{
				return false;
			}
		}

		public static function getUser(string $email){
			if(self::$db === null){
				self::$db = new DBConnection();
			}
			
			$user_rows = self::$db->select("accounts", array(":email" => $email));

			if(count($user_rows) == 1){
				return $user_rows[0];
			}else{
				return false;
			}
		}

		public static function authenticate(string $email, string $password){
			if(self::$db === null){
				self::$db = new DBConnection();
			}
			
			$user_rows = self::$db->select("accounts", array(":email" => $email));

			if(count($user_rows) == 1){
				$user_row = $user_rows[0];

				return password_verify($password, $user_row["pass"]);
			}else{
				return false;
			}
		}
	}
?>