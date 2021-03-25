"use strict";
$(document).ready(function () {       
    //icon picker options
    var iconPickerOptions = {searchText: 'Search...', labelHeader: '{0} of {1} Pages'};
    //sortable list options
    var sortableListOptions = { placeholderCss: {'background-color': '#ddd'} };

    var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions});

    editor.setForm($('#frmEdit'));
    editor.setUpdateButton($('#btnUpdate'));

    var strjson = menu_manager_all_menu;
    editor.setData(strjson);


    // click on save button
    $('#btnOut').on('click', function () {
        var str = editor.getString();
        $(this).addClass('btn-progress');
        var that = $(this);
        $.ajax({
                type: "POST",
                url: base_url+"menu_manager/insert_menu_data", 
                data: { "values": str },
                dataType: "JSON",
                success:function(data) {
                    $(that).removeClass('btn-progress');
                    location.reload();
                },
                error:function(data){
                  var span = document.createElement("span");
                  span.innerHTML = data.responseText;
                  swal({ title:global_lang_error, content:span,icon:'error'});
                }

            });
        
    }); // end of click on save button



    $('.reset_menu').on('click', function (e) {
        e.preventDefault();
        swal({
            title: global_lang_warning,
            text: menu_manager_restore_confirm,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        })
        .then((willreset) => {
            if (willreset) 
            {
                $(this).addClass('btn-progress');

                $.ajax({
                    context: this,
                    type:'POST' ,
                    url: base_url+"menu_manager/reset_to_default",
                    dataType: 'json',
                    success:function(response){ 
                        if(response.status == 1)
                        {
                            $(this).removeClass('btn-progress');
                            
                            swal(global_lang_success, response.message, 'success').then((value) => {
                                  location.reload();
                                });
                        }
                        else
                        {
                            swal(global_lang_error, response.message, 'error');
                        }
                    },
                    error:function(response){
                        var span = document.createElement("span");
                        span.innerHTML = response.responseText;
                        swal({ title:global_lang_error, content:span,icon:'error'});
                    }
                });
            } 
        });


    });


    $('#name').on('keydown', function () {
        $("#error_msg").html('');
    });


    $('#href').on('keydown', function () {
        $('#error_msg2').html('');
    });


    $('#target').on('change', function () {
        var target_field = $('#target').val();

        if( target_field == '0' )
        {
            $('#two').show();
            $('#one').hide();

        } else if(target_field == '1')
        {
            $('#two').hide(); 
            $('#one').show();
        }
    });


    // click on update button
    $('#btnUpdate').on('click', function () {

        var name_field   = $("#name").val();
        var menu_manager = $('#is_menu_manager').val();
        var icon_pick    = $('#iconPicker').val();

        if(menu_manager == '0') {
            if(name_field == '') {  
                var error = $("#error_msg").html("<B>"+menu_manager_name_required+"</B>");
                return error;
            }

            if(icon_pick == 'empty') {  //if name field is empty
                var error = $("#error_msg4").html("<B>"+menu_manager_icon_required+"</B>");
                return error;
            }
        }



        if(menu_manager == '1') {

            var url_field    = $("#href").val();
            var target_field = $("#target").val();
            var page_list    = $('#page_list').val();               

            if(name_field == '') {  
                var error = $("#error_msg").html("<B>"+menu_manager_name_required+"</B>");
                return error;
            } 

            if(target_field == '1') {
                $('#one').show();
                $('#two').hide();
            }
        }

        editor.update(); 

        // make editable content on update completion
        $('#target').removeAttr('disabled');
        $('#href').removeAttr('disabled');
        $('#only_admin').removeAttr('disabled');
        $('#only_member').removeAttr('disabled');
        $('#btnAdd').removeAttr('disabled');

        if(target_field == '1') {
            $('#one').show();
            $('#two').hide();
        }

        if(menu_manager == '1') {
            $('#one').show();
            $('#two').hide();
        }

    }); // end of update button click



    // click on Add button
    $('#btnAdd').on('click', function () {

        var name_field   = $("#name").val();
        var url_field    = $("#href").val();
        var target_field = $("#target").val();
        var page_list    = $('#page_list').val();
        var icon_pick    = $('#iconPicker').val();
        var header_text    = $('#header_text').val();

        if(name_field == '') {  //if name field is empty
            var error = $("#error_msg").html("<B>"+menu_manager_name_required+"</B>");
            return error;
        }

        editor.add();

        if($('#target').val() == '1') {
            $('#one').show();
            $('#two').hide();
        }

    }); // end of add button click


    $('#page_list').on('change', function () {
        $('#error_msg3').html('');
    });

    $('#myEditor_icon').on('change', function () {
        $('#error_msg4').html('');
    });


});