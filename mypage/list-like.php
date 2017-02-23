<?php
include("../function.php");

//値を受け取っているか確認
if(
  !isset($_GET["menber_id"]) || $_GET["menber_id"]=="" 
){
//  exit('ParamError');
    header("location: ./index.php");
//    echo "ok";
    exit();
}

$menber_id = $_GET["menber_id"];

$pdo = db_con();



$stmt = $pdo->prepare("
SELECT  B.review_id, B.review_point, C.spot_id, C.spotname, C.address, C.spot_point, D.image_name, E.images_name
FROM vote_table AS A
LEFT JOIN review_table AS B ON A.review_id = B.review_id
LEFT JOIN spot_table AS C ON B.spot_id = C.spot_id
LEFT JOIN (
    SELECT image_id, review_id, image_name 
    FROM images_table
    GROUP BY review_id 
    ) AS D ON B.review_id = D.review_id
LEFT JOIN (
    SELECT spot_id,group_concat(image_name) AS images_name 
    FROM images_table
    GROUP BY spot_id
    )AS E ON B.spot_id = E.spot_id
WHERE A.menber_id = :a1 AND A.type = 1;
");
$stmt->bindValue(':a1', $menber_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();


$reviewlist = array();
while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $reviewlist[] = $result;
}



echo json_encode( $reviewlist );


?>