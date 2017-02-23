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
SELECT A.review_id, A.time, A.money, A.comment, A.spot_id, A.D_point, A.L_point, A.review_point, B.image_name, C.images_name, D.spotname, D.lat, D.lng, E.tag_review_map_ids, E.tags_name, F.category_name
FROM review_table AS A
LEFT JOIN (
    SELECT image_id, review_id, image_name 
    FROM images_table
    GROUP BY review_id 
    ) AS B ON A.review_id = B.review_id
LEFT JOIN (
    SELECT spot_id,group_concat(image_name) AS images_name 
    FROM images_table
    GROUP BY spot_id
    )AS C ON A.spot_id = C.spot_id
LEFT JOIN spot_table AS D ON A.spot_id = D.spot_id
LEFT JOIN (
    SELECT group_concat(tag_review_map_id) AS tag_review_map_ids, review_id, group_concat(tag_name) AS tags_name
    FROM tag_review_map_table
    LEFT JOIN tags_table ON tag_review_map_table.tag_id = tags_table.tag_id
    GROUP BY review_id
    )AS E ON A.review_id = E.review_id
LEFT JOIN (
    SELECT category_spot_map_table.spot_id, categorys_table.category_name
    FROM category_spot_map_table
    LEFT JOIN categorys_table ON category_spot_map_table.category_id = categorys_table.category_id
    GROUP BY spot_id
    )AS F ON D.spot_id = F.spot_id
WHERE A.menber_id =:a1
ORDER BY A.review_id desc;
");
$stmt->bindValue(':a1', $menber_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();


$reviewlist = array();
$i = 0;
while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $reviewlist[] = $result;
    $reviewlist[$i]["spotname"] = h(mb_strimwidth($result["spotname"],0,30,"..."));
    $reviewlist[$i]["comment"] = h(mb_strimwidth($result["comment"],0,176,"..."));
    $reviewlist[$i]["image_name"] = h($result["image_name"]);
    $reviewlist[$i]["images_name"] = h($result["images_name"]);
    $reviewlist[$i]["time"] = change_time($result["time"]);
    $reviewlist[$i]["money"] = change_money($result["money"]);
    $i += 1;
}

echo json_encode( $reviewlist );
?>