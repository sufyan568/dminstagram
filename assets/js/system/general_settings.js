"use strict";
$('document').ready(function(){
	$(".settings_menu a").on('click',function(){
		$(".settings_menu a").removeClass("active");
		$(this).addClass("active");
	});
});