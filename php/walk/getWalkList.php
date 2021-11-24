<?php 
// Written by NamHyeok Kim
// 글 목록을 받아오는 기능

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$requireCount = $_POST['requireCount']; // 한번에 불러올 글의 개수
$walkListCount = $_POST['walkListCount']; // 현재까지 불러온 글의 개수
$requestTime = $_POST['requestTime']; // 조회 시작 시간

$resArray = array('isSuccess' => false);

try {
    $param = array(':reqTime' => $requestTime, ':requireCount' => $requireCount, ':walkListCount' => $walkListCount);
    
    $sql = "SELECT *";
    if(isset($_SESSION['userKey'])) { // 로그인 상태라면 거리 정보 추가
        // 사용자의 위, 경도 받아오기
        $getAddrSql = "SELECT addrLatitude, addrLongitude FROM account WHERE id = :id";
        $getAddrQuery = $database -> prepare($getAddrSql);
        $getAddrQuery -> bindValue(':id', $_SESSION['userKey'], PDO::PARAM_INT);
        execQuery($getAddrQuery);

        if($getAddrQuery -> rowCount() === 1) {
            $userAddr = $getAddrQuery -> fetch(PDO::FETCH_ASSOC);
            // 거리 조회 구문 추가
            $sql = $sql . ", HAVERSINE(depLatitude, depLongitude, :lat, :long) AS distance";
            $param = array_merge($param, array(':lat' => $userAddr['addrLatitude'], ':long' => $userAddr['addrLongitude']));
        }
    }
    // 쿼리 구문 완성
    $sql = $sql . " FROM walk WHERE writeTime <= STR_TO_DATE(:reqTime, '%Y-%m-%d %T') ORDER BY walkKey DESC LIMIT :requireCount OFFSET :walkListCount";

    $query = $database -> prepare($sql);

    foreach($param as $key => $value) {
        if(is_int($value)) {
            $query -> bindValue($key, $value, PDO::PARAM_INT);
        } else {
            $query -> bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    execQuery($query);
    
    $walkArray = $query -> fetchAll(PDO::FETCH_ASSOC);

    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $query->rowCount();
    $resArray['walks'] = $walkArray;

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>
