<?php
include("../function.php");

//値を受け取っているか確認
if(
  !isset($_GET["spot_id"]) || $_GET["spot_id"]=="" 
){
//  exit('ParamError');
    header("location: ./index.php");
//    echo "ok";
    exit();
}

$spot_id = $_GET["spot_id"];

$pdo = db_con();
$stmt = $pdo->prepare("
SELECT image_name
FROM images_table
WHERE spot_id = :a1
");
$stmt->bindValue(':a1', $spot_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();
if($status==false){
//4,データ登録処理後
    db_error($stmt);
}

$val = $stmt -> fetchAll();

echo json_encode( $val );
?>