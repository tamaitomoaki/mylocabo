<?php
include("../function.php");

//値を受け取っているか確認
if(
  !isset($_GET["menber_id"]) || $_GET["menber_id"]=="" 
){
    header("location: ./index.php");
    exit();
}

$menber_id = $_GET["menber_id"];

$pdo = db_con();

$stmt = $pdo->prepare("
SELECT spot_id, spotname, address
FROM spot_table
WHERE menber_id = :a1
ORDER BY spot_id DESC;
");
$stmt->bindValue(':a1', $menber_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();


$reviewlist = array();
while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $reviewlist[] = $result;
}

echo json_encode( $reviewlist );
?>