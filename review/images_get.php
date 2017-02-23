<?php
include("../function.php");

//値を受け取っているか確認
if(
  !isset($_GET["review_id"]) || $_GET["review_id"]=="" 
){
//  exit('ParamError');
    header("location: ./index.php");
//    echo "ok";
    exit();
}

$review_id = $_GET["review_id"];

$pdo = db_con();
$stmt = $pdo->prepare("
SELECT image_name
FROM images_table
WHERE review_id = :a1
");
$stmt->bindValue(':a1', $review_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();
if($status==false){
//4,データ登録処理後
    db_error($stmt);
}

$val = $stmt -> fetchAll();

echo json_encode( $val );
?>