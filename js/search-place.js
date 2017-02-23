var url = window.location;
    url = url.origin;
//検索、入力された検索場所を取得し、渡す
$('.search-map-button').click(function() {
    var area = $(".search-place").val();
    if (area == "") {
        $("#search-map-form").append("<div class='areasearch-error'>探したい場所はどこですか？入力をお願いします。</div>");
    } else {
        window.location.href = url+"/L/search/index.php?area=" + area;
    }
});
//検索、エンターキーを押したら、検索ボタンをclick
$(".search-place").on("keypress", function(e) {
    if (e.which == 13) {          
        $(".search-map-button").trigger("click");
    }
});