"use strict";
$(document).ready(function($) {
  $('div.note-group-select-from-files').remove();

  $(document).on('click', '.send_test_mail', function(event) {
    event.preventDefault();
    $("#modal_send_test_email").modal();
  });

  $(document).on('click', '#send_test_email', function(event) {
    event.preventDefault();

    var email=$("#recipient_email").val();
    var subject=$("#subject").val();
    var message=$("#message").val(); 

    if(email=='') {
      $("#recipient_email").addClass('is-invalid');
      return false;
    }
    else {
      $("#recipient_email").removeClass('is-invalid');
    }

    if(subject=='') {
      $("#subject").addClass('is-invalid');
      return false;
    }
    else {
      $("#subject").removeClass('is-invalid');
    }

    if(message=='') {
      $("#message").addClass('is-invalid');
      return false;
    }
    else {
      $("#message").removeClass('is-invalid');
    }

    $(this).addClass('btn-progress');
    $("#show_message").html('');
    $.ajax({
      context: this,
      type:'POST' ,
      url: base_url+"admin/send_test_email",
      data:{email:email,message:message,subject:subject},
      success:function(response){

        $(this).removeClass('btn-progress');      
        $("#show_message").addClass("alert alert-light");

        if(response == 1) {

          $("#show_message").html(smtp_settings_lang_test_mail_sent);

        } else {

          $("#show_message").html(response);

        }
      }
    });

  });
});
