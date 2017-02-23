<?php
session_start();
include("../function.php");

//入力チェック(受信確認処理追加)
if(
  !isset($_POST["email"]) || $_POST["email"]=="" ||
  !isset($_POST["password"]) || $_POST["password"]==""
){
    header("Location: index.php");
    exit();
}

//パスワードのバリデーション

if ( !preg_match('/\A[0-9a-zA-Z]{8,12}\Z/', $_POST["password"]) ){
    header("Location: index.php");
    exit();
}

//メールアドレスのバリデーション
//emailのバリデーション

if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    header("Location: index.php");
    exit();
}
$pdo = db_con();

$name  = strstr($_POST["email"], "@" , TRUE);
$email  = $_POST["email"];
$password = $_POST["password"];
$profileimg = "Default-profileimg.png";
//未記入
$area = 48;
$sex = 2;
$introduction = "未記入";
$token = md5(microtime());

//３．新規会員登録SQL作成　
$stmt = $pdo->prepare("
INSERT INTO pre_menber_table
(pre_menber_id, name, email, password, profileimg, area, sex, introduction, token, date)
VALUES
(NULL, :a1, :a2, :a3, :a4, :a6, :a7, :a8, :a9, now() )
");
$stmt->bindValue(':a1', $name,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a2', $email,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a3', $password,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a4', $profileimg,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a6', $area,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a7', $sex,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a8', $introduction,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':a9', $token,  PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();   //セキュリティにいい書き方

if($status==false){
//4,データ登録処理後
 //   db_error($stmt);
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
}



$title = "マイロカボの仮登録が完了しました。";
$content = "<p>".$name."様。</p><p>この度は、ラントリップに仮登録いただきありがとうございます。</p><p>本登録するためにはあとワンステップ必要です。</p><p>下記URLにアクセスしていただくと本登録が完了します。</p><p>http://mylocabo.from.tv/newmenber-r/act.php?token=".$token."</p><hr>";
mb_language("japanese");
mb_internal_encoding("UTF-8");

require("PHPMailer/PHPMailerAutoload.php");
$mailer = new PHPMailer();
$mailer->IsSMTP();
$mailer->Host = 'ssl://smtp.gmail.com:465';
$mailer->SMTPAuth = TRUE;
$mailer->Username = 'mylocabo@gmail.com';  // Gmailのアカウント名
$mailer->Password = 'lowcarbolife';  // Gmailのパスワード
//送信者
$mailer->From     = 'mylocabo@gmail.com';  // Fromのメールアドレス
$mailer->FromName = mb_encode_mimeheader(mb_convert_encoding("マイロカボ","JIS","UTF-8"));//Fromの名前
$mailer->Subject  = mb_encode_mimeheader(mb_convert_encoding($title,"JIS","UTF-8"));//メールのタイトル
$mailer->isHTML(true);//HTMLメールの場合
$mailer->Body     = mb_convert_encoding($content,"JIS","UTF-8");//メールの内容です！
//宛先
$mailer->AddAddress($email); // 宛先
$mailer->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);



if( !$mailer->Send() ){
	echo "送信エラー<br/ >";
	echo "Mailer Error: " . $mailer->ErrorInfo;
} else {
    header("Location: pre.php");
	echo "送信完了";
}
?>