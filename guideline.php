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
        
        .titleA{
/*            text-align: center;*/
            margin-bottom:40px;
            margin-top:50px;
            color:#484848;
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
        <a href="./about.php">マイロカボについて</a>
        <a href="./guideline.php" class="mypage-active">コンテンツガイドライン</a>
        <a href="./privacy.php">プライバシーポリシー</a>
        <a href="./terms.php">利用規約</a>
    </div>
</div>









<!---->

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="wrapper">
  <div class="site1column">
    <article class="contentsA">

      <h1 class="titleA">
        <span><strong>コンテンツガイドライン</strong></span>
      </h1>
      <h3>はじめに</h3>
<p>
マイロカボではユーザー様に低糖質な視点からスポットを紹介して頂きたいと思っておりますが、健全で価値あるサイト運営を行っていくためのルールとして、口コミや写真を投稿して頂く際に遵守して頂きたいいくつかのポイントをガイドラインとして設定させて頂きます。 </p>
<p>
ユーザー様にとって快適なサービスとなりますようご協力お願い致します。 </p>
<p>
本ガイドライン及び利用規約に違反する口コミや写真については、運営側より、ユーザー様に修正をお願いする場合や、場合によっては予告なく削除させて頂くことも御座います点ご承知置き下さい。万が一の場合に備え、口コミの文章/写真に関しては、ユーザー様にて原本を保管、バックアップ等しておくことをお勧め致します。 </p>
<p>
なお、本ガイドラインは予告なく改訂されることがあり、改訂後にマイロカボのサービスを利用した場合、ユーザー様は改訂後の本ガイドラインに同意したものとさせていただきます。 </p>
      

      <h3>1．スポットを探す方にとって意味のある情報になるよう心がけましょう</h3>
      <p>
      訪れたことのあるスポットの情報をご記載ください。<br />
      ※上記の内容に抵触している投稿は運営側の判断により、投稿を削除させて頂く事がござますので予めご了承ください。<br />
      </p>

      <h3>2．事実確認のできない誹謗中傷や個人的なクレームは避けましょう</h3>
      <p>
      根拠のない誹謗中傷はユーザーの混乱を招くことがあるため、事実確認のできない誹謗中傷の記載はお控えください。<br />
      個人的なクレームに関してもご遠慮ください。（投稿された口コミの内容が事実かどうかの確認は行っておりません。）<br />
      ＊根拠のない断定的な表現はお控えください。<br />
      ＊個人的なクレームや食品衛生法・衛星管理に関する内容の口コミはしかるべき当局へご連絡をお願いします。<br />
      </p>


      <h3>3．ユーザー間での誹謗中傷はやめましょう</h3>
      <p>
      マイロカボでは個人の主観を尊重することが閲覧者にとって有益な情報提供につながると考えているため、他のユーザーを誹謗中傷することはご遠慮ください。<br />
      </p>


      <h3>4．節度ある言葉使いと表現を心がけましょう</h3>
      <p>
      皆様に気持ちよく利用していただくため、節度ある言葉使いと表現を使ってください。<br />
      <br />
      </p>


      <h3>5．法律違反に結びつくような投稿はやめましょう</h3>
      <p>
      マイロカボは告発の場ではありません。また、法律に違反しているような問題は犯罪を助長する可能性があるため、しかるべき当局へご連絡ください。<br />
      </p>


      <h3>6．個人情報は守りましょう</h3>
      <p>
      プライバシーの侵害となるような内容や表現はご遠慮ください。<br />
      </p>


      <h3>7.著作権、知的財産権を守りましょう</h3>
      <p>
      雑誌、書籍等から引用する場合は、引用部分と口コミ本文とを明確明確に区別するようにしてください。<br />
      他のサイトから引用する場合は、そのサイトの運営者に引用する許可を必ずとってください。<br />
      無断で引用した投稿に関しては当社では一切責任を負いませんので予めご了承ください。<br />
      また、マイロカボ内で他のユーザー様の投稿を引用する事も同様です。<br />
      </p>


      <h3>8．店舗関係者によるやらせ等はやめましょう</h3>
      <p>
      閲覧する方にとっての信頼を担保する為、店舗関係者の方の口コミ等はご遠慮ください。<br />
      少しでも気になる口コミがありましたら事実を確認し削除させていただく事もございますのでご了承ください。<br />
      </p>

      <h3>最後に・・・</h3>
      <p>
      マイロカボでは健全な口コミと質を保つために<br />
      日々口コミを管理しチェックをさせていただいております。<br />
      本ガイドラインに抵触する口コミはユーザー様に断わりなく削除または、修正依頼をさせていただくことがございますので予めご了承ください。<br />
      </p>

    </article>
  </div>

  
</div>

        </div>
        
    </div>
    
</div>


<!---->


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
                    
                    if ( id == "area" || id == "sex"){
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