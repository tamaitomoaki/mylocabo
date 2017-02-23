<?php
session_start();
include("../function.php");
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
                    <h1>仮登録完了。</h1>
                </div>
                <h2>登録いただいたメールアドレス宛に本登録用URLを送信しましたのでご確認ください。URLをクリックしていただくことで登録完了となります。</h2>
                
            </div>
        </div>
    </div>   
</body>
</html>