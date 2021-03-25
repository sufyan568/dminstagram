"use strict";
$(document).ready(function() {
    $('#ticket_text').summernote({
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
    $(document.body).on('submit',function () {
        $(".open").attr("disabled", true);
        return true;
    });
});