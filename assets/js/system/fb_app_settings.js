"use strict";
$(document).ready(function() {
  var perscroll;
  var table = $("#mytable").DataTable({
      serverSide: true,
      processing:true,
      bFilter: true,
      order: [[ 2, "desc" ]],
      pageLength: 10,
      ajax: {
          url: base_url+'social_apps/facebook_settings_data',
          type: 'POST'
      },          
      language: 
      {
        url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
      },
      dom: '<"top"f>rt<"bottom"lip><"clear">',
      columnDefs: [
        {
            targets: [1,2,7],
            visible: false
        },
        {
            targets: '',
            className: 'text-center'
        },
        {
            targets: [0,1,5,6,7,8],
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


  $(document).on('click','.delete_app',function(e){
    e.preventDefault();    
    swal({
      title: global_lang_are_you_sure,
      text: facebook_app_delete_confirm,
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) 
      {
        var app_table_id = $(this).attr('table_id');
        var csrf_token = $(this).attr('csrf_token');
        $(this).removeClass('btn-outline-danger');
        $(this).addClass('btn-danger');
        $(this).addClass('btn-progress');

        $.ajax({
          context: this,
          type:'POST' ,
          url:base_url+"social_accounts/app_delete_action",
          dataType: 'json',
          data:{app_table_id : app_table_id,csrf_token:csrf_token},
          success:function(response){ 
            
            $(this).removeClass('btn-progress');
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-outline-danger');

            if(response.status == 1)
            {
              swal(global_lang_success, response.message, 'success').then((value) => {
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