<?php
session_start();
include("function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
//sessionCheck();//セッションの入れ替え


//入力チェック(受信確認処理追加)
if(
  !isset($_POST["email"]) || $_POST["email"]=="" ||
  !isset($_POST["password"]) || $_POST["password"]==""
){
    header("location: ./index.php");
//    echo "ok";
    exit();
}


//ログイン画面からPOSTされたデータを変数へ
$email = $_POST["email"];
$password = $_POST["password"];

//1. 接続します
$pdo = db_con();

//２．データ取得SQL作成
$stmt = $pdo->prepare('SELECT * FROM menber_table WHERE email=:a AND password=:b');
$stmt->bindValue(':a', $email);
$stmt->bindValue(':b', $password);
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
    //menber_tableの最終ログインをUPDATAで情報更新
        $stmt = $pdo->prepare("
        UPDATE menber_table 
        SET last_login_at = sysdate()
        WHERE menber_id = :a1
        ");
        $stmt->bindValue(':a1', $val["menber_id"],  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
        $status = $stmt->execute();   //セキュリティにいい書き方
        //SQL処理エラー
        if($status==false){
        //4,データ登録処理後
            db_error($stmt);
        }
  loginSessionSet($val);
  header("Location: ./mypage/index.php");
}else{
  //logout処理を経由して全画面へ
  header("Location: ./index.php");
}

exit();



?>

