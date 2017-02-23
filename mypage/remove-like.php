<?php
session_start();
include("../function.php");

//値を受け取っているか確認
if(
  !isset($_GET["review_id"]) || $_GET["review_id"]=="" ||
  !isset($_GET["spot_id"]) || $_GET["spot_id"]=="" 
){

    header("location: ./index.php");

    exit();
}

$review_id = $_GET["review_id"];
$spot_id = $_GET["spot_id"];
$menber_id = $_SESSION["menber_id"];

//DB接続
$pdo = db_con();

$stmt = $pdo->prepare("
DELETE FROM vote_table 
WHERE review_id = :a1 AND menber_id = :a2 AND type = :a3;
");
$stmt->bindValue(':a1', $review_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a2', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a3', 1,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();   //セキュリティにいい書き方
//SQL処理エラー
if($status==false){
//4,データ登録処理後
    db_error($stmt);
} 

echo $spot_id;

?>