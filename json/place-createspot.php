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
SELECT spot_id, spotname, address, lat, lng
FROM spot_table
WHERE menber_id = :a1
");
$stmt->bindValue(':a1', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result,JSON_UNESCAPED_UNICODE);

?>