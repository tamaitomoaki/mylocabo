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

//$review_id = str_replace("review", "", $review_id);

$pdo = db_con();



$stmt = $pdo->prepare("
SELECT  A.review_id, A.time, A.money, A.comment, A.D_point, A.L_point, A.spot_id, A.menber_id, A.review_point, B.images_name, C.spotname, D.menber_id, D.name,D.profileimg, E.tag_id, E.tag_review_map_ids, E.tags_name  
FROM review_table AS A
LEFT JOIN (
    SELECT review_id,group_concat(image_name) AS images_name 
    FROM images_table
    GROUP BY review_id
    )AS B ON A.review_id = B.review_id
LEFT JOIN spot_table AS C ON A.spot_id = C.spot_id
LEFT JOIN menber_table AS D ON A.menber_id = D.menber_id
LEFT JOIN (
    SELECT group_concat(tag_review_map_id) AS tag_review_map_ids, review_id, tags_table.tag_id, group_concat(tag_name) AS tags_name
    FROM tag_review_map_table
    LEFT JOIN tags_table ON tag_review_map_table.tag_id = tags_table.tag_id
    GROUP BY review_id
    )AS E ON A.review_id = E.review_id
WHERE A.review_id = :a1
"
);
$stmt->bindValue(':a1', $review_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();
if($status==false){
//4,データ登録処理後
    db_error($stmt);
}



$result = $stmt->fetch();


//$result["comment"] = nl2br($result["comment"]);

if( $result["images_name"] == false){
    $result["images_name"] = "";
}else{
    $image_name = explode(',',$result["images_name"]);
    $result["images_name"] = "";
    for( $i = 0; $i < count($image_name);$i++){
    $result["images_name"] .= "<img src='../upload/s/".h($image_name[$i])."' class='reviewpageimg-size img-rounded r-img' data-toggle='modal' data-target='#imageModal' data-recipient='".h($image_name[$i])."'>";
    }
}
//タグ
if( $result["tags_name"] == false){
    $result["tags_name"] = "";
}else{
    $tagarray = explode(',',$result["tags_name"]);
    $idarray = explode(',',$result["tag_review_map_ids"]);
    $result["tags_name"] = "";//からにしないと、このデータに追加されてしまう
    $result["tag_review_map_ids"] = "";
    for( $x = 0; $x < count($tagarray);$x++){
    $result["tags_name"] .= "<div class='taging' id='nowtag". $idarray[$x] ."'><span>#".$tagarray[$x]."</span><a class='btn btn-default btn-xs btn-delete-tag' role='button'>削除</a>　</div>";
    }
}
echo json_encode( $result );
?>