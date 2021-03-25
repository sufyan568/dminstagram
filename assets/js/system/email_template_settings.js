"use strict";
$('[data-toggle="popover"]').popover();
$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});
$('document').ready(function(){
$(".settings_menu a").on('click',function(){
	$(".settings_menu a").removeClass("active");
	$(this).addClass("active");
});
});