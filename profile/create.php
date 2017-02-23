<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え
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

  </head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
                <a class="navbar-brand" href="#">新規登録</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <form class="navbar-form navbar-right">
                    <div class="form-group">
                        <input type="text" placeholder="Email" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Sign in</button>
                </form>
            </div>
            <!--/.navbar-collapse -->
        </div>
    </nav>
<!--メインコンテンツ-->
<div class="container">
   <div class="row">
   <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

       <h1>
            <strong>プロフィール画像を設定しましょう。</strong>
        </h1>
        <div class="thumbnail">
            <img class="prof-img-size img-circle" src="../img/Default-profileimg.png">
            
        </div>
        
<!--
        <h1>
            参加者同士、口コミを見たときにどれくらいのスタンスで生活されているかの目安になります。
        </h1>
        <h1>
            参加者の方同士、活動エリアを知ることで、近くで同じ取り組みをしている人と仲間意識も芽生えいるかも？
        </h1>
        
        <h1>
            同性同士、同じ悩みを抱えているかも？同性の口コミが参考になるかもしれません
        </h1>
        
        <h1>取り組むようになったきっかけ、今までの体験などなど、
            <?=$_SESSION["name"]?>さんのLowCarboLifeについてたっぷりと記入してください
        </h1>
        <h1>
            すべての項目の記入は終わりましたか？よろしければ登録ボタンを押してください。あとで変更も可能です。
        </h1>
-->
        <!--<form action="profile-create_act.php" method="post" enctype="multipart/form-data" class="height100">-->
<form action="profile-create_act.php" method="post">
<!--  プロフィール画像-->
<div class="form-group">
    <label for="name">プロフィール画像</label>
    <input type="file" accept="image/*" capture="camera" id="profileimg" name="profileimg">
    <input type="hidden" name="profileimg" id="default-profileimg" value="Default-profileimg.png">
</div>
<!--   地域-->
<div class="form-group">
    <label for="area">活動エリア</label>
    <select class="form-control input-lg" name="area" id="area" placeholder="活動エリア">
        <option>北海道</option>
        <option>青森県</option>
        <option>岩手県</option>
        <option>宮城県</option>
        <option>秋田県</option>
        <option>山形県</option>  
        <option>福島県</option>  
        <option>茨城県</option>  
        <option>栃木県</option>  
        <option>群馬県</option>
        <option>埼玉県</option>  
        <option>千葉県</option>  
        <option>東京都</option>  
        <option>神奈川県</option>  
        <option>新潟県</option>
        <option>富山県</option>  
        <option>石川県</option>  
        <option>福井県</option>  
        <option>山梨県</option>  
        <option>長野県</option>
        <option>岐阜県</option>  
        <option>静岡県</option>  
        <option>愛知県</option>  
        <option>三重県</option>  
        <option>滋賀県</option>
        <option>京都府</option>  
        <option>大阪府</option>  
        <option>兵庫県</option>  
        <option>奈良県</option>  
        <option>和歌山県</option>
        <option>鳥取県</option>  
        <option>島根県</option>  
        <option>岡山県</option>  
        <option>広島県</option>  
        <option>山口県</option>
        <option>徳島県</option>  
        <option>香川県</option>  
        <option>愛媛県</option>  
        <option>高知県</option>  
        <option>福岡県</option>
        <option>佐賀県</option>  
        <option>長崎県</option>  
        <option>熊本県</option>  
        <option>大分県</option>  
        <option>宮崎県</option>
        <option>鹿児島県</option>
        <option>沖縄県</option>
    </select>
</div>
<!--   性別-->
<div class="form-group">
    <label for="sex">性別</label>
    <select class="form-control input-lg" name="sex" id="sex" placeholder="スタンス">
        <option>男性</option>
        <option>女性</option>
    </select>
</div>
<!--   自己紹介-->
<div class="form-group">
    <label for="introduction">自己紹介</label>
    <textarea class="form-control input-lg" rows="30" name="introduction" id="introduction" placeholder="あなたの紹介、体験、取り組みなど自由に記入してください"></textarea>
</div>
   
<input type="submit" class="btn btn-primary" value="プロフィールを登録する">
    
    
    
</form>
   </div>
   
   </div>
    

</div>





<script>
$(function(){
  //画像ファイルプレビュー表示のイベント追加 fileを選択時に発火するイベントを登録
  $('form').on('change', 'input[type="file"]', function(e) {
    var file = e.target.files[0],
        reader = new FileReader(),
        $medialeft = $(".media-left");
        t = this;

    // 画像ファイル以外の場合は何もしない
    if(file.type.indexOf("image") < 0){
      return false;
    }

    // ファイル読み込みが完了した際のイベント登録
    reader.onload = (function(file) {
      return function(e) {
        //既存のプレビューを削除
        $medialeft.empty();
        // .prevewの領域の中にロードした画像を表示するimageタグを追加
        $medialeft.append($('<img>').attr({
                  src: e.target.result,
                  class: "prof-img-size",
                  title: file.name
              }));
      };
    })(file);
      
    //ファイルが選択されなかった場合に設定するプロフィール画像のファイル名送信用タグ削除  
    $("#default-profileimg").remove();

    reader.readAsDataURL(file);
  });
});

</script>   
</body>
</html>