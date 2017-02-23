<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え



//口コミ口コミを書く際に、必要となる情報セット、セッション変数に代入済み
//1,$menber_id  投稿者     $_SESSION["menber_id"]
//2,$spot_id    口コミ先ID  $_SESSION["review_spot_id"]
//3,$spotname   口コミ先名前  $_SESSION["review_spotname"]
//4,$review_id  口コミID       
//5,$commnet    口コミ内容       $_SESSION["review_comment"]
//6,$point    口コミ内容       $_SESSION["review_point"]
//7,$img    アップロード画像       $_SESSION["review_img"]

//A、口コミ先を選択して、このページにやってきた場合
if( isset($_GET["spot_id"]) && $_GET["spot_id"]!="" ){
    $spot_id = $_GET["spot_id"];
    //データベース接続をして、口コミ口コミを書くspotの情報を取得
    $pdo = db_con();
    $stmt = $pdo->prepare("
    SELECT spot_id, spotname, lat, lng 
    FROM spot_table 
    WHERE spot_table.spot_id=:a1
    ");
    $stmt->bindValue(':a1', $spot_id, PDO::PARAM_INT);
    $status = $stmt->execute();
    if($status==false){
      db_error($stmt);
    }
    $val = $stmt->fetch();
    $spotname = $val["spotname"];
    $menber_id = $_SESSION["menber_id"];
    $_SESSION["review_img"] = "";
    $lat = $val["lat"];
    $lng = $val["lng"];
    
    
    
//    //review_tableへデータ登録SQL作成,commentのみあとで追加で登録
//    $stmt = $pdo->prepare("
//    INSERT INTO review_table
//    (review_id,menber_id,spot_id,review_state)
//    VALUES
//    (NULL, :a1, :a2, :a3)
//    ");
//    $stmt->bindValue(':a1', $menber_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
//    $stmt->bindValue(':a2', $spot_id,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
//    $stmt->bindValue(':a3', 0,  PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
//    $status = $stmt->execute();   //セキュリティにいい書き方
//    //SQL処理エラー
//    if($status==false){
//    //4,データ登録処理後
//        db_error($stmt);
//    }
//    //直近の登録したデータのユニークIDを取得
//    $_SESSION["review_review_id"] = $pdo->lastInsertid();
//    $_SESSION["review_comment"] = "";//テキストエリアに入力したものを取得したい
//    
//    $reviewimg = "";
   $_SESSION["review_point"] = 0;
}
//主に、口コミ投稿を拒否された時投稿データを反映


//値を保持するためにajaxを使って値の確認をする

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
        .r-img{
    width:16.2vw;
    margin-bottom:1vw;
    margin-left:0.5vw;
    margin-right:0.5vw;
}
        .reviewpoint{
            font-size:30px;
            color:#a0a0a0;
        }
        .active{
            color:#EB6E00;
        }
        #tag-displayarea{
            font-size:18px;
            color:gray;
        }
        /*        タグのcss*/
        .btn-delete-tag{
            margin-left:3px;
            vertical-align: bottom;
        }
        #tag-displayarea div{
            display: inline-block;
        }
/*        formの文字の装飾*/
        .page-header,
        form label{
            color:#484848;
        }
        form label{
            font-size:18px;
        }
        .necessary{
            color:#ff4b4b;
            font-size:18px;
            font-weight: 500;
        }
/*        画像選択部分*/
        .reviewimg-select{
            color:#484848;
        }
        .reviewimg-select span{
            margin-right:1%;;
        }
    </style>
    
  </head>
<body>
    <!-- container -->
<div id="index-main">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
                <a class="navbar-brand" href="../index.php">マイロカボ</a>
            </div>
         
            <div id="navbar" class="navbar-collapse collapse">
<!--ログアウト時-->
<?php if(isset($_SESSION["chk_ssid"]) != session_id()) : ?>
<!--ログイン時-->
<?php else : ?>
<?php endif; ?>
            </div>
        </div>
    </nav>
</div>
    <!-- /container -->
    
<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="page-header">
                <h3><?=$spotname?></h3>
            </div>
            <!--メインコンテンツ-->
<!--            <form action="create_act.php?lat=<?=$lat?>&lng=<?=$lng?>" method="post">-->
            <form action="create_act.php?spot_id=<?=$spot_id?>" method="post">
                
                <div class="form-group">
                    <label for="InputSelect">時間帯<spna class="necessary">（必須）</spna></label>
                        <select class="form-control input-lg" name="time" id="time" onchange="changeItem(this)" required style='color:#989898;'>
                              <option value='0' disabled selected style='display:none;'>時間帯</option>
                              <option value='1'>朝</option>
                              <option value='2'>昼</option>
                              <option value='3'>夜</option>
                              <option value='4'>その他</option>
                        </select>
                </div>
                <div class="alert-time"></div>
                <div class="form-group">
                    <label for="InputSelect">金額<spna class="necessary">（必須）</spna></label>
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
                <div class="alert-money"></div>
                <div class="form-group">
                    <div class="reviewpoint">
                        <span class="glyphicon glyphicon-star" aria-hidden="true" id="one"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" id="two"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" id="three"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" id="four"></span>
                        <span class="glyphicon glyphicon-star" aria-hidden="true" id="five"></span>
                        <spna class="necessary">（必須）</spna>
                    </div>
<!--                    <input type="hidden" id="point" name="point" value="">-->
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
                </div>
                <div class="alert-point"></div>
                <div class="form-group">
                    <label for="comment">コメントお願いします<spna class="necessary">（必須）</spna></label>
                    <textarea class="form-control" id="comment" name="comment" placeholder="" rows="15"></textarea>
                </div>
                <div class="alert-comment"></div>
                <div class="form-group">
                   <label for="InputSelect">画像</label>
                    <div class="uploadimgarea">
                           <p id="imgarea"></p>
                            <button type="button" class="btn btn-default btn-block btn-lg reviewimg-select"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span>画像を選ぶ</button>
                            
                    </div>
                </div>
                <div class="form-group">
                  <label for="InputSelect">タグ</label>
                   <p id="tag-displayarea"></p>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon">#</span>
                        <input type="text" class="form-control" placeholder="タグを付けましょう。" id="tag-inputarea">
                        <span class="input-group-btn">
                                <button type="button" class="btn btn-default" id="add-tag">追加</button>
                        </span>
                    </div>
                    <input type="hidden" name="tag" id="tag" value="">
                </div>
                  <div class="alertbox"></div>
                   
                    <button type="submit" id="registration-hide" class="btn btn-primary btn-block btn-lg">口コミを書く</button>
                    <button type="button" id="registration-show" class="btn btn-primary btn-block btn-lg">口コミを書く</button>
                
                
                
            </form>
            <!--       画像選択エリア-->
            <form action="reviewimg-upload.php" method="post" enctype="multipart/form-data" class="imgform">
                <input type="file" accept="image/jpg, image/jpeg" capture="camera" id="upreviewimg" name="upreviewimg[]" multiple="multiple">
                <input type="hidden" name="provisionalcomment" id="provisionalcomment" value="">
                <button type="button">アップロード</button>
            </form>
        </div>
    </div>
</div>
<script>
    $("#registration-hide").hide();//submitボタンを隠す
//口コミを書くボタンを押したらajaxで値が取得できているか確認して、取得できていればsubmit
    $("#registration-show").on("click", function(e){
        e.preventDefault();
        var time = $("#time").val();
        var money = $("#money").val();
        var comment = $("#comment").val();
        var point = $("#point").val();
            $.ajax({
                url: 'check.php',
                type: 'post',
                data: {
                    time:time,
                    money:money,
                    comment:comment,
                    point:point
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $(".alert-time").empty();
                    $(".alert-money").empty();
                    $(".alert-comment").empty();
                    $(".alert-point").empty();
                    if ( data.length == 0 ){
                        $("#registration-hide").trigger("click");
                    }else{
                        $.each(data, function(index, elem) {
                            console.log(elem);
                                switch(elem){
                                    case "time":
                                        $(".alert-time").append(
                                            '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>時間帯を選択してください。</div>'
                                        );
                                        break;
                                    case "money":
                                        $(".alert-money").append(
                                            '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>金額を選択してください。</div>'
                                        );
                                        break;
                                    case "comment":
                                        $(".alert-comment").append(
                                            '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>コメントの入力が必要です。</div>'
                                        );
                                        break;
                                    case "point":
                                        $(".alert-point").append(
                                            '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>レビュー評価をお願いします。</div>'
                                        );
                                        break;    
                                }
                        });
                        
                    }
                    
                },
                error: function(xhr, status, error) {
                    alert('ERROR : ' + status + ' : ' + error);
                }
            });
        
    });
    //スタンスselectの文字の色の調整
    function changeItem(obj){ 
        if( obj.value != 0 ){ 
            obj.style.color = '#555'; 
        }
    } 

//タグ入力に関しての処理
    //エンターを押した時の処理
    $("#tag-inputarea").on("keydown", function(e) {
        if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
            $("#add-tag").trigger("click");
            return false;
        } else {
            return true;
        }
    });
    var tag = [];//追加したタグを配列でphpへ渡す
    $("#add-tag").on("click", function(e){
        var createtag = $("#tag-inputarea").val();
        if ( createtag == ""){
            return false;
        }else{
            //空白削除
            createtag = createtag.replace(/\s+/g, "");
            $("#tag-displayarea").append("<div class='taging'>#"+createtag+"<a class='btn btn-default btn-xs btn-delete-tag'  role='button'>削除</a>　</div>");
            $("#tag-inputarea").val("");
            tag.push(createtag);//タグの配列作成
            $("#tag").val(tag);//inputに代入  
        }
    });

//表示されているタグの削除
    $(document).on("click", "#tag-displayarea div", function(e){
        var tagnum = $(".taging").index(this);//何番目のタグか確認
        var name = $("#tag-displayarea div").eq(tagnum).text();//タグ取得
        $("#tag-displayarea div").eq(tagnum).remove();//タグ削除
        var tagname = name.substr(1);
        tagname = tagname.substr(0, tagname.length-3);
        tag.splice(tagnum, 1);//クリックしたタグを配列から削除
        $("#tag").val(tag);
    });
//    //住所を入力時にエンターキーを押した場合の処理
//    $(document).on("keypress", "#comment", function(e) {
//        if (e.which == '13') {
//            $("#search").trigger("click");
//            e.preventDefault();
//        }
//        
//    });   
    $(".imgform").hide();
    //このスポットを登録するを押した時、空欄があれば、submitを取りやめる
    $("#registration").on("click", function(e){
        var comment = $("#comment").val();
        var point = $("#point").val();
        $(".alertbox").empty();
        if( comment == "" ){
            e.preventDefault();
            $(".alertbox").append(
                '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>コメントの入力が必要です。</div>'
            );
        }
        if( point == "" ){
            e.preventDefault();
            $(".alertbox").append(
                '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>レビュー評価の入力が必要です。</div>'
            );
        }
    });
    //画像選択
    //画像を選択するボタンをクリックして画像選択を起動
    $(".reviewimg-select").on("click", function(){
        $("#upreviewimg").trigger("click");
    });
    //画像選択されたら画像を保存
    $('#upreviewimg').on("change", function() {
        var spot_id = '<?=$spot_id?>';
            var $form = $('.imgform');
            var fdo = new FormData($form[0]);
            //問題点
//            console.log($form[0]);
            $.ajax({
                url: 'reviewimg-upload.php?spot_id=' + spot_id,
                type: 'post',
                processData: false,
                contentType: false,
                data: fdo,
                dataType: 'json',
                success: function(data) {
//                    var test = $.parseJSON(data);
//                    console.log(data);
                    $.each(data, function(index, elem) {
                        $("#imgarea").append(
                            '<img src="../upload/s/' + elem + '" class="r-img">'
                        );
                    });
                },
                error: function(xhr, status, error) {
                    alert('ERROR : ' + status + ' : ' + error);
                }
            });
        });
    //画像削除
    //クリックされたレビュー画像を削除
    $("#imgarea").on("click", function(e) {
        var image_name = $(".r-img").attr('src');
        console.log(image_name);
        if (window.confirm('この画像を削除しますか？')) {

            dele(image_name).
            done(function(result) {
                console.log(result);
                console.log("test");
                
                $("img[src='" + image_name + "']").remove();
            }).
            fail(function(result) {

            });
        } else {

        }
    });
    //画像削除のajax関数
    function dele(image_name) {
        return $.get(
            'reviewimg-delete.php?image_name=' + image_name
        );
    }
    //ページ離脱
    //ページ離脱の確認
    var onBeforeunloadHandler = function(e) {
        return 'ページを離脱すると記入内容が破棄されます。';
    };
    
    $(window).on('beforeunload', onBeforeunloadHandler);
    //ページ離脱、form送信の場合は解除
    $('form').on('submit', function(e) {
        $(window).off('beforeunload', onBeforeunloadHandler);
    });
    
    //評価ポイントクリック時の処理
    var review_point ="";
    $("#one").on("click", function(){
        console.log("one");
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
        console.log("two");
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
        console.log("three");
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
        console.log("four");
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
        console.log("five");
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
    


    
    
</script>
</body>
</html>