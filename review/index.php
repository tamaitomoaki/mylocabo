<?php
session_start();
include("../function.php");


//入力チェック(受信確認処理追加)
if(
  !isset($_GET["review_id"]) || $_GET["review_id"]=="" 
){
//  exit('ParamError');
    header("location: ../index.php");
//    echo "ok";
    exit();
}

$pdo = db_con();

$review_id = $_GET["review_id"];

$stmt = $pdo->prepare("
SELECT  A.review_id, A.time, A.money, A.comment,A.D_point, A.L_point, A.spot_id,A.menber_id, A.review_point, B.images_name,C.spotname, D.menber_id, D.name,D.profileimg, E.tags_name, F.categorys_name 
FROM review_table AS A
LEFT JOIN (
    SELECT review_id,group_concat(image_name) AS images_name 
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
    SELECT spot_id, group_concat(category_name) AS categorys_name
    FROM category_spot_map_table
    LEFT JOIN categorys_table ON category_spot_map_table.category_id = categorys_table.category_id
    GROUP BY spot_id
    )AS F ON C.spot_id = F.spot_id
WHERE A.review_id = :a1
");
$stmt->bindValue(':a1', $review_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();
if($status==false){
    db_error($stmt);
}
$val = $stmt->fetch(); //1レコードだけ取得する方法

$menber_id = $val["menber_id"];
$name = $val["name"];
$SAS = TOs(48,2);
$time = change_time($val['time']);
$money = change_money($val['money']);
$review_point = $val['review_point'];
$review_point_icon = floor($review_point);
$profileimg = $val["profileimg"];
$spot_id = $val["spot_id"];
$spotname = $val["spotname"];
$comment = nl2br(h($val["comment"]));
//タグ
$tag = "";
if( $val["tags_name"] == false){
    $tag = "";
}else{
    $tagarray = explode(',',$val["tags_name"]);
    for( $x = 0; $x < count($tagarray);$x++){
    $tag .= "#".$tagarray[$x];
    }
}
//カテゴリー
$category = "";
if( $val["categorys_name"] == false){
    $category = "";
}else{
    $categoryarray = explode(',',$val["categorys_name"]);
    for( $x = 0; $x < count($categoryarray);$x++){
    $category .= $categoryarray[$x];
    }
}
$D_point = $val["D_point"];
$L_point = $val["L_point"];

$menber_id = $val["menber_id"];

$reviewimg = "";
$original ="";
if( $val["images_name"] == false){
    $reviewimg = "";
}else{
    $image_name = explode(',',$val["images_name"]);
    for( $i = 0; $i < count($image_name);$i++){
    $reviewimg .= "<img src='../upload/s/".h($image_name[$i])."' class='reviewpageimg-size img-rounded images' data-toggle='modal' data-target='#spotimageslist' id='image". $i ."'>";
    }
}



if ( isset($_SESSION["chk_ssid"])){
    $my_id = $_SESSION["menber_id"];
    $stmt = $pdo->prepare("
    (SELECT EXISTS(
    SELECT *
    FROM vote_table 
    WHERE review_id = :a1 AND menber_id = :a2 AND type=0))
    UNION ALL
    (SELECT EXISTS(
    SELECT * 
    FROM vote_table 
    WHERE review_id = :a1 AND menber_id = :a2 AND type=1))
    ");
    $stmt->bindValue(':a1', $review_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $stmt->bindValue(':a2', $my_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    $status = $stmt->execute();
    if($status==false){
        db_error($stmt);
    }
    $voteresult = $stmt->fetchAll(); //1レコードだけ取得する方法
    //$valの値が１の時いいね！済みである場合
    //$valの値が０の時いいね！をまだしてない場合
    
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
        /*        名前のボジション*/

        .position-name {
            vertical-align: middle;
        }
        /*レビューページーーーーーーーーーー*/

        .reviewpage-name {
            font-size: 20px;
            color: #545454;
        }

        .reviewpage-stance {
            color: gray;
            font-size: 13px;
            font-weight: 100;
        }

        .reviewpage-spotname {
            padding: 20px 0px;
        }

        .reviewpage-spotname div {
            font-weight: bold;
            font-size: 20px;
            color: #545454;
        }

        .reviewpage-spotname p {
            color: gray;
            margin-bottom: 0px;
        }

        .reviewpage-image {
            padding: 0px;
        }

        .content-review .media {
            padding: 20px 0px;
        }

        .review-footer {
            /*    margin:0 20px;*/
            margin-top: 20px;
            padding-top: 20px;
            padding-bottom: 20px;
            background: #fbfbfb;
            border-radius: 0px;
        }
        /* 投票システム       */

        .point {
            color: gray;
            padding-bottom: 5px;
            text-align: left;
        }

        .share {
            width: 50px;
            /*    width: 16vw;*/
            display: inline-block;
            color: gray;
            margin: 0 6vw;
        }

        .share i {
            font-size: 30px;
        }

        .dld {
            font-size: 10px;
            /*    font-size:1vw;*/
            color: #737373;
            display: block;
            margin-top: 2px;
            font-weight: bold;
        }

        .on {
            color: #ffd800;
        }

        .tach:hover {
            background-color: #f5f5f5;
            text-decoration: none;
        }

        .hrcostom {
            margin: 0px;
        }

        .prof-img-size {
            width: 50px;
        }
        /*スペース管理*/

        .space-pc {
            height: 70px;
        }

        .spance-ad {
            height: 70px;
        }

        a:link {}

        a:visited {}

        a:hover {
            text-decoration: none;
        }

        a:active {}
        /*        画像リストのサイズ*/

        .reviewpageimg-size {
            width: 33.3%;
            padding: 0.9%;
        }

        .review-name {
            color: #484848;
        }

        .reviewpage-comment {
            font-size: 18px;
        }
        /*レビュー星のcss*/

        .review-data {
            font-size: 18px;
            margin: 20px 0px;
        }

        .review-data p {
            margin-bottom: 0px;
            display: inline-block;
            vertical-align: text-top;
            font-size: 14px;
            color: gray;
        }

        .review-data .review-point {
            color: #EB6E00;
            /*            color:#e10000;*/
            font-size: 20px;
            vertical-align: bottom;
        }

        .review-data .nocolor {
            color: #d2d2d2;
        }

        .review-data .color {
            color: #EB6E00;
        }
        /*        画像フォーカス時のcss*/

        #imageModal .modal-body img {
            width: 100%;
        }

        #imageModal .modal-body {
            padding: 0px;
        }

        #imageModal .modal-dialog {
            margin-top: 20vh;
        }
        /*        画像モーダル時のcss*/

        .review-image-list {
            width: 100%;
            margin-bottom: 20px;
        }

        .modal-images-focus {
            padding: 0px;
        }
        /*画像リスト時、フォーカス時のheader        */

        .modal-images-list-header button,
        .modal-images-focus-header button {
            font-size: 30px;
            color: #717171;
            border: none;
            width: 100%;
            height: 10vh;
            padding: 0px;
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
        /*画像リスト時のbody        */

        .modal-images-list {
            height: 80vh;
            padding: 0px;
            overflow: scroll;
            width: 100%;
            vertical-align: middle;
            display: table-cell;
        }
        /*画像フォーカス時のbody        */

        .modal-images-focus {
            height: 71vh;
            overflow: hidden;
            padding: 0px;
        }
        /*画像リスト時のfooter        */

        .modal-images-list-footer {
            height: 10vh;
        }
        /*画像フォーカス時のfooter        */

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
        /*画像focus時の画像の位置修正*/

        .free_Box {
            width: 100%;
            height: 71vh;
            vertical-align: middle;
            display: table-cell;
        }

        .free_Box img {
            width: 100%;
        }

        .item {
            text-align: -webkit-center;
        }
        /*ログインモーダルのズレを修正*/

        .modal-open {
            overflow: auto;
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
                    <!--メニューボタン-->
                    <?php include( '../External/menu-btn-small.php'); ?>
                </div>

                <!--PCデバイス時のメニューボタン-->
                <?php include( '../External/menu-btn-large.php'); ?>
                <!--/.navbar-collapse -->
            </div>
        </nav>
            <div class="container">
            <!-- Example row of columns -->
            <div class="space-pc hidden-xs"></div>
            <div class="content-review row">
                <div class="col-sm-7 col-md-5 col-md-offset-2 col-lg-5 col-lg-offset-2">
                    <!--  メインコンテンツ-->
                    <div class="tach" href="">
                       <a href="../menber/index.php?menber_id=<?=h($menber_id)?>">
                        <div class="media">
                            <span class="media-left">
                            <img src='../img/profileimg/<?=h($profileimg)?>' alt='' class='prof-img-size img-circle'>
                            </span>
                            <div class="media-body position-name">
<?php echo "\t\t\t<h4 class='review-name'>".h($name)."</h4>\n";?>
                            </div>
                        </div>
                        </a>
                    </div>
                    <hr class="hrcostom">
                    <div class="main">
                       <a href="../spot/index.php?spot_id=<?=h($spot_id)?>">
                        <div class="tach reviewpage-spotname">
                            <div class=""><?=h($spotname)?></div>
                            <p><?=h($category)?></p>
                        </div>
                        </a>
                        <hr class="hrcostom">
                        <div class="review-data">
<?php
    switch ($review_point_icon) {
            case 1:
                echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span>\n";
                break;
            case 2:
                echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span>\n";
                break;
            case 3:
                echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span>\n";
                break;
            case 4:
                echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star nocolor' aria-hidden='true'></span>\n";
                break;
            case 5:
                echo "\t\t\t<span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span>\n";
                break;
            default:
                echo "\t\t\t<span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span>\n";
    }
    echo "\t\t\t<p class='review-point'>".h($review_point)."</p>\n";
    echo "\t\t\t<p>　".h($time)." / ".h($money)."</p>\n";
?>
                        </div>
<!--         画像がない場合-->
<?php if( $reviewimg == ""):?>
<!--         画像がある場合-->
<?php elseif($reviewimg != ""):?>
    <p class="reviewpage-img">
        <?=$reviewimg?>
    </p>
<?php endif;?>
                        <p class="reviewpage-comment">
                            <?=$comment?>
                        </p>
                        <p class="tagarea"><?=h($tag)?></p>
                        <div class="point">
                            <i class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></i>
                            <span class="ico_heart D<?=h($review_id)?>"><?=h($D_point)?></span>
                            <i class="glyphicon glyphicon-star-empty" aria-hidden="true"></i>
                            <span class="ico_heart L<?=h($review_id)?>"><?=h($L_point)?></span>
                            <div class='small pull-right'>2016/07/24</div>
                        </div>
                    </div>
<!--  投票部分          -->
                    <div class="review-footer text-center">
<!--ログアウト時-->
<?php if( !isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]=="" ) : ?>
<p class="share" data-toggle="modal" data-target="#loginModal2">
    <i class="glyphicon glyphicon-thumbs-up" aria-hidden="true"><span class="dld">いいね</span></i>
</p>
<p class="share" data-toggle="modal" data-target="#sampleModal2">
    <i class="glyphicon glyphicon-star-empty" aria-hidden="true"><span class="dld">お気に入り</span></i>
</p>
<!--ログイン時-->
<?php else : ?>
<!--         すでにいいね！済みの場合、-->
<?php if($voteresult[0][0] == 0):?>
<p class="share btn_vote" id="D<?=h($review_id)?>">
    <i class="glyphicon glyphicon-thumbs-up" aria-hidden="true"><span class="dld">いいね</span></i>
</p>
<!--         すでにいいね！済みの場合、-->
<?php elseif($voteresult[0][0] == 1):?>
<p class="share btn_vote on" id="D<?=h($review_id)?>">
    <i class="glyphicon glyphicon-thumbs-up" aria-hidden="true"><span class="dld">いいね</span></i>
</p>
<?php endif;?>

<!--         まだお気に入り！をしていないの場合、-->
<?php if($voteresult[1][0] == 0):?>
<p class="share btn_vote" id="L<?=h($review_id)?>">
    <i class="glyphicon glyphicon-star-empty" aria-hidden="true"><span class="dld">お気に入り</span></i>
</p>
<!--         まだお気に入り！をしていないの場合、-->
<?php elseif($voteresult[1][0] == 1):?>
<p class="share btn_vote on" id="L<?=h($review_id)?>">
    <i class="glyphicon glyphicon-star-empty" aria-hidden="true"><span class="dld">お気に入り</span></i>
</p>
<?php endif;?>


<?php endif; ?>
                    </div>
        <!--                   投票部分終了-->
                </div>
                <div class="col-sm-5 col-md-3  col-lg-3">
                   <div class="spance-ad visible-xs-block"></div>
                    <div class="ad list-group">
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
    
    

<!-- 画像表示 モーダル -->
<div class="modal fade" id="imageModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-body">
			</div>
			
		</div>
	</div>
</div>

<!-- モーダル・ダイアログ スポットの写真リストを表示するため-->
    <div class="modal fade" id="spotimageslist" tabindex="-1">
        <div class="modal-dialog modal-sm">
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
    var review_id = '<?=$review_id?>';
    $(document).on("click", ".images",function(e){
               var id = e.target.id;
        var id_num = id.substr(5);//何個目の画像か取得
        edit(review_id).
        done(function(data) {
            console.log(data);
     
            var images = $.parseJSON(data);
            $(".modal-images-list").empty();
            $.each(images, function(index, elem) {
                $(".modal-images-list").append(
                    '<img src="../upload/s/'+ this.image_name　+'" alt="First slide" class="review-image-list" data-toggle="modal" data-target="#imagefocus" id="test'+ index +'">'
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
            for ( var i = 0; i < id_num; i++ ){
                var test = $("#test"+ i).prop('outerHTML');
                $("#test"+ i).remove();
                $(".modal-images-list").append(test);
            }
            
            
            
            
            
            
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
            './images_get.php?review_id='+ data
        );
    }
    
    //画像一枚だけフォーカス
	$('#imageModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		var recipient = button.data('recipient');
		var modal = $(this);
        modal.find('.modal-body').empty();
		modal.find('.modal-body').append('<img src="../upload/'+recipient+'">');
        console.log(recipient);
	});
</script>



    <!-- /container -->
    
    
    
    <script>
    
    
	$('.btn_vote').click(function() {
		$(this).toggleClass('on');
		var id = $(this).attr('id');
        var review_id = id.match(/\d+/);
        var type = id.match(/[a-zA-Z]*/);
		$(this).hasClass('on') ? Vote(id, review_id, type, 'plus') : Vote(id, review_id, type, 'minus');
	});

function Vote(id, review_id, type, plus) {
    var processing = plus;
    console.log(id);
    console.log(review_id[0]);
    console.log(type[0]);
    console.log(plus);
    console.log(processing);
	cls = $('.' + id);
	cls_num = Number(cls.html());
	count = plus == 'minus' ? cls_num - 1 : cls_num + 1;
	$.get(
        '../vote.php',
        {
            'count': count,
            'review_id':review_id[0],
            'type':type[0],
            'processing':processing
        },
        function(data) {
            console.log(data);
		cls.html(count)
	});
}
        
</script>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    



   
</body>
</html>