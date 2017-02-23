<?php
include("../function.php");


$spot_id = $_GET["spot_id"];

$spot_id = str_replace("spot", "", $spot_id);

$pdo = db_con();



$stmt = $pdo->prepare("
SELECT A.review_id, A.comment, B.name, C.spotname, C.address
FROM review_table AS A
LEFT JOIN menber_table AS B ON A.menber_id = B.menber_id
LEFT JOIN spot_table AS C ON A.spot_id = C.spot_id
WHERE A.spot_id = :a1
"
);
$stmt->bindValue(':a1', $spot_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();


//$val = $stmt->fetch();
$result = $stmt->fetchAll();




echo json_encode( $result );
?>