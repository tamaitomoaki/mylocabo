<?php
include("../function.php");
$pdo = db_con();

$stmt = $pdo->prepare("
SELECT  A.spot_id, A.spotname, A.address, A.lat, A.lng, A.spot_point, B.image_id, B.image_name, C.count
FROM spot_table AS A
LEFT JOIN (
    SELECT MIN(image_id) AS image_id, image_name, spot_id
    FROM images_table
    GROUP BY spot_id
    )AS B ON A.spot_id = B.spot_id
LEFT JOIN (
    SELECT count(*) AS count, spot_id
    FROM review_table
    GROUP BY spot_id
    )AS C ON A.spot_id = C.spot_id
");


$status = $stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

//値を受け取っているか確認
if(
  !isset($_GET["ne_lat"]) || $_GET["ne_lat"]=="" ||
  !isset($_GET["sw_lat"]) || $_GET["sw_lat"]=="" ||
  !isset($_GET["ne_lng"]) || $_GET["ne_lng"]=="" ||
  !isset($_GET["sw_lng"]) || $_GET["sw_lng"]=="" 
){
    header("location: ../index.php");
    exit();
}
//範囲データ取得
$ne_lat = $_GET["ne_lat"];
$sw_lat = $_GET["sw_lat"];
$ne_lng = $_GET["ne_lng"];
$sw_lng = $_GET["sw_lng"];

//出力

header("Content-Type: application/json; charset=utf-8");
$hyouzi = array();
    
for( $i = 0;$i < count($result);$i++){

    if( $result[$i]["lat"]<$ne_lat&&
        $result[$i]["lat"]>$sw_lat&&
        $result[$i]["lng"]<$ne_lng&&
        $result[$i]["lng"]>$sw_lng  ){
        
        array_push($hyouzi, $result[$i]);
    }
}
echo json_encode($hyouzi,JSON_UNESCAPED_UNICODE);


?>
