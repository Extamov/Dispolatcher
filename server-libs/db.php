<?php
	class DBConnection{
		private $db_con;

		function __construct(){
			$this->db_con = new PDO("mysql:host=localhost;dbname=dispolatcher", "root", "extzapy");
		}

		function __destruct(){
			$this->db_con = null;
		}

		function select(string $table_name, array $where_dict, string $where_conditions = ""){
			$sql_query_values = "";

			$where_conditions_arr = explode(" ", $where_conditions);

			$where_dict_keys = array_keys($where_dict);

			for ($i = 0; $i < count($where_dict); $i++) { 
				$key = $where_dict_keys[$i];
				$key_clean = str_replace(":", "", $key);

				$sql_query_values .= sprintf("%s=%s ", $key_clean, $key);

				if($i+1 < count($where_dict)){
					if($where_conditions){
						$sql_query_values .= $where_conditions_arr[$i]." ";
					}else{
						$sql_query_values .= "AND ";
					}
				}
			}


			$sql = sprintf("SELECT * FROM `%s` WHERE %s", $table_name, $sql_query_values);

			$statement = $this->db_con->prepare($sql);

			$statement->execute($where_dict);

			return $statement->fetchAll();
		}

		function insert(string $table_name, array $data_dict){
			$sql_query_values_col = "";
			$sql_query_values_data = "";

			foreach ($data_dict as $key => $value) {
				$key_clean = str_replace(":", "", $key);

				$sql_query_values_col .= $key_clean.",";
				$sql_query_values_data .= $key.",";
			}
			$sql_query_values_col = substr($sql_query_values_col, 0, strlen($sql_query_values_col)-1);
			$sql_query_values_data = substr($sql_query_values_data, 0, strlen($sql_query_values_data)-1);


			$sql = sprintf("INSERT INTO `%s` (%s) VALUES (%s)", $table_name, $sql_query_values_col, $sql_query_values_data);

			$statement = $this->db_con->prepare($sql);

			return $statement->execute($data_dict);
		}

		function update(string $table_name, array $data_dict, array $where_dict, string $where_conditions = ""){
			$sql_query_values_where = "";

			$where_conditions_arr = explode(" ", $where_conditions);

			$where_dict_keys = array_keys($where_dict);

			for ($i = 0; $i < count($where_dict); $i++) { 
				$key = $where_dict_keys[$i];
				$key_clean = str_replace(":", "", $key);

				$sql_query_values_where .= sprintf("%s=%s ", $key_clean, $key);

				if($i+1 < count($where_dict)){
					if($where_conditions){
						$sql_query_values_where .= $where_conditions_arr[$i]." ";
					}else{
						$sql_query_values_where .= "AND ";
					}
				}
			}

			$sql_query_values_data = "";

			foreach ($data_dict as $key => $value) {
				$key_clean = str_replace(":", "", $key);

				$sql_query_values_data .= sprintf("%s = %s, ", $key_clean, $key);
			};
			$sql_query_values_data = substr($sql_query_values_data, 0, strlen($sql_query_values_data)-2);

			$sql = sprintf("UPDATE `%s` SET %s WHERE %s", $table_name, $sql_query_values_data, $sql_query_values_where);

			$statement = $this->db_con->prepare($sql);

			$statement->execute($where_dict);

			return $statement->fetchAll();
		}

		function delete(string $table_name, array $where_dict, string $where_conditions = ""){
			$sql_query_values = "";

			$where_conditions_arr = explode(" ", $where_conditions);

			$where_dict_keys = array_keys($where_dict);

			for ($i = 0; $i < count($where_dict); $i++) { 
				$key = $where_dict_keys[$i];
				$key_clean = str_replace(":", "", $key);

				$sql_query_values .= sprintf("%s=%s ", $key_clean, $key);

				if($i+1 < count($where_dict)){
					if($where_conditions){
						$sql_query_values .= $where_conditions_arr[$i]." ";
					}else{
						$sql_query_values .= "AND ";
					}
				}
			}


			$sql = sprintf("DELETE FROM `%s` WHERE %s", $table_name, $sql_query_values);

			$statement = $this->db_con->prepare($sql);

			$statement->execute($where_dict);

			return $statement->fetchAll();
		}
	}
?>