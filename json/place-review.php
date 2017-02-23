<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
//sessionCheck();//セッションの入れ替え

//値を受け取っているか確認
if(
  !isset($_SESSION["menber_id"]) || $_SESSION["menber_id"]=="" 
){
//  exit('ParamError');
    header("location: ../index.php");
//    echo "ok";
    exit();
}

$pdo = db_con();

$menber_id = $_SESSION["menber_id"];

$stmt = $pdo->prepare("
SELECT  A.review_id, B.spot_id, B.spotname, B.lat, B.lng 
FROM review_table AS A
LEFT JOIN  spot_table AS B ON A.spot_id = B.spot_id
WHERE A.menber_id = :a1
");
$stmt->bindValue(':a1', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>
