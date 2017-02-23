<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え
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
        /*スタンスの違いを表す色*/
        
        .marker_yellow_hoso {
            background: linear-gradient(transparent 60%, #ffff66 60%);
            color: #545454;
        }
        
        .marker_red_hoso {
            background: linear-gradient(transparent 60%, #ff7466 60%);
            color: #545454;
        }
        
        .marker_green_hoso {
            background: linear-gradient(transparent 60%, #ceff66 60%);
            color: #545454;
        }
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
                    <h1>URLの有効期限切れです。</h1>
                </div>
                <h2>もう一度初めから会員登録をお願いします。</h2>
                
            </div>
        </div>
    </div>

       
</body>
</html>