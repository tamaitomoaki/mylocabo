<?php
session_start();
include("../function.php");

//入力チェック(受信確認処理追加)
if(
  !isset($_GET["spot_id"]) || $_GET["spot_id"]=="" 
){
//  exit('ParamError');
    header("location: ../index.php");
//    echo "ok";
    exit();
}

$spot_id = $_GET["spot_id"];

$pdo = db_con();

$stmt = $pdo->prepare("
SELECT A.spot_id, A.spotname, A.address, A.lat, A.lng, A.url, A.tel, A.open, A.spot_point, B.review_id, B.time, B.money, B.comment, B.review_state, B.D_point, B.L_point, B.review_point, C.menber_id, C.name, C.profileimg, D.image_name, E.images_name, F.tags_name, G.categorys_name
FROM spot_table AS A
LEFT JOIN review_table AS B ON A.spot_id = B.spot_id
LEFT JOIN menber_table AS C ON B.menber_id = C.menber_id
LEFT JOIN (
    SELECT review_id, image_name 
    FROM images_table
    GROUP BY review_id 
    ) AS D ON B.review_id = D.review_id
LEFT JOIN (
    SELECT spot_id,group_concat(image_name) AS images_name 
    FROM images_table
    GROUP BY spot_id
    )AS E ON A.spot_id = E.spot_id
LEFT JOIN (
    SELECT review_id, group_concat(tag_name) AS tags_name
    FROM tag_review_map_table
    LEFT JOIN tags_table ON tag_review_map_table.tag_id = tags_table.tag_id
    GROUP BY review_id
    )AS F ON B.review_id = F.review_id
LEFT JOIN (
    SELECT spot_id, group_concat(category_name) AS categorys_name
    FROM category_spot_map_table
    LEFT JOIN categorys_table ON category_spot_map_table.category_id = categorys_table.category_id
    GROUP BY spot_id
    )AS G ON A.spot_id = G.spot_id
WHERE A.spot_id =:a1 ;
"
);
$stmt->bindValue(':a1', $spot_id, PDO::PARAM_INT);
$status = $stmt->execute();
//SQL実行時にエラーがある場合
if($status==false){
  db_error($stmt);
}


//ショップデータの配列を作成
$shopdata = array();
while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $shopdata[] = $result;
}
$spotname = $shopdata[0]["spotname"];
$address = $shopdata[0]["address"];
$lat = $shopdata[0]["lat"];
$lng = $shopdata[0]["lng"];
$url = $shopdata[0]["url"];
$tel = $shopdata[0]["tel"];
$open = $shopdata[0]["open"];
$spot_point = round($shopdata[0]["spot_point"], 2);//スポットのポイントを丸める
$spot_point_icon = floor($spot_point);
//スポットに紐付いた画像全てを取得
$spotimg = "";
$images_count = "";
if( $shopdata[0]["images_name"] == false){
    $images_count = 0;
    $spotimg = "";
}else{
    $image_name = explode(',',$shopdata[0]["images_name"]);
    $images_count = count($image_name);
    if( $images_count < 4 ){
        for( $i = 0; $i < count($image_name);$i++){
            $spotimg .= "<img src='../upload/s/".h($image_name[$i])."' class='spotpageimg-size images' data-toggle='modal' data-target='#spotimageslist'>";
        }
    }else{
        for( $i = 0; $i < 3;$i++){
            $spotimg .= "<img src='../upload/s/".h($image_name[$i])."' class='spotpageimg-size images' data-toggle='modal' data-target='#spotimageslist'>";
        }
    }
}

//カテゴリー
$category = "";
if( $shopdata[0]["categorys_name"] == false){
    $category = "";
}else{
    $categoryarray = explode(',',$shopdata[0]["categorys_name"]);
    for( $x = 0; $x < count($categoryarray);$x++){
    $category .= $categoryarray[$x];
    }
}
//スポット情報の確認、入力されていなければ、”情報が不足しています。”
if ( $url == "" ){
    $url = "情報が不足しています。";
}
if ( $tel == "" ){
    $tel = "情報が不足しています。";
}
if ( $open == "" ){
    $open = "情報が不足しています。";
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
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/jumbotron.css" rel="stylesheet">
    <link href="../css/custom.css" rel="stylesheet">
    <link href="../css/menu.css" rel="stylesheet">
    <link href="../css/alert-login.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../js/bootstrap.js"></script>
    <style>
        .jumbotron {
            background: white;
            padding-left: 0px;
        }

        .spotmap {
            width: 100%;
        }

        .prof-img-size {
            width: 50px;
        }

        .spotreviews .list-group .list-group-item {
            border: none;
        }
        /*スペース管理*/

        .space-pc {
            height: 70px;
        }

        .spance-ad {
            height: 70px;
        }

        .marker_yellow_hoso,
        .marker_red_hoso,
        .marker_green_hoso {
            color: #545454;
            /*            margin-left:0.3em;*/
            vertical-align: middle;
        }
        /*        ホバーした際のアクション*/

        .tach:hover {
            background-color: #f5f5f5;
            text-decoration: none;
        }
        /*        リンク装飾*/

        a:link {}

        a:visited {}

        a:hover {
            text-decoration: none;
        }

        a:active {}
        /*        リストcss*/

        .list-media {
            padding: 10px;
        }
        /*        リストの投稿画像サイズ指定*/

        .list-img {
            width: 25%;
        }

        .list-img img {
            width: 100%;
        }
        /*        リストプロフィール画像*/

        .list-profileimg {
            width: 30px;
            display: inline-block;
        }

        .list-name {
            display: inline-block;
        }

        .list-content {
            font-size: 18px;
            color: #484848;
        }

        .list-display-l {
            display: inline-block;
            margin: 0px;
            line-height: 1.2;
            text-align: right;
        }

        .list-rating {
            display: inline-block;
            padding-top: 10px;
        }

        .point {
            color: gray;
            font-size: 10px;
        }

        .media-header {
            margin-bottom: 5px;
        }
        /*レビュー星のcss*/

        .list-rating span {
            color: #d2d2d2;
        }

        .list-rating .color {
            color: #EB6E00;
        }

        .spot-rating p {
            display: inline-block;
            font-size: 35px;
            vertical-align: baseline;
            margin-bottom: 0px;
            color: #EB6E00;
        }
        /*スポットのレビュー星のcss*/

        .spot-rating span {
            color: #d2d2d2;
            font-size: 25px;
        }

        .spot-rating .color {
            color: #EB6E00;
        }
        /*        口コミ投稿*/
        /*スポット情報編集*/

        .review p,
        .spot-edit {
            font-size: 18px;
        }
        /*                画像フォーカス時のcss*/
        /*        スポット口コミリストのcss*/

        .spotreviews .media-heading {
            display: inline-block;
        }

        .spotreviews span {}
        /*ログインモーダルのズレを修正*/

        .modal-open {
            overflow: auto;
        }
        /*        スポットプロフィール*/

        .spotprofile .name {
            font-size: 36px;
            margin: 20px 0px 5px;
            line-height: 1.1;
            color: #484848;
        }

        .spotprofile .category {
            font-size: 18px;
            color: gray;
        }

        .spotprofile .address,
        .spotprofile .url,
        .spotprofile .tel,
        .spotprofile .open {
            font-size: 18px;
            color: #484848;
            margin-bottom: 20px;
        }

        .spotprofile h4 {
            margin-bottom: 0px;
            color: #484848;
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
            /* 高さを幅の75%に固定 */
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

        .reviewimg-size,
        .listimg-size {
            width: 100%;
        }

        .listimg-size {
            padding-left: 10px;
        }

        .listimgxs-size {
            padding-left: 0px;
        }

        .list-spotname {
            display: inline-block;
            margin: 10px 0px;
        }

        .list-spotname img {
            display: inline-block;
        }

        .list-spotname h4 {
            color: #484848;
            display: inline-block;
        }

        .list-content {
            font-size: 18px;
            color: #484848;
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
            margin: 5px 0px 0px;
        }

        .list-display-l .review-point,
        .list-display-xs .review-point {
            color: #EB6E00;
            font-size: 20px;
            vertical-align: bottom;
            display: inline-block;
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

        .listRow div {
            padding-left: 0px;
            padding-right: 0px;
        }

        .point {
            font-size: 12px;
            color: gray;
            margin-top: 10px;
        }
        /*    タグ*/

        .tagarea {
            color: #c6b83f;
        }
        /*        スポット画像*/
        /*        スポット画像モーダル*/

        .modal-images-list {
            height: 70vh;
            overflow: scroll;
        }

        .modal-images-list-footer {
            height: 10vh;
        }

        .spot-image-list {
            width: 33.3%;
            padding: 0.9%;
        }

        .modal-close {
            padding: 0 15px;
        }

        .modal-close:hover {
            color:
        }

        .modal-close button:hover {
            background: none;
        }

        .modal-images-list-header,
        .modal-images-focus-header {
            padding: 0px;
            border: none;
        }

        .modal-images-list-header button,
        .modal-images-focus-header button {
            font-size: 30px;
            color: #717171;
            border: none;
            width: 100%;
            height: 10vh;
            /*            border-bottom:1px solid #ececec;*/
            /*            border-radius: 0px;*/
            padding: 0px;
            /*            background: #ececec;*/
        }

        .modal-images-focus {
            height: 71vh;
            overflow: hidden;
            padding: 0px;
        }

        .modal-images-list {
            height: 80vh;
            padding: 0px;
        }

        .modal-images-focus-footer {
            padding: 0px;
            border: none;
            background: #fbfbfb;
            border-top: 1px solid #f5f5f5;
            border-radius: 4px;
        }

        .modal-dialog .modal-content .modal-images-focus-footer button {
            font-size: 20px;
            color: #717171;
            width: 49%;
            margin: 0px;
            border: none;
            height: 9vh;
            padding: 0px;
            background: rgba(236, 236, 236, 0);

        }
        /*        画像リストのサイズ*/

        .spotpageimg-size {
            width: 33%;
            padding: 0.5%;
        }

        .free_Box {
            width: 100%;
            height: 71vh;
            vertical-align: middle;
            display: table-cell;
        }
        /*画像focus時の画像の位置修正*/

        .free_Box img {
            width: 100%;
        }

        .item {
            text-align: -webkit-center;
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
                    <!--小さいデバイス時のメニューボタン-->
                    <?php include( '../External/menu-btn-small.php'); ?>
                </div>
                <!--PCデバイス時のメニューボタン-->
                <?php include( '../External/menu-btn-large.php'); ?>


            </div>
        </nav>
        <div class="container">
            <!-- Example row of columns -->
            <div class="space-pc hidden-xs"></div>
            <div class="row">
                <div class="col-sm-7 col-md-5 col-md-offset-2 col-lg-5 col-lg-offset-2">
                <div class="spotheader">
                    <a href="http://maps.google.com/maps?q=<?=h($lat)?>,<?=h($lng)?>">
                        <img src="https://maps.googleapis.com/maps/api/staticmap?zoom=15&size=800x400&sensor=false&key=AIzaSyCKO-biURT4s7DR5PeZ8nAeAHureZpS0Gs&markers=size%3Amid%7C<?=h($lat)?>%2C<?=h($lng)?>" alt="" class="spotmap">
                    </a>
                </div>
                <div class="spotprofile">
                    <div class="name"><?=h($spotname)?></div>
                    <div class="category"><?=h($category)?></div>
                    <div class="spot-rating">
<?php
            switch ($spot_point_icon) {
                case 1:
                    echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    break;
                case 2:
                    echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    break;
                case 3:
                    echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    break;
                case 4:
                    echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
                    break;
                case 5:
                    echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                    break;
                default:
                    echo "\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
            }
            echo "\t\t\t<p class='review-point'>".h($spot_point)."</p>\n";

?>
                   </div>
                        <hr>
                        <h4><strong>住所</strong></h4>
                        <div class="address"><?=h($address)?></div>
                        <h4><strong>ウェブサイト</strong></h4>
                        <div class="url"><?=h($url)?></div>
                        <h4><strong>電話番号</strong></h4>
                        <div class="tel"><?=h($tel)?></div>
                        <h4><strong>営業時間</strong></h4>
                        <div class="open"><?=h($open)?></div>
                    </div>
<!--ログアウト時-->
<?php if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]=="") : ?>
<div class="spot-edit">
    <p><a data-toggle="modal" data-target="#loginModal2">このスポットの情報を編集する</a></p>
</div>
<hr>
<div class="reivew">
    <p class="test"><a data-toggle="modal" data-target="#loginModal2">このスポットの口コミを書く</a></p>
</div>
<!--ログイン時-->
<?php else : ?>
<div class="spot-edit">
    <p><a href="../spot/edit.php?spot_id=<?=h($spot_id)?>" role="button">このスポットの情報を編集する</a></p>
</div>
<hr>
<div class="review">
    <p><a href="../review/create.php?spot_id=<?=h($spot_id)?>" role="button">このスポットの口コミを書く</a></p>
</div>

<?php endif; ?>
                    <hr>
                    <div class="spotimages">
                       <h3><strong><?=h($images_count)?>枚の写真</strong></h3>
                        <div class="spotimages-main">
                            <?=$spotimg?>
                        </div>
                    </div>
                    <hr>
                    <div class="spotreviews">
<?php
            if( $shopdata[0]["review_id"] == false){
                echo "<h3><strong>0件の口コミ</strong></h3>";
            }else{
                echo "<h3><strong>".h(count($shopdata))."件の口コミ</strong></h3>";
                for($count = 0; $count < count($shopdata); $count++){
                    $name =  $shopdata[$count]['name'];
                    $time =  change_time($shopdata[$count]['time']);
                    $money =  change_money($shopdata[$count]['money']);
                    $comment =  $shopdata[$count]['comment'];
                    $menber_id = $shopdata[$count]['menber_id'];
                    $spot_id = $shopdata[$count]['spot_id'];
                    $review_id = $shopdata[$count]['review_id'];
                    $profileimg = $shopdata[$count]['profileimg'];
                    $L_point = $shopdata[$count]['L_point']; 
                    $D_point = $shopdata[$count]['D_point'];
                    $review_point = $shopdata[$count]['review_point'];
                    $review_point_icon = floor($review_point);//小数点切り捨て
                    if($shopdata[$count]['image_name']){
                        $reviewimgxs = "<img src='../upload/s/".h($shopdata[$count]["image_name"])."' class='listimgxs-size visible-xs-block'>";
                        $reviewimg = "<img src='../upload/s/".h($shopdata[$count]["image_name"])."' class='listimg-size hidden-xs'>";

                    }else{
                        $reviewimgxs  = ""; 
                        $reviewimg  = ""; 
                    }
                    //タグ
                    $tag = "";
                    if( $shopdata[$count]["tags_name"] == false){
                        $tag = "";
                    }else{
                        $tagarray = explode(',',$shopdata[$count]["tags_name"]);
                        for( $x = 0; $x < count($tagarray);$x++){
                        $tag .= "#".$tagarray[$x];
                        }
                    }
                    echo "<a class='' href='../review/index.php?review_id=".h($review_id)."'>\n";
                    echo "\t<div class='media tach list-media'>\n";
                    echo "\t\t<div class='list-spotname'>\n";
                    echo "\t\t\t<img class='media-object img-circle' src='../img/profileimg/".h($profileimg)."' alt='...'>\n";
                    echo "\t\t\t<h4>".h($name)."</h4>\n";
                    echo "\t\t</div>\n";
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
                            echo "\t\t\t<p>".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>　".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>　".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>　".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>　".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>　".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>".h($time)." / ".h($money)."</p>\n";
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
                            echo "\t\t\t<p>　".h($time)." / ".h($money)."</p>\n";
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
                    echo "\t</div>\n";
                    echo "</a>\n";
                }

            }
?>

                    </div>
                </div>
                <div class="col-sm-5 col-md-3  col-lg-3">
                   <div class="spance-ad visible-xs-block"></div>
                    <div class="list-group">
                        <a href="#" class="list-group-item active">Link</a>
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
        </div>
    </div>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    



    <!-- /container -->
<!--
    
     モーダル・ダイアログ 
<div class="modal fade" id="imageModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-body">
			</div>
			
		</div>
	</div>
</div>
-->
<!-- モーダル・ダイアログ スポットの写真リストを表示するため-->
    <div class="modal fade" id="spotimageslist" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-images-list-header">
                   <div class="modal-close">
                       <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></button>
                   </div>
                </div>
                <div class="modal-body modal-images-list">

                </div>
            </div>
        </div>
    </div>
<!-- モーダル・ダイアログ スポットの写真フォーカスを表示するため-->
    <div class="modal fade" id="imagefocus" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-images-focus-header">
<!--                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>-->
                   <div class="modal-close">
                       <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><span class="glyphicon glyphicon-remove pull-right" aria-hidden="true"></span></button>
                       
                   </div>
                </div>
                <div class="modal-body modal-images-focus">
                   
                    <div id="sampleCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                        <div class="carousel-inner" role="listbox" id="imagesmodal">
<!--
                            <div class="item active">
                                <img src="../img/sample-1140x500.png" alt="First slide">
                            </div>
                            <div class="item">
                                <img src="../img/sampleB-1140x500.png" alt="Second slide">
                            </div>
                            <div class="item">
                                <img src="../img/sampleC-1140x500.png" alt="Third slide">
                            </div>
-->
                        </div>
<!--
                        <a class="left carousel-control" href="#sampleCarousel" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">前へ</span>
                        </a>
                        <a class="right carousel-control" href="#sampleCarousel" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">次へ</span>
                        </a>
-->
                    </div>
                   
                    
                </div>
                <div class="modal-footer modal-images-focus-footer">
                   <button type="button" class="btn btn-default" id="left"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></button>
                   
                   <button type="button" class="btn btn-default" id="right"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></button>
                </div>
            </div>
        </div>
    </div>
<!-- モーダル・ダイアログ スポットの写真フォーカスを表示するため-->





<!--スマホサイズのメニュー画面展開画面-->
<?php include( '../External/menu-content-small.php'); ?>
<!-- ログイン モーダル -->
<?php include ('../External/modal-login.php'); ?>
<!--メニュー画面展開スクリプト-->
<script type="text/javascript" src="../js/menu.js"></script>
<!--メールアドレスチェックスクリプト-->
<script type="text/javascript" src="../js/validation-login.js"></script>

<!--場所検索フォームスクリプト-->
<script type="text/javascript" src="../js/search-place.js"></script>



<script>
//モーダルを呼び出し、画像データを持ってくる
    var spot_id = '<?=$spot_id?>';
    $(document).on("click", ".images",function(){
               console.log("ok");
        edit(spot_id).
        done(function(data) {
            console.log(data);
     
            var images = $.parseJSON(data);
            $(".modal-images-list").empty();
            $.each(images, function(index, elem) {
                $(".modal-images-list").append(
                    '<img src="../upload/s/'+ this.image_name　+'" alt="First slide" class="spot-image-list" data-toggle="modal" data-target="#imagefocus">'
                );
                //フォーカス写真
                if ( index == 0 ){
                    $("#imagesmodal").append(
                        '<div class="item active"><div class="free_Box"><img src="../upload/'+ this.image_name　+'" alt="First slide"></div></div>'   
                    );
                }else{
                    
                    $("#imagesmodal").append(
                        '<div class="item"><div class="free_Box"><img src="../upload/'+ this.image_name　+'" alt="First slide"></div></div>'   
                    );
                    
                }
            });
            
            
            
        }).
        fail(function(result) {
            alert("失敗しました。もう１度お願いします。");
        });
        
    });
    var nowindex;//フォーカスする画像の番号
    //スポット写真のリストをクリックした場合の処理
    $(document).on("click", ".spot-image-list", function(){
        nowindex = $('.spot-image-list').index(this);
        $("#imagefocus").carousel(nowindex);

    });
    $("#right").on("click", function(){
        $("#imagefocus").carousel('next');

    });
    $("#left").on("click", function(){
        $("#imagefocus").carousel('prev');

    });
    
    function edit(data) {
        return $.get(
            './images_get.php?spot_id='+ data
        );
    }
	$('#imageModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var recipient = button.data('recipient');
		var modal = $(this);
        modal.find('.modal-body').empty();
		modal.find('.modal-body').append('<img src="../upload/'+recipient+'">');
        console.log(recipient);
	});
</script>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    



   
</body>
</html>