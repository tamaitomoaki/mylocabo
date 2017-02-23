<?php


session_start();

include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え


$delete_image = substr($_GET["image_name"],12);//削除したい画像








//$pdo = db_con();
//
//$stmt = $pdo->prepare("
//DELETE FROM images_table 
//WHERE image_name=:a1
//");
//$stmt ->bindValue(':a1',$image_name,PDO::PARAM_INT);
//$status = $stmt->execute();//セキュリティにいい書き方
//if($status==false){
//    db_error($stmt);
//}
//
//unlink("../upload/".$image_name);
//unlink("../upload/s/".$image_name);


//3.UPDATE gs_an_table SET ....; で更新(bindValue)
//　基本的にinsert.phpの処理の流れです。
//header("Location: newreview-registration-write.php");

echo json_encode($delete_image);
exit;



?>
