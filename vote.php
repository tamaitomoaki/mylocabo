<?php
session_start();
include("function.php");
sessionCheck();//セッションの入れ替え

//入力チェック(受信確認処理追加)
if(
  !isset($_GET['type']) || $_GET['type']==""||
  !isset($_GET['review_id']) || $_GET['review_id']==""||
  !isset($_GET['count']) || $_GET['count']==""
)
{
  //未記入がある場合、エラー
    header( "Location: newreview-registration-write.php");
    exit;
}

$type	= $_GET['type'];
if( $type == "D"){
    $type = 0;//D
}elseif( $type == "L"){
    $type = 1;//D
}
$review_id	= $_GET['review_id'];
$count	= $_GET['count'];
$processing	= $_GET['processing'];
$menber_id = $_SESSION["menber_id"];
 
//DB接続
$pdo = db_con();

if( $type == 0){
    //review_tableへD_pointの増減用SQL作成　
    $stmt = $pdo->prepare("
    UPDATE review_table
    SET D_point=:a1
    WHERE review_id=:a2
    ");
    $stmt->bindValue(':a1', $count,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a2', $review_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $status = $stmt->execute();   //セキュリティにいい書き方
    if($status==false){
        db_error($stmt);
    }
}elseif($type == 1){
    //review_tableへlike_pointの増減用SQL作成　　
    $stmt = $pdo->prepare("
    UPDATE review_table
    SET L_point=:a1
    WHERE review_id=:a2
    ");
    $stmt->bindValue(':a1', $count,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a2', $review_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $status = $stmt->execute();   //セキュリティにいい書き方
    if($status==false){
        db_error($stmt);
    }
}

//２、review_tableへcommentのみデータ登録SQL作成　
if( $processing == "plus"){
    
    $stmt = $pdo->prepare("
    INSERT INTO vote_table 
    (review_id,menber_id, type)
    VALUES 
    (:a1, :a2, :a3) 
    ");
    $stmt->bindValue(':a1', $review_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a2', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a3', $type,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

    $status = $stmt->execute();   //セキュリティにいい書き方
    //SQL処理エラー
    if($status==false){
    //4,データ登録処理後
        db_error($stmt);
    }   
}elseif($processing == "minus"){
    $stmt = $pdo->prepare("
    DELETE FROM vote_table 
    WHERE review_id = :a1 AND menber_id = :a2 AND type = :a3;
    ");
    $stmt->bindValue(':a1', $review_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a2', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a3', $type,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

    $status = $stmt->execute();   //セキュリティにいい書き方
    //SQL処理エラー
    if($status==false){
    //4,データ登録処理後
        db_error($stmt);
    } 
}

echo $menber_id;

?>