<?php
require_once("dbconfig.php");
$_POST = JSON_DECODE(file_get_contents("php://input"),true);
$user_id =$_POST["user_id"];
$user_pw =$_POST["user_pw"];
$user_pwHashedPw=password_hash($user_pw, PASSWORD_DEFAULT); 

$nickname =$_POST["nickname"];

$mail =$_POST["mail"];
$phone =$_POST["phone"];
$birth =$_POST["birth"];
$gender =$_POST["gender"];
$addrLatitute =$_POST["addrLatitute"];
$addrLongitude =$_POST["addrLongitude"];

$sql = "SELECT * FROM account WHERE real_id ='$user_id'";
//echo $sql;
$res = $db->query($sql);
$row = $res->fetch_array(MYSQLI_ASSOC);
if($row==null){
    $sql = "INSERT INTO `account`(`real_id`,`pw`,`nickname`,`email`,`phone`,`birth`,`gender`,`addrLatitude`,`addrLongitude`) 
    VALUES('$user_id','$user_pwHashedPw','$nickname','$mail','$phone','$birth','$gender','$addrLatitute','$addrLongitude')";
    //echo $sql;
    $res2=$db->query($sql);
    $row2 = $res2->fetch_array(MYSQLI_ASSOC);
    echo json_encode(true,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
    echo $user_id;

}else{
    echo false;
    // echo json_encode(false,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK); 
} mysqli_close($db);
?>