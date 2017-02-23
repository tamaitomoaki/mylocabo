<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
//sessionCheck();//セッションの入れ替え

//値を受け取っているか確認
if(
  !isset($_GET["menber_id"]) || $_GET["menber_id"]=="" 
){
//  exit('ParamError');
    header("location: ../index.php");
//    echo "ok";
    exit();
}

$pdo = db_con();

$menber_id = $_GET["menber_id"];

$stmt = $pdo->prepare("
SELECT name, profileimg
FROM menber_table
WHERE menber_id = :a1
");
$stmt->bindValue(':a1', $menber_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();
if($status==false){
    db_error($stmt);
}
$val = $stmt->fetch(); //1レコードだけ取得する方法

$name = $val["name"];
$profileimg = $val["profileimg"];

//投稿件数取得
$stmt = $pdo->prepare("
SELECT count(menber_id = :a1 or null)
FROM review_table
");
$stmt->bindValue(':a1', $menber_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

$val = $stmt->fetch(); //1レコードだけ取得する方法

$countreview = $val[0];

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
    .menberpage-name {
        font-size: 20px;
        color: #545454;
    }
    
    .content-menber .media{
    padding:20px 0px;
}
    
    .tach:hover {
        background-color: #f5f5f5;
        text-decoration: none;
    }
    /*        名前のボジション*/
    .position-name{
        vertical-align: middle;
    }
    .hrcostom {
        margin: 0px;
    }
    /*スペース管理*/
    
    .space-pc {
        height: 70px;
    }
    
    .spance-ad {
        height: 70px;
    }
    /*        画像サイズ*/
    
    .prof-img-size {
        width: 50px;
    }
    
    
    
    
    
    
    .display-area-base,
    .display-area-detail {
/*        height: 80vh;*/
    }
    
 
    
    .row .menber-menu div button {
        border-right: none;
        border-left: none;
        border-top: none;
        border-radius: 0px;
        color: gray;
        font-size: 15px;
        height: 50px;
    }
    .row .menber-menu div .button-active{
            border-bottom: 4px solid #457fbb;
            color: #457fbb;
        }
    
    .display-list {
/*        height: 78vh;*/
/*        overflow: scroll;*/
    }
    

    
    .review-detail {
        border: none;
    }
    .menber-name{
        color:#484848;
    }
    /*        リストcss*/
        .content-menber .list-media{
            padding:10px;
        }
/*        リストの投稿画像サイズ指定*/
        .list-img{
            width:25%;
        }
        .list-img img{
            width:100%;
            
        }
/*        リストプロフィール画像*/
        .list-profileimg{
            width:30px;
            display:inline-block;
        }
        .list-spotname{
            display:inline-block;
        }
    .list-spotname h4{
        color:#484848;
        font-weight: bold;
        margin-bottom:5px;
    }
    .list-display-l{
        display:inline-block;
        margin:0px;
        line-height: 1.2;
        text-align: right;
    }
        .list-content{
            font-size: 18px;
            color:#484848;
/*            visibility: hidden;*/
        }
        /*レビュー星のcss*/
    .list-display-xs{
            font-size:18px;
            margin:5px 0px 0px;
        }
    .list-display-l .review-point,
    .list-display-xs .review-point{
            color:#EB6E00;
/*            color:#e10000;*/
            font-size:20px;
            vertical-align: baseline;
        margin-left:5px;
        display: inline-block
        }
        .list-display-l p,
    .list-display-xs p{
            margin-bottom:0px;
            vertical-align: text-top;
            font-size:14px;
            color:gray;
        }
    .list-display-l p{
        display: block;
    }
    .list-display-xs p{
        display:inline-block;
        font-size:14px;
        color:gray;
    }
    
        .list-rating-l span,
    .list-rating-s span{
            color:#d2d2d2;
        }
        .list-rating-l .color,
    .list-rating-s .color{
            color:#EB6E00;
        }
        .list-rating-s{
            font-size:18px;
            display: inline-block;
        }
    .list-rating-l{
            padding-top:10px;
        font-size:18px;
        display: inline-block;
        }
    
    
    
    
    
    .listRow div{
        padding-left:0px;
        padding-right:0px;
    }
    .list-body{
        padding-top:10px;
    }
    /*    タグ*/
    .tagarea{
        color: #c6b83f;
    }
        .point{
            color:gray;
            font-size:10px;
            margin-top:5px;
        }
        .media-header{
            margin-bottom:5px;
        }

    /*        ホバーした際のアクション*/
        .tach:hover{
           background-color:#f5f5f5;
            text-decoration: none;
        }

/*    プロフィールメニューcss*/
    .profile-menber{
        padding:20px 0px;
        font-size:18px;
    }
    /*ログインモーダルのズレを修正*/
        .modal-open {
            overflow: auto;
        }
/*    リストの画像のトリム*/
    .trim{
            overflow: hidden;
    width: 100%;/* トリミングしたい枠の幅 */
/*    height: 200px; トリミングしたい枠の高さ */
    position: relative;
            margin-bottom:5px;
            margin-top:5px;
        }
        .trim:before{
            content:"";
            display: block;
    padding-top: 62.5%; /* 高さを幅の75%に固定 */
        }
        .trim img{
            position: absolute;
  top: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  width: 100%;
  height: auto;
        }
    .reviewimg-size{
        width:100%;
        padding-left:10px;
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
    <div class="content-menber row">
        <div class="col-xs-12 col-sm-7 col-md-5 col-md-offset-2 col-lg-5 col-lg-offset-2">
            <!--  メインコンテンツ-->
            
            
        
                <div class="media">
                    <span class="media-left">
                                    <img src='../img/profileimg/<?=h($profileimg)?>' alt='' class='prof-img-size img-circle'>
                                </span>
                    <div class="media-body position-name">
<?php
//    switch ($stance){
//        case 1:
//            echo "\t\t\t<h4 class='media-heading'><spna class='marker_red_hoso'>".h($name)."</span></h4>\n";
//            break;
//        case 2:
//            echo "\t\t\t<h4 class='media-heading'><span class='marker_yellow_hoso'>".h($name)."</span></h4>\n";
//            break;
//        case 3:
//            echo "\t\t\t<h4 class='media-heading'><span class='marker_green_hoso'>".h($name)."</span></h4>\n";
//            break;
//    }
                                             echo "\t\t\t<h4 class='menber-name'>".h($name)."</h4>\n";
?>
                      
                    </div>
                </div>
           
           
           <div class="profile-menber tach"><a href="profile.php?menber_id=<?=h($menber_id)?>">プロフィール</a></div>
        <div class="display-area-base">
            <div class="display-control row">
                <div class="menber-menu btn-group btn-group-justified" role="group">
                    <div class="btn-group btn-group-lg" role="group">
                        <button type="button" id="review-list" class="btn btn-default">投稿<?=h($countreview)?>件</button>
                    </div>
<!--
                    <div class="menber-menu btn-group btn-group-lg" role="group">
                        <button type="button" class="btn btn-default">ストック</button>
                    </div>
                    <div class="menber-menu btn-group btn-group-lg" role="group">
                        <button type="button" class="btn btn-default">写真</button>
                    </div>
-->
                </div>
            </div>
            <div class="display-list row"></div>
        </div>

            
            
            
            
            
            
            
            
            
            
            
            
                    
            
        </div>
        <div class="col-xs-12 col-sm-5 col-md-3  col-lg-3">
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
        $(document).ready( function(){
            $("#review-list").trigger("click");
        });
        
    
    //投稿したリストを表示
    //クリックしてDBからデータを取得してリスト表示,ajax
    var menber_id = <?=h($_GET["menber_id"])?>;
    $("#review-list").on("click", function() {
//        $("#like-list").removeClass("button-active");
//        $("#createspot-list").removeClass("button-active");
//        $("#review-list").addClass("button-active");
        $.getJSON(
            "../mypage/list-review.php?", {
                menber_id: menber_id
            }
        ).done(
            function(data) {
                console.log(data);
                $(".display-list").empty();
                    $.each(data, function() {
                        console.log(data.category_name);
                        //タグのデータ処理
                        var tag = "";
                        if ( this.tags_name == null ){
                            tag = "";
                        }else{
                            var tagarray = this.tags_name.split(",");
                            for ( var i = 0; i < tagarray.length; i++){
                                tag += "#"+tagarray[i];
                            }
                        }
                        //レビューボイントの調整
                        var review_point = this.review_point;
                        review_point_icon = review_point.substr(0, 1);//星のアイコンの表示のために、少数点以下をカット
                        if ( this.image_name == ""){
                            switch (review_point_icon) {
                                    case "0":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a>"
                            );
                                
                                    break;
                                case "1":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a>"
                            );
                            }
                        }else{
                            switch (review_point_icon) {
                                    case "0":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a>"
                            );
                                
                                    break;
                                case "1":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><small>"+this.category_name+"</small></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a>"
                            );
                            }
                        }
                    
                });
                
            }
        );
    });
        var $setElm = $('.list-content');
        console.log($setElm.val());
        var cutFigure = '30'; // カットする文字数
        var afterTxt = ' …'; // 文字カット後に表示するテキスト

        $setElm.each(function(){
            var textLength = $(this).text().length;
            var textTrim = $(this).text().substr(0,(cutFigure))

            if(cutFigure < textLength) {
                $(this).html(textTrim + afterTxt).css({visibility:'visible'});
            } else if(cutFigure >= textLength) {
                $(this).css({visibility:'visible'});
            }
        });
        
        
        
        
        
            (function($) {
                $.extend({
                    htmlspecialchars: function htmlspecialchars(ch){
                            ch = ch.replace(/&/g,"&amp;") ;
                            ch = ch.replace(/"/g,"&quot;") ;
                            ch = ch.replace(/'/g,"&#039;") ;
                            ch = ch.replace(/</g,"&lt;") ;
                            ch = ch.replace(/>/g,"&gt;") ;
                            return ch ;
                        }
                });
            })(jQuery);
    </script>

    
    
    
    
    
    
    



   
</body>
</html>