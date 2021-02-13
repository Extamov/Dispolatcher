<?php
	class Website{
		public static function deploy(string $page_name, array $site_params = array()){
			require(sprintf("templates/pages/%s.php", $page_name));
			exit();
		}
		public static function redirect(string $destination){
			header("Location: ".$destination);
			exit();
		}
	}
?>