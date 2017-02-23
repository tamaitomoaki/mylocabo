<?php
session_start();
include("../function.php");
//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//セッションの入れ替え


$pdo = db_con();



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



$name = $_SESSION["name"];
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
        .mypage-header {
            color: white;
            background: #444444;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .Candidate{
            font-size:18px;
            cursor:pointer;
            
        }
        #address{
            margin-bottom:10px;
        }
/*        カテゴリー選択モーダル*/
        .item-category{
            font-size:18px;
            color:#757575;
        }
        #display-category{
            font-size:18px;
        }
/*        新規スポット登録のデザイン*/
        .required{
            font-size:18px;
        }
        .necessary{
            color:#ff4b4b;
        }
/*        文章の文字の色*/
        .page-header,
        form h2,
        form label{
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
                <a class="navbar-brand" href="../index.php">マイロカボ</a>
            </div>
        </div>
    </nav>
</div>
    <!-- /container -->
<!--
<div class="mypage-header">
    <div class="container">
        <?=$name?>
    </div>
    
</div>    
-->
<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
            <div class="page-header">
                <h1>新しいスポットを登録する</h1>
            </div>

            <!--メインコンテンツ-->
            <form action="../spot/create_act.php" method="post">
                <h2><strong>まだ知られていないスポットを開拓してみんなで共有しましょう。</strong></h2>
                <hr class="hrsmall">
                <div class="alertbox"></div>
                <div class="form-group">
                    <label class="required">スポットの名前を教えてください。<spna class="necessary">（必須）</spna></label>
                    <input type="text" class="form-control input-lg" id="spotname" name="spotname" placeholder="正確な名称を入力しましょう。">
                </div>
                <label class="required">スポットの場所を教えてください。<spna class="necessary">（必須）</spna></label>
                <div class="area-nondisplay">
                   
                    <div id="spot-address"></div>
                    <p class="text-muted">ピンは動かす事ができます。所在地とピンがズレている場合は正確な位置へ移動しましょう。</p>
                    <div id="option-address"></div>
                    

                    
                </div>
                <div class="form-group" id="form-address">
                    
                    <input type="text" class="form-control input-lg" id="address" name="address" placeholder="正確な住所を入力しましょう。">
                    <button type="button" class="btn btn-default btn-lg btn-block" id="search">マップ作成</button>
<!--
                    <div class="input-group">
                        <input type="text" class="form-control input-lg" id="address" name="address">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-lg" id="search">住所決定</button>
                        </span>
                    </div>
-->
                </div>
                <div class="form-group">
                    <label class="control-label required">カテゴリーを選択してください。<spna class="necessary">（必須）</spna></label>

                        <p class="form-control-static"><a href="" data-toggle="modal" data-target="#select-category" id="display-category">カテゴリーを選ぶ</a></p>
                        <input type="text" class="form-control input-lg hide" id="suggest" name="suggest" placeholder="最適なカテゴリーはなんですか？">
                        <input type="hidden" class="form-control input-lg" id="category" name="category" value="">
                        

                </div>
                <hr class="hrsmall">
                <div class="form-group">
                    <label>URL</label>
                    <input type="url" class="form-control input-lg" id="url" name="url" placeholder="（例）https://">
                </div>
                <div class="form-group">
                    <label>TEL</label>
                    <input type="tel" class="form-control input-lg" id="tel" name="tel" placeholder="電話番号">
                </div>
                <div class="form-group">
                    <label for="open">営業時間</label>
                    <textarea class="form-control" id="open" name="open" placeholder="" rows="5"></textarea>
                </div>
                <input type="hidden" name="lat" id="lat" value="">
                <input type="hidden" name="lng" id="lng" value="">
<!--                <button type="submit" id="registration-hidden" class="btn btn-primary btn-block btn-lg">このスポットを登録する</button>-->
               <div class="alertbox"></div>
                <button type="submit" class="btn btn-primary btn-lg btn-block" id="registration-show">スポット作成</button>
                
                
                
            </form>
        </div>
    </div>
</div>
            <!-- モーダル・ダイアログ -->
<div class="modal fade" id="select-category" tabindex="-1">
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

<!--    グーグルマップapi-->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKO-biURT4s7DR5PeZ8nAeAHureZpS0Gs"></script>
<script type="text/javascript">
    $(".area-nondisplay").hide();
    $("#registration-hidden").hide();
//    $("#spot-address").hide();
//    $("#registration").hide();
//カテゴリーを選択するをクリックして、カテゴリーをモーダルで呼び出す
    $(".item-category").on("click", function(e){
        var category = e.target.innerHTML;
        $("#display-category").empty();//表示部分を空に
        if ( category == "その他" ){
            $("#suggest").removeClass("hide");
            $("#suggest").addClass("show");
            
        }
        $("#display-category").html(category);//表示部分に選択したカテゴリーを表示
        $("#category").val(category);//取得したカテゴリーをvalueに代入
        $('#select-category').modal('hide');
    });
    

    
    
    //住所を入力時にエンターキーを押した場合の処理
    $(document).on("keypress", "#address", function(e) {
        if (e.which == '13') {
            $("#search").trigger("click");
            e.preventDefault();
        }
        
    });    
    //このスポットを登録するを押した時、空欄があれば、submitを取りやめる
    $("#registration-show").on("click", function(e){
        var name = $("#spotname").val();
        var category = $("#category").val();
        var address = $("#address").val();
        if( name == "" || address == "" || category == ""){
            e.preventDefault();
            $(".alertbox").empty();
            $(".alertbox").append(
                '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>スポットの名前、場所、カテゴリーは入力が必要です。</div>'
            );
        }
    });
    //マップ
        var map;
        var geo;
        var latlng; //中心座標
        var lat;
        var lng;
        var address;
        var level; //ズームレベル
        var opts; //マップの表示設定
        var req; //ジオコードする住所
        // Google Mapで利用する初期設定用の変数
        latlng = new google.maps.LatLng(35.665251, 139.712092);
        opts = {
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: latlng,
            disableDefaultUI: true,
        };
        map = new google.maps.Map(document.getElementById("spot-address"), opts);
        // ジオコードリクエストを送信するGeocoderの作成
        geo = new google.maps.Geocoder();



        $('#search').on("click", function(e) {
            e.preventDefault();
            buttonpress();
            function buttonpress() {
                address = document.getElementById("address").value;
                if( address == ""){
                    $(".area-nondisplay").hide();
//                    $("#spot-address").hide();
//                    $("#registration").hide();
                    alert("住所を入力してください");
                    return;
                }else{
                    $(".area-nondisplay").show();
//                    $("#spot-address").show();
//                    $("#registration").show();
                }
                // GeocoderRequest
                req = {
                    address: address,
                };
                //geoResultCallbackより先に$(".none).css・・・が実行される
                geo.geocode(req, geoResultCallback);
                $(".none").css("display", "block");
            }
            //緯度経度取得
            function geoResultCallback(result, status) {
                if (status != google.maps.GeocoderStatus.OK) {
                    alert("正しい住所を入力してください");
                    $(".area-nondisplay").hide();
//                    alert(status);
                    return;
                }else{
                    $(".area-nondisplay").show();
                }
                latlng = result[0].geometry.location;
                console.log(result);
                console.log("ok");
                opts = {
                    zoom: 17,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: latlng,
                    disableDefaultUI: true,
                };
                map = new google.maps.Map(document.getElementById("spot-address"), opts);
                map.setCenter(latlng);
                var marker = new google.maps.Marker({
                    position: latlng,
                    zoom: 10,
                    map: map,
                    title: latlng.toString(),
                    draggable: true,

                });
                //検索した場所をvalueへ挿入
                lat = latlng.lat();
                lng = latlng.lng();
                $("#lat").val(lat);
                $("#lng").val(lng);
                google.maps.event.addListener(marker, 'dragend', function(event) {
                    latlng = marker.getPosition();
                    $("#option-address").empty();
                    geo.geocode({'latLng': latlng}, function(results, status) {
                        console.log(results);
                        $('#option-address').append('<h4>住所候補</h4>');
                        $.each(results, function(i, val){ 
                            console.log(i);
                            console.log(this.formatted_address);
                            
                            var str = this.formatted_address;
                            var pos = str.indexOf(" ", str.indexOf(" ")+1);
                        
                            if (i > 3) {
                                return false;
                            } else {
                                var address = str.substring(pos);
                                $('#option-address').append('<p class="Candidate"><a>' + address + '</a></p>');

                            }
                        });
                    });
                    /* 中心座標を移動する */
                    map.panTo(latlng);
                    //中心をずらし変化した緯度経度をvalueへ挿入
                    lat = latlng.lat();
                    lng = latlng.lng();
                    $("#lat").val(lat);
                    $("#lng").val(lng);
                });
            }
            //地図のズームインボタン
            $("#zoomin").on("click", function() {
                level = map.getZoom();
                level++;
                map.setZoom(level);
            });
            //地図のズームアウトボタン
            $("#zoomout").on("click", function() {
                level = map.getZoom();
                if (level != 0) {
                    level--;
                }
                map.setZoom(level);
            });

        });

//    $("#option-address").on("click", "#ulParent", function(e){
//		console.log(e.target)
//	});
    $(document).on("click", ".Candidate", function(e){
       var address = e.target.childNodes[0].data;
        console.log(address);
        $("#address").empty();
        $("#address").val($.trim(address));
        $("#search").trigger("click");
    });
//    $("#option-address").on("#ulParent", function(e){
//		console.log(e.target)
//	});

</script>
</body>
</html>