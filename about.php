<?php
session_start();
include("./function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
//sessionCheck();//セッションの入れ替え


$pdo = db_con();

$stmt = $pdo->prepare("
SELECT  A.review_id, A.comment, A.review_state, A.spot_id, A.menber_id, A.discovery_point, A.like_point, B.image_name, C.spotname, D.name, D.area, D.sex, D.profileimg  
FROM review_table AS A
LEFT JOIN (
    SELECT review_id, image_name 
    FROM images_table
    WHERE image_name 
    LIKE '%0.jpg'
    )AS B ON A.review_id = B.review_id
LEFT JOIN spot_table AS C ON A.spot_id = C.spot_id
LEFT JOIN menber_table AS D ON A.menber_id = D.menber_id
WHERE A.review_state = 1
ORDER BY A.review_id desc;
");
$status = $stmt->execute();














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
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/jumbotron.css" rel="stylesheet">
    <link href="./css/custom.css" rel="stylesheet">
    <link href="./css/menu.css" rel="stylesheet">
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="./js/bootstrap.js"></script>
    <style>
        .jumbotron{
            background:white;
        }
        .mypage-header{
    background: rgba(35, 35, 35, 0.05);
/*    background: #444444;*/
            
        }
        .mypage-header a{
            color:#1e1e1e;
            padding-top:10px;
            padding-bottom:6px;
            display:inline-block;
            margin-right:20px;
        }
        .mypage-header .mypage-active{
            color:#1e1e1e;
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
        /*        スポット情報の文字サイズ*/
        form{
            font-size:18px;
        }
/*        編集ボタン*/
        .btn-edit,
        .btn-edit-stop{
            margin-top:10px;
        }
        .contentsA p{
            font-size:22px;
            line-height: 1.5;
            color:#484848;
        }
        .titleA{
            text-align: center;
            margin-bottom:40px;
            color:#484848;
        }
        #wrapper{
            margin-top:50px;
            text-align: left;
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
         
        </div>
    </nav>
<!--    メニュー-->
<div class="mypage-header">
    <div class="container">
        <a href="./about.php" class="mypage-active">マイロカボについて</a>
        <a href="./guideline.php">コンテンツガイドライン</a>
        <a href="./privacy.php">プライバシーポリシー</a>
        <a href="./terms.php">利用規約</a>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-lg-offset-2">
            <div id="wrapper">
  <div class="site1column">
    <article class="contentsA">

      <h1 class="titleA">
        <span><strong>マイロカボについて</strong></span>
      </h1>
      
    
    
    <p>マイロカボは、低糖質仲間におススメできるかどうかを基準に、実際にご利用されたスポットの体験や発見を文章/写真等により投稿していただくことで、体験や情報を共有出来る口コミサイトです。</p>
    <p>それぞれが見つけたお気に入りのスポットを記録することで、オリジナルなリスト、マップを作成できます。</p>
    <p>また、作成されたマップは共有することが出来るので、あたなの知らなかったスポットを発見したり、役立つ情報を見つけることでより豊かな低糖質な生活へとつながります。</p>
    <p>低糖質なスポットはまだまだ少ないため、一人一人が低糖質な世界の開拓者です。まだ見ぬ世界を開拓しましょう！</p>
    
    
    









    </article>
  </div>

  
</div>

        </div>
        
    </div>
    
</div>

</div>
    <!-- /container -->

    



<!--スマホサイズのメニュー画面展開画面-->
<?php include( './External/menu-content-small.php'); ?>
<!--メニュー画面展開スクリプト-->
<script type="text/javascript" src="./js/menu.js"></script> 
<!-- モーダル・ダイアログ　ログイン -->
<?php include ('./External/modal-login.php'); ?>
<!--場所検索フォームスクリプト-->
<script type="text/javascript" src="./js/search-place.js"></script>


<script>
    var area = '<?=$_SESSION["area_num"]?>';
    var sex = '<?=$_SESSION["sex_num"]?>';
    $("#area").val(area);
    $("#sex").val(sex);
//プロフィール情報の変更の処理
//    var menber_id = '<?=$_SESSION["menber_id"]?>';
    var data,id;
    $(".btn-edit").on("click", function(e){
        var data = e.target.previousElementSibling.attributes[0].ownerElement.value;//ボタンの１つ上の入力を取得
        var id = e.target.previousElementSibling.id;//ボタンの１つ上のid取得
        console.log(data);
        console.log(id);
//        data = data.replace(/\\n/g, "\n");
        
        //編集データ登録
            if (window.confirm('本当に変更してもよろしいですか？')) {
                $("#collapse-"+id).collapse('hide');
                edit(data, id).
                done(function(result) {
                    var result = JSON.parse(result);
                    console.log(result);
                    
                    if (id == "area" || id == "sex"){
                        $("#display-"+id).text("");
                        $("#display-"+id).text(result[0]);
                        $("#"+id).val("");
                        $("#"+id).val(result[1]);
                    }else if ( id == introduction){
                        $("#display-"+id).text("");
                        $("#display-"+id).text(result);
                        
                        $("#"+id).text("");
                        $("#"+id).text(result);
                    }else{
                        $("#display-"+id).text("");
                    $("#display-"+id).text(result);
                    $("#" + id).val(result);
                    }
                    
                    
                }).
                fail(function(result) {
                    alert("失敗しました。もう１度お願いします。");
                });
            } else {
            }
        //画像削除のajax関数
        function edit(data, id) {
//            return $.get(
//                'profile-edit_act.php?data=' + data + '&id=' + id 
//            );
            return $.ajax({
                        type: "POST",
                        url: "profile-edit_act.php",
                        data: {
                            data: data,
                            id:id
                        },
                    });
        }
        
    });
    $(".btn-edit-stop").click(function(e){
        var type = e.target.classList[3];
        $("#collapse-"+ type).collapse('hide');
        $("#"+type).val("");
    });
    
    function nl2br(str) {
    str = str.replace(/\r\n/g, "<br />");
    str = str.replace(/(\n|\r)/g, "<br />");
    return str;
}
    
    
    
    
    
    
    
    
    
    
    
    
    
//    
//    
//
//
//    $("#test").on("click", function(){
//    });
//    
//    
//    
//    $("#save").on("click", function(){
//        area = $("#area").val();
//        sex = $("#sex").val();
//    });
    

    
    
</script>  
        
        
        
        



   
 

  
</body>
</html>