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
            <a href="./privacy.php">プライバシーポリシー</a>
            <a href="./terms.php" class="mypage-active">利用規約</a>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                   <h1 class="titleA">
                   <span><strong>利用会員規約</strong></span>
                   </h1>
                  <h3>第１条　（本利用規約について）</h3>
                  <p>SARAH利用会員規約（以下「本利用規約」といいます。）は、株式会社SARAH（以下「当社」といいます。）が運営する飲食店のメニューを中心とした情報に係るインターネット上のコミュニティサイト「SARAH」（以下「本サイト」といいます。）の利用条件を定めるものです。本サービスをご利用される場合には、本利用規約に同意したものとみなされます。</p>
                  <h3>第２条　（定義）</h3>
                  <p>
                    本利用規約において使用する用語の意義は、次の各号に定めるとおりとします。<br />
                    <br />
                    １．「利用者」とは、本サービスを利用する全ての方をいいます。<br />
                    ２．「会員」とは、当社所定の登録手続を行って本サービスを利用する方をいいます。<br />
                    ３．「アカウント」とは、利用者が当社にメールアドレスその他当社所定の情報を申告し、当社所定の登録手続を経て当社が付与するアカウントをいい、当該アカウントが当社のサーバに登録されることを「アカウント登録」といいます。<br />
                    ４．「パスワード」とは、会員が登録手続時に登録し、または登録後に変更手続を行った、会員本人を識別するための文字および数字、記号の列をいいます。<br />
                    ５．「登録情報」とは、プロフィール情報（本サービス上での表示名、アイコン画像、メッセージ等）、メールアドレス、パスワード、氏名、生年月日、性別、職業、銀行口座、クレジットカード番号等、会員が当社に申告して当社がアカウントに登録した情報をいいます。<br />
                    ６．「個人情報」とは、特定の利用者を識別することができる情報（他の情報と容易に照合　　することができ、それにより特定の利用者を識別することができることとなるものを含みます。）をいいます。<br />
                  </p>
                  <h3>第３条　（会員登録、退会等）</h3>
                  <p>
                    １．会員登録は、当社所定の手続きを行なうことにより一人の個人につき一つの登録をすることができます。<br />
                    ２．会員は、当社所定の手続きにより任意で退会することができます。但し、次条に基づき当社が会員より使用の許諾を受け本サイトに投稿した会員自ら創作した文章及び会員自ら撮影した画像（投稿した文章及び画像を総称して「本コンテンツ」といいます。）に係る当社が有する使用権は、当該会員の退会後も有効に存続するものとします。<br />
                    ３．当社は、本条第１項に違反又はその疑いがあると当社が認めた会員登録があった場合、当該会員登録を失効できるものとします。<br />
                    ４．当社は、前項の定めの他、会員が本利用規約に違反又は違反するおそれがあると認めた場合、あらかじめ当該会員にこれを通知することなく、本コンテンツの投稿など当社が会員に提供する機能（以下「会員機能」という）の一部若しくは全部の停止、又は当該会員登録を失効させる場合があります。<br />
                  </p>
                  <h3>第４条　（本コンテンツ）</h3>
                  <p>
                    １．会員は、本サイトに本コンテンツを投稿することができます。<br />
                    ２．会員は、本コンテンツを本サイトに投稿した時点をもって、当該本コンテンツを使用する権利（使用目的の如何を問わず、複製、上演、演奏、上映、公衆送信、公衆伝達、口述、展示、頒布、譲渡、貸与、翻訳、翻案、二次的著作物に関する権利、及び当社が事業目的上必要とみなす範囲で改変する権利を含みます。また、これらの権利を第三者に再許諾する権利を含みます。）を無償かつ無期限に、地域の限定なく、当社に非独占的に許諾すること、並びに、当社又は当社の指定する第三者による本コンテンツの使用に対して本コンテンツに関する著作者人格権を行使しないこと、を予め承諾したものとみなされることに同意します。なお、投稿した本コンテンツに対して有する知的財産権（著作権、意匠権、特許権、実用新案権、商標権、及びその他営業上のノウハウ等を含むものとし、以下同様とします。）及びその他の権利は、投稿後も投稿前と変わることなく当該会員に帰属します。<br />
                    ３．当社は、会員が投稿した本コンテンツが法令若しくは本利用規約に違反した又は違反すると当社が認めた場合、又は、当社の本サイト運用上不適切であると当社が判断した場合、あらかじめ当該会員に通知することなく、本サイトから当該本コンテンツの一部若しくは全部を削除できるものとし、会員は、予めこれを承諾するものとします。<br />
                    ４．当社は、会員が退会した場合も、当該会員の本コンテンツを当社の判断において継続して使用し、又は適宜その一部若しくは全部を本サイトから削除できるものとし、会員は、予めこれを承諾するものとします。<br />
                  </p>
                  <h3>第５条　（メールアドレスおよびパスワードの管理）</h3>
                  <p>
                    １．会員は、メールアドレスおよびパスワードについて、自己の責任の下で管理を行うものとし、登録情報が不正確または虚偽であったために会員が被った一切の不利益および損害に関し、当社は一切の責任を負わないものとします。<br />
                    ２．当社は、ログイン時に入力されたメールアドレスおよびパスワードが、登録されたメールアドレスおよびパスワードと一致することを所定の方法により確認した場合、当該ログインを真正な会員のログインとみなし、会員による利用とみなします。<br />
                  </p>
                  <h3>第６条　（個人情報の取扱い等）</h3>
                  <p>
                    １．当社は、当社が取得した個人情報に関し、別途定める「Privacy Policy（個人情報保護方針）」に基づき、適切に取り扱うものとします。<br />
                    ２．利用者は、本サービスを利用するに際し、「Privacy Policy（個人情報保護方針）」で定める事項に加え、本サービスの利用者同士がお互いを認識できるよう、ニックネームやアイコン写真を本サイト上に表示することに同意するものとします。<br />
                  </p>
                  <h3>第７条　（登録情報の変更等）</h3>
                  <p>
                    １．会員は、登録情報に変更があった場合、すみやかに当社の定める手続により当社に申告するものとします。この申告がない場合、当社は登録情報の変更がないものとして取り扱います。<br />
                    ２．会員からの登録情報の変更の申告がないために、当社からの通知、その他が遅延し、または不着、不履行であった場合、当社はその責任を負わないものとします。<br />
                  </p>
                  <h3>第８条　（譲渡禁止等）</h3>
                  <p>
                    １．会員は、本サービスの提供を受ける権利を第三者に譲渡したり、売買、名義変更、質権の設定その他の担保に供したりする等の行為ができないものとします。<br />
                    ２．アカウント登録は一身専属のものとします。当社は、利用者の死亡を知り得た時点をもって退会の手続がなされたものとみなします。<br />
                  </p>
                  <h3>第９条　（連絡または通知）</h3>
                  <p>
                    １．会員への連絡または通知の必要があると当社が判断した場合には、登録されたメールアドレス宛にメールにて連絡または通知を行います。<br />
                    ２．利用者は、本利用規約に別段の定めがある場合を除き、当社への連絡はお問い合わせフォームから行うものとします。当社は電話による連絡および来訪は受け付けておりません。<br />
                  </p>
                  <h3>第１０条　（本サービスの提供の中断等）</h3>
                  <p>
                    １．当社は、以下のいずれかの事由が生じた場合には、利用者に事前に通知することなく、一時的に本サービスの全部または一部の提供を中断することがあります。<br />
                    (1) 本サービスを提供するための通信設備等の定期的な保守点検を行う場合または点検を緊急に行う場合<br />
                    (2) 火災、停電等により本サービスの提供ができなくなった場合<br />
                    (3) 地震、噴火、洪水、津波等の天災により本サービスの提供ができなくなった場合<br />
                    (4) 戦争、動乱、暴動、騒乱、労働争議等により本サービスの提供ができなくなった場合<br />
                    (5) その他、運用上または技術上、当社が本サービスの提供の一時的な中断を必要と判断した場合<br />
                    ２．当社が必要と判断した場合には、事前に通知することなくいつでも本サービスの内容を変更し、または本サービスの提供を停止もしくは中止することができるものとします。<br />
                    ３．当社は、第1項各号のいずれかまたはその他の事由により本サービスの全部または一部の提供に遅延もしくは中断が発生しても、これに起因する利用者または第三者が被った損害に関し、本利用規約で特に定める場合を除き、一切の責任を負いません。<br />
                    ４．当社が本サービスの内容を変更し、または本サービスの提供を停止もしくは中止した場合であっても、本利用規約で特に定める場合を除き、利用者に対して一切責任を負わないものとします。<br />
                  </p>
                  <h3>第１１条　（利用環境の整備）</h3>
                  <p>
                    １．利用者は、本サービスを利用するために必要な通信機器、ソフトウェアその他これらに付随して必要となる全ての機器を、自己の費用と責任において準備し、利用可能な状態に置くものとします。また、本サービスのご利用にあたっては、自己の費用と責任において、利用者が任意に選択し、電気通信サービスまたは電気通信回線を経由してインターネットに接続するものとします。<br />
                    ２．利用者は、関係官庁等が提供する情報を参考にして、自己の利用環境に応じ、コンピュータ・ウィルスの感染、不正アクセスおよび情報漏洩の防止等セキュリティを保持するものとします。<br />
                    ３．当社は、利用者の利用環境について一切関与せず、また一切の責任を負いません。<br />
                  </p>
                  <h3>第１２条　（自己責任の原則）</h3>
                  <p>
                    １．利用者は、利用者自身の自己責任において本サービスを利用するものとし、本サービスを利用してなされた一切の行為およびその結果についてその責任を負うものとします。<br />
                    ２．利用者は、本サービスのご利用に際し、他の利用者その他の第三者および当社に損害または不利益を与えた場合、自己の責任と費用においてこれを解決するものとします。<br />
                  </p>
                  <h3>第１３条　（禁止事項）</h3>
                  <p>
                    次の各号を当社が定めた禁止事項とします。<br />
                    １．当社、他の利用者、若しくは第三者の知的財産権、及びその他の権利を侵害する、又は侵害するおそれのある投稿<br />
                    ２．第三者の財産、プライバシー、又は肖像権を侵害する、又は侵害する恐れのある投稿<br />
                    ３．第三者を差別、若しくは誹謗中傷し、又はその名誉若しくは信用を毀損する投稿<br />
                    ４．詐欺、業務妨害等の犯罪行為、又はこれを誘発、若しくは煽動する投稿<br />
                    ５．上記各号の他、法令、又は本利用規約に違反する行為<br />
                    ６．その他、公序良俗に違反し、又は第三者の権利を著しく侵害すると当社が判断した投稿<br />
                    ７．通常に本サービスを利用する行為を超えてサーバに負荷をかける行為もしくはそれを助長するような行為、その他本サービスの運営・提供もしくは他の利用者による本サービスの利用を妨害し、またはそれらに支障をきたす行為<br />
                    ８．サーバ等のアクセス制御機能を解除または回避するための情報、機器、ソフトウェア等を流通させる行為<br />
                    ９．本サービスによって提供される機能を複製、修正、転載、改変、変更、リバースエンジニアリング、逆アセンブル、逆エンパイル、翻訳あるいは解析する行為<br />
                    １０．本サービスの運営を妨害する行為、他者が主導する情報の交換または共有を妨害する行為、信用の毀損または財産権の侵害等の当社または他者に不利益を与える行為<br />
                    １１．同様の問い合わせの繰り返しを過度に行い、または義務や理由のないことを強要し、当社の業務に著しく支障を来たす行為<br />
                    １２．本サービスについて、その全部あるいは一部を問わず、営業活動その他の営利を目的とした行為又はそれに準ずる行為やそのための準備行為を目的として、利用又はアクセスしてはならないものとします。また、その他、宗教活動、政治活動などの目的での利用又はアクセスも行ってはならないものとします。投稿した本人による投稿内容の利用等本利用規約が特に認めた場合を除き、本サービスに掲載されている情報を利用して利益を得た場合には、当社はその利益相当額の金員を請求できる権利を有するものとします。<br />
                    １３．利用者が以下のいずれかの者に該当する場合には、当該利用者は、本サービスを利用することができないものとします。<br />
                    (1) 暴力団<br />
                    (2) 暴力団員<br />
                    (3) 暴力団準構成員<br />
                    (4) 暴力団関係企業<br />
                    (5) 総会屋等、社会運動等標榜ゴロまたは特殊知能暴力集団等<br />
                    (6) その他前各号に準じる者<br />
                  </p>
                  <h3>第１４条　（免責）</h3>
                  <p>
                    １．当社は、本サービスの利用により発生した利用者の損害については、一切の賠償責任を負いません。<br />
                    ２．利用者が、本サービスを利用することにより、第三者に対し損害を与えた場合、利用者は自己の費用と責任においてこれを賠償するものとします。<br />
                    ３．当社は本サービスに発生した不具合、エラー、障害等により本サービスが利用できないことによって引き起こされた損害について一切の賠償責任を負いません 。<br />
                    ４．本サービスならびに本サイト上の発信内容および情報は、当社がその時点で提供可能なものとします。当社は提供する情報、会員が登録する文章およびソフトウェア等について、その完全性、正確性、適用性、有用性等いかなる保証も一切しません。<br />
                    ５．当社は、利用者に対して、適宜情報提供やアドバイスを行うことがありますが、その結果について責任を負わないものとします。<br />
                    本サービスが何らかの外的要因により、データ破損等をした場合、当社はその責任を負いません。<br />
                    ６．利用者との間の本利用規約に基づく契約が消費者契約法（平成12年法律第61号）第2条第3項の消費者契約に該当する場合には、本利用規約のうち、当社の責任を完全に免責する規定は適用されないものとします。本利用規約に基づく契約が消費者契約に該当し、かつ、当社が債務不履行または不法行為に基づき損害賠償責任を負う場合については、当社に故意または重過失がある場合を除いて、当社は、当該利用者が直接かつ現実に被った損害を上限として損害賠償責任を負うものとし、特別な事情から生じた損害等（損害発生につき予見し、または予見し得た場合を含みます。）については責任を負わないものとします。<br />
                  </p>
                  <h3>第１５条　（準拠法）</h3>
                  <p>本利用規約は、日本法に準拠し、解釈されるものとします。</p>
                  <h3>第１６条　（裁判管轄）</h3>
                  <p>利用者と当社との間で訴訟の必要が生じた場合、東京地方裁判所を第一審の専属的合意管轄裁判所とします。</p>  
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
    var stance = '<?=$_SESSION["stance_num"]?>';
    var area = '<?=$_SESSION["area_num"]?>';
    var sex = '<?=$_SESSION["sex_num"]?>';
    $("#stance").val(stance);
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
                    
                    if (id == "stance" || id == "area" || id == "sex"){
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