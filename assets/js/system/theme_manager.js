"use strict";
$("document").ready(function(){

  $('[data-toggle="popover"]').popover(); 
  $('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;}); 

  $(".activate_action").on('click',function(e){ 
     e.preventDefault();
     var folder_name = $(this).attr('data-unique-name');
     swal({
         title: theme_manager_lang_activation,
         text: theme_manager_lang_activation_confirmation,
         icon: 'info',
         buttons: true,
         dangerMode: true,
       })
       .then((willDelete) => {
         if (willDelete) 
         {
             $.ajax({
                type:'POST' ,
                url: base_url+"themes/active_deactive_theme",
                data:{folder_name:folder_name,active_or_deactive:'active'},
                dataType:'JSON',
                success:function(response)
                {
                   if(response.status == '1')
                   {
                     swal(global_lang_success, response.message, 'success')
                     .then((value) => {
                       location.reload();
                     });
                   }
                   else
                   {
                     swal(global_lang_error, response.message, 'error');
                   }
                }
            }); 
         } 
       });     
  });

  $(".deactivate_action").on('click',function(e){ 
     e.preventDefault();
     var folder_name = $(this).attr('data-unique-name');
     swal({
         title: theme_manager_lang_deactivation,
         text: theme_manager_lang_deactivation_confirmation,
         icon: 'warning',
         buttons: true,
         dangerMode: true,
       })
       .then((willDelete) => {
         if (willDelete) 
         {
             $.ajax({
                type:'POST' ,
                url: base_url+"themes/active_deactive_theme",
                data:{folder_name:folder_name,active_or_deactive:'deactive'},
                dataType:'JSON',
                success:function(response)
                {
                   if(response.status == '1')
                   {
                     swal(global_lang_success, response.message, 'success')
                     .then((value) => {
                       location.reload();
                     });
                   }
                   else
                   {
                     swal(global_lang_error, response.message, 'error');
                   }
                }
            }); 
         } 
       });     
  });

  $(".delete_action").on('click',function(e){ 
     e.preventDefault();
     var folder_name = $(this).attr('data-unique-name');
     swal({
         title: global_lang_delete,
         text: theme_manager_lang_delete_confirmation,
         icon: 'warning',
         buttons: true,
         dangerMode: true,
       })
       .then((willDelete) => {
         if (willDelete) 
         {
             $.ajax({
                type:'POST' ,
                url: base_url+"themes/delete_theme",
                data:{folder_name:folder_name},
                dataType:'JSON',
                success:function(response)
                {
                   if(response.status == '1')
                   {
                     swal(global_lang_success, response.message, 'success')
                     .then((value) => {
                       location.reload();
                     });
                   }
                   else
                   {
                     swal(global_lang_error, response.message, 'error');
                   }
                }
            }); 
         } 
       });     
  });

});
