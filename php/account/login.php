<?php
require_once("dbconfig.php");
$_POST = JSON_DECODE(file_get_contents("php://input"),true);
//세션 초기화
session_start();
$user_id=$_POST["user_id"];
$user_pw=$_POST["user_pw"];

$sql = "SELECT * FROM account WHERE real_id ='$user_id'";
$res = $db->query($sql);
$row = $res->fetch_array(MYSQLI_ASSOC);
if($row){
    $passwordResult = password_verify($user_pw, $row['pw']);
    if($passwordResult){
        $_SESSION['userId'] = $user_id;

        // ADD NH-K
        $_SESSION['userKey'] = $row['id'];
        $_SESSION['userNickname'] = $row['nickname'];
        // END

        echo json_encode(true,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
    }else{
        echo json_encode(false,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK); 
    }

} else {            // 만약 참이 아니면 로그인 실패
   echo false;
}
 mysqli_close($db);
?>