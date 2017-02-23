<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え


$menber_id = $_SESSION["menber_id"];

$pdo = db_con();

$stmt = $pdo->prepare("
SELECT count(menber_id = :a1 or null)
FROM review_table
");
$stmt->bindValue(':a1', $menber_id,   PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();
if($status==false){
    db_error($stmt);
}

$val = $stmt->fetch(); //1レコードだけ取得する方法

$countreview = $val[0];

$name = $_SESSION["name"];
$profileimg = $_SESSION["profileimg"];

if(
  !isset($_GET["lat"]) || $_GET["lat"]==""  ||
  !isset($_GET["lng"]) || $_GET["lng"]==""  
)
{
    $lat = "";
    $lng = "";
}else{
    $lat = $_GET["lat"];
    $lng = $_GET["lng"];
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
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="../js/bootstrap.js"></script>
    <style>
/*
        body{
            background:#f5f5f5;
        }
*/
        body {
                padding-bottom: 0px;
            }
        .jumbotron{
            background:white;
        }
        .display-area{
            width:500px;
        }
        .display-area-base,
        .display-area-detail{
            height:80vh;
        }
        #map-big{
            height:85vh;
            padding-left:0px;
            padding-right:0px;
        }
        #map-small{
            height:85vh;
            padding-left:0px;
            padding-right:0px;
        }
        .row .mypage-menu div button{
            border-right:none;
            border-left:none;
            border-top:none;
/*            border-bottom:none;*/
            border-radius:0px;
            color:gray;
            font-size:15px;
            height:50px;
            padding-top:11px;

        }
        .row .mypage-menu div .button-active{
            border-bottom: 4px solid #457fbb;
            color: #457fbb;
        }
        .display-list{
            height:100px;
            overflow-y: scroll;
        }
/*        マイページメニュー*/
        .mypage-header{
    background: #444444;
            
        }
        .mypage-header a{
            color:gray;
            padding-top:10px;
            padding-bottom:6px;
            display:inline-block;
            margin-right:20px;
        }
        .mypage-header .mypage-active{
            color:white;
            border-bottom:4px solid #7ebb45; 
        }
        /*        リンク装飾*/
        a:link{
   text-decoration: none ;
        } 
        a:visited{
 text-decoration: none ;
        } 
        a:hover{

            text-decoration: none ;
        } 
        a:active{
text-decoration: none ;
        }
/*        */
        .review-detail{
            border:none;
        }
/*        外部ファイルへ持っていくと効かない*/
        .nav li .profileimg-navbar{
    padding-top:10px;
    padding-bottom:10px;

}
.profileimg-navbar img{
    width:30px;
    height:auto;
}
        /*       navbar-profile画像サイズ*/
        .nav li .profileimg-navbar{
    padding-top:5px;
    padding-bottom:5px;

}
.profileimg-navbar img{
    width:40px;
    height:auto;
}
        

    /*スタンスの違いを表す色*/
        .marker_yellow_hoso {
            background: linear-gradient(transparent 60%, #ffff66 60%); 
            color:#545454;
        }
        .marker_red_hoso {
            background: linear-gradient(transparent 60%, #ff7466 60%); 
            color:#545454;
        }
        .marker_green_hoso {
            background: linear-gradient(transparent 60%, #ceff66 60%); 
            color:#545454;
        }
    /*        リストcss*/
        .display-area .list-media{
            padding:20px;
            font-size:18px;
        }
/*        データがない時表示*/
        .nodata{
            width:90%;
            color:#484848;
            margin-top:50px;
        }
        .nodata span{
            font-size:30px;
            
        }
        .nodata-title{
            margin-top:15px;
            margin-bottom:15px;
            font-size:32px;
        }
        .nodata-content{
            font-size:18px;
            color:gray;
        }
        .nodata .nodata-example{
            font-size:18px;
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
            font-weight: bold;
            color:#484848;
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
/*
        .list-rating-s{
            margin-right:18px;
        }
*/
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
/*
            padding-bottom:7px;
            font-size:18px;
            margin-right:18px;
*/
        }
    .list-rating-l{
        padding-top:10px;
        font-size:18px;
        display: inline-block;
/*
            padding-top:10px;
        font-size:18px;
*/
        }
        .review-point{
            display: inline-block;
    vertical-align: text-bottom;
    margin-left: 10px;
            margin-bottom:0px;
            font-size:20px;
            color:#EB6E00;
        }
                    /*レビュー星のcss*/
/*
        .list-rating span{
            color:#d2d2d2;
        }
        .list-rating .color{
            color:#EB6E00;
        }
        .list-display-xs{
            
        }
*/

            .listRow div{
        padding-left:0px;
        padding-right:0px;
    }
        .point{
            color:gray;
            font-size:10px;
            margin-top:10px;
        }
        .media-header{
            margin-bottom:5px;
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
        .like-list-delete{
            margin-left:10px;
        }
            /*        ホバーした際のアクション*/
        .tach:hover{
           background-color:#f5f5f5;
            text-decoration: none;
        }
        /*        名前のボジション*/
.position-name{
        vertical-align: bottom;
    }
/*レビューページーーーーーーーーーー*/
.reviewpage-name{
    font-size:20px;
    color:#545454;
}
.reviewpage-stance{
    color:gray;
    font-size:13px;
    font-weight:100;
}
.reviewpage-spotname{
    font-weight: bold;
    font-size: 20px;
    padding: 20px 0px;
    color:#545454;
}
.reviewpage-image{
    padding:0px;
}
.content-review .media{
    padding:20px 0px;
}
        
.review-footer{
/*    margin:0 20px;*/
    margin-top:20px;
    padding-top:20px;
    padding-bottom:20px;
    background:#fbfbfb;
    border-radius:0px;
}
        /*        画像リストのサイズ*/
        .reviewpageimg-size{
            width:20%;
        }

/*
        
        @media screen and (max-width: 767px)  {
            .display-area{
                width:100%;
            }
}
*/
/*            リスト、マップ切り替えボタン*/
            #switching{
                
            }
            #switching button{
                color:gray;
                margin:3px 0px;
            }
        .display-area{
            float:left;
        }
        #map-big{
            float:right;
/*
            width:100%;
            margin:0px 0px 0px -400px;
*/
        }
/*
        .wrap{
            overflow: hidden;
        }
*/
/*        リストの削除、編集ボタン*/
        .button-list{
            background:#f9f9f9;
            padding:10px 0px;
        }
        .mypage-edit{
            margin-right:20px;
        }
        .mypage-delete{
            margin-left:20px;
        }
        .button-list{
            text-align: center;
        }
/*        投稿編集画面css*/
        .reviewpoint{
            font-size:30px;
            color:#a0a0a0;
        }
        .active{
            color:#EB6E00;
        }
/*        タグのcss*/
        .btn-delete-tag{
            margin-left:3px;
            vertical-align: text-bottom;
        }
        #tag-displayarea div{
            display: inline-block;
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
        <!-- /container -->
<!--    メニュー-->
<div class="mypage-header">
    <div class="container">
        <a href="#" class="mypage-active">履歴</a>
        <div id="switching" class="visible-xs-inline pull-right">
            <button type="button" class="btn btn-default navbar-btn " id="mapbutton">マップ</button>
        </div>
    </div>
</div>
<div class="mypage-main container-fluid">

<!--    <div class="row">-->
<!--        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">-->
<!--
            <div class="page-header">
  <p class="text-center"><strong><?=$name?></strong></p>
</div>
-->
            
            <div class="row">
<!--
                <div class="col-sm-2 col-md-2">
                <div class="thumbnail"><img src="../img/fronta.jpg" class="img-circle" alt="..."></div>
            <p class="text-center"><strong><?=$name?></strong></p>
                   <div class="list-group">
                        <a id="review-list" class="list-group-item">投稿リスト<span class="pull-right"><?=$countreview?>件</span></a>
                        <a class="list-group-item">お気に入り</a>
                        <a class="list-group-item">行った</a>
                    </div>
                    
                    
                    <div class="list-group">
                        <a id="top" class="list-group-item">トップ</a>
                        <a href="./profile-edit.php" class="list-group-item">プロフィール</a>
                        <a href="../logout.php" class="list-group-item">ログアウト</a>
                    </div>
                </div>
-->

<div class="display-area">
    <div class="display-area-base">
        <div class="display-control">
            <div class="mypage-menu btn-group btn-group-justified" role="group">
                <div class="btn-group btn-group-lg" role="group">
                    <button type="button" id="review-list" class="btn btn-default button-active">口コミ</button>
                </div>
                <div class="mypage-menu btn-group btn-group-lg" role="group">
                    <button type="button" id="like-list" class="btn btn-default">お気に入り</button>
                </div>
                <div class="mypage-menu btn-group btn-group-lg" role="group">
                    <button type="button" id="createspot-list" class="btn btn-default">作成</button>
                </div>
            </div>
        </div>
        <div class="display-list show"></div>
        <div id="map-small" class="display-map hidden"></div>
    </div>
</div>
<!--                マップ表示-->
<!--               <div class="wrap">-->
                <div id="map-big" class="hidden-xs">
<!--
                    <div class="list-group">
                        <a class="list-group-item nonborder" href="../index.php?spot_id=<?=$spot_id?>">
                            <div class="media border-round">
                                <span class="media-left  media-middle" href="#">
                                                    <p class="icon-edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></p>

                                </span>
                                <div class="media-body">

                                    <h3 class="media-heading"><strong>口コミを投稿しよう！</strong></h3>
                                    <h4>
                                        あなたの体験一つ一つが貴重なものです。<br> あなたの投稿が多くの人の新しい発見や
                                        <br> 出会いへと繋がっていきます。
                                    </h4>
                                </div>
                            </div>
                        </a>
                        <a class="list-group-item nonborder" href="../spot/create.php">
                            <div class="media border-round">
                                <span class="media-left  media-middle" href="#">
                                                    <p class="icon-edit"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></p>

                                </span>
                                <div class="media-body">

                                    <h3 class="media-heading"><strong>MakeMapしよう！</strong></h3>
                                    <h4>
                                        まだ知られていないスポットを。<br> あなたが新発見したスポットを。
                                        <br> 地図上に登録して、みんなで共有しませんか？
                                    </h4>
                                </div>
                            </div>
                        </a>
                    </div>
-->
                </div>
<!--                </div>-->
            </div>
      


<!--        </div>-->
<!--    </div>-->


</div>
<div class="modal fade" id="review-edit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                <!--店舗名-->
                <h1 class="modal-title"></h1>
            </div>
            <div class="modal-body lead">
                <!--メインコンテンツ-->
                <form action="../review/edit_act.php" method="post">
                    <div class="alertbox"></div>
                        <div class="form-group">
                        <label for="InputSelect">時間帯</label>
                            <select class="form-control input-lg" name="time" id="time" onchange="changeItem(this)" required style='color:#989898;'>
                                  <option value='0' disabled selected style='display:none;'>時間帯</option>
                                  <option value='1'>朝</option>
                                  <option value='2'>昼</option>
                                  <option value='3'>夜</option>
                                  <option value='4'>その他</option>
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="InputSelect">金額</label>
                        <select class="form-control input-lg" name="money" id="money" onchange="changeItem(this)" required style='color:#989898;'>
                                <option value='0' disabled selected style='display:none;'>時間帯</option>
                                <option value='1'>~¥999</option>
                                <option value='2'>¥1,000~¥1,999</option>
                                <option value='3'>¥2,000~¥2,999</option>
                                <option value='4'>¥3,000~¥3,999</option>
                                <option value='5'>¥4,000~¥4,999</option>
                                <option value='6'>¥5,000~¥5,999</option>
                                <option value='7'>¥6,000~¥6,999</option>
                                <option value='8'>¥7,000~¥7,999</option>
                                <option value='9'>¥8,000~¥8,999</option>
                                <option value='10'>¥9,000~¥9,999</option>
                                <option value='11'>¥10,000~¥14,999</option>
                                <option value='12'>¥15,000~¥19,999</option>
                                <option value='13'>¥20,000~¥29,999</option>
                                <option value='14'>¥30,000~</option>
                            </select>
                    </div>
                    <div class="form-group">
                        <div class="reviewpoint">
                            <span class="glyphicon glyphicon-star" aria-hidden="true" id="one"></span>
                            <span class="glyphicon glyphicon-star" aria-hidden="true" id="two"></span>
                            <span class="glyphicon glyphicon-star" aria-hidden="true" id="three"></span>
                            <span class="glyphicon glyphicon-star" aria-hidden="true" id="four"></span>
                            <span class="glyphicon glyphicon-star" aria-hidden="true" id="five"></span>
                        </div>
                        <select class="form-control input-lg" name="point" id="point" onchange="changeItem(this)" required style='color:#989898;'>
                              <option value='0' disabled selected style='display:none;'>評価をお願いします。</option>
                              <option value='1.0'>1.0</option>
                              <option value='1.1'>1.1</option>
                              <option value='1.2'>1.2</option>
                              <option value='1.3'>1.3</option>
                              <option value='1.4'>1.4</option>
                              <option value='1.5'>1.5</option>
                              <option value='1.6'>1.6</option>
                              <option value='1.7'>1.7</option>
                              <option value='1.8'>1.8</option>
                              <option value='1.9'>1.9</option>
                              <option value='2.0'>2.0</option>
                              <option value='2.1'>2.1</option>
                              <option value='2.2'>2.2</option>
                              <option value='2.3'>2.3</option>
                              <option value='2.4'>2.4</option>
                              <option value='2.5'>2.5</option>
                              <option value='2.6'>2.6</option>
                              <option value='2.7'>2.7</option>
                              <option value='2.8'>2.8</option>
                              <option value='2.9'>2.9</option>
                              <option value='3.0'>3.0</option>
                              <option value='3.1'>3.1</option>
                              <option value='3.2'>3.2</option>
                              <option value='3.3'>3.3</option>
                              <option value='3.4'>3.4</option>
                              <option value='3.5'>3.5</option>
                              <option value='3.6'>3.6</option>
                              <option value='3.7'>3.7</option>
                              <option value='3.8'>3.8</option>
                              <option value='3.9'>3.9</option>
                              <option value='4.0'>4.0</option>
                              <option value='4.1'>4.1</option>
                              <option value='4.2'>4.2</option>
                              <option value='4.3'>4.3</option>
                              <option value='4.4'>4.4</option>
                              <option value='4.5'>4.5</option>
                              <option value='4.6'>4.6</option>
                              <option value='4.7'>4.7</option>
                              <option value='4.8'>4.8</option>
                              <option value='4.9'>4.9</option>
                              <option value='5.0'>5.0</option>
                              
                        </select>
<!--                        <input type="hidden" id="point" name="point" value="">-->
                    </div>
                    <div class="form-group">
                        <label for="comment">コメントお願いします</label>
                        <textarea class="form-control" id="comment" name="comment" placeholder="" rows="15"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="uploadimgarea">
                               <p id="imgarea"></p>
                                <button type="button" class="btn btn-default btn-block btn-lg reviewimg-select"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></button>

                        </div>
                    </div>
                    <div class="form-group">
                        <p id="tag-displayarea"></p>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">#</span>
                            <input type="text" class="form-control" placeholder="タグを付けましょう。" id="tag-inputarea">
                            <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default" id="add-tag">追加</button>
                                            </span>
                        </div>
                    </div>
                       <input type="hidden" name="review_id" id="review_id" value="">
                       <input type="hidden" name="spot_id" id="spot_id" value="">
                       <input type="hidden" name="uploadimg" id="uploadimg" value="">
                       <input type="hidden" name="deleteimg" id="deleteimg" value="">
                       <input type="hidden" name="uploadtag" id="uploadtag" value="">
                       <input type="hidden" name="deletetag" id="deletetag" value="">
                        <button type="submit" id="registration" class="btn btn-primary btn-block btn-lg">変更する</button>
                        
<!--                        <button type="button" id="delete" class="btn btn-primary btn-block btn-lg">削除</button>-->



                </form>
        <!--       画像選択エリア-->
                <form action="../review/reviewimg-upload-edit.php" method="post" enctype="multipart/form-data" class="imgform">
                    <input type="file" accept="image/jpg, image/jpeg" capture="camera" id="upreviewimg" name="upreviewimg[]" multiple="multiple">
                    <input type="hidden" name="provisionalcomment" id="provisionalcomment" value="">
                    <button type="button">アップロード</button>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" role="button" id="quit">編集をやめる</a>
<!--                        <a class="btn btn-default" role="button" id="format">変更点を元に戻す</a>-->
                        <a class="btn btn-default" role="button" id="delete" >削除</a>
            </div>
        </div>
    </div>
</div>
</div>

    
<!--スマホサイズのメニュー画面展開画面-->
<?php include( '../External/menu-content-small.php'); ?>
<!--メニュー画面展開スクリプト-->
<script type="text/javascript" src="../js/menu.js"></script> 
    
    
    
<!--場所検索フォームスクリプト-->
<script type="text/javascript" src="../js/search-place.js"></script>
     
        
        
        
 <!--    グーグルマップapi-->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKO-biURT4s7DR5PeZ8nAeAHureZpS0Gs"></script>
<script>

    
    
    
    
    $(document).ready( function(){
        $("#review-list").trigger("click");
        $(".imgform").hide();
    });
    
//編集をやめるボタン
    $("#quit").on("click", function(){
        $("#review-edit").modal('hide');
    });
//変更点を元に戻すボタン
//        $("#format").on("click", function(){
//            $(".mypage-edit").trigger("click");
//    });
    
//タグ入力に関しての処理
        var uploadtag = [];//投稿したいタグの名前を配列で保存、後でinputで渡す
        var deletetag = [];//すでに登録してあるが削除したいタグの名前を配列で保存、後でinputで渡す
    //エンターを押した時の処理
    $("#tag-inputarea").on("keydown", function(e) {
        
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            $("#add-tag").trigger("click");
            return false;
        } else {
            return true;
        }
    });
    //タグ追加ボタンを押すと追加される処理
    $("#add-tag").on("click", function(e){
        var createtag = $("#tag-inputarea").val();
        console.log(createtag);
            if ( createtag.match(/,/)) {
                alert(",は使えません");//,を使うと配列分割の部分で余計な部分で分割されてしまうのでやめる。
                e.preventDefault();

            }else{
                //空白削除
                createtag = createtag.replace(/\s+/g, "");
                var newtag_num = uploadtag.length;
                $("#tag-displayarea").append("<div class='taging' id='newtag"+ newtag_num +"'><span>#"+createtag+"</span><a class='btn btn-default btn-xs btn-delete-tag' href='#' role='button'>削除</a>　</div>");
                $("#tag-inputarea").val("");
                uploadtag.push(createtag);//追加したいtagの名前を追加
                $("#uploadtag").val(uploadtag);//inputへ渡す
                console.log(uploadtag);
            }
    });
       
    //表示されているタグの削除
    $(document).on("click", ".taging span, .taging a", function(e){
        var id = e.target.parentElement.id;//クリックした要素のidを取得
        
        ///タグの表示を削除部分の処理
        var tagnum = $(".taging").index(this);//何番目のタグか確認
        var name = e.target.textContent;//タグの名称取得
        $("#"+ id).remove();//タグ削除
        
        
        console.log(id);
        var id_type = id.substr(0, 6);//idの先頭、nowtag,newtagを切り取る
        var id_num = id.substr(6);//idの数字を切り取る、uploadtagの何番目の要素か判断
        console.log(id_num);
        //タグの種類で分岐
        if( id_type == "newtag" ){
            //idがnewtagの場合は、新しく登録しようとしているtag
                //uploadarrayの中から、取得したタグの名称を削除
                    var tagname = name.substr(1);//＃を削除
                    uploadtag[id_num] = "";//アップロード配列からクリックした要素を空白にする、php側で空白を削除する
                    $("#uploadtag").val(uploadtag);//inputに代入、POSTで送信
            
        }else{
            console.log(id_num);
            //idがnowtagの場合は、すでに登録済みのtag
            //タグのデータを削除部分の処理
            deletetag.push(id_num);//消したタグのタグidをdeletetagに配列として入れる
            $("#deletetag").val(deletetag);//inputに代入、POSTで送信
            //最初の状態に戻すボタンを表示する→deletetagをクリアにする
            console.log(deletetag);
        }
        
        
        
        
        
        
        
        
        
        
        
        

        
        

        
    });
//投稿リスト
    //口コミ編集ボタンクリック処理
    var reviewimg = "";//投稿した画像の名前の変数、削除の際に代入する
    var uploadimg = [];//投稿したい画像の名前を配列で保存、後でinputで渡す
    var deleteimg = [];//削除したい画像の名前を配列で保存、後でinputで渡す

    $(document).on("click", ".mypage-edit", function(e){
        uploadimg = [];
        deleteimg = [];
        uploadtag = [];
        deletetag = [];
        $("#uploadimg").val(uploadimg);//inputに代入、POSTで送信
        $("#deleteimg").val(deleteimg);//inputに代入、POSTで送信
        $("#uploadtag").val(uploadtag);//inputに代入、POSTで送信
        $("#deletetag").val(deletetag);//inputに代入、POSTで送信
        console.log(uploadtag);
        console.log(deletetag);
        

        point_reset();
        console.log(e);
        reviewimg = "";
        $("#imgarea").empty();
        $("#tag-displayarea").empty();
        review_id = e.target.dataset.review_id;
        $("#review_id").val(review_id);
        spot_id = e.target.dataset.spot_id;
        $("#spot_id").val(spot_id);
        $.getJSON(
            "../mypage/data-review.php", {
                review_id: review_id
            }
        ).done(
            function(data) {
                console.log(data);
                //レビューボイントの調整
                var review_point = data.review_point;
//                review_point = Number(review_point);
                review_point_icon = review_point.substr(0, 1);//星のアイコンの表示のために、少数点以下をカット
                console.log(review_point_icon);
                
                $(".modal-title").text(data.spotname);//スポット名を挿入
                $("#time").val(data.time);//時間帯を表示
                $("#money").val(data.money);//金額を表示
                $("#point").val(data.review_point);//評価を表示
                $("#comment").text(data.comment);//コメント挿入
                switch(review_point_icon){
                    case "1":
                        $("#one").addClass("active");
                        break;
                    case "2":
                        $("#one").addClass("active");
                        $("#two").addClass("active");
                        break;
                    case "3":
                        $("#one").addClass("active");
                        $("#two").addClass("active");
                        $("#three").addClass("active");
                        break;
                    case "4":
                        $("#one").addClass("active");
                        $("#two").addClass("active");
                        $("#three").addClass("active");
                        $("#four").addClass("active");
                        break;
                    case "5":
                        $("#one").addClass("active");
                        $("#two").addClass("active");
                        $("#three").addClass("active");
                        $("#four").addClass("active");
                        $("#five").addClass("active");
                        break;     
                }
//                if( data.images_name == false){
//                    var reviewimg = "";
//                }else{
////                    var image_name = data.images_name.split(",");
//                    var image_name = [];
//                    image_name = data.images_name;
//                    console.log(image_name);
//                    console.log(image_name.length);
//                    for( var i = 0; i < image_name.length ; i++){
//                    var reviewimg = "<img src='../upload/s/"+ image_name[i] +"' class='reviewpageimg-size img-rounded' data-toggle='modal' data-target='#imageModal' data-recipient='"+ image_name[i] +"'>";
//                        $("#imgarea").append(reviewimg);
//                    }
//                }
                $("#imgarea").append(data.images_name);//画像差し込み
                $("#tag-displayarea").append(data.tags_name);//画像差し込み
                $("#point").val(data.review_point);
                if( data[8] == null ){
                }else{
                    reviewimg = data[9].split(",");//データベースの項目の数が変わるとこの数字の部分がずれてうまく動かなくなる
                }
            }
        );
        
    });
//評価ポイント
    var review_point ="";
    //評価ポイントクリック時の処理
    $("#one").on("click", function(){
        $(".reviewpoint span").removeClass("active");
        $(this).toggleClass("active");
        if ( review_point == "" ){
            $("#point").val("1.0");
        }else{
            $("#point").val(review_point);
            review_point ="";
        } 
    });
    $("#two").on("click", function(){
        $(".reviewpoint span").removeClass("active");
        $("#one").toggleClass("active");
        $(this).toggleClass("active");
        if ( review_point == "" ){
            $("#point").val("2.0");
        }else{
            $("#point").val(review_point);
            review_point ="";
        } 
    });
    $("#three").on("click", function(){
        $(".reviewpoint span").removeClass("active");
        $("#one").toggleClass("active");
        $("#two").toggleClass("active");
        $(this).toggleClass("active"); 
        if ( review_point == "" ){
            $("#point").val("3.0");
        }else{
            $("#point").val(review_point);
            review_point ="";
        } 
    });
    $("#four").on("click", function(){
        $(".reviewpoint span").removeClass("active");
        $("#one").toggleClass("active");
        $("#two").toggleClass("active");
        $("#three").toggleClass("active");
        $(this).toggleClass("active");
        if ( review_point == "" ){
            $("#point").val("4.0");
        }else{
            $("#point").val(review_point);
            review_point ="";
        } 
    });
    $("#five").on("click", function(){
        $(".reviewpoint span").removeClass("active");
        $("#one").toggleClass("active");
        $("#two").toggleClass("active");
        $("#three").toggleClass("active");
        $("#four").toggleClass("active");
        $(this).toggleClass("active");
        if ( review_point == "" ){
            $("#point").val("5.0");
        }else{
            $("#point").val(review_point);
            review_point ="";
        } 
    });
    //評価ポイントのcssを解除
    function point_reset(){
        $("#one").removeClass("active");
        $("#two").removeClass("active");
        $("#three").removeClass("active");
        $("#four").removeClass("active");
        $("#five").removeClass("active");
    }
    //評価ポイントのプルダウンを選択した場合の処理
    $("#point").on("change", function(){
        review_point = $("#point").val();
        var point = review_point.substr(0, 1);
        console.log(review_point);
        switch (point){
            case "1": $("#one").trigger("click"); break;
            case "2": $("#two").trigger("click"); break;
            case "3": $("#three").trigger("click"); break;
            case "4": $("#four").trigger("click"); break;
            case "5": $("#five").trigger("click"); break;
            default : "もう一度選択してください";
        }
        
    });
        //スタンスselectの文字の色の調整
    function changeItem(obj){ 
        if( obj.value != 0 ){ 
            obj.style.color = '#555'; 
        }
    } 
//画像削除
    //クリックされたレビュー画像を削除
    $(document).on("click", "#imgarea img", function(e) {
        var image_name = e.target.attributes[0].value;
        console.log(image_name);
        if (window.confirm('元に戻すことが出来なくなりますが、この画像を削除してもよろしいですか？')) {

            dele(image_name).
            done(function(result) {
                console.log(result);
                deleteimg.push($.parseJSON(result));
                console.log(deleteimg);
                $("#imgarea img[src='" + image_name + "']").remove();//削除したい画像を代入、クリックされたら入れる
                console.log("tesuto");
                console.log(deleteimg);
                $("#deleteimg").val(deleteimg);
            }).
            fail(function(result) {

            });
        } else {

        }
    });
    //画像削除のajax関数
    function dele(image_name) {
        return $.get(
            '../review/reviewimg-delete-edit.php?image_name=' + image_name
        );
    }
    //画像選択
    //画像を選択するボタンをクリックして画像選択を起動
    $(".reviewimg-select").on("click", function(){
        $("#upreviewimg").trigger("click");
    });
    //画像選択されたら画像を保存
    $('#upreviewimg').on("change", function() {
        var $form = $('.imgform');
        var fdo = new FormData($form[0]);
        $.ajax({
            url: '../review/reviewimg-upload-edit.php?spot_id='+spot_id,
            type: 'post',
            processData: false,
            contentType: false,
            data: fdo,
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, elem) {
                    uploadimg.push(elem);
                    $("#imgarea").append(
                        '<img src="../upload/s/' + elem + '" class="r-img reviewpageimg-size">'
                    );
                });
                $("#uploadimg").val(uploadimg);
            },
            error: function(xhr, status, error) {
                alert('ERROR : ' + status + ' : ' + error);
            }
        });
    });
//口コミ削除ボタンクリック処理
    $("#delete").on("click", function(){
        
        if(!confirm('本当に削除しますか？')){
            /* キャンセルの時の処理 */
            return false;
        }else{
            /*　OKの時の処理 */
            var review_id = $("#review_id").val();
            if(reviewimg == null){
                location.href = '../review/delete.php?review_id='+review_id;
            }else{
                location.href = '../review/delete.php?review_id='+review_id+'&reviewimg='+reviewimg;
            }
            
        }
    });
//お気に入りリスト
//お気に入りリストから削除する処理
    $(document).on("click", ".like-list-delete", function(e){
        var review_id = e.target.attributes[1].value;
        var spot_id = e.target.attributes[2].value;
        $.getJSON(
            "../mypage/remove-like.php", {
                review_id: review_id,
                spot_id: spot_id
            }
        ).done(
            function(data) {
                $("#spot"+data).remove();
                
            }
        );
    });
        
    
    
//履歴表示切り替え
    var type = "";//表示typeを記録
    $(".display-area-detail").hide();
    //投稿したリストを表示
    //クリックしてDBからデータを取得してリスト表示,ajax
    var menber_id = <?=$_SESSION["menber_id"]?>;
    $("#review-list").on("click", function() {
        $("#like-list").removeClass("button-active");
        $("#createspot-list").removeClass("button-active");
        $("#review-list").addClass("button-active");
        type = 1;
        $.getJSON(
            "../mypage/list-review.php?", {
                menber_id: menber_id
            }
        ).done(
            function(data) {
                console.log("test");
                $(".display-list").empty();
                if ( data == "" ){
                    console.log("から");
                    $(".display-list").append(
                                "<div class='nodata-box'><div class='nodata center-block'><p class='nodata-title'>"+'<?=$_SESSION["name"]?>'+"さんの体験を書きましょう。</p><p class='nodata-content'>あなたの体験一つ一つが貴重なものです。口コミに書いてより多くの人と共有しましょう。</p></div></div>"
                            );
                }
                
                console.log(data);
                    $.each(data, function(index) {
                        //タグのデータ処理
                        var tag = "";
                        if ( this.tags_name == null ){
                            tag = "";
                        }else{
                            var tagarray = this.tags_name.split(",");
                            var idarray = this.tag_review_map_ids.split(",");
                            for ( var i = 0; i < tagarray.length; i++){
                                tag += "<span id='tag"+idarray[i]+"'>#"+ tagarray[i] +"</span>";
                            }
                        }
                        //レビューボイントの調整
                        var review_point = this.review_point;
                        review_point_icon = review_point.substr(0, 1);//星のアイコンの表示のために、少数点以下をカット
                        
                        if ( this.image_name == ""){
                            switch (review_point_icon) {
                                    case "0":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button' >編集する</a></div></div>"
                            );
                                
                                    break;
                                case "1":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div><div class='list-content'>" + this.comment + "</div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                            }
                        }else{
                            switch (review_point_icon) {
                                    case "0":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                
                                    break;
                                case "1":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><small>"+this.category_name+"</small></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<div class='list-parts' id='review"+this.review_id+"'><a class='' href='../review/index.php?review_id=" + this.review_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "<br><span class='small'>"+ this.category_name +"</span></h4></div><div class='list-display-l pull-right hidden-xs'><div class='list-rating-l'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>"+ this.time +" / "+ this.money +"</p></div><div class='list-display-xs visible-xs-block'><div class='list-rating-s'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><p class='review-point'>"+ review_point +"</p><p>　"+ this.time +" / "+ this.money +"</p></div><div class='media-body list-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + this.comment + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div><div class='tagarea'>"+tag+"</div><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small pull-right'>2016/07/24</div></div></div></div></a><div class='button-list'><a class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-spot_id='"+this.spot_id+"' data-review_id='"+this.review_id+"' role='button'>編集する</a></div></div>"
                            );
                            }
                        }
                });
                
            }
        );
        MarkerClear();
        ListCrear();
        setPointMarker();
    });
    //保存したリストを表示
    //クリックしてDBからデータを取得してリスト表示,ajax
    $("#like-list").on("click", function() {
        $(this).addClass("button-active");
        $("#createspot-list").removeClass("button-active");
        $("#review-list").removeClass("button-active");
        type = 2;
        $.getJSON(
            "../mypage/list-like.php?", {
                menber_id: menber_id
            }
        ).done(
            function(data) {
                console.log(data);
                $(".display-list").empty();
                if ( data == "" ){
                    console.log("から");
                    $(".display-list").append(
                                "<div class='nodata center-block'><p class='nodata-title'>リストを作成しましょう。</p><p class='nodata-content'><span class='glyphicon glyphicon-star-empty nodata-example' aria-hidden='true'></span>のボタンをクリックするとリストへ追加できます。気になったスポット、お気に入りのスポットを追加して、オリジナルなリストを作成しましょう。</p></div>"
                            );
                }
                    $.each(data, function() {
                        if ( this.image_name == null){
                            switch (this.spot_point) {
                                case "1":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><div class='list-content'>" + h(this.address) + "</div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><div class='list-content'>" + h(this.address) + "</div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><div class='list-content'>" + h(this.address) + "</div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><div class='list-content'>" + h(this.address) + "</div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='media-body'><div><div class='list-content'>" + h(this.address) + "</div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><div class='list-content'>" + h(this.address) + "</div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                            }
                        }else{
                            switch (this.spot_point) {
                                case "1":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + h(this.address) + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + h(this.address) + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + h(this.address) + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + h(this.address) + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='media-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + h(this.address) + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<div class='list-parts' id='spot"+this.spot_id+"'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='list-rating-l pull-right hidden-xs'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='list-rating-s visible-xs-block'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div class='container-fluid'><div class='row listRow'><div class='col-xs-12 col-sm-9 col-md-9 list-content'>" + h(this.address) + "</div><div class='col-xs-12 col-sm-3 col-md-3'><div class='trim visible-xs-block'><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimgxs-size'></div><img src='../upload/s/"+this.image_name+"' alt='...' class='reviewimg-size hidden-xs'></div></div></div></div></div></a><div class='button-list'><a class='btn btn-default' href='../review/create.php?spot_id="+this.spot_id+"' role='button'>口コミを書く</a><button class='btn btn-default like-list-delete' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>お気に入りから消す</button></div></div>"
                            );
                            }
                        }
//                        if ( this.image_name == null){
//                            $(".display-list").append(
//                                "<div class='list-parts'><a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='media-body'>"+this.address+"</div></div></a><div class='button-list'><button class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>編集する</button><button class='btn btn-default mypage-delete'>削除する</button></div></div>"
//                            );
//                        }else{
//                            $(".display-list").append(
//                                "<div class='list-parts'><a id='review" + this.review_id + "' class='review-detail'><div class='media tach list-media'><div class='list-spotname'><h4>" + this.spotname + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star-empty' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + this.comment + "</p><div class='point'><i class='glyphicon glyphicon-thumbs-up' aria-hidden='true'></i><span class='ico_heart discovery"+this.review_id+"'>" + this.D_point + "</span><i class='glyphicon glyphicon-heart' aria-hidden='true'></i><span class='ico_heart like"+this.review_id+"'>" + this.L_point + "</span><div class='small'>2016/07/24</div></div></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a><div class='button-list'><button class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-review_id='"+this.review_id+"' data-spot_id='"+this.spot_id+"'>編集する</button><button class='btn btn-default mypage-delete'>削除する</button></div></div>"
//                            );
//                        }
                });
                
            }
        );
        MarkerClear();
        ListCrear();
        likesetPointMarker();
    });
    //新規登録したリストを表示
    //クリックしてDBからデータを取得してリスト表示,ajax
    $("#createspot-list").on("click", function() {
        $("#like-list").removeClass("button-active");
        $(this).addClass("button-active");
        $("#review-list").removeClass("button-active");
        type = 3;
        $.getJSON(
            "../mypage/list-createspot.php?", {
                menber_id: menber_id
            }
        ).done(
            function(data) {
                console.log(data);
                $(".display-list").empty();
                if ( data == "" ){
                    console.log("から");
                    $(".display-list").append(
                                "<div class='nodata center-block'><p class='nodata-title'>地図に新しいスポットを登録しましょう。</p><p class='nodata-content'>あなたの知っているスポットは登録されていますか？もしなければ新規登録をしましょう。地図上に新しい世界を築くことがきます。</p></div>"
                            );
                }
                    $.each(data, function() {
                        if ( this.image_name == null){
                            $(".display-list").append(
                                "<div class='list-parts'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='media-body'><div><p class='list-content'>" + h(this.address) + "</p></div></div></div></a><div class='button-list'><a class='btn btn-default spot-edit' href='../spot/edit.php?spot_id="+this.spot_id+"' role='button'>スポットを編集する</a></div></div>"
                            );
                        }else{
                            $(".display-list").append(
                                "<div class='list-parts'><a href='../spot/index.php?spot_id="+this.spot_id+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + h(this.spotname) + "</h4></div><div class='media-body'><div><p class='list-content'>" + h(this.address) + "</p></div></div></div></a><div class='button-list'><button class='btn btn-default mypage-edit' data-toggle='modal' data-target='#review-edit' data-review_id='"+this.review_id+"' data-spot_id='"+h(this.spot_id)+"'>スポットを編集する</button></div></div>"
                            );
                        }
                });
                
            }
        );
        MarkerClear();
        ListCrear();
        createspotsetPointMarker();
    });
    //レビュー詳細ページへ
    //review-detailをクリック時の処理
    $(".display-area").on("click", ".review-detail", function(e, data) {
        $(".display-area-base").hide();
        $(".display-area-detail").show();
        $(".display-area-detail").empty();
//        console.log(e.currentTarget.id);
//        console.log(e);
        var review_id = this.id;

        $.getJSON(
            "./review-detail_get.php?", {
                review_id: review_id
            }
        ).done(
            function(data) {
                if ( data["images_name"] == null){
                    $(".display-area-detail").append(
                        '<h1><button class="back btn btn-default">リストへ戻る</button></h1><div class="main"><div class="tach reviewpage-spotname ">'+data["spotname"]+'</div><p class="reviewpage-comment">'+data["comment"]+'</p><div class="point"><i class="glyphiconglyphicon-thumbs-up" aria-hidden="true"></i><span class="ico_heart D">'+data["D_point"]+'</span><i class="glyphicon glyphicon-star-empty" aria-hidden="true"></i><span class="ico_heart like">'+data["L_point"]+'</span></div></div>'
                    );
                    
                    
                }else{
                    var reviewimg_array = data["images_name"].split(",");
                    var reviewimg;
                    for( var i = 0; i < reviewimg_array.length;i++){
                        reviewimg += '<img src="../upload/s/'+reviewimg_array[i]+'" class="reviewpageimg-size">';
                    }
                   $(".display-area-detail").append(
                        '<h1><button class="back btn btn-default">リストへ戻る</button></h1><div class="main"><div class="tach reviewpage-spotname ">'+data["spotname"]+'</div><p class="reviewpage-img">'+reviewimg+'</p><p class="reviewpage-comment">'+data["comment"]+'</p><div class="point"><i class="glyphiconglyphicon-thumbs-up" aria-hidden="true"></i><span class="ico_heart D">'+data["D_point"]+'</span><i class="glyphicon glyphicon-star-empty" aria-hidden="true"></i><span class="ico_heart like">'+data["L_point"]+'</span></div></div>'
                    ); 
                }
            }
        );
    });
    //戻る
    $(".display-area").on("click", ".back", function(){
        $(".display-area-detail").hide();
        $(".display-area-base").show();
    });
    //スポットの詳細ページへ
    //spot-detailをクリック時の処理
    $(".display-area").on("click", ".spot-detail", function() {
        $(".result-box").empty();
        var spot_id = this.id;

        $.getJSON(
            "./spot-detail_get.php?", {
                spot_id: spot_id
            }
        ).done(
            function(data) {
                $(".result-box").empty();
                $(".result-box").append(
                    '<h1><strong>' + data[0].spotname + '</strong></h1><p>' + data[0].address + '</p><hr><h2>口コミ</h2>'
                );
                $.each(data, function() {
                    $(".result-box").append(
                        '<p class="lead">' + this.name + '</p><p>' + h(this.comment) + '</p><button id="review' + this.review_id + '" class="review-detail btn btn-default">口コミ詳細</button><hr>'
                    );
                });


            }
        );
    });
    
    
    
    //topをクリックして、htmlを挿入
    $("#top").on("click", function() {
        $(".display-list").empty();
        $(".display-list").append(
            '<div class="list-group"><a class="list-group-item nonborder" href="../index.php"><div class="media border-round"><span class="media-left  media-middle" href="#"><p class="icon-edit"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></p></span><div class="media-body"><h3 class="media-heading"><strong>口コミを書こう！</strong></h3><h4>あなたの体験一つ一つが貴重なものです。<br> あなたの口コミが多くの人の新しい発見や<br> 出会いへと繋がっていきます。</h4></div></div></a><a class="list-group-item nonborder" href="../spot/create.php"><div class="media border-round"><span class="media-left  media-middle" href="#"><p class="icon-edit"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span></p></span><div class="media-body"><h3 class="media-heading"><strong>MakeMapしよう！</strong></h3><h4>まだ知られていないスポットを。<br> あなたが新発見したスポットを。<br> 地図上に登録して、みんなで共有しませんか？</h4></div></div></a></div>'
        );
    });
    //以下マップ

    
    
    
//マップ表示
    
    //マップ、ジオコーディング用コード
    var map;
    var geo;
    var latlng; //中心座標
    var set_latlng; //中心移動する際のマーカーの緯度経度
    var address;
    var level; //ズームレベル
    var opts; //マップの表示設定
    var req; //ジオコードする住所

    var marker_ary = []; //DBから呼び出した店舗データを入れる配列変数
    var slideCurrent = 0; // スライドの現在地を示す変数
    var slideWidth; // .slideの幅を取得して代入
    var slideNum; // .slideの数を取得して代入
    var slideSetWidth; // .slideの幅×数で求めた値を代入
    
    var lat = "<?=$lat?>";//スポット登録、口コミをした場合使用
    var lng = "<?=$lng?>";//スポット登録、口コミをした場合使用
    var address = "<?=$_SESSION["area"]?>";//通常登録したエリアを取得
    // ジオコードリクエストを送信するGeocoderの作成
    geo = new google.maps.Geocoder();

    
    
if( lat != "" && lng != ""){

                //新規スポット登録、口コミをした場合、登録した場所をアップで表示
                //    latlng = new google.maps.LatLng(35.3921572, 139.428869);
                latlng = new google.maps.LatLng(lat, lng);
                opts = {
                    zoom: 18,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: latlng,
                    mapTypeControl: false,//falseでマップ名及び航空写真（マップタイプ）の非表示
                    streetViewControl: false,//falseで非表示
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.LARGE,
                        position: google.maps.ControlPosition.TOP_LEFT
                    },
                    //                disableDefaultUI: true,
                };
                if ( $("#map-big").is(":visible")){
                    map = new google.maps.Map(document.getElementById("map-big"), opts);
                    position = google.maps.ControlPosition.TOP_LEFT;
                }else{
                    map = new google.maps.Map(document.getElementById("map-small"), opts);
                    position = google.maps.ControlPosition.TOP_RIGHT;
                }
        
}else{
                //通常の場合、プロフィールで指定したエリアを地図で表示する
                // GeocoderRequest
                req = {
                    address: address,
                };
                geo.geocode(req, geoResultCallback);
            //緯度経度取得
            function geoResultCallback(result, status) {
                var zoom;
                if (status != google.maps.GeocoderStatus.OK) {
        //            alert("検索された場所が見つかりませんでした。");
                    latlng = new google.maps.LatLng(38.8873052, 139.6003076);
                    zoom = 5;
        //            return;
                }else{
                    latlng = result[0].geometry.location;
                    zoom = 10;
                }
                //マップ拡大縮小のコントローラーボジション
                if ( $("#map-big").is(":visible")){
                    position = google.maps.ControlPosition.TOP_LEFT;
                }else{
                    position = google.maps.ControlPosition.UNDER_RIGHT;
                }
                opts = {
                    zoom: zoom,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: latlng,
                    mapTypeControl: false,//falseでマップ名及び航空写真（マップタイプ）の非表示
                    streetViewControl: false,//falseで非表示
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.LARGE,
                        position: position
                    },
        //            disableDefaultUI: true,
                };
                if ( $("#map-big").is(":visible")){
                    map = new google.maps.Map(document.getElementById("map-big"), opts);
                    position = google.maps.ControlPosition.TOP_LEFT;
                }else{
                    map = new google.maps.Map(document.getElementById("map-small"), opts);
                    position = google.maps.ControlPosition.TOP_RIGHT;
                }

                //getBoundsは、地図の描画が終わってからでないと正確な値がとれないので、projection_changedイベントに登録する。  

                google.maps.event.addListener(map, 'projection_changed', function() {
                    setPointMarker();
                });
                google.maps.event.addListener(map, 'center_changed', function() {
                    $(".re-search").show();
                    getcenter();
                });
            }
            //終了、通常の場合、プロフィールで指定したエリアを地図で表示する
        
}

    
    




    //データを呼び出し登録されてる地点情報をマーカーで表示
    function setPointMarker() {
        $.ajax({
            url: "../json/place-review.php",
            type: 'GET',
            dataType: 'json',
            timeout: 1000,
            error: function(data) {
                console.log("情報の読み込みに失敗しました");
            },
            success: function(data) {
                console.log("情報の読み込み成功");
                marker_ary.length = 0;
                $(".result-box").empty();
                //マップの端末サイズの変化による表示切り替え
                if ( $("#map-big").is(":visible")){
                    map = new google.maps.Map(document.getElementById("map-big"), opts);
                }else{
                    map = new google.maps.Map(document.getElementById("map-small"), opts);
                }
                $.each(data, function(i) {

                    //mapにマーカーを表示
                    var addlat = this.lat;
                    var addlng = this.lng;
                    var marker_position = new google.maps.LatLng(addlat, addlng);
                    var markerOpts = {
                        map: map,
                        position: marker_position
                    }
                    var marker_num = marker_ary.length; //markerを複数作成する際のマーカー番号、配列番号
                    marker_ary[marker_num] = new google.maps.Marker(markerOpts);
                    //レビューリストをクリックするとそのレビューを中心に移動
                    $("#review"+data[i].review_id).on("click", function(){
                                set_latlng = marker_ary[marker_num].getPosition();
                                map.panTo(set_latlng);
                    });
                    //マップ下部のlist表示
                    $(".slideSet").append(
                        //                            '<div class="slide">' + this.spotname + '</div>'
                        '<a href="newreview-registration-write.php?spot_id=' + this.spot_id + '"><div class="media slide"><span class="media-left" href="#"><img src="./img/fronta.jpg" alt=""></span><div class="media-body"><h4 class="media-heading">' + h(this.spotname) + '</h4></div></div></a>'
                    );
                    //一回りごとにmarkerイベントを設定、markerがクリックされた場合
                    google.maps.event.addListener(marker_ary[marker_num], 'click',
                        function() {
                            // そのマーカーの地点を拡大表示
                            set_latlng = marker_ary[marker_num].getPosition();
                            map.panTo(set_latlng);
                            //マーカーをクリックしてスポットの詳細を表示
                            $('#review'+data[i].review_id).trigger("click");
                            //マーカーの配列番号をslideCurrentにいれる、何個目の店舗が取得し、その数×幅で表示を移動する
                            slideCurrent = marker_num;
                            $('.slideSet').stop().animate({
                                left: slideCurrent * -slideWidth
                            });
                        });
                    //以上マップ表示
    

                    //list表示
                    $(".result-box").append(
                        '<div id="spot' + this.spot_id + '" class="spot-detail list-group-item nonborder" data-spot="' + this.spot_id + '"><div class="media"><span class="media-left  media-middle" href="#"><a href="../review/create.php?spot_id=' + this.spot_id + '" class="btn btn-success">口コミを書く</a><button id="spot' + this.spot_id + '" class="spot-detail btn btn-default">店舗詳細</button></span><div class="media-body"><h3 class="media-heading"><strong>' + h(this.spotname) + '</strong></h3><h4>lat:' + this.lat + ',lng:' + this.lng + '</h4></div></div></div>'
                    );
                });
                //スライダー数値取得
                slideWidth = $('.slide').outerWidth(); // .slideの幅を取得して代入
                slideNum = $('.slide').length; // .slideの数を取得して代入
                slideSetWidth = slideWidth * slideNum; // .slideの幅×数で求めた値を代入
                $('.slideSet').css('width', slideSetWidth); // .slideSetのスタイルシートにwidth: slideSetWidthを指定
            }
        });
    }
    //likeしたデータを呼び出し登録されてる地点情報をマーカーで表示
    function likesetPointMarker() {
        $.ajax({
            url: "../json/place-like.php",
            type: 'GET',
            dataType: 'json',
            timeout: 1000,
            error: function(data) {
                console.log("情報の読み込みに失敗しました");
                
            },
            success: function(data) {
                console.log("情報の読み込み成功");
                
                marker_ary.length = 0;
                $(".result-box").empty();
                //マップの端末サイズの変化による表示切り替え
                if ( $("#map-big").is(":visible")){
                    map = new google.maps.Map(document.getElementById("map-big"), opts);
                }else{
                    map = new google.maps.Map(document.getElementById("map-small"), opts);
                }
                $.each(data, function(i) {

                    //mapにマーカーを表示
                    var addlat = this.lat;
                    var addlng = this.lng;
                    var marker_position = new google.maps.LatLng(addlat, addlng);
                    var markerOpts = {
                        map: map,
                        position: marker_position
                    }
                    var marker_num = marker_ary.length; //markerを複数作成する際のマーカー番号、配列番号
                    marker_ary[marker_num] = new google.maps.Marker(markerOpts);
                    //レビューリストをクリックするとそのレビューを中心に移動
                    $("#review"+data[i].review_id).on("click", function(){
                                set_latlng = marker_ary[marker_num].getPosition();
                                map.panTo(set_latlng);
                    });
                    //マップ下部のlist表示
                    $(".slideSet").append(
                        //                            '<div class="slide">' + this.spotname + '</div>'
                        '<a href="newreview-registration-write.php?spot_id=' + this.spot_id + '"><div class="media slide"><span class="media-left" href="#"><img src="./img/fronta.jpg" alt=""></span><div class="media-body"><h4 class="media-heading">' + h(this.spotname) + '</h4></div></div></a>'
                    );
                    //一回りごとにmarkerイベントを設定、markerがクリックされた場合
                    google.maps.event.addListener(marker_ary[marker_num], 'click',
                        function() {
                            // そのマーカーの地点を拡大表示
                            set_latlng = marker_ary[marker_num].getPosition();
                            map.panTo(set_latlng);
                            //マーカーをクリックしてスポットの詳細を表示
                            $('#review'+data[i].review_id).trigger("click");
                            //マーカーの配列番号をslideCurrentにいれる、何個目の店舗が取得し、その数×幅で表示を移動する
                            slideCurrent = marker_num;
                            $('.slideSet').stop().animate({
                                left: slideCurrent * -slideWidth
                            });
                        });
                    //以上マップ表示
    

                    //list表示
                    $(".result-box").append(
                        '<div id="spot' + this.spot_id + '" class="spot-detail list-group-item nonborder" data-spot="' + this.spot_id + '"><div class="media"><span class="media-left  media-middle" href="#"><a href="../review/create.php?spot_id=' + this.spot_id + '" class="btn btn-success">口コミを書く</a><button id="spot' + this.spot_id + '" class="spot-detail btn btn-default">店舗詳細</button></span><div class="media-body"><h3 class="media-heading"><strong>' + h(this.spotname) + '</strong></h3><h4>lat:' + this.lat + ',lng:' + this.lng + '</h4></div></div></div>'
                    );
                });
                //スライダー数値取得
                slideWidth = $('.slide').outerWidth(); // .slideの幅を取得して代入
                slideNum = $('.slide').length; // .slideの数を取得して代入
                slideSetWidth = slideWidth * slideNum; // .slideの幅×数で求めた値を代入
                $('.slideSet').css('width', slideSetWidth); // .slideSetのスタイルシートにwidth: slideSetWidthを指定
            }
        });
    }
    //新規登録したスポットのデータを呼び出し登録されてる地点情報をマーカーで表示
    function createspotsetPointMarker() {
        $.ajax({
            url: "../json/place-createspot.php",
            type: 'GET',
            dataType: 'json',
            timeout: 1000,
            error: function(data) {
                console.log("情報の読み込みに失敗しました");

            },
            success: function(data) {
                console.log("情報の読み込み成功");
                marker_ary.length = 0;
                $(".result-box").empty();
                //マップの端末サイズの変化による表示切り替え
                if ( $("#map-big").is(":visible")){
                    map = new google.maps.Map(document.getElementById("map-big"), opts);
                }else{
                    map = new google.maps.Map(document.getElementById("map-small"), opts);
                }
                $.each(data, function(i) {


                    //mapにマーカーを表示
                    var addlat = this.lat;
                    var addlng = this.lng;
                    var marker_position = new google.maps.LatLng(addlat, addlng);
                    var markerOpts = {
                        map: map,
                        position: marker_position
                    }
                    var marker_num = marker_ary.length; //markerを複数作成する際のマーカー番号、配列番号
                    marker_ary[marker_num] = new google.maps.Marker(markerOpts);
                    //レビューリストをクリックするとそのレビューを中心に移動
                    $("#review"+data[i].review_id).on("click", function(){
                                set_latlng = marker_ary[marker_num].getPosition();
                                map.panTo(set_latlng);
                    });
                    //マップ下部のlist表示
                    $(".slideSet").append(
                        //                            '<div class="slide">' + this.spotname + '</div>'
                        '<a href="newreview-registration-write.php?spot_id=' + this.spot_id + '"><div class="media slide"><span class="media-left" href="#"><img src="./img/fronta.jpg" alt=""></span><div class="media-body"><h4 class="media-heading">' + h(this.spotname) + '</h4></div></div></a>'
                    );
                    //一回りごとにmarkerイベントを設定、markerがクリックされた場合
                    google.maps.event.addListener(marker_ary[marker_num], 'click',
                        function() {
                            // そのマーカーの地点を拡大表示
                            set_latlng = marker_ary[marker_num].getPosition();
                            map.panTo(set_latlng);
                            //マーカーをクリックしてスポットの詳細を表示
                            $('#review'+data[i].review_id).trigger("click");
                            //マーカーの配列番号をslideCurrentにいれる、何個目の店舗が取得し、その数×幅で表示を移動する
                            slideCurrent = marker_num;
                            $('.slideSet').stop().animate({
                                left: slideCurrent * -slideWidth
                            });
                        });
                    //以上マップ表示
    

                    //list表示
                    $(".result-box").append(
                        '<div id="spot' + this.spot_id + '" class="spot-detail list-group-item nonborder" data-spot="' + this.spot_id + '"><div class="media"><span class="media-left  media-middle" href="#"><a href="../review/create.php?spot_id=' + this.spot_id + '" class="btn btn-success">口コミを書く</a><button id="spot' + this.spot_id + '" class="spot-detail btn btn-default">店舗詳細</button></span><div class="media-body"><h3 class="media-heading"><strong>' + h(this.spotname) + '</strong></h3><h4>lat:' + this.lat + ',lng:' + this.lng + '</h4></div></div></div>'
                    );
                });
                //スライダー数値取得
                slideWidth = $('.slide').outerWidth(); // .slideの幅を取得して代入
                slideNum = $('.slide').length; // .slideの数を取得して代入
                slideSetWidth = slideWidth * slideNum; // .slideの幅×数で求めた値を代入
                $('.slideSet').css('width', slideSetWidth); // .slideSetのスタイルシートにwidth: slideSetWidthを指定
            }
        });
    }
    //リセット関数
    //Map
    function MarkerClear() {
        //表示中のマーカーがあれば削除
        if (marker_ary.length > 0) {
            //マーカー削除
            for (i = 0; i < marker_ary.length; i++) {
                marker_ary[i].setMap(null);
            }
            //配列削除
            for (i = 0; i <= marker_ary.length; i++) {
                marker_ary.shift();
            }
        }
    }
    //リスト
    function ListCrear() {
        $(".slideSet").html("");
        $(".result-box").html("");
    }
    //search.phpのリスト、マップ画面切り替え
    $("#change1").on("click", function() {
        $(".inline-block").css("display", "none");
        $(".none").css("display", "inline-block");
        $("#change1").attr("id", "change2");
    });
    $("#change2").on("click", function() {
        $(".inline-block").css("display", "inline-block");
        $(".none").css("display", "none");
        $("#change2").attr("id", "change1");
    });

    // アニメーションを実行する独自関数横、要素の個数×横幅、この横幅をslideCurrentの数値によって移動する
    var sliding = function() {
            // slideCurrentが0以下だったら
            if (slideCurrent < 0) {
                slideCurrent = slideNum - 1;
                // slideCurrentがslideNumを超えたら
            } else if (slideCurrent > slideNum - 1) { // slideCUrrent >= slideNumでも可
                slideCurrent = 0;
            }

            $('.slideSet').stop().animate({
                left: slideCurrent * -slideWidth
            });
        }
        // 前へボタンが押されたとき
    $(document).on("click", '.slider-prev', function() {
        slideCurrent--;
        sliding();
        // そのマーカーの地点を拡大表示
        set_latlng = marker_ary[slideCurrent].getPosition();
        map.panTo(set_latlng);
    });
    // 次へボタンが押されたとき
    $(document).on("click", '.slider-next', function() {
        slideCurrent++;
        sliding();
        // そのマーカーの地点を拡大表示
        set_latlng = marker_ary[slideCurrent].getPosition();
        map.panTo(set_latlng);
    });
    // この周辺を検索を押した時
    $("#re-search-btn").on("click", function() {
        MarkerClear();
        ListCrear();
        setPointMarker();
    });
    //現在位置取得
    $(".Current-position-search").on("click", function() {
        MarkerClear();
        ListCrear();
        Current();


    });


    //現在地表示
    function Current() {
        // ユーザーの端末がGeoLocation APIに対応しているかの判定

        // 対応している場合
        if (navigator.geolocation) {
            // 現在地を取得
            navigator.geolocation.getCurrentPosition(

                // [第1引数] 取得に成功した場合の関数
                function(position) {
                    // 取得したデータの整理
                    var data = position.coords;

                    // データの整理
                    var lat = data.latitude;
                    var lng = data.longitude;


                    // 位置情報
                    latlng = new google.maps.LatLng(lat, lng);

                    // Google Mapsに書き出し
                    map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 17,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        center: latlng,
                        zoomControl: true,
                        zoomControlOptions: {
                            position: google.maps.ControlPosition.RIGHT_CENTER
                        },
                        disableDefaultUI: true,
                    });
                    google.maps.event.addListener(map, 'projection_changed', function() {
                        $('#re-search-btn').trigger('click'); //機能があれば、なにか処理をしたきっかけで、そちらを作動させることができる。
                    });
                },

                // [第2引数] 取得に失敗した場合の関数
                function(error) {
                    // エラーコード(error.code)の番号
                    // 0:UNKNOWN_ERROR				原因不明のエラー
                    // 1:PERMISSION_DENIED			利用者が位置情報の取得を許可しなかった
                    // 2:POSITION_UNAVAILABLE		電波状況などで位置情報が取得できなかった
                    // 3:TIMEOUT					位置情報の取得に時間がかかり過ぎた…

                    // エラー番号に対応したメッセージ
                    var errorInfo = [
                        "原因不明のエラーが発生しました…。",
                        "位置情報の取得が許可されませんでした…。",
                        "電波状況などで位置情報が取得できませんでした…。",
                        "位置情報の取得に時間がかかり過ぎてタイムアウトしました…。"
                    ];
                    // エラー番号
                    var errorNo = error.code;
                    // エラーメッセージ
                    var errorMessage = "[エラー番号: " + errorNo + "]\n" + errorInfo[errorNo];
                    // アラート表示
                    alert(errorMessage);
                    // HTMLに書き出し
                    document.getElementById("result").innerHTML = errorMessage;
                },
                // [第3引数] オプション
                {
                    "enableHighAccuracy": false,
                    "timeout": 8000,
                    "maximumAge": 2000,
                }
            );
        }
        // 対応していない場合
        else {
            // エラーメッセージ
            var errorMessage = "お使いの端末は、GeoLacation APIに対応していません。";
            // アラート表示
            alert(errorMessage);
            // HTMLに書き出し
            document.getElementById('result').innerHTML = errorMessage;
        }

    }
    
    
    
    
//スマートフォン表示の際のマップ、リスト切り替え
        $("#switching").on("click", "#mapbutton", function(){
           $("#switching").empty(); 
           $("#switching").append(
               '<button type="button" class="btn btn-default navbar-btn visible-xs-inline" id="listbutton">リスト</button>'
           ); 
            $(".display-list").removeClass("show").addClass("hidden");
            $(".display-map").removeClass("hidden").addClass("show");
            switch(type){
                case 1:
                    $("#review-list").trigger("click");
                    break;
                case 2:
                    $("#like-list").trigger("click");
                    break;
                case 3:
                    $("#createspot-list").trigger("click");
                    break;    
            }
            
        });
        $("#switching").on("click", "#listbutton", function(){
           $("#switching").empty(); 
           $("#switching").append(
               '<button type="button" class="btn btn-default navbar-btn visible-xs-inline" id="mapbutton">マップ</button>'
           ); 
            $(".display-map").removeClass("show").addClass("hidden");
            $(".display-list").removeClass("hidden").addClass("show");
        });
    
//ページの要素のサイズ取得
    $(document).ready(function() {
        var hsize = $(window).height();
        var wsize = $(window).width();

        var H = hsize - 90;
        var W = wsize - 500;

        
        
        //  $(".heighttest").css("height", hsize + "px");
        //スマホデバイスの場合のリストwidth
        if ( $("#map-big").is(":visible")){
            $("#map-big").css("height", H + "px");
            $("#map-big").css("width", W + "px");
            var h = H - $(".display-control").outerHeight();
            $(".display-list").css("height", h+"px");
//            $(".display-area").css("width", "400px");
            
        }else{
            $(".display-area").css("width", "100%");
            var h = H - $(".display-control").outerHeight();
            $(".display-list").css("height", h+"px");
            $(".display-map").css("height", h+"px");
        }
        
    });
    $(window).resize(function() {
        var hsize = $(window).height();
        var wsize = $(window).width();

        var H = hsize - 90;
        var W = wsize - 500;

        
        //スマホデバイスの場合のリストwidth,heightを設定
        if ( $("#map-big").is(":visible")){
            $("#map-big").css("height", H + "px");
            $("#map-big").css("width", W + "px");
            var h = H - $(".display-control").outerHeight();
            $(".display-list").css("height", h+"px");
//            $(".display-area").css("width", "400px");
        }else{
            $(".display-area").css("width", "100%");
            var h = H - $(".display-control").outerHeight();
            $(".display-list").css("height", h+"px");
            $(".display-map").css("height", h+"px");
        }
    });
    
    
//$.extend({
//		htmlspecialchars: function htmlspecialchars(ch){
//				ch = ch.replace(/&/g,"&amp;") ;
//			    ch = ch.replace(/"/g,"&quot;") ;
//			    ch = ch.replace(/'/g,"&#039;") ;
//			    ch = ch.replace(/</g,"&lt;") ;
//			    ch = ch.replace(/>/g,"&gt;") ;
//			    return ch ;
//			}
//	});
//})(jQuery);
function h(val){
  return val.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, "\\$&");
}
//function escapeSelectorString(val){
//  return val.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, "\\$&");
//}

</script>   

   
 

  
</body>
</html>