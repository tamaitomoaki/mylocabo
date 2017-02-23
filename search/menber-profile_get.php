<?php
include("../function.php");


$menber_id = $_GET["menber_id"];

$menber_id = str_replace("menber", "", $menber_id);

$pdo = db_con();

$stmt = $pdo->prepare("
SELECT A.review_id, A.comment, B.spot_id, B.spotname, C.name, C.introduction 
FROM review_table AS A
LEFT JOIN spot_table AS B ON A.spot_id = B.spot_id
LEFT JOIN menber_table AS C ON A.menber_id = C.menber_id
WHERE A.menber_id = :a1
"
);
$stmt->bindValue(':a1', $menber_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();


$result = $stmt->fetchAll();

echo json_encode( $result );
?>