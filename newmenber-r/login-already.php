<?php
session_start();
include("../function.php");

//値を受け取っているか確認
if(
  !isset($_GET["email"]) || $_GET["email"]=="" 
){
    header("location: ../index.php");
    exit();
}

$email = $_GET["email"];

$pdo = db_con();
//メールアドレスが登録済みかどうか
$stmt = $pdo->prepare('
SELECT email, profileimg, name
FROM menber_table
WHERE email = :a1
');
$stmt->bindValue(':a1', $email,   PDO::PARAM_STR);
$res = $stmt->execute();

$val = $stmt->fetch();
//SQL実行時にエラーがある場合
if($res==false){
  db_error($stmt);
}

$email = $val["email"];
$profileimg = $val["profileimg"];
$name = $val["name"];
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>マイロカボ</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/jumbotron.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../js/bootstrap.js"></script>
    <style>
        /*    モーダルのデザイン*/

        .modal-footer {
            text-align: left;
            color: gray;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="../index.php">マイロカボ</a>
            </div>

            <!--/.navbar-collapse -->
        </div>
    </nav>
    <!--メインコンテンツ-->
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
                <div class="page-header">
                    <h1>すでに登録済みです。</h1>
                </div>
                <h3><?=$name?></h3>
                <h3><?=$email?></h3>
                <form name="login-already" action="../login_act.php" method="post" id="login-already">
                    <div class="form-group">
                        <input type="password" class="form-control input-lg validate[required]" name="password" id="password" placeholder="パスワード" autocomplete="off" required>
                    </div>
                    <input type="hidden" id="email" name="email" value="<?=h($email)?>">
                    <p>
                        <button type="submit" class="btn btn-success btn-lg btn-block" id="registration">ログイン</button>
                    </p>
                </form>
            </div>
        </div>
    </div>  
</body>
</html>