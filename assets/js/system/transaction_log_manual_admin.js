"use strict";
$(document).ready(function() {   
    // Approve manual transaction
    $(document).on('click', '#mp-approve-btn, #mp-reject-btn', function(e) {
      e.preventDefault();

      // Makes reference
      var that = this;

      // Gets transaction ID
      var id = $(that).data('id');
      var action_type = $(that).attr('id');

      if ('mp-reject-btn' === action_type) {
        var reject_modal = $('#manual-payment-reject-modal');

        // Sets values to rejection form's hidden fields
        $('#mp-transaction-id').val(id);
        $('#mp-action-type').val(action_type);

        // Opens up rejection modal
        $(reject_modal).modal();
        return;
      }

      // Gets classes
      var prev_btn_el = $(that).parent().prev(); 
      var el_classes = prev_btn_el ? prev_btn_el[0].className : '';
      var new_classes = el_classes ? el_classes.replace('-outline', '') : '';

      // Shows spinner
      $(prev_btn_el).removeClass();
      $(prev_btn_el).addClass(new_classes.concat(' disabled btn-progress'));

      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: { id, action_type },
        url: base_url+'payment/manual_payment_handle_actions',
        success: function(res) {
          // Stops spinner
          $(prev_btn_el).removeClass();
          $(prev_btn_el).addClass(el_classes);

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
            // Shows success message
            swal({
              icon: 'success',
              text: res.message,
              title: global_lang_success,
            });

            // Reloads datatable
            table.ajax.reload();
          }
        },
        error: function(xhr, status, error) {
          // Stops spinner
          $(prev_btn_el).removeClass();
          $(prev_btn_el).addClass(el_classes);

          // Shows error if something goes wrong
          swal({
            icon: 'error',
            text: xhr.responseText,
            title: global_lang_error,
          });            
        }
      });
    });

    // Handles payment's approval
    $(document).on('click', '#manual-payment-reject-submit', function(e) {
      e.preventDefault();

      // Makes reference
      var that = this;

      // Starts spinner
      $(that).addClass('btn-progress disabled');

      // Gets some vars
      var id = $('#mp-transaction-id').val();
      var action_type = $('#mp-action-type').val();
      var rejected_reason = $('#rejected-reason').val();

      $.ajax({
        type: 'POST',
        dataType: 'JSON',
        data: { id, action_type, rejected_reason },
        url: base_url+'payment/manual_payment_handle_actions',
        success: function(res) {
          // Stops spinner
          $(that).removeClass('btn-progress disabled');

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
            // Shows success message
            swal({
              icon: 'success',
              text: res.message,
              title: global_lang_success,
            });

            // Clears rejection msg
            $('#rejected-reason').val('');

            // Closes modal
            $('#manual-payment-reject-modal').modal('toggle');

            // Reloads datatable
            table.ajax.reload();
          }
        },
        error: function(xhr, status, error) {
          // Stops spinner
          $(that).removeClass('btn-progress disabled');

          // Shows error if something goes wrong
          swal({
            icon: 'error',
            text: xhr.responseText,
            title: global_lang_error,
          });            
        }
      });
    }); 

});
