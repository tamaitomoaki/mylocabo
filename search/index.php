<?php
session_start();
include("../function.php");

//入力チェック(受信確認処理追加)
if(
  !isset($_GET["area"]) || $_GET["area"]=="" 
){
    header("location: ../index.php");
    exit();
}

$area = json_encode($_GET["area"]);

$pagetype =  $_SERVER["REQUEST_URI"];
$pagetype = strstr($pagetype,'?',true); 
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
        <link href="../css/alert-login.css" rel="stylesheet">
        <!-- jQuery読み込み -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- BootstrapのJS読み込み -->
        <script src="../js/bootstrap.js"></script>
    <style>
        /*        このページの地図の表示のため*/

        body {
            padding-bottom: 0px;
        }
        /*        */

        .jumbotron {
            background: white;
        }

        .search-box {
            margin: 20px 10px 0px;
        }

        .display-list {
            overflow: scroll;
        }

        #display-map,
        .display-map {
            position: relative;
            padding-left: 0px;
            padding-right: 0px;
        }

        #display-map {
            margin-left: 400px;
        }

        #display-map button {
            position: absolute;
            left: 55px;
            top: 20px;
            z-index: 10;
        }

        .display-map button {
            position: absolute;
            left: 1em;
            top: 1em;
            z-index: 10;
        }
        /*        リンク装飾*/

        a:link {}

        a:visited {}

        a:hover {
            text-decoration: none;
        }

        a:active {}
        /*        ホバーした際のアクション*/

        .tach:hover {
            background-color: #f5f5f5;
            text-decoration: none;
        }
        /*レビュー星のcss*/

        .list-rating span {
            color: #d2d2d2;
        }

        .list-rating .color {
            color: #EB6E00;
        }
        /*        リストcss*/

        .display-area {
            width: 400px;
            float: left;
        }

        .display-area .list-media {
            padding: 10px;
        }
        /*        リストの投稿画像サイズ指定*/

        .list-img {
            width: 25%;
        }

        .list-img img {
            width: 100%;
        }
        /*        リストプロフィール画像*/

        .list-profileimg {
            width: 30px;
            display: inline-block;
        }

        .list-spotname {
            display: inline-block;
        }

        .list-spotname h4 {
            color: #484848;
        }

        .list-content {
            font-size: 12px;
            color: #484848;
        }

        .list-rating {
            padding-top: 10px;
        }

        .point {
            color: gray;
            font-size: 10px;
        }

        .media-header {
            margin-bottom: 5px;
        }
        /*        名前のボジション*/

        .position-name {
            vertical-align: bottom;
        }
        /*            マップ表示画面のスライダー*/

        .map-detail {
            width: 100%;
            height: 120px;
        }

        .slider {
            width: 100%;
            height: 100%;
            overflow: hidden;
            position: relative;
            margin: 0 auto;
        }

        .slider .slideSet {
            height: 100%;
            position: absolute;
        }

        .slider .slide {
            width: 90vw;
            height: 100%;
            float: left;
            background: white;
            margin-top: 0px;
            padding: 10px;
        }

        .slider img {
            width: 20vw;
        }

        .slide .media-left {
            padding-right: 3.5vw;
        }

        .pager {
            margin: 2vh 0 0;
        }
        /*            リスト、マップ切り替えボタン*/

        #switching {
            margin-right: 8px;
        }

        #switching button {
            color: gray;
        }
        /*        データがない時表示*/

        .nodata {
            width: 90%;
            color: #484848;
            margin-top: 50px;
        }

        .nodata span {
            font-size: 30px;
        }

        .nodata-title {
            margin-top: 15px;
            margin-bottom: 15px;
            font-size: 32px;
        }

        .nodata-content {
            font-size: 18px;
            color: gray;
        }

        .nodata .nodata-example {
            font-size: 18px;
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
                        <div id="switching" class="pull-right">
                            <button type="button" class="btn btn-default navbar-btn visible-xs-inline" id="mapbutton">リスト</button>
                        </div>
                    </div>
                    <!--PCデバイス時のメニューボタン-->
                    <?php include( '../External/menu-btn-large.php'); ?>
                </div>
            </nav>

            <!-- Main jumbotron for a primary marketing message or call to action -->
        </div>
        <!-- /container -->

        <div class="container-fluid">
            <div class="row">
                <div class="display-area">
                    <div class="display-area-base hidden-xs">
                       <div class="search-box">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" id="address" placeholder="場所を入力">
                                <span class="input-group-btn">
                            <button type="button" class="search-map-button btn btn-default">検索</button>
                        </span>
                            </div>
                        </div>
                        <hr class="hrsmall">
                        <div class="display-list show"></div>

                    </div>
                <!--    スマホサイズのページ構造-->
                    <div class="display-xs visible-xs-block">
                       <div class="display-list hidden"></div>
                       <div class="display-map show">
                           <div id="map-small" class="display-map"></div>
                           <button id="" class="re-search-btn btn btn-default re-search">再検索をする</button>
                       </div>
                       <!--    クリックされた店舗のリスト挿入位置-->
                        <div class="map-detail show">
                            <div class="slider">
                                <div class="slideSet">
                                </div>
                            </div>
                            <nav>
                                <ul class="pager">
                                    <li class="previous slider-prev"><a href="#">前へ</a></li>
                                    <li class="next slider-next"><a href="#">次へ</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
    <!--                マップ表示-->
                <div id="display-map" class="hidden-xs">
                   <div id="map-big" class="map"></div>
                   <button id="" class="re-search-btn btn btn-default re-search">再検索をする</button>

                </div>
            </div>
        </div>
        
<!-- ログイン モーダル -->
<?php include ('../External/modal-login.php'); ?>
<!--メールアドレスチェックスクリプト-->
<script type="text/javascript" src="../js/validation-login.js"></script>
        
        

<!--    グーグルマップapi-->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKO-biURT4s7DR5PeZ8nAeAHureZpS0Gs"></script>
<script>
    //マップ、ジオコーディング用コード
    var map;
    var geo;
    var latlng; //中心座標
    var set_latlng; //中心移動する際のマーカーの緯度経度
    var address;
    var level; //ズームレベル
    var opts; //マップの表示設定
    var req; //ジオコードする住所

    var marker_ary = []; //DBから呼び出した店舗データを入れる配列変数
    var slideCurrent = 0; // スライドの現在地を示す変数
    var slideWidth; // .slideの幅を取得して代入
    var slideNum; // .slideの数を取得して代入
    var slideSetWidth; // .slideの幅×数で求めた値を代入
    
    var position = "";//マップの拡大縮小のコントローラーのポジション


    // ジオコードリクエストを送信するGeocoderの作成
    geo = new google.maps.Geocoder();
    $(".re-search").hide();
    MarkerClear();
    ListCrear();
    buttonpress();
    $('.re-search-btn').trigger('click');


    $('.search-map-button').on("click", function(e) {
        e.preventDefault();
        MarkerClear();
        ListCrear();
        buttonpress();
        $('.re-search-btn').trigger('click'); //機能があれば、なにか処理をしたきっかけで、そちらを作動させることができる。
    });

    function buttonpress() {
        address = document.getElementById("address").value;
        if (address == "") {
            address = <?=$area?>;
        }
        console.log(address);
        // GeocoderRequest
        req = {
            address: address,
        };
        geo.geocode(req, geoResultCallback);
    }
    //緯度経度取得
    function geoResultCallback(result, status) {
        if (status != google.maps.GeocoderStatus.OK) {
            alert("検索された場所が見つかりませんでした。");
            return;
        }
        latlng = result[0].geometry.location;
        //マップ拡大縮小のコントローラーボジション
        if ( $("#map-big").is(":visible")){
            position = google.maps.ControlPosition.TOP_LEFT;
        }else{
            position = google.maps.ControlPosition.UNDER_RIGHT;
        }
        opts = {
            zoom: 17,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: latlng,
            mapTypeControl: false,//falseでマップ名及び航空写真（マップタイプ）の非表示
            streetViewControl: false,//falseで非表示
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: position
            },
//            disableDefaultUI: true,
        };
        if ( $("#map-big").is(":visible")){
            map = new google.maps.Map(document.getElementById("map-big"), opts);
            position = google.maps.ControlPosition.TOP_LEFT;
        }else{
            map = new google.maps.Map(document.getElementById("map-small"), opts);
            position = google.maps.ControlPosition.TOP_RIGHT;
        }

        //getBoundsは、地図の描画が終わってからでないと正確な値がとれないので、projection_changedイベントに登録する。  

        google.maps.event.addListener(map, 'projection_changed', function() {
            setPointMarker();
        });
        google.maps.event.addListener(map, 'center_changed', function() {
            $(".re-search").show();
            getcenter();
        });
    }
    //センター取得関数
    function getcenter(){
        opts = {
            zoom: 17,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: map.getCenter(),
            mapTypeControl: false,//falseでマップ名及び航空写真（マップタイプ）の非表示
            streetViewControl: false,//falseで非表示
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: position,
            },
        };
    }
    //データを呼び出し登録されてる地点情報をマーカーで表示
    function setPointMarker() {
        //地図の範囲内を取得
        var bounds = map.getBounds();
        var map_ne_lat = bounds.getNorthEast().lat();
        var map_sw_lat = bounds.getSouthWest().lat();
        var map_ne_lng = bounds.getNorthEast().lng();
        var map_sw_lng = bounds.getSouthWest().lng();
        $.ajax({
            url: "../json/place.php?ne_lat=" + map_ne_lat + "&sw_lat=" + map_sw_lat + "&ne_lng=" + map_ne_lng + "&sw_lng=" + map_sw_lng,
            type: 'GET',
            dataType: 'json',
            timeout: 1000,
            error: function(data) {
                console.log("情報の読み込みに失敗しました");
                console.log(data);
            },
            success: function(data) {
                console.log("情報の読み込み成功");
                console.dir(data);
                marker_ary.length = 0;
                $(".display-list").empty();
                
                if ( data == "" ){
                    console.log("から");
                    $(".display-list").append(
                                "<div class='nodata-box'><div class='nodata center-block'><p class='nodata-title'>スポットを見つけることができませんでした。</p><p class='nodata-content'>このエリアはまだ低糖質な開拓がされていない場所です。違う場所を検索するか、あなたの知っているスポットを登録して低糖質な世界を築きましょう！違う場所を検索するか、あなたの探しているスポットを登録しましょう。</p></div></div>"
                            );
                }
                
                //マップの端末サイズの変化による表示切り替え
                $.each(data, function(i) {
                    console.log(i);
                    console.log(this);
                    if(this.count == null){
                        this.count = 0;
                    }
                    
                 
                    //mapにマーカーを表示
                    var addlat = this.lat;
                    var addlng = this.lng;
                    var marker_position = new google.maps.LatLng(addlat, addlng);
                    var markerOpts = {
                        map: map,
                        position: marker_position
                    }
                    var marker_num = marker_ary.length; //markerを複数作成する際のマーカー番号、配列番号
                    marker_ary[marker_num] = new google.maps.Marker(markerOpts);

                    //list表示
                    if ( this.image_name == null){
                        switch (this.spot_point) {
                                case "0":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                break;
                                case "1":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                
                                    break;
                                case "2":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                    break;
                                case "3":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                    break;
                                case "4":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                    break;
                                case "5":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                    break;
                                default:
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                            }
                        }else{
                            switch (this.spot_point) {
                                    case "0":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "1":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                
                                    break;
                                case "2":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "3":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "4":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "5":
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                default:
                                    $(".slideSet").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media slide'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                            }
                        }
                    //一回りごとにmarkerイベントを設定、markerがクリックされた場合
                    google.maps.event.addListener(marker_ary[marker_num], 'click',
                        function() {
                            // そのマーカーの地点を拡大表示
                            set_latlng = marker_ary[marker_num].getPosition();
                            map.panTo(set_latlng);
                            //マーカーの配列番号をslideCurrentにいれる、何個目の店舗が取得し、その数×幅で表示を移動する
                            slideCurrent = marker_num;
                            $('.slideSet').stop().animate({
                                left: slideCurrent * -slideWidth
                            });
                        });
                    //list表示
                    if ( this.image_name == null){
                        switch (this.spot_point) {
                                case "0":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                break;
                                case "1":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div></div></a>"
                            );
                            }
                        }else{
                            switch (this.spot_point) {
                                    case "0":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "1":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                
                                    break;
                                case "2":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "3":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "4":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                case "5":
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star color' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                                    break;
                                default:
                                    $(".display-list").append(
                                "<a href='../spot/index.php?spot_id="+$.htmlspecialchars(this.spot_id)+"'><div class='media tach list-media'><div class='list-spotname'><h4>" + $.htmlspecialchars(this.spotname) + "</h4></div><div class='list-rating pull-right'><span class='glyphicon glyphicon-star color' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span><span class='glyphicon glyphicon-star' aria-hidden='true'></span></div><div class='media-body'><div><p class='list-content'>" + $.htmlspecialchars(this.address) + "</p><p>レビュー"+this.count+"件</p></div></div><span class='media-right list-img' href='#'><img src='../upload/s/"+this.image_name+"' alt='...'></span></div></a>"
                            );
                            }
                        }
                });
                //スライダー数値取得
                slideWidth = $('.slider').outerWidth(); // .slideの幅を取得して代入
                $('.slide').css('width', slideWidth);
                slideNum = $('.slide').length; // .slideの数を取得して代入
                slideSetWidth = slideWidth * slideNum; // .slideの幅×数で求めた値を代入
                $('.slideSet').css('width', slideSetWidth); // .slideSetのスタイルシートにwidth: slideSetWidthを指定
            }
        });
    }
    //リセット関数
    //Map
    function MarkerClear() {
        //表示中のマーカーがあれば削除
        if (marker_ary.length > 0) {
            //マーカー削除
            for (i = 0; i < marker_ary.length; i++) {
                marker_ary[i].setMap(null);
            }
            //配列削除
            for (i = 0; i <= marker_ary.length; i++) {
                marker_ary.shift();
            }
        }
    }
    //リスト
    function ListCrear() {
        $(".slideSet").html("");
        $(".display-list").html("");
    }
    //search.phpのリスト、マップ画面切り替え
    $("#change1").on("click", function() {
        $(".inline-block").css("display", "none");
        $(".none").css("display", "inline-block");
        $("#change1").attr("id", "change2");
    });
    $("#change2").on("click", function() {
        $(".inline-block").css("display", "inline-block");
        $(".none").css("display", "none");
        $("#change2").attr("id", "change1");
    });

    // アニメーションを実行する独自関数横、要素の個数×横幅、この横幅をslideCurrentの数値によって移動する
    var sliding = function() {
            // slideCurrentが0以下だったら
            if (slideCurrent < 0) {
                slideCurrent = slideNum - 1;
                // slideCurrentがslideNumを超えたら
            } else if (slideCurrent > slideNum - 1) { // slideCUrrent >= slideNumでも可
                slideCurrent = 0;
            }

            $('.slideSet').stop().animate({
                left: slideCurrent * -slideWidth
            });
        }
        // 前へボタンが押されたとき
    $(document).on("click", '.slider-prev', function() {
        slideCurrent--;
        sliding();
        // そのマーカーの地点を拡大表示
        set_latlng = marker_ary[slideCurrent].getPosition();
        map.panTo(set_latlng);
    });
    // 次へボタンが押されたとき
    $(document).on("click", '.slider-next', function() {
        slideCurrent++;
        sliding();
        // そのマーカーの地点を拡大表示
        set_latlng = marker_ary[slideCurrent].getPosition();
        map.panTo(set_latlng);
    });
    // この周辺を検索を押した時
    $(".re-search-btn").on("click", function() {
        MarkerClear();
        ListCrear();
        setPointMarker();
    });
    //現在位置取得
    $(".Current-position-search").on("click", function() {
        MarkerClear();
        ListCrear();
        Current();


    });


    //現在地表示
    function Current() {
        // ユーザーの端末がGeoLocation APIに対応しているかの判定

        // 対応している場合
        if (navigator.geolocation) {
            // 現在地を取得
            navigator.geolocation.getCurrentPosition(

                // [第1引数] 取得に成功した場合の関数
                function(position) {
                    // 取得したデータの整理
                    var data = position.coords;

                    // データの整理
                    var lat = data.latitude;
                    var lng = data.longitude;


                    // 位置情報
                    latlng = new google.maps.LatLng(lat, lng);

                    // Google Mapsに書き出し
                    map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 17,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        center: latlng,
                        zoomControl: true,
                        zoomControlOptions: {
                            position: google.maps.ControlPosition.RIGHT_CENTER
                        },
                        disableDefaultUI: true,
                    });
                    google.maps.event.addListener(map, 'projection_changed', function() {
                        $('.re-search-btn').trigger('click'); //機能があれば、なにか処理をしたきっかけで、そちらを作動させることができる。
                    });
                },

                // [第2引数] 取得に失敗した場合の関数
                function(error) {
                    // エラーコード(error.code)の番号
                    // 0:UNKNOWN_ERROR				原因不明のエラー
                    // 1:PERMISSION_DENIED			利用者が位置情報の取得を許可しなかった
                    // 2:POSITION_UNAVAILABLE		電波状況などで位置情報が取得できなかった
                    // 3:TIMEOUT					位置情報の取得に時間がかかり過ぎた…

                    // エラー番号に対応したメッセージ
                    var errorInfo = [
                        "原因不明のエラーが発生しました…。",
                        "位置情報の取得が許可されませんでした…。",
                        "電波状況などで位置情報が取得できませんでした…。",
                        "位置情報の取得に時間がかかり過ぎてタイムアウトしました…。"
                    ];
                    // エラー番号
                    var errorNo = error.code;
                    // エラーメッセージ
                    var errorMessage = "[エラー番号: " + errorNo + "]\n" + errorInfo[errorNo];
                    // アラート表示
                    alert(errorMessage);
                    // HTMLに書き出し
                    document.getElementById("result").innerHTML = errorMessage;
                },
                // [第3引数] オプション
                {
                    "enableHighAccuracy": false,
                    "timeout": 8000,
                    "maximumAge": 2000,
                }
            );
        }
        // 対応していない場合
        else {
            // エラーメッセージ
            var errorMessage = "お使いの端末は、GeoLacation APIに対応していません。";
            // アラート表示
            alert(errorMessage);
            // HTMLに書き出し
            document.getElementById('result').innerHTML = errorMessage;
        }

    }


    
    
    
    
    
    
//スマートフォン表示の際のマップ、リスト切り替え
    $("#switching").on("click", "#mapbutton", function(){
       $("#switching").empty(); 
       $("#switching").append(
           '<button type="button" class="btn btn-default navbar-btn visible-xs-inline" id="listbutton">マップ</button>'
       ); 
        $(".display-list").removeClass("hidden").addClass("show");
        $(".display-map").removeClass("show").addClass("hidden");
        $(".map-detail").removeClass("show").addClass("hidden");
        
    });
    $("#switching").on("click", "#listbutton", function(){
       $("#switching").empty(); 
       $("#switching").append(
           '<button type="button" class="btn btn-default navbar-btn visible-xs-inline" id="mapbutton">リスト</button>'
       ); 
        $(".display-map").removeClass("hidden").addClass("show");
        $(".display-list").removeClass("show").addClass("hidden");
        $(".map-detail").removeClass("hidden").addClass("show");
    });

//ページの要素のサイズ取得
    $(document).ready(function() {
        var hsize = $(window).height();
        var wsize = $(window).width();

        var H = hsize - 50;
        var W = wsize - 400;

        if ( $("#map-big").is(":visible")){
            $("#map-big").css("height", H + "px");
            $("#map-big").css("width", W + "px");
            var h = H - 107;
            $(".display-list").css("height", h+"px");
        }else{
            //スマホデバイスの場合のリストwidth,heightを設定
            $(".display-area").css("width", "100%");
            $(".display-xs").css("height", "50vh");
            $(".display-map").css("height", "50vh");
        }
        
    });
    $(window).resize(function() {
        var hsize = $(window).height();
        var wsize = $(window).width();

        var H = hsize - 50;
        var W = wsize - 400;

        if ( $("#map-big").is(":visible")){
            $("#map-big").css("height", H + "px");
            $("#map-big").css("width", W + "px");
            var h = H - 107;
            $(".display-list").css("height", h+"px");
        }else{
            //スマホデバイスの場合のリストwidth,heightを設定
            $(".display-area").css("width", "100%");
            $(".display-xs").css("height", "50vh");
            $(".display-map").css("height", "50vh");
        }
    });
//検索入力しエンター押して処理実行
    //検索、エンターキーを押したら、検索ボタンをclick
    $("#address").on("keypress", function(e) {
        if (e.which == 13) {          
            $(".search-map-button").trigger("click");
        }
    });
</script>

    </body>

    </html>
