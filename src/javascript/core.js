$(function(){

$("#show_navigation").click(function(e){
  if($("#primary_wrap").hasClass("panned"))
    $("#primary_wrap").removeClass();
  else
    $("#primary_wrap").removeClass().addClass("pan-left panned");
});

$("#show_sidebar").click(function(){
  if($("#primary_wrap").hasClass("panned"))
    $("#primary_wrap").removeClass();
  else
    $("#primary_wrap").removeClass().addClass("pan-right panned");
});

});