<?php

session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え

//入力チェック(受信確認処理追加)
if(
  !isset($_POST["area"]) || $_POST["area"]=="" ||
  !isset($_POST["stance"]) || $_POST["stance"]=="" ||
  !isset($_POST["sex"]) || $_POST["sex"]=="" ||
  !isset($_POST["introduction"]) || $_POST["introduction"]==""
)
{
    //未記入がある場合、profile-edit.phpへ戻る
    header( "Location: profile-edit.php" );
    exit;
}   
if($_POST["profileimg"]){
    //プロフィール画像が選択されていない場合はデフォルトの画像がせっていされる
    $profileimg = $_POST["profileimg"];
    
}elseif( isset($_FILES['profileimg']['error']) || is_int($_FILES['profileimg']['error'])){
    //プロフィール画像が選択されている場合

    //画像をuploadフォルダに移動 
    $tmp_path = $_FILES["profileimg"]["tmp_name"]; //"/usr/www/tmp/1.jpg"アップロード先のTempフォルダ 
    $uniq_name = fileUniqRenameSingle($_FILES["profileimg"]["name"]); //"1.jpg"ファイル名取得//func.phpに関数を用意！
    // FileUpload [--Start--]
    if ( is_uploaded_file( $tmp_path ) ) {
        if ( move_uploaded_file( $tmp_path, "upload/".$uniq_name ) ) {
            chmod( "upload/".$uniq_name, 0644 ); 
            //画像の名前を変数に代入
            $profileimg = $uniq_name;
            //デフォルト画像がunlinkされないための処理
            if ( $_SESSION["profileimg"] != "Default-profileimg.png"){
                unlink("./upload/".$_SESSION["profileimg"]);
            }
        } else {
            echo "ファイルのアップロードが出来ませんでした。";
        }
    }
// FileUpload [--End--]
}    
$introduction = $_POST["introduction"];
//$SAS = TOn($_POST['stance'],$_POST['area'],$_POST['sex']);
$stance = $_POST['stance'];
$area = $_POST['area'];
$sex = $_POST['sex'];
$profileimg = "Default-profileimg.png";
$menber_id = $_SESSION["menber_id"];


//2. DB接続します(エラー処理追加)　定番の表現　変更点はDbConnectErrorくらい
$pdo = db_con();

//３．データ更新SQL作成　
$stmt = $pdo->prepare('UPDATE menber_table SET profileimg=:a1,stance=:a2,area=:a3,sex=:a4,introduction=:a5 WHERE menber_id=:a6');
$stmt->bindValue(':a1', $profileimg,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a2', $stance,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a3', $area,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a4', $sex,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a5', $introduction,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a6', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();   //セキュリティにいい書き方

if($status==false){
//4,データ登録処理後
    db_error($stmt);
}

//２．データ取得SQL作成
$stmt = $pdo->prepare('SELECT * FROM menber_table WHERE menber_id=:a1');
$stmt->bindValue(':a1', $menber_id);
$res = $stmt->execute();

//SQL実行時にエラーがある場合
if($res==false){
  db_error($stmt);
}

//３．抽出データ数を取得
//$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()
$val = $stmt->fetch(); //1レコードだけ取得する方法

//４. 該当レコードがあればSESSIONに値を代入
if( $val["menber_id"] != "" ){
  loginSessionSet($val);
  header("Location: profile-edit.php");
}else{
  header("Location: profile-edit.php?erro=1");
}

exit();

?>
