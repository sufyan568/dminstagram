"use strict";
$(document).ready(function($) {
    $('#page_description').summernote(); 
    $('div.note-group-select-from-files').remove();   

    $(document).on('click', '#create_page', function(event) {
        event.preventDefault();

        var page_name = $("#page_name").val();
        var page_description = $("#page_description").val();

        if(page_name=='') {
            $("#page_name").addClass('is-invalid');
            return false;
        }
        else {
            $("#page_name").removeClass('is-invalid');
        }

        if(page_description=='') {
            $("#page_description").addClass('is-invalid');
            return false;
        }
        else {
            $("#page_description").removeClass('is-invalid');
        }

        $(this).addClass('btn-progress');
        var that= $(this);

        var report_link = base_url+"menu_manager/get_page_lists";

        $.ajax({
            url: base_url+'menu_manager/create_page_action',
            type: 'POST',
            dataType:'JSON',
            data: {page_name: page_name, page_description:page_description},
            success:function(response) {
                $(that).removeClass('btn-progress');
                if(response.error) {
                    var span = document.createElement("span");
                    span.innerHTML = response.error;
                    swal({ title:global_lang_warning, content:span,icon:'warning'});
                }

                if(response.status =="1") {
                    var span = document.createElement("span");
                    span.innerHTML = menu_manager_page_created;
                    swal({ title:global_lang_success, content:span,icon:'success'}).then((value) => {window.location.href=report_link;});
                } else if(response.status =='0') {
                    var span = document.createElement("span");
                    span.innerHTML = global_lang_something_went_wrong;
                    swal({ title:global_lang_error, content:span,icon:'error'}).then((value) => {window.location.href=report_link;});
                }
            }
        })
    });

    $(document).on('click', '#update_page', function(event) {
        event.preventDefault();

        var table_id = $("#page_table_id").val();
        var page_name = $("#page_name").val();
        var page_description = $("#page_description").val();

        if(page_name=='') {
            $("#page_name").addClass('is-invalid');
            return false;
        }
        else {
            $("#page_name").removeClass('is-invalid');
        }

        if(page_description=='') {
            $("#page_description").addClass('is-invalid');
            return false;
        }
        else {
            $("#page_description").removeClass('is-invalid');
        }

        $(this).addClass('btn-progress');
        var that= $(this);

        var report_link = base_url+"menu_manager/get_page_lists";

        $.ajax({
            url: base_url+'menu_manager/edit_page_action',
            type: 'POST',
            dataType:'JSON',
            data: {page_table_id:table_id,page_name: page_name, page_description:page_description},
            success:function(response) {
                $(that).removeClass('btn-progress');

                if(response.error) {
                    var span = document.createElement("span");
                    span.innerHTML = response.error;
                    swal({ title:global_lang_warning, content:span,icon:'warning'});
                }

                if(response.status =="1") {
                    var span = document.createElement("span");
                    span.innerHTML = menu_manager_page_updated;
                    swal({ title:global_lang_success, content:span,icon:'success'}).then((value) => {window.location.href=report_link;});
                } else if(response.status =='0') {
                    var span = document.createElement("span");
                    span.innerHTML = global_lang_something_went_wrong;
                    swal({ title:global_lang_error, content:span,icon:'error'}).then((value) => {window.location.href=report_link;});
                }
            }
        })
    });
});