$(function(){

$(".navigate_menu").click(function(e){
  $("#primary_wrap").removeClass("pan-left pan-right").addClass("pan-left");
});

$(".navigate_sidebar").click(function(){
  $("#primary_wrap").removeClass("pan-left pan-right").addClass("pan-right");
});

$(".navigate_content").click(function(){
  $("#primary_wrap").removeClass("pan-left pan-right");
});

});