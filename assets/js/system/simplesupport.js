"use strict";
$(document).ready(function() {

  var table = $("#mytable").DataTable({          
      processing:true,
      bFilter: true,
      pageLength: 10,
      language: 
      {
        url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
      },
      dom: '<"top"f>rt<"bottom"lip><"clear">',
      columnDefs: [            
        {
            targets: '',
            className: 'text-center'
        },
        {
            targets: [0],
            sortable: false
        }
      ]
  });

  $('#ticket_reply_text').summernote({
    height: 300,
    minHeight:300,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link']],
        ['view', ['codeview']]
    ]
  });

  $(document.body).on('click', '.ticket_action', function(e) {
    e.preventDefault();
    var id = $(this).attr("table_id");
    var action = $(this).attr("data-type");
    
    $(this).addClass('btn-progress');
    $.ajax({
      context: this,
      url: base_url+"simplesupport/ticket_action",
      type: 'POST',
      dataType: 'JSON',
      data: {id:id,action:action},
        success:function(response)
        {
          $(this).removeClass('btn-progress');
          if(response.status == "1") iziToast.success({title: support_lang_success,message: response.message,position: 'bottomRight'});
          else iziToast.error({title: support_lang_error,message: response.message,position: 'bottomRight'});

          setTimeout(function() {          
            location.reload();
          }, 2000);
        }
    });
  });

  $(document.body).on('submit',function () {
    $(".reply").attr("disabled", true);
    return true;
  });
});