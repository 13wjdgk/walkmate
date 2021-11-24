<?php 
// Written by NamHyeok Kim
// 신청한 사람을 승인 또는 거절하는 기능

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$resArray = array('isSuccess' => false);
try {
    if(!isset($_SESSION['userKey'])) { // 로그인 체크
        throw new Exception("로그인 세션 오류", 2);
    }

    $targetWalkKey = $_POST['walkKey']; // 승인, 거절할 글의 키
    $confirmData = $_POST['confirmData']; // 처리할 유저 정보 ['memberKey': 유저 키, 'isAccept': true면 승인]
    
    // 모집 글의 작성자 키 조회
    $sql = "SELECT hostKey FROM walk WHERE walkKey = :walkKey";
    $getHostKeyQuery = $database -> prepare($sql);
    $getHostKeyQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

    execQuery($getHostKeyQuery);

    if($getHostKeyQuery -> rowCount() < 1) { // 해당 키의 글이 없는 경우
        throw new Exception("없는 글", 3);
    }

    $hostKey = $getHostKeyQuery -> fetch(PDO::FETCH_COLUMN); // 작성자 키

    if($hostKey !== $_SESSION['userKey']) { // 로그인 한 사용자가 글의 작성자가 아닌 경우
        throw new Exception("권한 없음", 5);
    }

    // 신청 리스트 조회
    $sql = "SELECT * FROM applylist WHERE walkKey = :walkKey AND memberKey = :memberKey";
    $getApplyQuery = $database -> prepare($sql);
    $getApplyQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
    $getApplyQuery -> bindValue(':memberKey', $confirmData['memberKey'], PDO::PARAM_INT);

    execQuery($getApplyQuery);

    if($getApplyQuery -> rowCount() < 1) { // 조회된 신청이 없는 경우
        throw new Exception("신청한 사용자 없음", 4);
    }

    $app = $getApplyQuery -> fetch(PDO::FETCH_ASSOC); // 신청 내용

    $app['memberKey'] = (int)$app['memberKey']; // DB 리턴값은 String 이므로 캐스팅
    if($confirmData['userKey'] === $app['memberKey']) { // 키 값 재확인
        if($confirmData['isAccept']) { // 승인이면
            unset($app['applyTime']); // 신청서 내용에서 신청 시간만 삭제
            
            // 참가자 DB에 INSERT
            $confirmSql = "INSERT INTO memberlist(walkKey, memberKey, memberID, nickname, joinTime)
                            VALUES (:walkKey, :memberKey, :memberID, :nickname, NOW())";
            $confirmQuery = $database -> prepare($confirmSql);
            foreach($app as $key => $value) {
                if(is_int($value)) {
                    $confirmQuery -> bindValue(':'.$key, $value, PDO::PARAM_INT);
                } else {
                    $confirmQuery -> bindValue(':'.$key, $value, PDO::PARAM_STR);
                }
            }
            execQuery($confirmQuery);
            
            // 승인 되었으므로 nowMemberCount + 1, applyMemberCount - 1
            $changeCountSql = "UPDATE walk SET nowMemberCount = nowMemberCount + 1, applyMemberCount = applyMemberCount - 1 WHERE walkKey = :walkKey";
        } else { // applyMemberCount만 - 1
            $changeCountSql = "UPDATE walk SET applyMemberCount = applyMemberCount - 1 WHERE walkKey = :walkKey";
        }

        // Count UPDATE
        $changeCountQuery = $database -> prepare($changeCountSql);
        $changeCountQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);

        execQuery($changeCountQuery);
        
        // 신청 내용 DELETE
        $delApplySql = "DELETE FROM applylist WHERE walkKey = :walkKey AND memberKey = :memberKey";
        $delApplyQuery = $database -> prepare($delApplySql);

        $delApplyQuery -> bindValue(':walkKey', $targetWalkKey, PDO::PARAM_INT);
        $delApplyQuery -> bindValue(':memberKey', $app['memberKey'], PDO::PARAM_STR);
        
        execQuery($delApplyQuery);

        $resArray['isSuccess'] = true;
    }

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>