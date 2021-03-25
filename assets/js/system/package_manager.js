"use strict";
$(document).ready(function() {

   var perscroll;

   var table = $("#mytable").DataTable({
      serverSide: true,
      processing:true,
      bFilter: true,
      order: [[ 1, "desc" ]],
      pageLength: 10,
      ajax: 
      {
          "url": base_url+'payment/package_manager_data',
          "type": 'POST'
      },
      language: 
      {
        url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
      },
      dom: '<"top"f>rt<"bottom"lip><"clear">',
      columnDefs: [
        {
            targets: [1],
            visible: false
        },
        {
            targets: '',
            className: 'text-center'
        },
        {
            targets: [0,6],
            sortable: false
        },
        {
          targets: [3],
          "render": function ( data, type, row, meta ) 
          {
             if(row[5]=="1" && row[3]=="0")
             return "Free"; 
             else return data;  
          }
        },
        {
          targets: [4],
          "render": function ( data, type, row, meta ) 
          {
             if(row[5]=="1" && row[3]=="0")
             return "Unlimited"; 
             else return data; 
          }
        },
        {
          targets: [5],
          "render": function ( data, type, row, meta ) 
          {
             if(data==1) return "<i class='fas fa-check-circle green'></i>";            
             else return "<i class='fas fa-times-circle'></i>";
          }
        },
        {
          targets: [6],
          "render": function ( data, type, row, meta ) 
          {
              var url=base_url+'payment/details_package/'+row[1];        
              var edit_url=base_url+'payment/edit_package/'+row[1];
              var delete_url=base_url+'payment/delete_package/'+row[1];
              var more=global_lang_view;
              var edit_str=global_lang_edit;
              var delete_str=global_lang_delete;
              var str="";   
              str="&nbsp;<a class='btn btn-circle btn-outline-primary' href='"+url+"'>"+'<i class="fas fa-eye"></i>'+"</a>";
              str=str+"&nbsp;<a class='btn btn-circle btn-outline-warning' href='"+edit_url+"'>"+'<i class="fas fa-edit"></i>'+"</a>";
             
              if(row[5]=='0')
              str=str+"&nbsp;<a href='"+delete_url+"' csrf_token='"+csrf_token+"' class='are_you_sure_datatable btn btn-circle btn-outline-danger'>"+'<i class="fa fa-trash"></i>'+"</a>";
              else str=str+"&nbsp;<a class='btn btn-circle btn-outline-light' data-toggle='tooltip' title='"+package_manager_lang_cannot_deleted+"'>"+'<i class="fa fa-trash"></i>'+"</a>";
            
              return "<div class='min_width_130px'>"+str+'</div>';
          }
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

if($("#price_default").val()=="0") $("#hidden").hide();
else $("#validity").show();

$("#all_modules").on('change',function(){
  if ($(this).is(':checked')) 
  $(".modules:not(.mandatory)").prop("checked",true);
  else
  $(".modules:not(.mandatory)").prop("checked",false);
});

$("#price_default").on('change',function(){
  if($(this).val()=="0") $("#hidden").hide();
  else $("#hidden").show();
});