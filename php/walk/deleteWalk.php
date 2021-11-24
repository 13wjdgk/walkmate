<?php 
// Written by NamHyeok Kim
// 모집 글 삭제 기능

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$targetWalkKey = $_POST['walkKey']; // 삭제할 글의 키
$resArray = array('isSuccess' => false);

try {
    if(!isset($_SESSION['userKey'])) { // 로그인 체크
        throw new Exception("로그인 세션 없음", 2);        
    }

    // 해당 글 조회
    $getHostSql = "SELECT hostKey FROM walk WHERE walkKey = :walkKey";
    $getHostQuery = $database -> prepare($getHostSql);
    $getHostQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    
    execQuery($getHostQuery);
    
    if($getHostQuery -> rowCount() < 1) { // 글이 존재하지 않는 경우
        throw new Exception("존재하지 않는 글", 3);
    }

    $hostKey = $getHostQuery -> fetch(PDO::FETCH_COLUMN);

    if($_SESSION['userKey'] !== $hostKey) { // 현재 세션 키랑 호스트 키랑 불 일치
        throw new Exception("권한 없음", 5);
    }

    // 글 삭제
    $delWalkSql = "DELETE FROM walk WHERE walkKey = :walkKey";
    $delWalkQuery = $database -> prepare($delWalkSql);
    $delWalkQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    execQuery($delWalkQuery);

    // 참가자 삭제
    $delMemberListSql = "DELETE FROM memberlist WHERE walkKey = :walkKey";
    $delMemberListQuery = $database -> prepare($delMemberListSql);
    $delMemberListQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    execQuery($delMemberListQuery);

    // 신청서 삭제
    $delApplyListSql = "DELETE FROM applylist WHERE walkKey = :walkKey";
    $delApplyListQuery = $database -> prepare($delApplyListSql);
    $delApplyListQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    execQuery($delApplyListQuery);

    $resArray['isSuccess'] = true;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS);
unset($database);

?>