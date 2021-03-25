"use strict";
setTimeout(function(){ 
  $("#mytable_filter").append(drop_menu); 
  $('#payment_date_range').daterangepicker({
    ranges: {
      "Last 30 Days": [moment().subtract(29, 'days'), moment()],
      "This Month"  : [moment().startOf('month'), moment().endOf('month')],
      "Last Month"  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate  : moment()
  }, function (start, end) {
    $('#payment_date_range_val').val(start.format('YYYY-M-D') + '|' + end.format('YYYY-M-D')).change();
  });
}, 2000);
  

$(document).ready(function() {
  var perscroll;
  var table = $("#mytable").DataTable({
      serverSide: true,
      processing:true,
      bFilter: true,
      order: [[ 0, "desc" ]],
      pageLength: 10,
      ajax: {
        url: base_url+'payment/transaction_log_manual_data',
        type: 'POST',
        data: function (d) {
          d.payment_date_range = $('#payment_date_range_val').val();
        }
      },
      language: {
        url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
      },
      dom: '<"top"f>rt<"bottom"lip><"clear">',
      columns: [
        {data: 'id'},
        {data: 'name'},
        {data: 'email'},
        {data: 'additional_info'},
        {data: 'attachment'},
        {data: 'status'},
        {data: 'actions'},
        {data: 'created_at'},
        {data: 'paid_amount'},
      ],          
      columnDefs: [
        {
          // targets: [1,2],
          // visible: false
        },
        {
          targets: [0,1,2,4,5,6,7,8],
          className: 'text-center'
        },
        {
          // targets: [10],
          // className: 'text-right'
        },
        {
          targets: [3,4,5,6,8],
          sortable: false
        }
      ],
      footerCallback: function ( row, data, start, end, display ) {
        var api = this.api(), data;
        var payment_total = api
          .column(8)
          .data()
          .reduce(function (a, b) {
            return parseInt(a) + parseInt(b);
          }, 0);

          $(api.column(8).footer()).html(parseFloat(payment_total, 2));
      },
      fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
          if(areWeUsingScroll) {
            if (perscroll) {
              perscroll.destroy();
            }

            perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
          }
      },
      scrollX: 'auto',
      fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
          if(areWeUsingScroll) { 
            if (perscroll) {
              perscroll.destroy();
            }

            perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
          }
      }
  });

  $(document).on('change', '#payment_date_range_val', function(event) {
    event.preventDefault(); 
    table.draw();
  });

  // Downloads file
  $(document).on('click', '#mp-download-file', function(e) {
    e.preventDefault();

    // Makes reference 
    var that = this;

    // Starts spinner
    $(that).removeClass('btn-outline-info');
    $(that).addClass('btn-info disabled btn-progress');

    // Grabs ID
    var file = $(this).data('id');

    // Requests for file
    $.ajax({
      type: 'POST',
      data: { file },
      dataType: 'JSON',
      url: base_url+"payment/manual_payment_download_file",
      success: function(res) {
        // Stops spinner
        $(that).removeClass('btn-info disabled btn-progress');
        $(that).addClass('btn-outline-info');

        // Shows error if something goes wrong
        if (res.error) {
          swal({
            icon: 'error',
            text: res.error,
            title: global_lang_error,
          });
          return;
        }

        // If everything goes well, requests for downloading the file
        if (res.status && 'ok' === res.status) {
          window.location = base_url+'payment/manual_payment_download_file';
        }
      },
      error: function(xhr, status, error) {
        // Stops spinner
        $(that).removeClass('btn-info disabled btn-progress');
        $(that).addClass('btn-outline-info');

        // Shows internal errors
        swal({
          icon: 'error',
          text: error,
          title: global_lang_error,
        });
      }
    });
  });

  // Handles data re-submit form's data
  $(document).on('click', '#manual-payment-resubmit', function(e) {
    e.preventDefault();

    // Makes reference 
    var that = this;

    // Gets transaction ID
    var id = $(that).data('id');
    $('#mp-resubmitted-id').val(id);

    // Starts spinner
    $('#mp-spinner').addClass('d-flex');

    // Opens up modal
    $('#manual-payment-modal').modal();

    // Gets data via ajax
    $.ajax({
      method: 'POST',
      dataType: 'JSON',
      cache: false,
      data: { id },
      url: base_url+'payment/transaction_log_manual_resubmit_data',
      success: function(res) {

        if (res.status && 'ok' === res.status) {
          // Stops spinner
          $('#mp-spinner').removeClass('d-flex');
          $('#mp-spinner').addClass('d-none');
          
          // Sets values
          if (res.manual_payment_status 
            && 'yes' === res.manual_payment_status
          ) {
            $('#manual-payment-instructions').removeClass('d-none');
          } else {
            $('#manual-payment-instructions').addClass('d-none');
          }

          if (res.manual_payment_instruction) {
            $('#payment-instructions').text(res.manual_payment_instruction);
          }

          $('#paid-amount').val(res.paid_amount);
          $('#paid-currency').val(res.paid_currency);
          $('#additional-info').val(res.additional_info);
          $('#selected-package-id').val(res.package_id);
        }

        if (res.error) {
          swal({
            icon: 'error',
            title: global_lang_error,
            text: res.error,
          });
        }
      },
      error: function(xhr, status, error) {
        // Stops spinner
        $('#mp-spinner').removeClass('d-flex');
        $('#mp-spinner').addClass('d-none');

        // Displays error
        swal({
          icon: 'error',
          title: global_lang_error,
          text: error,
        });
      },
    });
  });

  // Uploads files
  var uploaded_file = $('#uploaded-file');
  Dropzone.autoDiscover = false;
  $("#manual-payment-dropzone").dropzone({ 
    url: base_url+'payment/manual_payment_upload_file',
    maxFilesize:5,
    uploadMultiple:false,
    paramName:"file",
    createImageThumbnails:true,
    acceptedFiles: ".pdf,.doc,.txt,.png,.jpg,.jpeg,.zip",
    maxFiles:1,
    addRemoveLinks:true,
    success:function(file, response) {
      var data = JSON.parse(response);

      // Shows error message
      if (data.error) {
        swal({
          icon: 'error',
          text: data.error,
          title: global_lang_error
        });
        return;
      }

      if (data.filename) {
        $(uploaded_file).val(data.filename);
      }
    },
    removedfile: function(file) {
      var filename = $(uploaded_file).val();
      delete_uploaded_file(filename);
    },
  });

  // Handles form submit
  $(document).on('click', '#manual-payment-submit', function() {
    
    // Reference to the current el
    var that = this;

    // Shows spinner
    $(that).addClass('disabled btn-progress');

    var data = {
      paid_amount: $('#paid-amount').val(),
      paid_currency: $('#paid-currency').val(),
      package_id: $('#selected-package-id').val(),
      additional_info: $('#additional-info').val(),
      mp_resubmitted_id: $('#mp-resubmitted-id').val(),
    };

    $.ajax({
      type: 'POST',
      dataType: 'JSON',
      url: base_url+'payment/manual_payment',
      data: data,
      success: function(response) {
        if (response.success) {
          // Hides spinner
          $(that).removeClass('disabled btn-progress');

          // Empties form values
          empty_form_values();
          $('#selected-package-id').val('');  
          $('#mp-resubmitted-id').val('');  

          // Shows success message
          swal({
            icon: 'success',
            title: global_lang_success,
            text: response.success,
          });

          // Refreshes datatable
          table.ajax.reload();

          // Hides modal
          $('#manual-payment-modal').modal('hide');
        }

        // Shows error message
        if (response.error) {
          // Hides spinner
          $(that).removeClass('disabled btn-progress');

          swal({
            icon: 'error',
            title: global_lang_error,
            text: response.error,
          });
        }
      },
      error: function(xhr, status, error) {
        $(that).removeClass('disabled btn-progress');
      },
    });
  });

  $('#manual-payment-modal').on('hidden.bs.modal', function (e) {
    var filename = $(uploaded_file).val();
    delete_uploaded_file(filename);
    $('#selected-package-id').val(''); 
  });

  function delete_uploaded_file(filename) {
    if('' !== filename) {     
      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: { filename },
        url: base_url+'payment/manual_payment_delete_file',
        success: function(data) {
          $('#uploaded-file').val('');
        }
      });
    }

    // Empties form values
    empty_form_values();     
  }

  // Empties form values
  function empty_form_values() {
    $('#paid-amount').val(''),
    $('.dz-preview').remove();
    $('#additional-info').val(''),
    $('#paid-currency').prop("selectedIndex", 0);
    $('#manual-payment-dropzone').removeClass('dz-started dz-max-files-reached');

    // Clears added file
    Dropzone.forElement('#manual-payment-dropzone').removeAllFiles(true);
  }    

});
