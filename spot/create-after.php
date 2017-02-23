<?php
session_start();
include("../function.php");
sessionCheck();//セッションの入れ替え

$spot_id = $_GET["spot_id"];

$pdo = db_con();

//２．データ取得SQL作成
$stmt = $pdo->prepare('
SELECT spot_id, spotname
FROM spot_table
WHERE spot_id=:a1
');
$stmt->bindValue(':a1', $spot_id);
$res = $stmt->execute();

//SQL実行時にエラーがある場合
if($res==false){
  db_error($stmt);
}

//３．抽出データ数を取得
//$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()
$val = $stmt->fetch(); //1レコードだけ取得する方法

$spot_id = $val["spot_id"];
$spotname = $val["spotname"];
$menber_id = $_SESSION["menber_id"];
$name = $_SESSION["name"];

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
        .mypage-header {
            color: white;
            background: #444444;
            padding-top: 10px;
            padding-bottom: 10px;
        }
    </style>
    </head>

<body>
    <!-- container -->
    <div id="index-main">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../index.php">マイロカボ</a>
                </div>
            </div>
        </nav>
    </div>
    <!-- /container -->
    <div class="mypage-header">
        <div class="container">
            <?=$name?>
        </div>

    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>登録完了です！</h1>
                </div>

                <!--メインコンテンツ-->
                <div class="spot-padding">
                    <h2><strong><?=$spotname?>の情報<br>
                ありがとうございました！</strong></h2>
                    <h3>少しずつですが<br> 新しい世界が形になっていますね。
                        <br> 一歩一歩着実に、
                        <br> 地図上に私たちの世界を
                        <br> 作り上げましょう！！
                    </h3>
                </div>
                <a class="list-group-item nonborder" href="../review/create.php?spot_id=<?=$spot_id?>">
                    <div class="media border-round">
                        <span class="media-left  media-middle" href="#">
                                    <p class="icon-edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></p>

                        </span>
                        <div class="media-body">

                            <h3 class="media-heading"><strong><?=$spotname?>の口コミを投稿しましょう！</strong></h3>
                            <h4>
                                <?=$name?>さんの口コミがみんなの参考になります。いろいろと教えてください！</h4>
                            <!--                            <p class="lead link">口コミを書く</p>-->
                        </div>
                    </div>
                </a>
                <a class="list-group-item nonborder" href="#">
                    <div class="media border-round">
                        <span class="media-left media-middle" href="#">
                                    <p class="icon-edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></p>

                        </span>
                        <div class="media-body">

                            <h3 class="media-heading"><strong><?=$spotname?>のさらに詳細をご存知ですか？</strong></h3>
                            <h4>他にもあると便利な情報があります。より豊富な情報を共有しましょう！</h4>
                            <!--                            <p class="lead link">編集する</p>-->
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>

</html>
