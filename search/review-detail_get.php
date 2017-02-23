<?php
include("../function.php");


$review_id = $_GET["review_id"];

$review_id = str_replace("review", "", $review_id);

$pdo = db_con();



$stmt = $pdo->prepare("
SELECT A.review_id, A.comment, B.menber_id, B.name, C.spotname, C.spot_id
FROM review_table AS A
LEFT JOIN menber_table AS B ON A.menber_id = B.menber_id
LEFT JOIN spot_table AS C ON A.spot_id = C.spot_id
WHERE A.review_id = :a1
"
);
$stmt->bindValue(':a1', $review_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();


//$val = $stmt->fetch();
$result = $stmt->fetch();




echo json_encode( $result );
?>