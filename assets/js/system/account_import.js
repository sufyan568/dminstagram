"use strict";
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

$("document").ready(function() {

	// instagram section
	$(document).on('click','.update_account',function(){
		var table_id = $(this).attr('table_id');
		$(this).removeClass('fas fa-sync-alt');
		$(this).addClass('fas fa-spinner');
		$.ajax({
			context: this,
			type:'POST' ,
			url:base_url+"instagram_reply/update_your_account_info",
			dataType: 'json',
			data:{table_id:table_id},
			success:function(response){ 
				
				$(this).removeClass('fas fa-spinner');
				$(this).addClass('fas fa-sync-alt');

				if(response.status == 1)
				{
					swal(global_lang_success, response.message, 'success').then((value) => {
                          $("#media_count_"+table_id).text(response.media_count);
                          $("#follower_count_"+table_id).text(response.follower_count);
                        });
				}
				else
				{
					swal(global_lang_error, response.message, 'error');
				}
			},
			error:function(response){
				$(this).removeClass('fas fa-spinner');
				$(this).addClass('fas fa-sync-alt');
                var span = document.createElement("span");
                span.innerHTML = response.responseText;
                swal({ title:global_lang_error, content:span,icon:'error'});
            }
		});
	});


	// sweet alert + confirmation
	$(document).on('click','.enable_webhook',function(){
		var restart = $(this).attr('restart');
		if(restart == 1)
		{
			var confirm_str = import_account_bot_restart_confirm
			var confirm_alert = import_account_bot_restart;
		}
		else
		{
			var confirm_str = import_account_bot_enable_confirm;
			var confirm_alert = import_account_bot_enable;
		}
		swal({
			title: confirm_alert,
			text: confirm_str,
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) 
			{
				var page_id = $(this).attr('bot-enable');
				
				$(this).removeClass('btn-outline-primary');
				$(this).addClass('btn-primary');
				$(this).addClass('btn-progress');

				$.ajax({
					context: this,
					type:'POST' ,
					url:base_url+"social_accounts/enable_disable_webhook",
					dataType: 'json',
					data:{page_id:page_id,enable_disable:'enable',restart:restart},
					success:function(response){ 
						$(this).removeClass('btn-progress');
						$(this).removeClass('btn-primary');
						$(this).addClass('btn-outline-primary');
						if(response.status == 1)
						{
							swal(global_lang_success, response.message, 'success').then((value) => {
			         			  location.reload();
								});
						}
						else
						{
							var success_message=response.message;
							var span = document.createElement("span");
							span.innerHTML = success_message;
							swal({ title:global_lang_error, content:span, icon:'error'});
						}
					}
				});
			} 
		});


	});

	$(document).on('click','.disable_webhook',function(){

		swal({
			title: import_account_bot_disable,
			text: import_account_bot_disable_confirm,
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) 
			{
				var page_id = $(this).attr('bot-enable');
				var restart = $(this).attr('restart');

				$(this).removeClass('btn-outline-dark');
				$(this).addClass('btn-dark');
				$(this).addClass('btn-progress');

				$.ajax({
					context: this,
					type:'POST' ,
					url:base_url+"social_accounts/enable_disable_webhook",
					dataType: 'json',
					data:{page_id:page_id,enable_disable:'disable',restart:restart},
					success:function(response){ 
						$(this).removeClass('btn-progress');
						$(this).removeClass('btn-dark');
						$(this).addClass('btn-outline-dark');
						if(response.status == 1)
						{
							swal(global_lang_success, response.message, 'success').then((value) => {
			         			  location.reload();
								});
						}
						else
						{
							swal(global_lang_error, response.message, 'error');
						}
					}
				});
			} 
		});


	});


	$(document).on('click','.delete_full_bot',function(){
		var confirm_str = import_account_bot_delete_confirm
		swal({
			title: import_account_bot_delete,
			text: confirm_str,
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) 
			{
				var page_id = $(this).attr('bot-enable');
			    var already_disabled = $(this).attr('already_disabled');

			    $(this).removeClass('btn-outline-danger');
			    $(this).addClass('btn-danger');
				$(this).addClass('btn-progress');

				$.ajax({
					context: this,
					type:'POST' ,
					url:base_url+"social_accounts/delete_full_bot",
					dataType: 'json',
					data:{page_id:page_id,already_disabled:already_disabled},
					success:function(response){ 
						$(this).removeClass('btn-progress');
						$(this).removeClass('btn-danger');
						$(this).addClass('btn-outline-danger');
						if(response.status == 1)
						{
							swal(global_lang_success, response.message, 'success').then((value) => {
			         			  location.reload();
								});
						}
						else
						{
							swal(global_lang_error, response.message, 'error');
						}
					}
				});
			} 
		});


	});



	$(document).on('click','.group_delete',function(e){
		e.preventDefault();
		var group_table_id = $(this).attr('table_id');
		swal({
			title: global_lang_warning,
			text: import_account_group_delete_confirm,
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) 
			{
				$(this).removeClass('btn-outline-danger');
			    $(this).addClass('btn-danger');
				$(this).addClass('btn-progress');

				$.ajax({
					context: this,
					type:'POST' ,
					url:base_url+"social_accounts/group_delete_action",
					dataType: 'json',
					data:{group_table_id:group_table_id},
					success:function(response){ 
						$(this).removeClass('btn-progress');
						$(this).removeClass('btn-danger');
						$(this).addClass('btn-outline-danger');
						if(response.status == 1)
						{
							swal(global_lang_success, response.message, 'success').then((value) => {
			         			  location.reload();
								});
						}
						else
						{
							swal(global_lang_error, response.message, 'error');
						}
					}
				});
			} 
		});


	});



	$(document).on('click','.page_delete',function(){
		swal({
			title: global_lang_are_you_sure,
			text: import_account_page_delete_confirm,
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) 
			{
				var page_table_id = $(this).attr('table_id');

				$(this).removeClass('btn-outline-danger');
			    $(this).addClass('btn-danger');
				$(this).addClass('btn-progress');

				$.ajax({
					context: this,
					type:'POST' ,
					url:base_url+"social_accounts/page_delete_action",
					dataType: 'json',
					data:{page_table_id : page_table_id},
					success:function(response){ 
						if(response.status == 1)
						{
							$(this).removeClass('btn-progress');
							$(this).removeClass('btn-danger');
							$(this).addClass('btn-outline-danger');
							
							swal(global_lang_success, response.message, 'success').then((value) => {
			         			  location.reload();
								});
						}
						else
						{
							swal(global_lang_error, response.message, 'error');
						}
					}
				});
			} 
		});


	});



	$(document).on('click','.delete_account',function(){
		swal({
			title: global_lang_are_you_sure,
			text: import_account_delete_confirm,
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		})
		.then((willDelete) => {
			if (willDelete) 
			{
				var user_table_id = $(this).attr('table_id');
				$(this).removeClass('btn-outline-danger');
			    $(this).addClass('btn-danger');
				$(this).addClass('btn-progress');

				$.ajax({
					context: this,
					type:'POST' ,
					url:base_url+"social_accounts/account_delete_action",
					dataType: 'json',
					data:{user_table_id : user_table_id},
					success:function(response){ 
						
						$(this).removeClass('btn-progress');
						$(this).removeClass('btn-danger');
						$(this).addClass('btn-outline-danger');

						if(response.status == 1)
						{
							swal(global_lang_success, response.message, 'success').then((value) => {
			         			  location.reload();
								});
						}
						else
						{
							swal(global_lang_error, response.message, 'error');
						}
					}
				});
			} 
		});


	});

	$(document).on('click', '#submit', function() {
		var fb_numeric_id = $("#fb_numeric_id").val().trim();
		if(fb_numeric_id == '')
		{
			alert(import_account_gb_numberic_id);
			return false;
		}

		var loading = '<br/><br/><img src="'+base_url+'assets/pre-loader/Fading squares2.gif" class="center-block"><br/>';
    	$("#response").html(loading);

		$.ajax
		({
		   type:'POST',
		   url:base_url+'social_accounts/send_user_roll_access',
		   data:{fb_numeric_id:fb_numeric_id},
		   success:function(response)
		    {
		        $("#response").html(response);
		    }
		       
		});
	});

	
	$(document.body).on('click','#fb_confirm',function(){
		var loading = '<br/><br/><img src="'+base_url+'assets/pre-loader/Fading squares2.gif" class="center-block"><br/>';
    	$("#response").html(loading);
		$.ajax
		({
		   type:'POST',
		   // async:false,
		   url:base_url+'social_accounts/ajax_get_login_button',
		   data:{},
		   success:function(response)
		    {
		        $("#response").html(response);
		    }
		       
		});
	});


});