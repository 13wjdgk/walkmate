<?php
// Written by NamHyeok Kim

//error_reporting(E_ALL);
//ini_set("display_errors", 1); // 디버그 용 오류 echo 설정 (배포시 주석처리)



error_reporting(E_ALL);
ini_set("display_errors", 1); // 디버그 용 오류 echo 설정 (배포시 주석처리)

$__dsn = "mysql:host=localhost;dbname=walkmate;charset=utf8mb4";
$__dbUserName = "root";
$__dbPassword = "kk6786"; // $__dsn, $__dbUserName, $__dbPassword 노출 방지


header("Content-Type:application/json");

// 자주 사용하는 플래그 정의
$__JSON_FLAGS = JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK;

try {
    // DB 연결
    $database = new PDO($__dsn, $__dbUserName, $__dbPassword);
} catch(PDOException $e) {
    echo "DB Error / {$e -> getMessage()}";
}   

function execQuery($query) { // DB 오류를 확인하면서 쿼리 실행
    if(!$query -> execute()) throw new Exception("DB 오류", 1);
}

?>