<!-- SIGNUP php write 김가은 -->
<?php
require_once("dbconfig.php");
$_POST = JSON_DECODE(file_get_contents("php://input"),true);
//입력 정보 받아오기
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

mysqli_report(MYSQLI_REPORT_ERROR);
$sql = "SELECT * FROM account WHERE real_id ='$user_id'";
$res = $db->query($sql);
$row = $res->fetch_array(MYSQLI_ASSOC);
if($row==null){ //중복확인
    
    $sql = "INSERT INTO `account`(`real_id`,`pw`,`nickname`,`email`,`phone`,`birth`,`gender`,`addrLongitude`,`addrLatitude`) 
    VALUES('$user_id','$user_pwHashedPw','$nickname','$mail','$phone','$birth','$gender','$addrLongitude','$addrLatitute')";
    $res=$db->query($sql);
    echo $res;
    echo json_encode(true,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
    echo $user_id;

}else{
    echo false;
} mysqli_close($db);
?>