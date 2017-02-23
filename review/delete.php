<?php
session_start();
include("../function.php");
sessionCheck();//セッションの入れ替え

$review_id = $_GET["review_id"];
$reviewimg = $_GET["reviewimg"];




//review_table, images_table, vote_tableから削除したいレビューに日もずくデータを削除
$pdo = db_con();

//review_table
$stmt = $pdo->prepare("
DELETE review_table, images_table, vote_table
FROM review_table
LEFT JOIN images_table ON review_table.review_id = images_table.review_id 
LEFT JOIN vote_table ON review_table.review_id = vote_table.review_id
WHERE review_table.review_id = :a1
");
$stmt ->bindValue(':a1',$review_id, PDO::PARAM_INT);
$status = $stmt->execute();//セキュリティにいい書き方
if($status==false){
    db_error($stmt);
}

//削除する口コミに画像があればフォルダから削除する
if( $reviewimg != ""){
    $reviewimg = explode(",", $reviewimg);
    foreach ($reviewimg as $image_name) {
        unlink("../upload/".$image_name);
        unlink("../upload/s/".$image_name);
    }
}

header("Location: ../mypage/index.php");
exit;

?>