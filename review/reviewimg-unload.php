<?php
session_start();

include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え

//画像をアップロードはしたが、口コミ投稿せずに終了した場合に投稿と紐付けされていない画像削除
$pdo = db_con();

$uploadimg = $_SESSION["review_img"];





// 削除するレコードが複数の場合はカンマ区切りで追加する
 

foreach ($uploadimg as $image_name) {
    $stmt = $pdo->prepare("
    DELETE FROM images_table 
    WHERE image_name=:a1
    ");
    $stmt ->bindValue(':a1',$image_name,PDO::PARAM_STR);
    $status = $stmt->execute();//セキュリティにいい書き方
    if($status==false){
        db_error($stmt);
    }
    unlink("../upload/".$image_name);
    unlink("../upload/s/".$image_name);
}


//echo json_encode($uploadimg);
//exit;











//3.UPDATE gs_an_table SET ....; で更新(bindValue)
//　基本的にinsert.phpの処理の流れです。
//header("Location: newreview-registration-write.php");
//echo $image_name;
//exit;



?>
