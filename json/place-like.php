<?php
session_start();
include("../function.php");

//値を受け取っているか確認
if(
  !isset($_SESSION["menber_id"]) || $_SESSION["menber_id"]=="" 
){
    header("location: ../index.php");
    exit();
}

$pdo = db_con();

$menber_id = $_SESSION["menber_id"];

$stmt = $pdo->prepare("
SELECT C.spot_id, C.spotname, C.address, C.lat, C.lng
FROM vote_table AS A
LEFT JOIN review_table AS B ON A.review_id = B.review_id
LEFT JOIN spot_table AS C ON B.spot_id = C.spot_id
WHERE A.menber_id = :a1 AND A.type = 1;
");
$stmt->bindValue(':a1', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>