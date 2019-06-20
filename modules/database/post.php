<?php
	function db_all_post(){
		db_connect();
		$sql = "SELECT post.id, post_header, post_content, create_date, avatar_url, post.poster_id, user.username FROM post INNER JOIN user ON post.poster_id = user.id  ORDER BY create_date DESC, post.id DESC";
		$result = db_get_list($sql);
		db_close();
		return $result;
	}
	function db_insert_post($post = array()){
		db_connect();
		$fields = '';
		$values = '';
		foreach ($post as $field => $value){
			$fields .= $field .',';
			$values .= "'".addslashes($value)."',";
		}
		echo $fields.$values;
		$fields = trim($fields, ',');
		$values = trim($values, ',');
		$sql = "INSERT INTO  post (".$fields.", create_date) VALUES (".$values.", NOW())";
		echo $sql;
		$result =  db_execute($sql);
		db_close();
		return $result;
	}
	function db_get_post_by_id($id){
		db_connect();
		$sql = "SELECT * FROM post WHERE id ='".$id."';";
		$result = db_get_row($sql);
		db_close();
		return $result;
	}
	function delete_post_by_id($id){
		
	}
	function edit_post( $post = array()){
		db_connect();
		$sql ='UPDATE post SET post_header = "'.$post["post_header"].'", post_content = "'.$post["post_content"].'", avatar_url ="'.$post["avatar_url"].'" , create_date = NOW() WHERE id ="'.$post["id"].'" AND poster_id = "'.get_user_id().'"';
		$result =  db_execute($sql);
		db_close();
		return $result;
	}
	function delete_post($id){
		db_connect();
		$sql = 'DELETE FROM post WHERE post.id ="'.$id.'"AND poster_id ="'.get_user_id().'";';
		echo $sql;
		$result =  db_execute($sql);
		db_close();
		return $result;
	}
?>