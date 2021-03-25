  "use strict";
  $(document).ready(function() {

    $('div.note-group-select-from-files').remove();

    setTimeout(function(){ 
      $("#mytable_filter").append(drop_menu); 
    }, 2000);
    
    var perscroll;
    var table = $("#mytable").DataTable({
        serverSide: true,
        processing:true,
        bFilter: true,
        order: [[ 2, "desc" ]],
        pageLength: 10,
        ajax: {
            "url": base_url+'admin/user_manager_data',
            "type": 'POST'
        },
        language: 
        {
          url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        columnDefs: [
          {
              targets: [2,8],
              visible: false
          },
          {
              targets: [0,1,3,7,9,10,11,13],
              className: 'text-center'
          },
          {
              targets: [0,1,3,10],
              sortable: false
          }
        ],
        fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
            if(areWeUsingScroll)
            {
              if (perscroll) perscroll.destroy();
              perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
            }
        },
        scrollX: 'auto',
        fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
            if(areWeUsingScroll)
            {
              if (perscroll) perscroll.destroy();
              perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
            }
        }
    });

    $(document).on('click', '.change_password', function(e) {
      e.preventDefault();

      var user_id = $(this).attr('data-id');
      var user_name = $(this).attr('data-user');

      $("#putname").html(user_name);
      $("#putid").val(user_id);

      $("#change_password").modal();
    });

    var confirm_match=0;
    $(".password").on('keyup',function(){
      
        var new_pass=$("#password").val();
        var conf_pass=$("#confirm_password").val();

        if(new_pass=='' || conf_pass=='') 
        {
          return false;
        }

        if(new_pass==conf_pass)
        {
            confirm_match=1;
            $("#password").removeClass('is-invalid');
            $("#confirm_password").removeClass('is-invalid');
        }
        else
        {
            confirm_match=0;
            $("#confirm_password").addClass('is-invalid');
        }

    });

    $(document).on('click', '#save_change_password_button', function(e) {
      e.preventDefault();

      var user_id =  $("#putid").val();
      var password =  $("#password").val();
      var confirm_password =  $("#confirm_password").val();
      var csrf_token = $("#csrf_token").val();

      password = password.trim();
      confirm_password = confirm_password.trim();

      if(password=='' || confirm_password=='')
      {
          $("#password").addClass('is-invalid');
          return false;
      }
      else
      {
          $("#password").removeClass('is-invalid');
      }

      if(confirm_match=='1')
      {
          $("#confirm_password").removeClass('is-invalid');
      }
      else
      {
          $("#confirm_password").addClass('is-invalid');
          return false;
      }

      $("#save_change_password_button").addClass("btn-progress");

      $.ajax({
      url: base_url+'admin/change_user_password_action',
      type: 'POST',
      dataType: 'JSON',
      data: {user_id:user_id,password:password,confirm_password:confirm_password,csrf_token:csrf_token},
        success:function(response)
        {
          $("#save_change_password_button").removeClass("btn-progress");

          if(response.status == "1")  
            swal(global_lang_success,response.message, 'success')
           .then((value) => {
               $("#change_password").modal('hide');
            });

          else  swal(global_lang_error,response.message, 'error');
        },
        error:function(response){
          var span = document.createElement("span");
          span.innerHTML = response.responseText;
          swal({ title:global_lang_error, content:span,icon:'error'});
        }
    });

    });


    $(document).on('click', '.send_email_ui', function(e) {
      var user_ids = [];
      $(".datatableCheckboxRow:checked").each(function ()
      {
          user_ids.push(parseInt($(this).val()));
      });
      
      if(user_ids.length==0) 
      {
        swal(global_lang_warning, user_manager_lang_not_selected, 'warning');
        return false;
      }
      else  $("#modal_send_sms_email").modal();
    });

    $(document).on('click', '#send_sms_email', function(e) { 
              
      var subject=$("#subject").val();
      var message=$("#message").val();
      var csrf_token = $("#csrf_token").val();

      var user_ids = [];
      $(".datatableCheckboxRow:checked").each(function ()
      {
          user_ids.push(parseInt($(this).val()));
      });
      
      if(user_ids.length==0) 
      {
        swal(global_lang_warning, user_manager_lang_not_selected, 'warning');
        return false;
      }

      if(subject=='')
      {
          $("#subject").addClass('is-invalid');
          return false;
      }
      else
      {
          $("#subject").removeClass('is-invalid');
      }

      if(message=='')
      {
          $("#message").addClass('is-invalid');
          return false;
      }
      else
      {
          $("#message").removeClass('is-invalid');
      }

      $(this).addClass('btn-progress');
      $("#show_message").html('');
      $.ajax({
      context: this,
      type:'POST' ,
      url: base_url+"admin/send_email_member",
      data:{message:message,user_ids:user_ids,subject:subject,csrf_token:csrf_token},
      success:function(response){
        $(this).removeClass('btn-progress');                  
        $("#show_message").addClass("alert alert-primary");
        $("#show_message").html(response);
      }
    }); 

  });
});