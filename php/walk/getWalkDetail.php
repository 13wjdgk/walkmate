<?php 
// Written by NamHyeok Kim
// 모집 글 상세내용을 조회하는 기능

session_start();
require_once("dbConfig.php");

$_POST = json_decode(file_get_contents("php://input"), true);

$reqWalkKey = $_POST['walkKey']; // 조회할 글의 키 값
$resArray = array('isSuccess' => false);

try { 
    // 글 조회
    $sql = "SELECT * FROM walk WHERE walkKey = :reqKey";
    $query = $database -> prepare($sql);
    $query -> bindValue(':reqKey', $reqWalkKey, PDO::PARAM_INT);

    execQuery($query);

    if($query -> rowCount() < 1) { // 조회 된 내용이 없는 경우
        throw new Exception("없는 글", 3);
    }   

    $walkPost = $query -> fetch(PDO::FETCH_ASSOC);
    $resArray['isSuccess'] = true;
    $resArray['body'] = $walkPost;
    
    // 로그인 상태고 글 작성자인 경우
    if(isset($_SESSION['userKey']) && ($_SESSION['userKey'] === $walkPost['hostKey'])) {
        $resArray['isHost'] = true;

        // 참가자, 신청자 리스트 조회 후 추가
        $memberSql = "SELECT * FROM memberList WHERE walkKey = :reqKey";
        $memberQuery = $database -> prepare($memberSql);
        $memberQuery -> bindValue(':reqKey', $reqWalkKey, PDO::PARAM_INT);
        execQuery($memberQuery);
        $resArray['memberList'] = $memberQuery -> fetchAll(PDO::FETCH_ASSOC);
        
        $applySql = "SELECT * FROM applyList WHERE walkKey = :reqKey";
        $applyQuery = $database -> prepare($applySql);
        $applyQuery -> bindValue(':reqKey', $reqWalkKey, PDO::PARAM_INT);
        execQuery($applyQuery);
        $resArray['applyList'] = $applyQuery -> fetchAll(PDO::FETCH_ASSOC);

    } else { // 로그인 상태가 아니거나 글 작성자가 아닌 경우
        $resArray['isHost'] = false;
    }
} catch (Exception $e) {
    $resArray['code'] = $e -> getCode();
    $resArray['errorDetail'] = $e -> getMessage();
}

echo json_encode($resArray, $__JSON_FLAGS|JSON_FORCE_OBJECT);
unset($database);

?>
