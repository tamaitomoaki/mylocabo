<?php
session_start();
include("function.php");

//ログインしていない状態でマイページに入ってきた場合の処理
if(
  !isset($_GET["login"]) || $_GET["login"]=="" 
){
    $login = "";
}else{
    $login = $_GET["login"];
}

$pdo = db_con();

$stmt = $pdo->prepare("
SELECT  A.review_id, A.comment, A.review_state, A.spot_id, A.menber_id, A.D_point, A.L_point, A.review_point, B.image_name, C.spotname, D.name, D.area, D.sex, D.profileimg, E.tags_name, F.category_name 
FROM review_table AS A
LEFT JOIN (
    SELECT review_id, image_name 
    FROM images_table
    GROUP BY review_id 
    )AS B ON A.review_id = B.review_id
LEFT JOIN spot_table AS C ON A.spot_id = C.spot_id
LEFT JOIN menber_table AS D ON A.menber_id = D.menber_id
LEFT JOIN (
    SELECT review_id, group_concat(tag_name) AS tags_name
    FROM tag_review_map_table
    LEFT JOIN tags_table ON tag_review_map_table.tag_id = tags_table.tag_id
    GROUP BY review_id
    )AS E ON A.review_id = E.review_id
LEFT JOIN (
    SELECT spot_id, category_name
    FROM category_spot_map_table
    LEFT JOIN categorys_table ON category_spot_map_table.category_id = categorys_table.category_id
    GROUP BY spot_id
    )AS F ON C.spot_id = F.spot_id
ORDER BY A.review_id desc;
");
$status = $stmt->execute();
if($status==false){
    db_error($stmt);
}

$pagetype =  $_SERVER["REQUEST_URI"];

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>マイロカボ</title>
    <!-- BootstrapのCSS読み込み -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jumbotron.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <link href="css/alert-login.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="js/bootstrap.js"></script>
    <style>
        .jumbotron {
            background: white;
            padding-left: 0px;
        }

        .row .top-jumbotron {
            padding-left: 0px;
        }

        .ad {
            padding-top: 70px;
        }

        .trim {
            overflow: hidden;
            width: 100%;
            position: relative;
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .trim:before {
            content: "";
            display: block;
            padding-top: 62.5%;
        }

        .trim img {
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            width: 100%;
            height: auto;
        }

        .reviewimg-size {
            width: 100%;
        }
        /*        リンク装飾*/

        a:link {}

        a:visited {}

        a:hover {
            text-decoration: none;
        }

        a:active {}
        /*        トップ画面のhr*/

        .tophr {
            margin-top: 15px;
            margin-bottom: 20px;
        }

        .tophr-xs {
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .js-matchHeight {
            margin-bottom: 40px;
        }
        /*スタンスの違いを表す色*/

        .review-name {
            color: #484848;
        }
        /*        ホバーした際のアクション*/

        .tach:hover {
            /*           background-color:#f5f5f5;*/
            opacity: 0.7;
            text-decoration: none;
        }
        /*       navbar-profile画像サイズ*/

        .nav li .profileimg-navbar {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .profileimg-navbar img {
            width: 50px;
            height: auto;
        }
        /*レビュー星のcss*/

        .review-data {
            font-size: 18px;
            margin: 5px 0px 10px;
        }

        .review-data .review-point {
            color: #EB6E00;
            /*            color:#e10000;*/
            font-size: 20px;
            vertical-align: bottom;
        }

        .review-data p {
            margin-bottom: 0px;
            display: inline-block;
            vertical-align: text-top;
            font-size: 14px;
            color: gray;
        }
        /*        レビューカードのデザイン*/

        .index-review h5 {
            color: #484848;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .index-review .comment {
            color: #484848;
            margin: 0px;
            font-size: 18px;
            line-height: 1.5;
        }
        /*        votecss*/

        .point {
            font-size: 12px;
            color: gray;
            margin-top: 10px;
        }
        /*        モーダルウィンドウを開いたときのズレを整えるcss*/
        /*        bootstrap.jsの一部を消去して対処*/

        .modal-open {
            overflow: auto;
        }
        /*        マイロカボとは*/

        .mylocabo {
            color: #8DC63F;
        }
        /*        footer*/

        footer-ul {
            list-style-type: none;
            padding-left: 0;
        }

        .footer-ul li {
            /*	background: #CCC;*/
            width: 50px;
            height: 50px;
            padding: 10px;
            margin: 10px;
            color: gray;
        }

        .footer-ul li a {
            color: gray;
        }

        .footer-ul {
            text-align: left;
        }

        .footer-ul li {
            display: inline;
        }

        .copy {
            color: gray;
        }
        /*        リストのスポットネームcss*/

        .list-spotname {
            display: inline-block;
            margin-bottom: 10px;
        }

        .list-rating-l span,
        .list-rating-s span {
            color: #d2d2d2;
        }

        .list-rating-l .color,
        .list-rating-s .color {
            color: #EB6E00;
        }

        .list-rating-s {
            font-size: 18px;
            display: inline-block;
        }

        .list-rating-l {
            padding-top: 10px;
            font-size: 18px;
            display: inline-block;
        }
        /*レビュー星のcss*/

        .list-display-l {
            display: inline-block;
            margin: 0px;
            line-height: 1.2;
            text-align: right;
        }

        .list-display-xs {
            font-size: 18px;
            margin: 0px 0px 0px;
        }

        .list-display-l .review-point,
        .list-display-xs .review-point {
            color: #EB6E00;
            font-size: 20px;
            vertical-align: baseline;
            margin-left: 5px;
            display: inline-block
        }

        .list-display-l p,
        .list-display-xs p {
            margin-bottom: 0px;
            vertical-align: text-top;
            font-size: 14px;
            color: gray;
        }

        .list-display-l p {
            display: block;
        }

        .list-display-xs p {
            display: inline-block;
            font-size: 14px;
            color: gray;
        }
        /*    リストのコメントと画像のレスポンシブ、グリッドのpaddingを調整*/

        .listRow div {
            padding-left: 0px;
            padding-right: 0px;
        }
        /*        リストのPC画面時の画像のサイズ*/

        .listimg-size {
            width: 100%;
            padding-left: 10px;
        }
        /*        コメントの文字サイズ*/

        .list-content {
            font-size: 18px;
            color: #484848;
            /*            visibility: hidden;*/
        }
    </style>
  </head>
<body>
 <!-- container -->
<div id="index-main">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="./index.php">マイロカボ</a>
                <!--小さいデバイス時のメニューボタン-->
                <?php include( './External/menu-btn-small.php'); ?>
            </div>
            <!--PCデバイス時のメニューボタン-->
            <?php include( './External/menu-btn-large.php'); ?>
            <!--/.navbar-collapse -->
        </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    
    <div class="container">
        <!-- Example row of columns -->
        <div class="row">
            <div class="col-sm-12 col-md-9">
                <div class="row">
                    <div class="jumbotron">
                        <h1>低糖質な暮らしを<br>発見しよう。</h1>
                        <p>スポットや情報など、<br>新しい視点をあなたに。</p>
                        <div class="input-group input-group-lg col-sm-12  col-md-offset-7 col-md-5">
                            <input type="text" class="form-control search-place" placeholder="場所を入力">
                            <span class="input-group-btn">
                                        <button type="button" class="search-map-button btn btn-default">検索</button>
                                    </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-3">
                <div class="ad list-group">
                    <a href="#" class="list-group-item">Link</a>
                    <a href="#" class="list-group-item">Link</a>
                    <a href="#" class="list-group-item">Link</a>
                    <a href="#" class="list-group-item">Link</a>
                    <a href="#" class="list-group-item">Link</a>
                    <a href="#" class="list-group-item">Link</a>
                    <a href="#" class="list-group-item">Link</a>
                    <a href="#" class="list-group-item">Link</a>
                    <a href="#" class="list-group-item">Link</a>
                    <a href="#" class="list-group-item">Link</a>
                </div>
            </div>
        </div>
        <div class="row">
<?php
        $i = 0;
        while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
            $review_id = $result['review_id'];
            $name = $result['name'];
            $review_point = $result['review_point'];
            $review_point_icon = floor($review_point);
            $spotname =  mb_strimwidth($result["spotname"],0,28,"...");
            $comment =  $result['comment'];
            $menber_id = $result['menber_id'];
            $spot_id = $result['spot_id'];
            $profileimg = $result['profileimg'];
            //画像
            if($result['image_name']){
                $reviewimgxs = "<img src='upload/s/".h($result["image_name"])."' class='listimgxs-size visible-xs-block'>";
                $reviewimg = "<img src='upload/s/".h($result["image_name"])."' class='listimg-size hidden-xs'>";
            }else{
                $reviewimgxs  = ""; 
                $reviewimg  = ""; 
            }
            //タグ
            $tag = "";
            if( $result["tags_name"] == false){
                $tag = "";
            }else{
                $tagarray = explode(',',$result["tags_name"]);
                for( $x = 0; $x < count($tagarray);$x++){
                $tag .= "#".$tagarray[$x];
                }
            }
            //カテゴリー
            $category_name = $result["category_name"];
            if ( $category_name == "" ){
                $category_name = "カテゴリー無し";
            }
            
            $D_point = $result["D_point"];
            $L_point = $result["L_point"];
            
            echo "<div class='col-sm-12 col-md-6 js-matchHeight'>\n";
            echo "<a href='review/index.php?review_id=".h($review_id)."'>\n";
            echo "<div class='thumbnail tach clearfix'>\n";
            echo "<div class='index-review'>\n";
            echo "\t<div class='media'>\n";
            
            echo "\t\t<div class='media-left media-top'>\n";
            echo "\t\t\t<img class='media-object img-circle' src='img/profileimg/".h($profileimg)."' alt='...'>\n";
            echo "\t\t</div>\n";
            echo "\t\t<div class='media-body'>\n";
            echo "\t\t\t<h4 class='review-name'>".h($name)."</h4>\n";
            echo "\t\t</div>\n";
            echo "\t</div>\n";
            
            echo "\t<hr class='tophr hidden-xs'>\n";
            echo "\t<hr class='tophr-xs visible-xs-block'>\n";
            echo "\t\t<div class='list-spotname'><h5><strong>".h($spotname)."</strong><br><span class='small'>".h($category_name)."</spna></h5></div>\n";
            switch ($review_point_icon) {
                case 1:
                    echo "\t\t<div class='list-display-l pull-right hidden-xs'>\n";
                    echo "\t\t\t<div class='list-rating-l'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    echo "\t\t<div class='list-display-xs visible-xs-block'>\n";
                    echo "\t\t\t<div class='list-rating-s'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    break;
                case 2:
                    echo "\t\t<div class='list-display-l pull-right hidden-xs'>\n";
                    echo "\t\t\t<div class='list-rating-l'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    echo "\t\t<div class='list-display-xs visible-xs-block'>\n";
                    echo "\t\t\t<div class='list-rating-s'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    break;
                case 3:
                    echo "\t\t<div class='list-display-l pull-right hidden-xs'>\n";
                    echo "\t\t\t<div class='list-rating-l'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    echo "\t\t<div class='list-display-xs visible-xs-block'>\n";
                    echo "\t\t\t<div class='list-rating-s'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    break;
                case 4:
                    echo "\t\t<div class='list-display-l pull-right hidden-xs'>\n";
                    echo "\t\t\t<div class='list-rating-l'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    echo "\t\t<div class='list-display-xs visible-xs-block'>\n";
                    echo "\t\t\t<div class='list-rating-s'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    break;
                case 5:
                    echo "\t\t<div class='list-display-l pull-right hidden-xs'>\n";
                    echo "\t\t\t<div class='list-rating-l'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    echo "\t\t<div class='list-display-xs visible-xs-block'>\n";
                    echo "\t\t\t<div class='list-rating-s'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    break;
                default:
                    echo "\t\t<div class='list-display-l pull-right hidden-xs'>\n";
                    echo "\t\t\t<div class='list-rating-l'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
                    echo "\t\t<div class='list-display-xs visible-xs-block'>\n";
                    echo "\t\t\t<div class='list-rating-s'>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    echo "\t\t\t</div>\n";
                    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
                    echo "\t\t\t<p>　朝/〜¥999</p>\n";
                    echo "\t\t</div>\n";
            }
            echo "\t\t<div class='media-body'>\n";
            echo "\t\t\t<div class='container-fluid'>\n";
            echo "\t\t\t\t<div class='row listRow'>\n";
            if( $reviewimgxs != "" && $reviewimg != ""){
                echo "\t\t\t\t\t<div class='col-xs-12 col-sm-9 col-md-9 list-content'>".h(mb_strimwidth($comment,0,176,"..."))."</div>\n";
            }else{
                echo "\t\t\t\t\t<div class='col-xs-12 col-sm-12 col-md-12 list-content'>".h(mb_strimwidth($comment,0,176,"..."))."</div>\n";
            }
 
            if( $reviewimgxs != "" && $reviewimg != ""){
                echo "\t\t\t\t\t<div class='col-xs-12 col-sm-3 col-md-3'>\n";
                echo "\t\t\t\t\t\t<div class='trim visible-xs-block'>\n";
                echo "\t\t\t\t\t\t\t".$reviewimgxs."\n";
                echo "\t\t\t\t\t\t</div>\n";
                echo "\t\t\t\t\t\t".$reviewimg."\n";
                echo "\t\t\t\t\t</div>\n";
            }else{
            }

            echo "\t\t\t\t</div>\n";
            echo "\t\t\t</div>\n";
            echo "\t\t\t<div class='tagarea'>".h($tag)."</div>\n";
            echo "\t\t\t<div class='point'>\n";
            echo "\t\t\t\t<i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i>\n";
            echo "\t\t\t\t<span class='ico_heart discovery".h($review_id)."'>".h($D_point)."</span>\n";
            echo "\t\t\t\t<i class='glyphicon glyphicon-heart' aria-hidden='true'></i>\n";
            echo "\t\t\t\t<span class='ico_heart like".h($review_id)."'>".h($L_point)."</span>\n";
            echo "\t\t\t\t<div class='small pull-right'>2016/07/24</div>\n";
            echo "\t\t\t</div>\n";
            echo "\t\t</div>\n";

            
            echo "</div>\n";
            echo "</div>\n";
            echo "</a>\n";
            echo "</div>\n";
            
        }        
?>            
        </div>           
    </div>
   
 
  
    <!-- /container -->
    <hr>
    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-8 hidden-xs">
                    <ul class="footer-ul">
                        <li><a href="./guideline.php">コンテンツガイドライン</a></li>
                        <li><a href="./privacy.php">プライバシーポリシー</a></li>
                        <li><a href="./terms.php">利用規約</a></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <p class="pull-right copy">&copy; 2017 マイロカボ</p>
                </div>
            </div>
        </div>
    </footer>
</div>  

<!--スマホサイズのメニュー画面展開画面-->
<?php include( './External/menu-content-small.php'); ?>
<!-- モーダル・ダイアログ　ログイン -->
<?php include ('./External/modal-login.php'); ?>
<!-- モーダル・ダイアログ　マイロカボについて -->
<?php include ('./External/about.php'); ?>
<!--メニュー画面展開スクリプト-->
<script type="text/javascript" src="js/menu.js"></script>
<!--メールアドレスチェックスクリプト-->
<script type="text/javascript" src="js/validation-login.js"></script>

<!--場所検索フォームスクリプト-->
<script type="text/javascript" src="js/search-place.js"></script>



<script>
    $(function(){
      $('.js-matchHeight').matchHeight();
        var login = "<?=$login?>";
        if(login == "no"){
            $(".menu-login").trigger("click");
        }
    });

//    //ログイン画面でエンターを押した時に、submitをさせない処理
    $("#loginform input").on("keydown", function(e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            $("#dummy-btn").trigger("click");
            return false;
        } else {
            return true;
        }
    });

    //要素の非表示
    $("#search").hide();
    $("#review").hide();
    
    //検索、画面の表示切り替え
    $('.search-btn').click(function() {
        $('#index-main').hide();
        $("#search").show();
        $("#review").hide();
        $('#address').focus();
    });
    //投稿、画面の表示切り替え
    $('.review-btn').click(function() {
        $('#index-main').hide();
        $("#search").hide();
        $("#review").show();
    });
    //画面を閉じるicon
    $(".icon-close span").on("click", function() {
        $('#index-main').show();
        $("#search").hide();
        $("#review").hide();
    });
</script>
<script type="text/javascript" src="js/jquery.matchHeight-min.js"></script> 
</body>
</html>