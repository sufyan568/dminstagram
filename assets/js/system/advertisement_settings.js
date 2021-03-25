"use strict";
$(document).ready(function() {
  var selected_pre = $(".custom-switch-input:checked").val();
    if(selected_pre=="0")
    $(".change_status").attr('disabled','disabled');
    else $(".change_status").removeAttr('disabled');

  
  $(".custom-switch-inputs").on('change',function(){
    var selected = $(".custom-switch-input:checked").val();
    if(selected=="0")
    $(".change_status").attr('disabled','disabled');
    else $(".change_status").removeAttr('disabled');
  });
});