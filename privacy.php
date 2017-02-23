<?php
session_start();
include("./function.php");


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
        .jumbotron {
            background: white;
        }

        .mypage-header {
            background: rgba(35, 35, 35, 0.05);
            /*    background: #444444;*/
        }

        .mypage-header a {
            color: #1e1e1e;
            padding-top: 10px;
            padding-bottom: 6px;
            display: inline-block;
            margin-right: 20px;
        }

        .mypage-header .mypage-active {
            color: #1e1e1e;
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

        .titleA {
            margin-bottom: 40px;
            margin-top: 50px;
            color: #484848;
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
                <a href="./guideline.php">コンテンツガイドライン</a>
                <a href="./privacy.php" class="mypage-active">プライバシーポリシー</a>
                <a href="./terms.php">利用規約</a>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h1 class="titleA">
                        <span><strong>個人情報保護方針</strong></span>
                    </h1>
                    <p>
                        マイロカボは、以下のとおり個人情報保護方針を定め、個人情報保護の仕組みを構築し、全従業員に個人情報保護の重要性の認識と取組みを徹底させることにより、個人情報の保護を推進致します。
                    </p>

                    <h3>個人情報の管理</h3>
                    <p>当社は、お客さまの個人情報を正確かつ最新の状態に保ち、個人情報への不正アクセス・紛失・破損・改ざん・漏洩などを防止するため、セキュリティシステムの維持・管理体制の整備・社員教育の徹底等の必要な措置を講じ、安全対策を実施し個人情報の厳重な管理を行ないます。</p>

                    <h3>個人情報の利用目的</h3>
                    <p>お客さまからお預かりした個人情報は、当社からのご連絡や業務のご案内やご質問に対する回答として、電子メールや資料のご送付に利用いたします。</p>

                    <h3>個人情報の第三者への開示・提供の禁止</h3>
                    <p>当社は、お客さまよりお預かりした個人情報を適切に管理し、次のいずれかに該当する場合を除き、個人情報を第三者に開示いたしません。<br /> 　・お客さまの同意がある場合

                        <br /> 　・お客さまが希望されるサービスを行なうために当社が業務を委託する業者に対して開示する場合
                        <br /> 　・法令に基づき開示することが必要である場合
                        <br />
                    </p>

                    <h3>個人情報の安全対策</h3>
                    <p>当社は、個人情報の正確性及び安全性確保のために、セキュリティに万全の対策を講じています。</p>

                    <h3>ご本人の照会</h3>
                    <p>お客さまがご本人の個人情報の照会・修正・削除などをご希望される場合には、ご本人であることを確認の上、対応させていただきます。</p>

                    <h3>法令、規範の遵守と見直し</h3>
                    <p>当社は、保有する個人情報に関して適用される日本の法令、その他規範を遵守するとともに、本ポリシーの内容を適宜見直し、その改善に努めます。</p>

                    <h3>お問い合せ</h3>
                    <p>
                        当社の個人情報の取扱に関するお問い合せは下記までご連絡ください。<br />
                        <br /> マイロカボ
                        <br /> Mail:info@sarah30.com
                        <br />
                        <!-- .mainview -->
                    </p>
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
</script>   
</body>
</html>