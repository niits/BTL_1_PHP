<?php
function base_url($uri = ''){
    return "index.php".$uri ;
} 
function redirect($url){
    header("Location:{$url}");
    exit();
}
 
function input_post($key){
    return isset($_POST[$key]) ? trim($_POST[$key]) : false;
}
function set_edit_post($id){
	$_SESSION['id'] = $id;
}
function get_edit_post(){
	return $_SESSION['id'];
}	
function input_get($key){
    return isset($_GET[$key]) ? trim($_GET[$key]) : false;
}
 
function is_submit($key){
    return (isset($_POST['request_name']) AND $_POST['request_name'] == $key);
}
 
function show_error($error, $key){
    echo '<span class="message">'.(empty($error[$key]) ? "" : $error[$key]). '</span>';
}
function changeDateFormat($date){
	$date = date("d-m-Y", strtotime($date));
	$date = str_replace("-", " - ", $date);
	return $date;
}
?>