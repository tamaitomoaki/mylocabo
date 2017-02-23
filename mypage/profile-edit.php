<?php
session_start();
include("../function.php");
sessionCheck();//セッションの入れ替え


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

$profileimg = $_SESSION["profileimg"];

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
        .jumbotron {
            background: white;
        }

        .mypage-header {
            background: #444444;
        }

        .mypage-header a {
            color: gray;
            padding-top: 10px;
            padding-bottom: 6px;
            display: inline-block;
            margin-right: 20px;
        }

        .mypage-header .mypage-active {
            color: white;
            border-bottom: 4px solid #7ebb45;
        }
        /*        リンク装飾*/

        a:link {
            text-decoration: none;
        }

        a:visited {
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        a:active {
            text-decoration: none;
        }
        /*        スポット情報の文字サイズ*/

        form {
            font-size: 18px;
        }
        /*        編集ボタン*/

        .btn-edit,
        .btn-edit-stop {
            margin-top: 10px;
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
        <!--    メニュー-->
        <div class="mypage-header">
            <div class="container">
                <a href="#" class="mypage-active">プロフィール編集</a>
                <a href="./profileimg-edit.php">プロフィール画像変更</a>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                    <form action="profile-edit_act.php" method="post" class="form-horizontal">
                        <div class="page-header">
                            <h3>プロフィールを充実させましょう。<br>編集したい項目をクリックしてください。</h3>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="col-sm-3 control-label">名前</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <a data-toggle="collapse" href="#collapse-name" id="display-name">
                                        <?=h($_SESSION["name"])?>
                                    </a>
                                </p>
                                <!--           スライド出現部分-->
                                <div class="collapse" id="collapse-name">
                                    <div class="well">
                                        <input type="text" class="form-control input-lg" id="name" name="name" placeholder="（例）飲食店">
                                        <a class="btn btn-default btn-edit" role="button">変更する</a>
                                        <a class="btn btn-default btn-edit-stop name" role="button">変更をやめる</a>
                                    </div>
                                </div>
                                <!--           終了-->
                            </div>
                        </div>
                        <!--   地域-->
                        <div class="form-group">
                            <label for="area" class="col-sm-3 control-label">活動エリア</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <a data-toggle="collapse" href="#collapse-area" id="display-area">
                                        <?=h($_SESSION["area"])?>
                                    </a>
                                </p>
                                <!--           スライド出現部分-->
                                <div class="collapse" id="collapse-area">
                                    <div class="well">
                                        <select class="form-control input-lg" name="area" id="area">
                                            <option value="1">北海道</option>
                                            <option value="2">青森県</option>
                                            <option value="3">岩手県</option>
                                            <option value="4">宮城県</option>
                                            <option value="5">秋田県</option>
                                            <option value="6">山形県</option>  
                                            <option value="7">福島県</option>  
                                            <option value="8">茨城県</option>  
                                            <option value="9">栃木県</option>  
                                            <option value="10">群馬県</option>
                                            <option value="11">埼玉県</option>  
                                            <option value="12">千葉県</option>  
                                            <option value="13">東京都</option>  
                                            <option value="14">神奈川県</option>  
                                            <option value="15">新潟県</option>
                                            <option value="16">富山県</option>  
                                            <option value="17">石川県</option>  
                                            <option value="18">福井県</option>  
                                            <option value="19">山梨県</option>  
                                            <option value="20">長野県</option>
                                            <option value="21">岐阜県</option>  
                                            <option value="22">静岡県</option>  
                                            <option value="23">愛知県</option>  
                                            <option value="24">三重県</option>  
                                            <option value="25">滋賀県</option>
                                            <option value="26">京都府</option>  
                                            <option value="27">大阪府</option>  
                                            <option value="28">兵庫県</option>  
                                            <option value="29">奈良県</option>  
                                            <option value="30">和歌山県</option>
                                            <option value="31">鳥取県</option>  
                                            <option value="32">島根県</option>  
                                            <option value="33">岡山県</option>  
                                            <option value="34">広島県</option>  
                                            <option value="35">山口県</option>
                                            <option value="36">徳島県</option>  
                                            <option value="37">香川県</option>  
                                            <option value="38">愛媛県</option>  
                                            <option value="39">高知県</option>  
                                            <option value="40">福岡県</option>
                                            <option value="41">佐賀県</option>  
                                            <option value="42">長崎県</option>  
                                            <option value="43">熊本県</option>  
                                            <option value="44">大分県</option>  
                                            <option value="45">宮崎県</option>
                                            <option value="46">鹿児島県</option>
                                            <option value="47">沖縄県</option>
                                            <option value="48">未選択</option>
                                            </select>
                                        <a class="btn btn-default btn-edit" role="button">変更する</a>
                                        <a class="btn btn-default btn-edit-stop area" role="button">変更をやめる</a>
                                    </div>
                                </div>
                                <!--           終了-->
                            </div>
                        </div>
                        <!--   性別-->
                        <div class="form-group">
                            <label for="sex" class="col-sm-3 control-label">性別</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <a data-toggle="collapse" href="#collapse-sex" id="display-sex">
                                        <?=h($_SESSION["sex"])?>
                                    </a>
                                </p>
                                <!--           スライド出現部分-->
                                <div class="collapse" id="collapse-sex">
                                    <div class="well">
                                        <select class="form-control input-lg" name="sex" id="sex">
                                            <option value="0">男性</option>
                                            <option value="1">女性</option>
                                            <option value="2">未選択</option>
                                        </select>
                                        <a class="btn btn-default btn-edit" role="button">変更する</a>
                                        <a class="btn btn-default btn-edit-stop sex" role="button">変更をやめる</a>
                                    </div>
                                </div>
                                <!--           終了-->   
                            </div>
                        </div>
                        <!--   自己紹介-->
                        <div class="form-group">
                            <label for="introduction" class="col-sm-3 control-label">自己紹介</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <a data-toggle="collapse" href="#collapse-introduction" id="display-introduction">
                                        <?=nl2br(h($_SESSION["introduction"]))?>
                                    </a>
                                </p>
                                <!--           スライド出現部分-->
                                <div class="collapse" id="collapse-introduction">
                                    <div class="well">
                                        <textarea class="form-control input-lg" rows="10" name="introduction" id="introduction" placeholder="あなたの紹介、体験、取り組みなど自由に記入してください"><?=h($_SESSION["introduction"])?></textarea>
                                        <a class="btn btn-default btn-edit" role="button">変更する</a>
                                        <a class="btn btn-default btn-edit-stop introduction" role="button">変更をやめる</a>
                                    </div>
                                </div>
                                <!--           終了-->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /container -->

    



<!--スマホサイズのメニュー画面展開画面-->
<?php include( '../External/menu-content-small.php'); ?>
<!--メニュー画面展開スクリプト-->
<script type="text/javascript" src="../js/menu.js"></script> 

<!--場所検索フォームスクリプト-->
<script type="text/javascript" src="../js/search-place.js"></script>


<script>
    var area = '<?=h($_SESSION["area_num"])?>';
    var sex = '<?=h($_SESSION["sex_num"])?>';
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
                    console.log(result);
                    var result = JSON.parse(result);
                    console.log(result);
                    console.log("確認");
                    
                    if (id == "area" || id == "sex"){
                        $("#display-"+id).text("");
                        $("#display-"+id).text(result[0]);
                        $("#"+id).val("");
                        $("#"+id).val(result[1]);
                    }else if ( id == "introduction"){
                        $("#display-"+id).empty();
                        $("#display-"+id).append(result);
                        
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
//        stance = $("#stance").val();
//        area = $("#area").val();
//        sex = $("#sex").val();
//    });
    

function h(val){
  return val.replace(/[ !"#$%&'()*+,.\/:;<=>?@\[\\\]^`{|}~]/g, "\\$&");
}
    
</script>  
        
        
        
        



   
 

  
</body>
</html>