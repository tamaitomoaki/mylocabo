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
SELECT name, profileimg, area, sex, introduction
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
$AS = TOs($val['area'],$val['sex']);
$area = $AS["area"];
$sex = $AS["sex"];


$comment = $val["introduction"];
//$comment = nl2br($val["introduction"]);
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
        height: 80vh;
    }
    
 
    
    .row .menber-menu div button {
        border-right: none;
        border-left: none;
        border-top: none;
        border-radius: 0px;
        color: gray;
        font-size: 15px;
        height: 7vh;
    }
    
    .display-list {
        height: 78vh;
        overflow: scroll;
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
    }
        .list-content{
            font-size: 12px;
            color:#484848;
/*            visibility: hidden;*/
        }
        .list-rating{
            padding-top:10px;
        }
        .point{
            color:gray;
            font-size:10px;
        }
        .media-header{
            margin-bottom:5px;
        }
            /*        リンク装飾*/
        a:link{
   
        } 
        a:visited{
 
        } 
        a:hover{

            text-decoration: none ;
        } 
        a:active{

        }
    /*        ホバーした際のアクション*/
        .tach:hover{
           background-color:#f5f5f5;
            text-decoration: none;
        }
    /*レビュー星のcss*/
        .list-rating span{
            color:#d2d2d2;
        }
        .list-rating .color{
            color:#EB6E00;
        }
    
    
    
    
/*    項目*/
    .item-profile{
        color:gray;
        font-size:18px;
    }
    .item-profile span,
    .item-profile p{
        color:#4e4e4e;
    }
    .item-profile p{
        margin-top:10px;
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
        <div class="col-sm-7 col-md-5 col-md-offset-2 col-lg-5 col-lg-offset-2">
            <!--  メインコンテンツ-->
            
            
        
                <div class="media">
                    <span class="media-left">
                                    <img src='../img/profileimg/<?=h($profileimg)?>' alt='' class='prof-img-size img-circle'>
                                </span>
                    <div class="media-body position-name">
<?php

                                             echo "\t\t\t<h4 class='menber-name'>".h($name)."</h4>\n";
?>
                      
                    </div>
                </div>
           
           <div>
              <hr>
               <div class="item-profile">活動エリア<span class="pull-right"><?=h($area)?></span></div>
               <hr>
               <div class="item-profile">性別<span class="pull-right"><?=h($sex)?></span></div>
               <hr>
               <div class="item-profile">自己紹介<p><?=nl2br(h($comment))?></div>
               
               
               
           </div>

            
            
            
            
            
            
            
            
            
            
            
            
                    
            
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
        $.getJSON(
            "../mypage/list-review.php?", {
                menber_id: menber_id
            }
        ).done(
            function(data) {
                console.log(data);
                $(".display-list").empty();
                    $.each(data, function() {
                        if ( this.image_name == null){
                            switch (this.point) {
                                case "1":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div></div></a>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div></div></a>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div></div></a>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div></div></a>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div></div></a>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div></div></a>"
                            );
                            }
                        }else{
                            switch (this.point) {
                                case "1":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
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
    </script>

    
    
    
    
    
    
    



   
</body>
</html>