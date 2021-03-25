"use strict";
function search_in_td(obj,td_id){  // obj = 'this' of jquery, td_id = id of the td
  	var filter=$(obj).val().toUpperCase();
	 	if(filter != ""){
	  	$('#'+td_id+' td .text_key').each(function(){
	  		var content = $(this).text().trim();

	  		if (content.toUpperCase().indexOf(filter) > -1) {
	  			$(this).css('display','block');
	  			$(this).parent().parent().find('.text_value').css("display","block");
	  			$(this).parent().parent().css('display','table-row');
	  		}
	  		else {
	  			$(this).parent().parent().css('display','none');
	  		}

  		});
  	} else 
  	{
  		$('#'+td_id+' tbody tr').each(function(index, el) {
  			$(this).css("display","table-row");
  		});

  	}
}

$(document).ready(function($) {

	// getting addon language folders to download
	$(document).on('click', '.download_addon', function(event) {
		event.preventDefault();

		var addon 	    = $(this).attr("addonname");
		var clickedtype = $(this).attr("id");

		$.ajax({
			url: base_url +"multi_language/get_addon_folders_to_download",
			type: 'POST',
			data: {addon: addon},
			success:function(response)
			{
				if(response)
				{
					$("#language_file_modal").modal();
					$('#languageDataBody').html(response);
					$("#addon_names").html(addon);
					$("#addon_type").html(clickedtype);
					$(".modal-title").html('<i class="fa fa-download"></i> '+language_manager_lang_download);
				} else
				{
					$("#addon_names").html('');
					$("#addon_type").html('');
				}
			}
		})
	});

	// getting language folders to delete from all
	$(document).on('click', '.delete', function(event) {
		event.preventDefault();

		$.ajax({
			url: base_url+'multi_language/get_all_languages_to_delete',
			type: 'POST',
			data: {param1: 'value1'},
			success:function(response)
			{
				$("#language_file_modal").modal();
				$("#languageDataBody").html(response);
				$(".modal-title").html('<i class="fa fa-trash"></i> '+language_manager_lang_delete);
				$("#addon_names").html('');
				$("#addon_type").html('');

			}
		})
	});


	// deleting the language from all, main,plugin,addons
	$(document).on('click', '.delete_language', function(event) {
		event.preventDefault();
		var langname = $(this).html();
		var selectedLang = language_manager_lang_selected_lang;

		if(langname == 'english')
		{
			swal(support_lang_error, language_manager_lang_cannot_delete, 'error');
			return;
		}

		if(langname == selectedLang)
		{
			swal(support_lang_error, language_manager_lang_cannot_delete_default, 'error');
			return;
		}

		var that_parent = $(this).parent().parent().parent().parent();


		swal({
	      title: language_manager_lang_cannot_delete_confirmation,
	      text: language_manager_lang_cannot_delete_confirmation_msg,
	      icon: 'warning',
	      buttons: true,
	      dangerMode: true,
	    })
	    .then((willDelete) => {
	      if (willDelete) {
		     $.ajax({
		     	url: base_url+'multi_language/delete_language_from_all',
		     	type: 'POST',
		     	data: {langname: langname},
		     	success:function(response)
		     	{
		     		if(response =='1')
		     		{
		     			iziToast.success({title: '',message: language_manager_lang_cannot_delete_success_msg,position: 'bottomRight'});
		     			$(that_parent).addClass('d-none');

		     		}
		     		else
		     		{
		     			iziToast.success({title: '',message: global_lang_something_went_wrong,position: 'bottomRight'});
		     			$(that_parent).removeClass('d-none');
		     		}
		     	}
		     })
	      } 
	    });

	});


	// if delete modal reload the location else no reload
	$('#modal_close').on('click', function(event) {
		event.preventDefault();

		console.log("dsdsdfs");

		var download_modal = $("#addon_type").html();
		if(download_modal == "addons")
		{
			//no reload
			var tab = $("#addonTab").attr("href");

		} else {
			// if delete modal then do reload
			location.reload();
		}
	});


	// save language name for all
	$(document).on('click', '#save_language_name', function(event) {
		event.preventDefault();
		var languageName = $('#language_name').val();

		// if the language name filed is empty
		if(languageName == '') {
			var giveAname = language_manager_lang_alert1;
			swal(global_lang_warning, giveAname, 'warning');
			return false;
		}

		$.ajax({
			url: base_url+'multi_language/save_language_name',
			type: 'POST',
			data: {languageName: languageName},
			success:function(response)
			{
				if(response == "1") 
				{
					swal(global_lang_success, global_lang_saved_successfully, 'success');

				} else if(response == '3') 
				{
					swal(global_lang_error, language_manager_lang_only_char_allowed, 'error');
				}
				else 
				{
					swal(global_lang_error, language_manager_lang_language_exist, 'error');
				}

			}
		});

	});


	// showing language files data from directory
	$(document).on('click', '.language_file', function(event) {
		event.preventDefault();

		var languageFieldSelect = $(this).attr('id');
		var languageName = $.trim($('#language_name').val());
		var fileType = $(this).attr('file_type');

		// if the language name filed is empty
		if(languageName == '') 
		{
			var giveAname = language_manager_lang_alert1;
			swal(global_lang_warning, giveAname, 'warning');
			return false;
		} 

		// loading processing img
		var loading = '<br><img src="'+base_url+'assets/pre-loader/color/Preloader_9.gif" class="center-block" height="30" width="30">';
		$('#response_status').html(loading);

	    $.ajax({
	    	type:'POST',
	    	url: base_url+"multi_language/ajax_get_language_details",
	    	data: {fileType:fileType,languageName:languageName},
	    	dataType: 'JSON',
	    	success:function(response){
		    	if(response.result == "1") 
		    	{
    		  		$('#language_file_modal').modal();
    				$('#languageDataBody').html(response.langForm);
    		  		$("#language_type_modal").html(fileType);
    		  		$('#response_status').html('');
    		  		$("#new_lang_val").html(languageName);

		    	} else
		    	{
		    		var giveAname = language_manager_lang_alert1;
		    		swal(global_lang_warning, giveAname, 'warning');
		    	}
	    	}
	    });
	});


	// saving language file with language folder name
	$(document).on('click', '.save_language_button', function(event) {
		event.preventDefault();

		var languageFieldSelect = $(this).attr('id');
		var languageName 		= $('#language_name').val();

		// if the language name filed is empty
		if(languageName == '') {
			var giveAname = language_manager_lang_alert1;
			swal(global_lang_warning, giveAname, 'warning');
			return false;
		}

		$('#saving_response').html('');
		$(this).addClass("btn-progress");

		// Generate the language folder name from input
		var folder_name = $("#language_folder_name").val(languageName);
		// detect the file type clicked
		var clickedFile = $("#language_file_id").val();
		var ftype 		= $("#language_type_modal").html();	

		var alldatas = new FormData($("#language_creating_form")[0]);

	    $.ajax({
	    	context: this,
	    	type:'POST',
	    	url: base_url+"multi_language/ajax_language_file_saving",
	    	data: alldatas,
	    	dataType : 'JSON',
	    	cache: false,
	    	contentType: false,
	    	processData: false,
	    	success:function(response){
	    		$(this).removeClass("btn-progress");
	    		if(response.status=="1")
		        {
		        	iziToast.success({title: '',message: response.message,position: 'bottomRight'});
		        }
		        else
		        {
		        	iziToast.error({title: '',message: response.message,position: 'bottomRight'});
		        }
	    	}
	    });
	});

	// updating language name
	$(document).on('click', '#update_language_name', function(event) {
		event.preventDefault();

		var languagename = $("#language_name").val();
		var pre_value 	 = language_manager_lang_editable_language;


		if(languagename == '')
		{
			var giveAname = language_manager_lang_alert2;
			swal(global_lang_warning, giveAname, 'warning');
			return false;
		}

		if(languagename === pre_value)
		{
			swal(global_lang_warning, language_manager_lang_language_exist_update, 'warning');

		} 
		else 
		{
			$.ajax({
				url: base_url+'multi_language/updating_language_name',
				type: 'POST',
				dataType:'JSON',
				data: {languagename: languagename,pre_value:pre_value},
				success:function(response)
				{
					if(response.status =="1")
					{
						var name = response.new_name;
						var currentUrl = base_url+"multi_language/edit_language/"+name+"/main_app";
						location.assign(currentUrl);
						
					} 
					else if(response.status =='3') 
					{
						swal(global_lang_warning, language_manager_lang_only_char_allowed, 'error');
					} else
					{
						swal(global_lang_warning, language_manager_lang_language_exist_try, 'error');
					}
				}
			});
		}
	});


	// showing language files data from directory
	$(document).on('click', '.allFiles', function(event) {
		event.preventDefault();

		// getting which file is clicked
		var fileType 			= $(this).attr('file_type');
		var languageFieldSelect = $(this).attr('id');
		var languageName 		= language_manager_lang_editable_language;
		var langname_existance  = $("#language_name").val();
		var addonLangName		= $(this).attr("folderName");

		// if the language name filed is empty
		if(languageFieldSelect == "main_app") 
		{
			if(langname_existance == '') 
			{
				var giveAname = language_manager_lang_alert2;
				swal(global_lang_warning, giveAname, 'warning');
				return false;
			}
		}

		// loading processing img
		var loading = '<br><img src="'+base_url+'assets/pre-loader/color/Preloader_9.gif" class="center-block" height="30" width="30">';
		$('#response_status').html(loading);

	    $.ajax({
	    	type:'POST',
	    	url: base_url+"multi_language/ajax_get_lang_file_data_update",
	    	dataType:'JSON',
	    	data: {fileType:fileType,languageName:languageName,langname_existance:langname_existance},
	    	success:function(response)
	    	{
		    	if(response.status == "1") 
		    	{
		    		$('#language_file_modal').modal();
		  			$('#languageDataBody').html(response.langForm);
		    		$('#response_status').html('');
		    		$("#languName").html(languageName);
		    		$("#addon_languName").html(addonLangName);

		    	} else if(response.status == "3")
		    	{
		    		swal(global_lang_error, language_manager_lang_update_name_first, 'error');
		    	} else
		    	{
		    		$('#response_status').html(loading);
		    	}
	    	}

	    });

	});
	

	// saving language file with language folder name
	$(document).on('click', '.update_language_button', function(event) {
		event.preventDefault();

		var languageName = $('#language_name').val();

		// if the language name filed is empty
		if(languageName == '') 
		{
			var giveAname = language_manager_lang_alert2;
			swal(global_lang_warning, giveAname, 'warning');
			return false;
		}

		$('#saving_response').html('');
		$(this).addClass("btn-progress");

		// Generate the language folder name from input
		var folder_name = $("#language_folder_name").val(languageName);
		// detect the file type clicked
		var clickedFile = $("#language_file_id").val();

		
		var alldatas = new FormData($("#language_creating_form")[0]);

	    $.ajax({
	    	context: this,
	    	type:'POST',
	    	url: base_url+"multi_language/ajax_updating_lang_file_data",
	    	data: alldatas,
	    	dataType : 'JSON',
	    	cache: false,
	    	contentType: false,
	    	processData: false,
	    	success:function(response){
	    		$(this).removeClass("btn-progress");
	    		if(response.status=="1")
		        {
		        	iziToast.success({title: '',message: response.message,position: 'bottomRight'});
		        }
		        else
		        {
		        	iziToast.error({title: '',message: response.message,position: 'bottomRight'});
		        }
	    	}

	    });
	});

});
