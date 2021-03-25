"use strict";
$(document).ready(function() {
	$(".user_type").on('click',function(){
	  if($(this).val()=="Admin") $("#hidden").hide();
	  else $("#hidden").show();
	});
});