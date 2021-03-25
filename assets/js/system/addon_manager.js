"use strict";
  $("document").ready(function(){

    $('[data-toggle="popover"]').popover(); 
    $('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;}); 

    $(".activate_action").on('click',function(e){ 
       e.preventDefault();
       var action = $(this).attr('data-href');
       var datai = $(this).attr('data-i');
       $("#href-action").val(action);      
       $(".put_add_on_title").html($("#get_add_on_title_"+datai).html());       
       $("#activate_action_modal_refesh").val('0');      
       $("#activate_action_modal").modal();       
    });

    $('#activate_action_modal').on('hidden.bs.modal', function () { 
      if($("#activate_action_modal_refesh").val()=="1")
      location.reload(); 
    })

    $("#activate_submit").on('click',function(){    
       if(is_demo=='1') 
       {
         alertify.alert(addon_manager_lang_alert,'Permission denied',function(){ });
         return false;
       }        
       var action = base_url+$("#href-action").val();
       var purchase_code=$("#purchase_code").val(); 

       $("#activate_submit").addClass('btn-progress');
       $("#activate_action_modal_msg").removeClass('alert').removeClass('alert-success').removeClass('alert-danger');
       $("#activate_action_modal_msg").html('');

       $.ajax({
             type:'POST' ,
             url: action,
             data:{purchase_code:purchase_code},
             dataType:'JSON',
             success:function(response)
             {
                $("#activate_action_modal_msg").html('');
                 $("#activate_submit").removeClass('btn-progress');

                if(response.status == '1')
                {
                  swal(support_lang_success, response.message, 'success')
                  .then((value) => {
                    location.reload();
                  });
                }
                else
                {
                  swal(support_lang_error, response.message, 'error');
                }
             }
         });        
    });

    $(".deactivate_action").on('click',function(e){ 
       e.preventDefault();
       if(is_demo=='1') 
       {
         alertify.alert(addon_manager_lang_alert,'Permission denied',function(){ });
         return false;
       } 
       var action = base_url+$(this).attr('data-href');

       swal({
            title: addon_manager_lang_deactive_addon,
            text: addon_manager_lang_deactive_addon_confirmation,
            icon: 'error',
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) 
            {
                $(this).addClass('btn-progress');
                $.ajax({
                   context: this,
                   type:'POST',
                   url: action,
                   dataType:'JSON',
                   success:function(response)
                   {
                      $(this).removeClass('btn-progress');
                      if(response.status == '1')
                      {
                        swal(support_lang_success, response.message, 'success')
                        .then((value) => {
                          location.reload();
                        });
                      }
                      else
                      {
                        swal(support_lang_error, response.message, 'error');
                      }
                   }
               }); 
            } 
          });
    });


    $(".delete_action").on('click',function(e){ 
       e.preventDefault();
       if(is_demo=='1') 
       {
         alertify.alert(addon_manager_lang_alert,'Permission denied',function(){ });
         return false;
       } 
       var action =  base_url+$(this).attr('data-href');

        swal({
            title: addon_manager_lang_delete_addon,
            text: addon_manager_lang_delete_addon_confirmation,
            icon: 'error',
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) 
            {
                $(this).addClass('btn-progress');
                $.ajax({
                   context: this,
                   type:'POST' ,
                   url: action,
                   dataType:'JSON',
                   success:function(response)
                   {
                      $(this).addClass('btn-progress');
                      if(response.status == '1')
                      {
                        swal(support_lang_success, response.message, 'success')
                        .then((value) => {
                          location.reload();
                        });
                      }
                      else
                      {
                        swal(support_lang_error, response.message, 'error');
                      }
                   }
               }); 
            } 
          });
    });   

  });
