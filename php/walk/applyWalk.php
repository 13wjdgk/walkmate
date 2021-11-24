<?php 
// Written by NamHyeok Kim
// 모집 글에 신청하는 기능

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);
$resArray = array('isSuccess' => false);

try {
    if(!isset($_SESSION['userKey'])) { // 로그인 체크
        throw new Exception("로그인 세션 오류", 2);
    }

    $targetWalkKey = $_POST['walkKey']; // 신청할 글의 키

    //모집글에 참가, 신청 중인 유저 리스트를 받아와 순회하여 중복체크
    $memberSql = "SELECT * FROM memberlist WHERE walkKey = :walkKey";
    $memberQuery = $database -> prepare($memberSql);
    $memberQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

    execQuery($memberQuery);

    $flag = true;
    if($memberQuery -> rowCount() > 0) {
        $memberResult = $memberQuery -> fetchAll(PDO::FETCH_ASSOC);
        foreach($memberResult as $value) {
            if($value['memberKey'] === $_SESSION['userKey']) {
                $flag = false;
                break;
            }
        }
    }

    $applySql = "SELECT * FROM applylist WHERE walkKey = :walkKey";
    $applyQuery = $database -> prepare($applySql);
    $applyQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

    execQuery($applyQuery);

    if($applyQuery -> rowCount() > 0) {
        $applyResult = $applyQuery -> fetchAll(PDO::FETCH_ASSOC);
        foreach($applyResult as $value) {
            if($value['memberKey'] === $_SESSION['userKey']) {
                $flag = false;
                break;
            }
        }
    }

    if(!$flag) { // 목록에서 같은 키 값을 찾은 경우
        throw new Exception("이미 신청 또는 참가 중", 4);
    }
    
    // 신청 쿼리
    $insSql = "INSERT applylist (walkKey, memberKey, memberID, nickname, applyTime) 
            VALUES (:walkKey, :memberKey, :memberID, :nickname, NOW())";
    $insParam = array(':walkKey' => $targetWalkKey, ':memberKey' => $_SESSION['userKey'],
                        ':memberID' => $_SESSION['userId'], ':nickname' => $_SESSION['userNickname']);

    $insQuery = $database -> prepare($insSql);
    foreach($insParam as $key => $value) {
        if(is_int($value)) {
            $insQuery -> bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $insQuery -> bindValue($key, $value, PDO::PARAM_STR);
        }
    }
    
    execQuery($insQuery);

    // 참가 글 DB에 신청자 수 업데이트
    $countQuery = $database -> prepare("UPDATE walk SET applyMemberCount = applyMemberCount + 1 WHERE walkKey = :walkKey");
    $countQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    execQuery($countQuery);
    $resArray['isSuccess'] = true;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS);
unset($database);

?>