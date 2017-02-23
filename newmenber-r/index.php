<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
//sessionCheck();//セッションの入れ替え
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
        /*スタンスの違いを表す色*/
        
        .marker_yellow_hoso {
            background: linear-gradient(transparent 60%, #ffff66 60%);
            color: #545454;
        }
        
        .marker_red_hoso {
            background: linear-gradient(transparent 60%, #ff7466 60%);
            color: #545454;
        }
        
        .marker_green_hoso {
            background: linear-gradient(transparent 60%, #ceff66 60%);
            color: #545454;
        }
        /*    モーダルのデザイン*/
        
        .modal-footer {
            text-align: left;
            color: gray;
        }
/*        フォームのデザイン*/
        .note{
            color:gray;
            margin-top:5px;
            margin-bottom:5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="../index.php">マイロカボ</a>
            </div>

            <!--/.navbar-collapse -->
        </div>
    </nav>
    <!--メインコンテンツ-->
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3">
                <div class="page-header">
                    <h1>あなたのオリジナルな地図を作成して低糖質な世界を開拓しましょう！</h1>
<!--                    <h1>低糖質な世界を築くのはあなたです！あなたの知っている世界を教えてください！</h1>-->
                </div>
                <form name="newmenber-form" action="./mail.php" method="post" id="newmenber-form">
                    <div class="form-group">
                        <!--                    <label for="email">メールアドレス</label>-->
                        <input type="email" class="form-control input-lg validate[required]" name="email" id="email" placeholder="メールアドレス" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <!--                    <label for="passwd">パスワード</label>-->
                        <input type="password" class="form-control input-lg validate[required]" name="password" id="password" placeholder="パスワード" autocomplete="off" required>
                        <p class="note">※8文字以上12文字未満の半角英数字</p>
                        <a class="btn btn-default" role="button" id="password-show">パスワードを表示する</a>
                    </div>
                    
                    <!--        スタンス-->
<!--
                    <div class="form-group">
                                            <label for="stance">スタンス</label>
                        <select class="form-control input-lg" name="stance" id="stance" onchange="changeItem(this)" required style='color:#989898;'>
                                  <option value='0' disabled selected style='display:none;'>スタンス</option>
                                   <option value='1'>きっちり</option>
                                   <option value='2'>しっかり</option>
                                   <option value='3'>ゆるく</option>
                                </select>
                    </div>
                    <hr>
                    <a href="" data-toggle="modal" data-target="#sampleModal">
                        <h4>スタンスとは・・・</h4>
                    </a>
                    <hr>
-->
                    
                    <p>
                        <button type="submit" class="btn btn-success btn-lg btn-block" id="registration">登録する</button> 
                        <a href="#" class="btn btn-success btn-lg btn-block" disabled="disabled" id="loading"><img src="../img/ajax-loader.gif" alt=""></a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <!-- モーダル・ダイアログ -->
    <div class="modal fade" id="sampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                    <h1 class="modal-title">スタンスとは</h1>
                </div>
                <div class="modal-body lead">
                    <p>あなたがどれくらいの意識で低糖質生活を送っているのか教えて下さい。口コミを投稿した人のスタンスを把握することで、口コミをより有効に活用できます。</p>
                    <h3><span class="marker_yellow_hoso">きっちり</span><br><small>1日の食事ごとに摂取するｇなども意識している。</small></h3>
                    <h3><span class="marker_red_hoso">しっかり</span><br><small>主食（根菜類）は摂取を控えているが、おかずなどは気にせず摂取している。</small></h3>
                    <h3><span class="marker_green_hoso">ゆるく</span><br><small>時々、炭水化物を抜いたり、一度の食事で摂取する量を抑えている。</small></h3>
                </div>
                <div class="modal-footer">※上記は目安です。あとで変更も可能です。詳細は、自己紹介欄などで記入していただく事もできます。
                </div>
            </div>
        </div>
    </div>
    <script>
        $("#loading").hide();
        
//パスワードのバリデーション
          $("form").submit(function(){
              //submitされた時のパスワードのチェック
            if( !$("input[name=password]").val().match(/^[a-z\d]{8,12}$/i) ){
              alert("パスワードの入力は8文字以上12文字以内の半角英数字でお願いします。");
//              return false;
            }
            return true;
          });
//パスワードの表示、非表示切り替え
        $("#password-show").on("click", function(e){
            console.log(e.target.innerHTML);
            var state = e.target.innerHTML;
            if ( state == "パスワードを表示する" ) {
                $('#password').attr('type','text');
                $(this).html("パスワードを隠す");
            } else {
                $('#password').attr('type','password');
                $(this).html("パスワードを表示する");
            }

        });

        
        $("#registration").submit(function(){
            $("#registration").hide();
            $("#loading").show();
        });
        //スタンスselectの文字の色の調整
        function changeItem(obj){ 
            if( obj.value != 0 ){ 
                obj.style.color = '#555'; 
            }
        } 
        $("#registration").on("click",function(){
            var name = $("#name").val();
            var email = $("#email").val();
            var pass = $("#password").val();
        });
//        //入力されているかのチェック
//        $("input, select").blur(function(){
//            var name = $("#name").val();
//            var email = $("#email").val();
//            var pass = $("#password").val();
//            var stance = $("#stance").val();
//            if( name.length > 0 && email.length > 0 && pass.length > 0 && stance != null ){
//                $('#registration').removeAttr('disabled');
//            }else{
//                $('#registration').attr('disabled', 'disabled');
//            }
//        });
//        $("input").focus(function(){
//            $('#registration').attr('disabled', 'disabled');
//        });
    </script>   
</body>
</html>