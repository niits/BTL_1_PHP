<?php
	function db_user_get_by_username($username){
		db_connect();
		$sql = "SELECT * FROM user WHERE username ='".$username."'";
		$result = db_get_row($sql);
		db_close();
		return $result;
	}
	function db_insert_user($user = array()){
		db_connect();
		$fields = '';
		$values = '';
		foreach ($user as $field => $value){
			$fields .= $field .',';
			$values .= "'".addslashes($value)."',";
		}
		echo $fields.$values;
		$fields = trim($fields, ',');
		$values = trim($values, ',');
		$sql = "INSERT INTO  user (".$fields.") VALUES (".$values.")";
		
		echo $sql;
		$result =  db_execute($sql);
		db_close();
		return $result;
	}
	function db_user_validate($username){
		db_connect();
		$sql = "SELECT * FROM user WHERE username = '".$username."'";
		$result = db_get_row($sql);
		db_close();
		return empty(!$result);
	}
	function set_logged($username){
		$_SESSION["ss_user_token"] = $username;
	}
	function get_username(){
		$user = $_SESSION["ss_user_token"];
		return $user["username"];
	}
	
	function get_user_id(){
		$user = $_SESSION["ss_user_token"];
		return $user["id"];
	}
	
	function set_logout(){
		if (isset($_SESSION["ss_user_token"])){
			unset($_SESSION["ss_user_token"]);
		}
	}
	
	function is_logged(){
		return (isset($_SESSION["ss_user_token"])) ? true : false;
	}
?>