<?php
session_start();
include("../function.php");
sessionCheck();//セッションの入れ替え

//入力チェック(受信確認処理追加)
//コメントをセッションに入れているので、送られてきていないのでページが必ず戻る
if(
  !isset($_POST["time"]) || $_POST["time"]==""  ||
  !isset($_POST["money"]) || $_POST["money"]==""  ||
  !isset($_POST["comment"]) || $_POST["comment"]==""  ||
  !isset($_POST["point"]) || $_POST["point"]==""
)
{
  //未記入がある場合、エラー
    header( "Location: ./create.php");
    exit;
}

$time = $_POST["time"];
$money = $_POST["money"];
$comment = $_POST["comment"]; 
$point = $_POST["point"];
$menber_id = $_SESSION["menber_id"];
$spot_id = $_GET["spot_id"];

$uploadimg = $_SESSION["review_img"];//画像の名前

$tagarray = array();

//DB接続
$pdo = db_con();

//review_tableへデータ登録SQL作成
$stmt = $pdo->prepare("
INSERT INTO review_table
(review_id, time, money, review_point, comment, menber_id, spot_id, D_point, L_point, created_at)
VALUES
(NULL, :a1, :a2, :a3, :a4, :a5, :a6, :a7, :a8, sysdate())
");
$stmt->bindValue(':a1', $time,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a2', $money,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a3', $point,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a4', $comment,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a5', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a6', $spot_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a7', 0,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a8', 0,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();   //セキュリティにいい書き方
//SQL処理エラー
if($status==false){
//4,データ登録処理後
    db_error($stmt);
}
$review_id = $pdo->lastInsertId('review_id');

//画像の登録
if( isset($uploadimg) & $uploadimg != "" ){
     //アップロードした画像とreview_idを紐付ける
    foreach ($uploadimg as $image_name) {
        $stmt = $pdo->prepare('
        UPDATE images_table 
        SET review_id =:a1
        WHERE image_name=:a2
        ');
        $stmt->bindValue(':a1', $review_id,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a2', $image_name,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();   //セキュリティにいい書き方

        if($status==false){
        //4,データ登録処理後
            db_error($stmt);
        }
    }   
}

//タグの登録
if( $_POST["tag"] != "" ){
    $tagarray = explode(",", $_POST["tag"]);//タグの値を取得して配列に
    foreach ($tagarray as $tag_name) {
        //tags_tableに存在するタグか確認して、なければ登録
        $stmt = $pdo->prepare('
        INSERT tags_table (tag_name, usage_count, menber_id, created_at)
        VALUES(:a1, :a2, :a3, sysdate())
        ON DUPLICATE KEY UPDATE usage_count = usage_count + VALUES(usage_count)
        ');
        $stmt->bindValue(':a1', $tag_name, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a2', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a3', $menber_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();   //セキュリティにいい書き方
        if($status==false){
        //4,データ登録処理後
            db_error($stmt);
        }
        $tag_id = $pdo->lastInsertId('tag_id');
        //tag_review_mapにreview_idと紐付けされるtag_idを登録
        $stmt = $pdo->prepare("
        INSERT INTO tag_review_map_table
        (tag_review_map_id, review_id, tag_id)
        VALUES
        (NULL, :a1, :a2)
        ");
        $stmt->bindValue(':a1', $review_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a2', $tag_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();   //セキュリティにいい書き方
        //SQL処理エラー
        if($status==false){
        //4,データ登録処理後
            db_error($stmt);
        }
    }  
}

//DB接続
$pdo = db_con();

//spot_tableへデータ登録SQL作成
$stmt = $pdo->prepare("
UPDATE spot_table 
SET spot_point =(
    SELECT avg(review_point)
    FROM review_table
    WHERE spot_id = :a1)
WHERE spot_id=:a1

");
$stmt->bindValue(':a1', $spot_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();   //セキュリティにいい書き方
//SQL処理エラー
if($status==false){
//4,データ登録処理後
    db_error($stmt);
}

//口コミを書いたスポットの情報を取得,マイページでスポットをクローズアップする
$stmt = $pdo->prepare("
SELECT lat, lng
FROM spot_table
WHERE spot_id=:a1
");
$stmt->bindValue(':a1', $spot_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();   //セキュリティにいい書き方
//SQL処理エラー
if($status==false){
//4,データ登録処理後
    db_error($stmt);
}
$val = $stmt->fetch(); //1レコードだけ取得する方法

$_SESSION["review_img"] = 0;
header("Location: ../mypage/index.php?lat=".$val["lat"]."&lng=".$val["lng"]);
exit;

?>