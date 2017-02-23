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

$review_id = str_replace("review", "", $review_id);

$pdo = db_con();



$stmt = $pdo->prepare("
SELECT  A.review_id,A.comment,A.D_point, A.L_point, A.spot_id,A.menber_id,B.images_name,C.spotname, D.menber_id, D.name,D.profileimg  
FROM review_table AS A
LEFT JOIN (
    SELECT review_id,group_concat(image_name) AS images_name 
    FROM images_table
    GROUP BY review_id
    )AS B ON A.review_id = B.review_id
LEFT JOIN spot_table AS C ON A.spot_id = C.spot_id
LEFT JOIN menber_table AS D ON A.menber_id = D.menber_id
WHERE A.review_id = :a1
"
);
$stmt->bindValue(':a1', $review_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();


//$val = $stmt->fetch();
$result = $stmt->fetch();
$result["comment"] = nl2br($result["comment"]);




echo json_encode( $result );
?>