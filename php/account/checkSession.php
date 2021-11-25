
<?php
//세션이 유효한지 검사 write 김가은 
require_once("dbconfig.php");
session_start(); 
if($_SESSION[ 'userId' ]){ //userId가 유효하면
  $userId=$_SESSION[ 'userId' ];
  $sql="SELECT real_id FROM account WHERE real_id ='$userId'"; //userId와  real_id가 일치하는 row
  $data=array();
  $res=$db->query($sql);
  for($i=0;$i<$res->num_rows;$i++){
    $row=$res->fetch_array(MYSQLI_ASSOC);
    array_push($data,$row);
  }
  echo json_encode($data);
}
?>
