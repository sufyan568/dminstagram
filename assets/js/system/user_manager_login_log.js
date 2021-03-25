"use strict";
  $(document).ready(function() {

    var perscroll;
    var table = $("#mytable").DataTable({          
        processing:true,
        bFilter: true,
        order: [[ 3, "desc" ]],
        pageLength: 25,
        language: 
        {
          url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        columnDefs: [            
          {
              targets: [3,4],
              className: 'text-center'
          },
          {
              targets: [0],
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

  });
