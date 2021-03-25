"use strict";
$(document).ready(function($) {

    setTimeout(function(){ 
      $('#page_date_range').daterangepicker({
        ranges: {
          global_lang_last_30_days: [moment().subtract(29, 'days'), moment()],
          global_lang_this_month  : [moment().startOf('month'), moment().endOf('month')],
          global_lang_last_month  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      }, function (start, end) {
        $('#page_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
      });
    }, 2000);

    var today = new Date();
    var next_date = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
    $('.datepicker_x').datetimepicker({
        theme:'light',
        format:'Y-m-d H:i:s',
        formatDate:'Y-m-d H:i:s',
        minDate: today,
        maxDate: next_date
    })

    $('[data-toggle=\"tooltip\"]').tooltip();

    // =========================== SMS API Section started and datatable section started ========================
    var perscroll;
    var table = $("#mytable_custom_page_lists").DataTable({
        serverSide: true,
        processing:true,
        bFilter: false,
        order: [[ 2, "desc" ]],
        pageLength: 10,
        ajax: 
        {
          "url": base_url+'menu_manager/page_lists_data',
          "type": 'POST',
          data: function ( d )
          {
              d.searching = $('#searching_page').val();
              d.page_date_range = $('#page_date_range_val').val();
          }
        },
        language: 
        {
          url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        columnDefs: [
            {
              targets: [2],
              visible: false
            },
            {
              targets: [0,1,3,4,6,7],
              className: 'text-center'
            },
            {
              targets: [0,1,3,4,6],
              sortable: false
            }
        ],
        fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
          if(areWeUsingScroll)
          {
            if (perscroll) perscroll.destroy();
            perscroll = new PerfectScrollbar('#mytable_custom_page_lists_wrapper .dataTables_scrollBody');
          }
        },
        scrollX: 'auto',
        fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
          if(areWeUsingScroll)
          { 
            if (perscroll) perscroll.destroy();
            perscroll = new PerfectScrollbar('#mytable_custom_page_lists_wrapper .dataTables_scrollBody');
          }
        }
    });


    $(document).on('keyup', '#searching_page', function(event) {
      event.preventDefault(); 
      table.draw();
    });

    $(document).on('change', '#page_date_range_val', function(event) {
      event.preventDefault(); 
      table.draw();
    });

    $(document).on('click','.delete_page',function(e){
        e.preventDefault();
        swal({
            title: global_lang_are_you_sure,
            text: global_lang_delete_confirmation,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) 
            {
                var table_id = $(this).attr('table_id');

                $.ajax({
                    context: this,
                    type:'POST' ,
                    url:base_url+"menu_manager/delete_single_page",
                    data:{table_id:table_id},
                    success:function(response)
                    { 
                        if(response == '1')
                        {
                            iziToast.success({title: '',message: menu_manager_page_deleted,position: 'bottomRight',timeout: 3000});
                        } else
                        {
                            iziToast.error({title: '',message: global_lang_something_went_wrong,position: 'bottomRight',timeout: 3000});
                        }
                        table.draw();
                    }
                });
            } 
        });
    });


    $(document).on('click', '.delete_selected_page', function(event) {
      event.preventDefault();

      var page_ids = [];
      $(".datatableCheckboxRow:checked").each(function ()
      {
          page_ids.push(parseInt($(this).val()));
      });
      
      if(page_ids.length==0) {

          swal(global_lang_warning, menu_manager_page_not_selected, 'warning');
          return false;

      }
      else {

        swal({title: global_lang_are_you_sure,text: global_lang_delete_confirmation,icon: 'warning',buttons: true,dangerMode: true,})
        .then((willDelete) => {

            if (willDelete) {

              $(this).addClass('btn-progress');
              $.ajax({
                  context: this,
                  type:'POST',
                  url: base_url+"menu_manager/ajax_delete_all_selected_pages",
                  data:{info:page_ids},
                  success:function(response){
                      $(this).removeClass('btn-progress');

                      if(response == '1') {

                        iziToast.success({title: '',message: menu_manager_pages_deleted,position: 'bottomRight'});

                      } else {

                        iziToast.error({title: '',message: global_lang_something_went_wronga,position: 'bottomRight'});

                      }

                      table.draw();
                  }
              });

            } 
        });
      }

    });
});