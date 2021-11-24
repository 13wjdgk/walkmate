<?php 
// Written by NamHyeok Kim
// 신청한 글 조회

session_start();
require_once("dbConfig.php");

$resArray = array('isSuccess' => false);

try {
    if(!isset($_SESSION['userKey'])) { // 로그인 체크
        throw new Exception("로그인 세션 없음", 2);
    }

    // 신청한 글의 키 조회
    $getKeysSql = "SELECT walkKey FROM applylist WHERE memberKey = :memberKey";
    $getKeysQuery = $database -> prepare($getKeysSql);
    $getKeysQuery -> bindValue(':memberKey', $_SESSION['userKey'], PDO::PARAM_INT);

    execQuery($getKeysQuery);

    $applyKeys = $getKeysQuery -> fetchAll(PDO::FETCH_COLUMN); // 글의 키
    
    // 키 배열을 N, N, N 형태의 문자열로 변환
    $applyKeysString = implode(', ', $applyKeys);
    $walksList = array();

    if($getKeysQuery -> rowCount() > 0) {
        // 사용자의 위, 경도 받아오기
        $getAddrSql = "SELECT addrLatitude, addrLongitude FROM account WHERE id = :id";
        $getAddrQuery = $database -> prepare($getAddrSql);
        $getAddrQuery -> bindValue(':id', $_SESSION['userKey'], PDO::PARAM_INT);
        
        execQuery($getAddrQuery);
    
        $userAddr = $getAddrQuery -> fetch(PDO::FETCH_ASSOC);
        
        // 신청한 글 받아오기 (키 배열 문자열, WHERE IN 구문 활용)
        $getWalksSql = "SELECT walk.*, HAVERSINE(walk.depLatitude, walk.depLongitude, :lat, :long) AS distance, applylist.applyTime FROM walk INNER JOIN applylist ON walk.walkKey = applylist.walkKey WHERE walk.walkKey IN ({$applyKeysString}) ORDER BY walk.writeTime DESC";
        $getWalksQuery = $database -> prepare($getWalksSql);
    
        $getWalksQuery -> bindValue(':lat', $userAddr['addrLatitude'], PDO::PARAM_STR);
        $getWalksQuery -> bindValue(':long', $userAddr['addrLongitude'], PDO::PARAM_STR);

        execQuery($getWalksQuery);

        $walksList = $getWalksQuery -> fetchAll(PDO::FETCH_ASSOC);
    }

    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $getKeysQuery -> rowCount();
    $resArray['walks'] = $walksList;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>