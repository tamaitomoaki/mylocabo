
$("#menu-btn").on("click", function(){
    $('#index-main').hide();
//    $('#menu').attr('style', 'display: block !important;');
    
    $("#menu").removeClass('hidden');
    $("#menu").addClass("show");

});
//画面を閉じるicon
$(".menu-close span").on("click", function() {
    $('#index-main').show();
    $("#menu").removeClass("show");
    $("#menu").addClass("hidden");
});