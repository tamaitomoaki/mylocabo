<?php
session_start();

include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え


//$info = var_export($_FILES["image_file"], TRUE);

//$result = array();
//$result['code'] = 200;
//$result['info'] = $info;
////$result['info2'] = $info2;
//echo json_encode($_FILES["image_file"]);



//if($_GET["spot_id"]){
$spot_id = $_GET["spot_id"];
//}else{
//   $spot_id = $_SESSION["review_spot_id"]; 
//}

//$spot_id = $_SESSION["review_spot_id"]; 




$pdo = db_con();

$reviewimg ="";
$msg = "";
$arrayimg = array();



//if($_GET["spot_id"]){
//    //口コミ編集の場合
//}else{
//　　//口コミ作成の場合
//   //アップロードした画像を登録完了まで保持する
//    $uploadimg = array();
//    $uploadimg= $_SESSION["review_img"];
//}
//アップロードした画像を登録完了まで保持する
//    $uploadimg = array();
//    $uploadimg= $_SESSION["review_img"];




// ファイル処理
// 複数ファイルのアップロード対応
foreach ($_FILES["upreviewimg"]["error"] as $key => $value) {
    
    
    
    // アップロード成功した際の処理
    if (!isset($_FILES["upreviewimg"]["error"][$key]) || !is_int($_FILES["upreviewimg"]["error"][$key]) ){
        //画像複数選択のとき、その数分チェックしているが、その中の１つがエラーの場合どうしよう。
        echo "File Upload Error !!";
        exit;
        
    }else{
        $tmp_path = $_FILES["upreviewimg"]["tmp_name"][$key];
        $uniq_name = fileUniqRename($_FILES["upreviewimg"]["name"][$key],$key);  //func.phpに関数を用意！
         if ( is_uploaded_file( $tmp_path ) ) {
             
             if ( move_uploaded_file( $tmp_path, "../upload/".$uniq_name ) ) {
                 chmod( "../upload/".$uniq_name, 0644 );
                 $reviewimg = $uniq_name;
                 $arrayimg[] = $uniq_name;
                 
//                 if($_GET["spot_id"]){
//                     //口コミ編集の場合
//                }else{
//                     //口コミ作成の場合
//                   $uploadimg[] = $uniq_name;
//                }
                 //画像をフォルダにアップロード完了、その画像をリサイズしてサムネイルに
                 $file = $uniq_name;
                 $imagesize = getimagesize("../upload/".$file);
                 
                 $width = $imagesize[0];
                 $height = $imagesize[1];  
                 //元画像の縦横の大きさを比べてどちらかにあわせる
                // なおかつ縦横の差をコピー開始位置として使えるようセット
                if($width > $height){
                    $diff  = ($width - $height) * 0.5; 
                    $diffW = $height;
                    $diffH = $height;
                    $diffY = 0;
                    $diffX = $diff;
                }elseif($width < $height){
                    $diff  = ($height - $width) * 0.5; 
                    $diffW = $width;
                    $diffH = $width;
                    $diffY = $diff;
                    $diffX = 0;
                }elseif($width === $height){
                    $diffW = $width;
                    $diffH = $height;
                    $diffY = 0;
                    $diffX = 0;
                }
                 $width_s = 300;
                 $height_s = 300;
                 //サムネイルの画像ディレクトリ
                 $thumb_dir = "../upload/s/";
                 $thumb_name = $thumb_dir.$uniq_name;
                 
                 
                 
                 $image = imagecreatefromjpeg("../upload/".$uniq_name);
                 $image_s = imagecreatetruecolor($width_s,$height_s);
                 $result = imagecopyresampled($image_s, $image, 0, 0, $diffX, $diffY, $width_s, $height_s, $diffW, $diffH);
                 if($result){
                     if( imagejpeg($image_s,$thumb_name)){
                         $msg="サムネイル作成成功";
                     }else{
                         $msg="サムネイル作成失敗";
                     }
                 }else{
                     $msg="サンプリング失敗";
                 }
                 //取り出した元画像とサンプリングのための作成したベースが画像を削除する
                 imagedestroy($image);
                 imagedestroy($image_s);
                 //３．images_tableへデータ登録SQL作成　 
                 $stmt = $pdo->prepare("
                 INSERT INTO images_table 
                 (image_id, image_name, spot_id)
                 VALUES 
                 (NULL, :a1, :a2) 
                 ");
                 $stmt->bindValue(':a1', $reviewimg, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)  
                 $stmt->bindValue(':a2', $spot_id, PDO::PARAM_INT); //Integer（数値の場合 PDO::PARAM_INT) 
                 $status = $stmt->execute(); //セキュリティにいい書き方 
                 //SQL処理エラー 
                 if($status==false){
                     //4,データ登録処理後 
                     db_error($stmt);
                 } 
//                 header( "Location: newreview-registration-write.php?test=".$reviewimg );
        //         header( "Location: newreview-registration-write.php?img=".urlencode($uniq_name) );
//                 exit; 
             } else {
                 echo "Error:. Fileupload OK!!"; 
             } 
         }
    }
}


//if($_GET["spot_id"]){
//    //口コミ編集の場合
//}else{
//　　//口コミ作成の場合
//   $_SESSION["review_img"] = $uploadimg;
//}
//$_SESSION["review_img"] = $uploadimg;
//header( "Location: newreview-registration-write.php" );
echo json_encode($arrayimg);
exit;

        
?>
