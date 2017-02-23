<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え


//var_dump($_POST["uploadtag"]);
//var_dump($_POST["deletetag"]);
//
//exit();

//入力チェック(受信確認処理追加)
//コメントをセッションに入れているので、送られてきていないのでページが必ず戻る
if(
  !isset($_POST["time"]) || $_POST["time"]==""  ||
  !isset($_POST["money"]) || $_POST["money"]==""  ||
  !isset($_POST["comment"]) || $_POST["comment"]==""  ||
  !isset($_POST["review_id"]) || $_POST["review_id"]==""  ||
  !isset($_POST["spot_id"]) || $_POST["spot_id"]==""  ||
  !isset($_POST["point"]) || $_POST["point"]==""
)
{
  //未記入がある場合、エラー
    header( "Location: ../index.php");
    exit;
}
 


//取得データを変数へ
$time = $_POST["time"];
$money = $_POST["money"];
$comment = $_POST["comment"];
$point = $_POST["point"];
$review_id = $_POST["review_id"];
$menber_id = $_SESSION["menber_id"];
$spot_id = $_POST["spot_id"];
//$stance = $_SESSION["stance_num"];


//DB接続
$pdo = db_con();

//review_tableへデータ登録SQL作成
    $stmt = $pdo->prepare("
    UPDATE review_table 
    SET time = :a1, money = :a2, comment =:a3, review_point = :a4, updated_at = sysdate() 
    WHERE review_id = :a5
    ");
    $stmt->bindValue(':a1', $time,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a2', $money,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a3', $comment,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a4', $point,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a5', $review_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $status = $stmt->execute();   //セキュリティにいい書き方
    //SQL処理エラー
    if($status==false){
    //4,データ登録処理後
        db_error($stmt);
    }

//画像処理
if( $_POST["uploadimg"] != "" ){
    $uploadimg = explode(",",$_POST["uploadimg"]);
    //アップロードした画像とreview_idを紐付ける
    foreach ($uploadimg as $image_name) {
        $stmt = $pdo->prepare('
        UPDATE images_table 
        SET review_id =:a1
        WHERE image_name=:a2
        ');
        $stmt->bindValue(':a1', $review_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $stmt->bindValue(':a2', $image_name,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();   //セキュリティにいい書き方

        if($status==false){
        //4,データ登録処理後
            db_error($stmt);
        }
    }
}

if( $_POST["deleteimg"] != "" ){
    $deleteimg = explode(",",$_POST["deleteimg"]);
    //編集時に削除した画像を実際に削除する
    foreach ($deleteimg as $image_name) {
        $stmt = $pdo->prepare("
        DELETE FROM images_table 
        WHERE image_name=:a1
        ");
        $stmt ->bindValue(':a1',$image_name,PDO::PARAM_INT);
        $status = $stmt->execute();//セキュリティにいい書き方
        if($status==false){
            db_error($stmt);
        }

        unlink("../upload/".$image_name);
        unlink("../upload/s/".$image_name);
    }
}

//タグ処理
//配列作成
$uploadtag = explode(",",$_POST["uploadtag"]);//文字データを配列化
$delete = array("");//取得したuploadtag配列の中の消されているデータを削除
$result = array_diff($uploadtag, $delete);//削除実行
$result = array_values($result);//indexを詰める
    //アップロード
    if( !empty($_POST["uploadtag"]) ){

        //アップロードした画像とreview_idを紐付ける
        foreach ($result as $tag_name) {
            //tags_tableに存在するタグか確認して、なければ登録
            $stmt = $pdo->prepare('
            INSERT tags_table (tag_name, usage_count, menber_id, created_at)
            VALUES(:a1, :a2, :a3, sysdate())
            ON DUPLICATE KEY UPDATE usage_count = usage_count + VALUES(usage_count)
            ');
            //タグ　 ON DUPLICATE KEY UPDATE tag_id = LAST_INSERT_ID(tag_id), usage_count = usage_count + VALUES(usage_count)が記述されていたが、なんでLAST_INSERT_IDをしているのかわからない、アップデートするならカウントだけでいいのでは？カウントだけにしてみた
            $stmt->bindValue(':a1', $tag_name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
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
    //デリート
    if( !empty($_POST["deletetag"])){
        $deletetag = explode(",",$_POST["deletetag"]);

        //編集時に削除した画像を実際に削除する
        foreach ($deletetag as $tag_review_map_id) {
        //tags_tableのカウントをマイナス１
            $stmt = $pdo->prepare("
            UPDATE tags_table 
            SET usage_count = usage_count - 1
            WHERE tag_id = (
                SELECT tag_id
                FROM tag_review_map_table
                WHERE tag_review_map_id = :a1
                )
            ");
            $stmt->bindValue(':a1', $tag_review_map_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
            $status = $stmt->execute();   //セキュリティにいい書き方
            //SQL処理エラー
            if($status==false){
            //4,データ登録処理後
                db_error($stmt);
            }
        //tag_review_map_tableから紐付けしているデータを削除
            $stmt = $pdo->prepare("
            DELETE 
            FROM tag_review_map_table 
            WHERE tag_review_map_id = :a1;
            ");
            $stmt ->bindValue(':a1',$tag_review_map_id ,PDO::PARAM_INT);
            $status = $stmt->execute();//セキュリティにいい書き方
            if($status==false){
                db_error($stmt);
            }

        }
    }
//        var_dump("test");
//        exit;




//DB接続
//$pdo = db_con();

//spot_tableにレビューの評価ポイントの平均点を挿入
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












////口コミ投稿時にセットした変数を破棄
//unset($_SESSION["review_spot_id"],
//      $_SESSION["review_spotname"],
//      $_SESSION["review_comment"],
//      $_SESSION["review_point"]
//     ); 
//$_SESSION["review_img"] = 0;
header("Location: ../mypage/index.php");
exit;

?>