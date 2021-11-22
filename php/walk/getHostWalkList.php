<?php 
// Written by NamHyeok Kim

session_start();
require_once("dbConfig.php");

//$_POST = json_decode(file_get_contents("php://input"), true);
$resArray = array('isSuccess' => false);


try {
    if(!isset($_SESSION['userKey'])) {
        throw new Exception("로그인 세션 없음", 2);
    }

    $getAddrSql = "SELECT addrLatitude, addrLongitude FROM account WHERE id = :id";
    $getAddrQuery = $database -> prepare($getAddrSql);
    $getAddrQuery -> bindValue(':id', $_SESSION['userKey'], PDO::PARAM_INT);
    
    execQuery($getAddrQuery);

    $userAddr = $getAddrQuery -> fetch(PDO::FETCH_ASSOC);

    $getWalksSql = "SELECT *, HAVERSINE(depLatitude, depLongitude, :lat, :long) AS distance FROM walk WHERE hostKey = :hostKey ORDER BY writeTime DESC";
    $getWalksQuery = $database -> prepare($getWalksSql);

    $getWalksQuery -> bindValue(':lat', $userAddr['addrLatitude'], PDO::PARAM_STR);
    $getWalksQuery -> bindValue(':long', $userAddr['addrLongitude'], PDO::PARAM_STR);
    $getWalksQuery -> bindValue(':hostKey', $_SESSION['userKey'], PDO::PARAM_INT);

    execQuery($getWalksQuery);

    $resArray['isSuccess'] = true;
    $resArray['walksCount'] = $getWalksQuery -> rowCount();
    $resArray['walks'] = $getWalksQuery -> fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>