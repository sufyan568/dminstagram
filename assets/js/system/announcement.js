"use strict";
var counter=0;
$(document).ready(function() {      

    setTimeout(function() {          
      var start = $("#load_more").attr("data-start");   
      load_data(start,false,false);
    }, 1000);


    $(document).on('click', '#load_more', function(e) {
      var start = $("#load_more").attr("data-start");   
      load_data(start,false,true);
    });

    $(document).on('change', '#seen_type', function(e) {
      var start = '0';
      load_data(start,true,false);
    });


    $(document).on('click', '#search_submit', function(e) {
      var start = '0';
      load_data(start,true,false);
    });

    function load_data(start,reset,popmessage) 
    {
      var limit = $("#load_more").attr("data-limit");        
      var search = $("#search").val();
      var seen_type = $("#seen_type").val();
      $("#waiting").show();
      if(reset) 
      {
        $("#search_submit").addClass("btn-progress");
        counter = 0;
      }
      $.ajax({
        url: base_url+'announcement/list_data',
        type: 'POST',
        dataType : 'JSON',
        data: {start:start,limit:limit,search:search,seen_type:seen_type},
          success:function(response)
          {
            $("#waiting").hide();
            $("#nodata").hide();
            $("#search_submit").removeClass("btn-progress");

            counter += response.found; 
            $("#load_more").attr("data-start",counter); 
            if(!reset)  $("#load_data").append(response.html);
            else $("#load_data").html(response.html);

            if(response.found!='0') $("#load_more").show();                
            else 
            {
              $("#load_more").hide();
              if(popmessage) 
              {
                swal(support_lang_no_data_found, "", "warning");
                $("#nodata").hide();
              }
              else $("#nodata").show();
            }
          }
      });
    }

    $(document).on('click', '.mark_seen', function(e) {
      e.preventDefault();
      var link = $(this).attr("href");
      
      $(this).addClass('btn-progress');
      $.ajax({
        context: this,
        url: link,
        type: 'POST',
        dataType: 'JSON',
        data: {},
          success:function(response)
          {
            $(this).removeClass('btn-progress');
            if(response.status == "1")  
            {
              iziToast.success({title: support_lang_success,message: response.message,position: 'bottomRight'});
              $(this).parent().parent().parent().hide();
            }
            else iziToast.error({title: support_lang_error,message: response.message,position: 'bottomRight'});
          }
      });
    });

    $(document).on('click', '#mark_seen_all', function(e) {
        e.preventDefault();
        var mes = announcement_lang_mark_seen_confirmation;  
        swal({
          title: support_lang_are_you_sure,
          text: mes,
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => 
        {
          if (willDelete) 
          {
              $(this).addClass('btn-progress');
              $.ajax({
                context: this,
                url: base_url+"announcement/mark_seen_all",
                type: 'POST',
                dataType: 'JSON',
                data: {},
                  success:function(response)
                  {
                    $(this).removeClass('btn-progress');
                    if(response.status == "1")  
                    {
                      location.reload();
                    }
                    else iziToast.error({title: support_lang_error,message: response.message,position: 'bottomRight'});
                  }
              });
          } 
        });
    
    }); 

    $(document).on('click', '.delete_annoucement', function(e) {
        e.preventDefault();
        var link = $(this).attr("href");
        var mes = support_lang_ticket_delete_confirm;  
        swal({
          title: support_lang_are_you_sure,
          text: mes,
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => 
        {
          if (willDelete) 
          {
              $(this).addClass('btn-progress');
              $.ajax({
                context: this,
                url: link,
                type: 'POST',
                dataType: 'JSON',
                data: {},
                success:function(response)
                {
                  $(this).removeClass('btn-progress');
                  if(response.status == "1")  
                  {
                      iziToast.success({title: support_lang_success,message: response.message,position: 'bottomRight'});
                      $(this).parent().parent().parent().parent().parent().hide();
                  }
                  else iziToast.error({title: support_lang_error,message: response.message,position: 'bottomRight'});
                  }                      
              });
            } 
        });
    
    });

});
