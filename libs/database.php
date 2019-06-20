<?php
$conn = null;

function db_connect(){
    global $conn;
    if(! $conn){
        $conn = mysqli_connect("localhost", "root", "", "app") or die ('Không thể kết nối CSDL');
        mysqli_set_charset($conn, 'utf8');
    }
}

function db_close(){
    global $conn;
    if ($conn){
        mysqli_close($conn);
    }
    $conn = null;
}

function db_get_list($sql){
    global $conn;
    $data  = array();
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    return $data;
}
 
function db_get_row($sql){
    global $conn;
    $result = mysqli_query($conn, $sql);
    $row = array();
    if (mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
    }    
    return $row;
}
 
function db_execute($sql){
    global $conn;
    return mysqli_query($conn, $sql);
}
?>