
<?php
//로그인 php write 김가은 
require_once("dbconfig.php");
$_POST = JSON_DECODE(file_get_contents("php://input"),true);
//세션 초기화
session_start();
$user_id=$_POST["user_id"]; //id 가져오기
$user_pw=$_POST["user_pw"]; //pw 가져오기

$sql = "SELECT * FROM account WHERE real_id ='$user_id'"; //테이블에 user_id 값과 동일한 row가 있는지 
$res = $db->query($sql);
$row = $res->fetch_array(MYSQLI_ASSOC);
if($row){ // 동일한 row가 없으면
    
    $passwordResult = password_verify($user_pw, $row['pw']); //pw 암호화
    if($passwordResult){
        $_SESSION['userId'] = $user_id; //세션으로 넘겨줌
        echo  $user_id;
        echo json_encode(true,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);

    }else{
        echo json_encode(false,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK); 
    }
    // ADD NH-K
    $_SESSION['userKey'] = $row['id'];
    $_SESSION['userNickname'] = $row['nickname'];
    // END

    echo $_SESSION['userId'];
} else {            // 만약 참이 아니면 로그인 실패
   echo false;
}
 mysqli_close($db);
?>