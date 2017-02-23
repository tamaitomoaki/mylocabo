<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え

$spot_id = $_GET["spot_id"];

$pdo = db_con();

$stmt = $pdo->prepare("
SELECT A.spotname, A.address, A.lat, A.lng, A.url, A.tel, A.open, C.category_name
FROM spot_table AS A
LEFT JOIN category_spot_map_table AS B ON A.spot_id = B.spot_id
LEFT JOIN categorys_table AS C ON B.category_id = C.category_id
WHERE A.spot_id = :a1;
");
$stmt->bindValue(':a1', $spot_id,   PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();
if($status==false){
  db_error($stmt);
}

$val = $stmt->fetch();

$spotname = $val["spotname"];
$address = $val["address"];
$lat = $val["lat"];
$lng = $val["lng"];
$category = $val["category_name"];
$url = $val["url"];
$tel = $val["tel"];
$open = $val["open"];

if($url == ""){
    $url = "URLを追加する";
}
if($tel == ""){
    $tel = "電話番号を追加する";
}
if($open == ""){
    $open = "営業時間を入力する";
}

//カテゴリーデータの呼び出し
$stmt = $pdo->prepare("
SELECT category_id, category_name
FROM categorys_table
"
);
$status = $stmt->execute();
if($status==false){
//4,データ登録処理後
    db_error($stmt);
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
        .jumbotron{
            background:white;
        }
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
/*                リンク装飾*/
        a:link{
   text-decoration: none ;
        } 
        a:visited{
 text-decoration: none ;
        } 
        a:hover{
            cursor:pointer;
/*            text-decoration: none ;*/
        } 
        a:active{
text-decoration: none ;
        }
/*        スポット情報の文字サイズ*/
        form{
            font-size:18px;
        }
/*        マップ*/
        .spotmap{
            width:100%;
        }
/*        編集ボタン*/
        .btn-edit,
        .btn-edit-stop{
            margin-top:10px;
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
        <a href="#" class="mypage-active">スポットの情報を編集</a>
<!--        最終更新者の名前を記述する-->
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">

 
               
                


    <form method="post" class="form-horizontal">
       <div class="page-header">
  <h3>編集したい項目をクリックしてください。</h3>
</div>
        <div class="form-group">
		<label class="col-sm-3 control-label">名称</label>
		<div class="col-sm-9">
			<p class="form-control-static"><a data-toggle="collapse" href="#collapse-spotname" id="display-spotname"><?=$spotname?></a></p>
			<!--           スライド出現部分-->
            <div class="collapse" id="collapse-spotname">
                <div class="well">
                    <input type="text" class="form-control input-lg" id="spotname" name="spotname" placeholder="（例）飲食店">
                    <a class="btn btn-default btn-edit" role="button">変更する</a>
                    <a class="btn btn-default btn-edit-stop spotname" role="button">変更をやめる</a>
                </div>
            </div>
            <!--           終了-->
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">住所</label>
		<div class="col-sm-9">
			<p class="form-control-static"><a href=""><?=$address?></a></p>
			<div class="spotheader">
                <a href="http://maps.google.com/maps?q=<?=h($lat)?>,<?=h($lng)?>">
                    <img src="https://maps.googleapis.com/maps/api/staticmap?zoom=20&size=800x400&sensor=false&key=AIzaSyCKO-biURT4s7DR5PeZ8nAeAHureZpS0Gs&markers=size%3Amid%7C<?=h($lat)?>%2C<?=h($lng)?>" alt="" class="spotmap">
                </a>
            </div>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">カテゴリー</label>
		<div class="col-sm-9">
			<p class="form-control-static"><a href="" data-toggle="modal" data-target="#edit-category" id="category"><?=$category?></a></p>
            <!--           スライド出現部分-->
<!--
            <div class="collapse" id="collapse-category">
                <div class="well">
                    <input type="text" class="form-control input-lg" id="category" name="category" placeholder="（例）飲食店">
                    <a class="btn btn-default btn-edit" role="button">変更する</a>
                    <a class="btn btn-default btn-edit-stop category" role="button">変更をやめる</a>
                </div>
            </div>
-->
            <!--           終了-->
		</div>

	</div>
	
	<div class="form-group">
		<label class="col-sm-3 control-label">URL</label>
		<div class="col-sm-9">
			<p class="form-control-static"><a data-toggle="collapse" href="#collapse-url" id="display-url"><?=$url?></a></p>
			<!--           スライド出現部分-->
            <div class="collapse" id="collapse-url">
                <div class="well">
                    <input type="url" class="form-control input-lg" id="url" name="url" placeholder="（例）飲食店">
                    <a class="btn btn-default btn-edit" role="button">変更する</a>
                    <a class="btn btn-default btn-edit-stop url" role="button">変更をやめる</a>
                </div>
            </div>
            <!--           終了-->
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">電話番号</label>
		<div class="col-sm-9">
			<p class="form-control-static"><a data-toggle="collapse" href="#collapse-tel" id="display-tel"><?=$tel?></a></p>
			<!--           スライド出現部分-->
            <div class="collapse" id="collapse-tel">
                <div class="well">
                    <input type="text" class="form-control input-lg" id="tel" name="tel" placeholder="（例）飲食店">
                    <a class="btn btn-default btn-edit" role="button">変更する</a>
                    <a class="btn btn-default btn-edit-stop tel" role="button">変更をやめる</a>
                </div>
            </div>
            <!--           終了-->
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">営業時間</label>
		<div class="col-sm-9">
			<p class="form-control-static"><a data-toggle="collapse" href="#collapse-open" id="display-open"><?=$open?></a></p>
			<!--           スライド出現部分-->
            <div class="collapse" id="collapse-open">
                <div class="well">
                    <input type="text" class="form-control input-lg" id="open" name="open" placeholder="（例）飲食店">
                    <a class="btn btn-default btn-edit" role="button">変更する</a>
                    <a class="btn btn-default btn-edit-stop open" role="button">変更をやめる</a>
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
    
<!-- モーダル・ダイアログ カテゴリー編集-->
<div class="modal fade" id="edit-category" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>×</span></button>
				<h4 class="modal-title">カテゴリーを選択してください。</h4>
			</div>
			<div class="modal-body" id="list">
			
			
			<?php
                while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){

                    
                    echo '<button class="btn btn-default item-category btn-block btn-lg" id="item-category">'.$result["category_name"].'</button>';



                }
                
                
                
            ?>
<!--				<button class="btn btn-default item-category btn-block btn-lg" id="other">その他</button>-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
				<button type="button" class="btn btn-primary">ボタン</button>
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


<script>
//categoryの編集
    var nowcategory;
    var newcategory;
    var id;
    //現在の値を取得
    $("#category").on("click", function(e){
        nowcategory = e.target.innerHTML;
        id = e.target.id;
    });
    //カテゴリーを選択するをクリックして、カテゴリーをモーダルで呼び出す
    $(".item-category").on("click", function(e){
        newcategory = e.target.innerHTML;




//        編集データ登録
            if (window.confirm('本当に変更してもよろしいですか？')) {
                $('#edit-category').modal('hide');
                editcategory(data, id).
                done(function(result) {
                    var result = JSON.parse(result);
                    console.log(result);
                    $("#"+id).text("");
                    $("#"+id).text(result);
                }).
                fail(function(result) {
                    alert("失敗しました。もう１度お願いします。");
                });
            } else {
            }
        //画像削除のajax関数
        function editcategory(data, id) {
            return $.get(
                'edit_act.php?data=' + nowcategory + '&id=' + id + '&spot_id=' + spot_id + '&data2=' + newcategory
            );
        }
//        $("#display-category").empty();//表示部分を空に
//        if ( category == "その他" ){
//            $("#suggest").removeClass("hide");
//            $("#suggest").addClass("show");
//            
//        }
//        $("#display-category").html(category);//表示部分に選択したカテゴリーを表示
//        $("#category").val(category);//取得したカテゴリーをvalueに代入
//        $('#select-category').modal('hide');
    });
    
//スポット情報,url,tel,openの変更の処理
    var spot_id = '<?=$spot_id?>';
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
                    $("#display-"+id).text("");
                    $("#display-"+id).text(result);
                    console.log(result);
                }).
                fail(function(result) {
                    alert("失敗しました。もう１度お願いします。");
                });
            } else {
            }
        //画像削除のajax関数
        function edit(data, id) {
            return $.get(
                'edit_act.php?data=' + data + '&id=' + id + '&spot_id=' + spot_id
            );
        }
        
    });
    $(".btn-edit-stop").click(function(e){
        var type = e.target.classList[3];
        $("#collapse-"+ type).collapse('hide');
        $("#"+type).val("");
    });
    
    
    
    
    
    

var area = '<?=$_SESSION["area_num"]?>';
var sex = '<?=$_SESSION["sex_num"]?>';

    $("#test").on("click", function(){
        console.log(stance);
    });
    $("#stance").val(stance);
    $("#area").val(area);
    $("#sex").val(sex);
    
    
    $("#save").on("click", function(){
        stance = $("#stance").val();
        area = $("#area").val();
        sex = $("#sex").val();
    });
    

    
    
</script>  
        
        
        
        



   
 

  
</body>
</html>