<?php
include("../function.php");
$pdo = db_con();

//-------------

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

//$locate_ary = 
//    array(
//    
//    0 => array("name" => "高知県立美術館", "lat" => 33.56104, "lng" => 133.57295 ),
//    1 => array("name" => "牧野植物園", "lat" => 33.54661, "lng" => 133.57790 ),
//    2 => array("name" => "桂浜", "lat" => 33.497145, "lng" => 133.57480 ),
//    3 => array("name" => "高知市文化プラザかるぽーと", "lat" => 33.55826, "lng" => 133.54725 ),
//    4 => array("name" => "高知城", "lat" => 33.56067, "lng" => 133.53147 )
//    );
    
//[
//["高知県立美術館",33.56104,133.57295],
//["牧野植物園",33.54661,133.57790],
//["桂浜",33.497145,133.57480],
//["高知市文化プラザかるぽーと",33.55826,133.54725],
//["高知城",33.56067,133.53147]
//    
//];
//echo $locale_ary;

//$json = json_encode( $locate_ary ) ;


//値を受け取っているか確認
if(
  !isset($_GET["ne_lat"]) || $_GET["ne_lat"]=="" ||
  !isset($_GET["sw_lat"]) || $_GET["sw_lat"]=="" ||
  !isset($_GET["ne_lng"]) || $_GET["ne_lng"]=="" ||
  !isset($_GET["sw_lng"]) || $_GET["sw_lng"]=="" 
){
//  exit('ParamError');
    header("location: ../index.php");
//    echo "ok";
    exit();
}
//範囲データ取得
$ne_lat = $_GET["ne_lat"];
$sw_lat = $_GET["sw_lat"];
$ne_lng = $_GET["ne_lng"];
$sw_lng = $_GET["sw_lng"];




// print $locate_ary[1][1];
//出力

header("Content-Type: application/json; charset=utf-8");
$hyouzi = array();
    
for( $i = 0;$i < count($result);$i++){

    if( $result[$i]["lat"]<$ne_lat&&
        $result[$i]["lat"]>$sw_lat&&
        $result[$i]["lng"]<$ne_lng&&
        $result[$i]["lng"]>$sw_lng  ){
//        $hyouzi[] = $locate_ary[$i];
        array_push($hyouzi, $result[$i]);
//        $hyouzi = "test";
    }
    //array_push($hyouzi, $locate_ary[$i]);
    //$hyouzi[] = $locate_ary[0][1];
    //
    //$hyouzi[] = $locate_ary[0][$i];
    //$hyouzi[] = $locate_ary[$i];
    //var_dump($locate_ary[$i]);
    //var_dump($locate_ary[$i]);
}

echo json_encode($hyouzi,JSON_UNESCAPED_UNICODE);




//foreach($locate_ary as $val){
//    if(
//        $val[1] < $ne_lat && 
//        $val[1] > $sw_lat && 
//        $val[2] < $ne_lng && 
//        $val[2] > $sw_lng){
//        echo json_encode( $val ) ;
//    }
//}

?>
