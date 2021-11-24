<?php 
// Written by NamHyeok Kim
// 작성 글 받아오기

session_start();
require_once("dbConfig.php");

$resArray = array('isSuccess' => false);


try {
    if(!isset($_SESSION['userKey'])) { // 로그인 체크
        throw new Exception("로그인 세션 없음", 2);
    }

    // 사용자의 위, 경도 받아오기
    $getAddrSql = "SELECT addrLatitude, addrLongitude FROM account WHERE id = :id";
    $getAddrQuery = $database -> prepare($getAddrSql);
    $getAddrQuery -> bindValue(':id', $_SESSION['userKey'], PDO::PARAM_INT);
    
    execQuery($getAddrQuery);

    $userAddr = $getAddrQuery -> fetch(PDO::FETCH_ASSOC);

    // 작성한 글 받아오기
    $getWalksSql = "SELECT *, HAVERSINE(depLatitude, depLongitude, :lat, :long) AS distance 
                    FROM walk WHERE hostKey = :hostKey ORDER BY writeTime DESC";
    $getWalksQuery = $database -> prepare($getWalksSql);

    $getWalksQuery -> bindValue(':lat', $userAddr['addrLatitude'], PDO::PARAM_STR);
    $getWalksQuery -> bindValue(':long', $userAddr['addrLongitude'], PDO::PARAM_STR);
    $getWalksQuery -> bindValue(':hostKey', $_SESSION['userKey'], PDO::PARAM_INT);

    execQuery($getWalksQuery);

    $walkList = $getWalksQuery -> fetchAll(PDO::FETCH_ASSOC);

    // 작성글에서 키만 N, N, N 형식의 문자열로 만들기(for WHERE IN)
    foreach($walkList as $walk) {
        if(!isset($walkKeyString)) {
            $walkKeyString = $walk['walkKey'];
        } else {
            $walkKeyString .= ',' . $walk['walkKey'];
        }
    }

    // 참가자 받아오기
    $getMemberSql = "SELECT * FROM memberlist WHERE walkKey IN ({$walkKeyString})";
    $getMemberQuery = $database -> prepare($getMemberSql);

    execQuery($getMemberQuery);
    if($getMemberQuery -> rowCount() > 0) {
        // PDO::FETCH_GROUP => 가장 왼쪽 컬럼을 키 값으로 한 배열로 반환
        $memberList = $getMemberQuery -> fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
    }

    // 신청자 받아오기
    $getApplySql = "SELECT * FROM applylist WHERE walkKey IN ({$walkKeyString})";
    $getApplyQuery = $database -> prepare($getApplySql);

    execQuery($getApplyQuery);
    if($getApplyQuery -> rowCount() > 0) {
        $applyList = $getApplyQuery -> fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
    }

    // 글 목록을 순회하며 해당하는 키의 참가자, 신청자 목록이 존재하면 추가
    foreach($walkList as $key => $value) {
        if(isset($memberList[$value['walkKey']]))
                $walkList[$key]['memberList'] = $memberList[$value['walkKey']];
        if(isset($applyList[$value['walkKey']]))
            $walkList[$key]['applyList'] = $applyList[$value['walkKey']];
    }

    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $getWalksQuery -> rowCount();
    $resArray['walks'] = $walkList;
} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);