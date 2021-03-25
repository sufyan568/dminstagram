"use strict";
$(document).ready(function() {	
	if(user_type=="Admin") $("#hidden").hide();
	else $("#validity").show();
	$(".user_type").on('click',function(){
	  if($(this).val()=="Admin") $("#hidden").hide();
	  else $("#hidden").show();
	});
});