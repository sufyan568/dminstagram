  $(function() {
      "use strict";
      $(document).ready(function() {

	    // Fixes multiple modal issues
	    $('.modal').on("hidden.bs.modal", function (e) { 
	      if ($('.modal:visible').length) { 
	        $('body').addClass('modal-open');
	      }
	    });

	    var payment_modal = $('#payment_modal');
	  

	    $(document).on('click', ".choose_package", function(e) {
	       e.preventDefault();           
	       var package2=$(this).attr('data-id');
	       // Sets package id for manual payment
	       $('#selected-package-id').val(package2);
	       var redirect_url = base_url+'payment/payment_button/'+package2;

	       if(payment_has_reccuring==true)  
	       {
	        swal(payment_lang_subscription_message, payment_lang_subscription_message_deatils)
	        .then((value) => { 
	          window.location.assign(redirect_url)          
	        });
	      }
	      else 
	      {
	        window.location.assign(redirect_url)
	      }
	    });
    });
  });

  if ('yes' == payment_is_manaual_payment)
  {
      $(function() {
        "use strict";
        $(document).ready(function() {

        $(document).on('click', '#manual-payment-button', function() {
          $('#payment_modal').modal('toggle');
          $('#manual-payment-modal').modal();
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

                // Shows success message
                swal({
                  icon: 'success',
                  title: global_lang_success,
                  text: response.success,
                });

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
    });
  }



















