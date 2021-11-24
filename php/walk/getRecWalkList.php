<?php 
// Written by NamHyeok Kim
// 거리순으로 글을 가져오는 기능

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$requireCount = $_POST['requireCount']; // 한번에 전송할 개수
$walkListCount = $_POST['walkListCount']; // 현재까지 전송한 개수
$requestTime = $_POST['requestTime']; // 최초 요청 시간 ($requestTime 이전 작성글만 조회)
$limitDistance = $_POST['limitDistance']; // 조회 할 최대 거리(km)

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

    if($getAddrQuery -> rowCount() < 1) {  // 사용자가 없는 경우
        throw new Exception("잘못된 사용자", 4);
    }

    $userAddr = $getAddrQuery -> fetch(PDO::FETCH_ASSOC);

    // 글 조회 (서브쿼리 활용 & 전체를 다 조회하므로 느림.. 개선 필요)
    // 서브쿼리로 조회시간 이전 작성 글에 거리 계산 값을 포함하여 조회 이 후 거리 내 값만 다시 조회, 거리 순 정렬
    $getListSql = "SELECT * FROM (SELECT *, HAVERSINE(depLatitude, depLongitude, :lat, :long) AS distance 
                FROM walk WHERE writeTime <= STR_TO_DATE(:reqTime, '%Y-%m-%d %T')) AS X WHERE distance <= :limitDist ORDER BY distance LIMIT :reqCount OFFSET :walkListCount";
    $getListQuery = $database -> prepare($getListSql);

    $getListParam = array(':lat' => $userAddr['addrLatitude'], ':long' => $userAddr['addrLongitude'], ':reqTime' => $requestTime,
                        ':limitDist' => $limitDistance, ':reqCount' => $requireCount, ':walkListCount' => $walkListCount);

    foreach($getListParam as $key => $value) {
        if(is_int($value)) {
            $getListQuery -> bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $getListQuery -> bindValue($key, $value, PDO::PARAM_STR);
        }
    }        

    execQuery($getListQuery);

    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $getListQuery -> rowCount();
    $resArray['walks'] = $getListQuery -> fetchAll(PDO::FETCH_ASSOC);
    
    
} catch(Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>
