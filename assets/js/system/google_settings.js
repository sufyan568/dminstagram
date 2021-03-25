"use strict";
$(document).ready(function() {

	$(document).on('mouseover', '[data-toggle=\"tooltip\"]', function(event) {
		event.preventDefault();
		$('[data-toggle=\"tooltip\"]').tooltip();
	});
	
	var perscroll;
	var table = $("#mytable").DataTable({
	    serverSide: true,
	    processing:true,
	    bFilter: true,
	    order: [[ 1, "desc" ]],
	    pageLength: 10,
	    ajax: 
	    {
	        "url": base_url+'social_apps/google_settings_data',
	        "type": 'POST'
	    },
	    language: 
	    {
	      url: base_url+"assets/modules/datatables/language/"+selected_language+".json"
	    },
	    dom: '<"top"f>rt<"bottom"lip><"clear">',
	    columnDefs: [
	      {
	    	  targets: [1],
	    	  visible: false
	      },
	      {
	          targets: '',
	          className: 'text-center'
	      },
	      {
	          targets: [0,6,7],
	          sortable: false
	      },
	      {
	      		targets: [3],
	      		render: function ( data, type, row, meta ) 
	      		{
	      			if (data == '0')
	      				return "<span class='text-danger min_width_120px'><i class='fas fa-times-circle'></i> "+global_lang_inactive+"</span>";
	      			else if (data == '1')
	      				return "<span class='text-success min_width_120px'><i class='fas fa-check-circle'></i> "+global_lang_active+"</span>";
	      		}
	      },
	      {
	      	  targets: [4],
	      	  render: function ( data, type, row, meta ) 
	      	  {
	      	  		var action_width = (3 * 47) + 20;
	      	  		var campaign_status_title;
	      	  		var campaign_status_icon;

	      	  		if (row[3] == '0') {

	      	  			campaign_status_title = fb_settings_lang_make_active;
	      	  			campaign_status_icon = 'fa-play';
	      	  		}
	      	  		else if (row[3] == '1') {

	      	  			campaign_status_title = fb_settings_lang_make_inactive;
	      	  			campaign_status_icon = 'fa-pause'
	      	  		}

	      	  		var string = '<div class="dropdown d-inline dropleft"><button class="btn btn-outline-primary dropdown-toggle no_caret" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-briefcase"></i></button><div class="dropdown-menu mini_dropdown text-center" style="width:'+ action_width +'px !important"><a class="btn btn-circle btn-outline-info state_change_action" href="#" data-toggle="tooltip" title="'+ campaign_status_title +'" table_id="' + row[1] +'"><i class="fas '+ campaign_status_icon +'"></i></a>&nbsp;';
	      	     	string += '<a class="btn btn-circle btn-outline-warning edit_table_data" href="#" data-toggle="tooltip" title="'+global_lang_edit+'" table_id="' + row[1] +'"><i class="fas fa-edit"></i></a>&nbsp;';
	      	     	string += '<a class="btn btn-circle btn-outline-danger delete_table_data" href="#" data-toggle="tooltip" title="'+global_lang_delete+'" table_id="' + row[1] +'"><i class="fas fa-trash"></i></a></div></div>';

	      	  		return string;  
	      	  }
	      },
	      {
	      		targets: [5],
	      		render: function ( data, type, row, meta ) 
	      		{
	      			
	      			return row[4];
	      		}
	      },
	      {
	      		targets: [6],
	      		render: function ( data, type, row, meta ) 
	      		{
	      			
	      			return row[5];
	      		}
	      },
	      {
	      		targets: [7],
	      		render: function ( data, type, row, meta ) 
	      		{
	      			
	      			return row[6];
	      		}
	      },
	    ],
	    fnInitComplete:function(){ // when initialization is completed then apply scroll plugin
	    	if(areWeUsingScroll)
	    	{
	    		if (perscroll) perscroll.destroy();
	    		perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
	    	}
	    },
	    scrollX: 'auto',
	    fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again 
	    	if(areWeUsingScroll)
	    	{ 
	    		if (perscroll) perscroll.destroy();
	    		perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
	    	}
	    }
	});


	$(document).on('keyup', '#app_name, #api_key, #google_client_id, #google_client_secret', function(event) {
		event.preventDefault();
		
		var app_name = $("#app_name").val();
		var api_key = $("#api_key").val();
		var google_client_id = $("#google_client_id").val();
		var google_client_secret = $("#google_client_secret").val();

		if (app_name == '' && api_key == '' && google_client_id == '' && google_client_secret == '') {

			$("#add_app_btn").html('<i class="fa fa-plus"></i> '+fb_settings_lang_add_app);
			$("#submit_type").val('add');
			$("#app_status").prop('checked', false);
		}
	});


	$(document).on('click', '#add_app_btn', function(event) {
		event.preventDefault();

		var form_data = new FormData($("#google_config_form_data")[0]);

		var submit_type = $("#submit_type").val();

		var btn_content = $(this).html();
		btn_content = btn_content.replace('<i class="fa fa-plus"></i>', '<i class="fas fa-spin fa-spinner"></i>');
		btn_content = btn_content.replace('<i class="fa fa-edit"></i>', '<i class="fas fa-spin fa-spinner"></i>');

		$(this).html(btn_content);

		var that = $(this);

		$.ajax({
			url:  base_url+'social_apps/google_settings_action',
			type: 'POST',
			dataType: 'json',
			cache: false,
			processData: false,
			contentType: false,
			data: form_data,
			success: function (response) {

				if (response.type == 'error')
					swal(global_lang_error, response.message, 'error');
				else if (response.type == 'success')
					swal(global_lang_success, response.message, 'success');

				table.draw();
				
				if (submit_type == 'add')
					btn_content = btn_content.replace('<i class="fas fa-spin fa-spinner"></i>', '<i class="fa fa-plus"></i>');
				else
					btn_content = btn_content.replace('<i class="fas fa-spin fa-spinner"></i>', '<i class="fa fa-edit"></i>');
				$(that).html(btn_content);
			}
		});
			
	});	



	$(document).on('click', '.edit_table_data', function(event) {
		event.preventDefault();
		
		var parent = $(this).parent().parent().parent().parent();
		var childrens = $(parent).children();

		var app_name = $(childrens)[1]['innerHTML'];
		var api_key = $(childrens)[4]['innerHTML'];
		var google_client_id = $(childrens)[5]['innerHTML'];
		var google_client_secret = $(childrens)[6]['innerHTML'];
		var app_status = $(childrens)[2]['innerHTML'];
		
		$("#app_name").val(app_name);
		$("#api_key").val(api_key);
		$("#google_client_id").val(google_client_id);
		$("#google_client_secret").val(google_client_secret);

		if (app_status.indexOf(global_lang_active) != -1) 
			$("#app_status").prop('checked', true);
		else 
			$("#app_status").prop('checked', false);

		$("#table_id_info").val($(this).attr('table_id'));
		$("#submit_type").val('edit');

		$("#add_app_btn").html('<i class="fa fa-edit"></i> '+fb_settings_lang_edit_app);

		location.href = base_url+'social_apps/google_settings#app_info_card';
	});


	$(document).on('click', '.state_change_action', function(event) {
		event.preventDefault();
		
		var table_id = $(this).attr('table_id');
		var that = $(this).parent().parent();

		swal({
         title: global_lang_are_you_sure,
         text: fb_settings_lang_change_app_state_confirmation,
         icon: 'warning',
         buttons: true,
         dangerMode: true,
       })
       .then((willDelete) => {
         if (willDelete) 
         {

         	 $(that).find('button i').addClass('fa-spin fa-spinner');
         	 $(that).find('button i').removeClass('fa-trash');


             var user_page_id = $("#migrate_list").attr('button_id');

             $.ajax({
               type:'POST' ,
               url:base_url+"social_apps/change_google_app_state",
               dataType: 'json',
               data:{table_id : table_id},
               success:function(response){ 
                  
                  
                  if(response.type == '1')
                  	swal(global_lang_success, response.message, 'success');
                  else
                    swal(global_lang_error, response.message, 'error');


                  $(that).find('button i').removeClass('fa-spin fa-spinner');
                  $(that).find('button i').addClass('fa-trash');
                  table.draw();
               }
             });
         } 
       });
	});


	$(document).on('click', '.delete_table_data', function(event) {
		event.preventDefault();
		
		var table_id = $(this).attr('table_id');
		var that = $(this).parent().parent();

		swal({
         title: global_lang_are_you_sure,
         text: google_settings_lang_delete_app_confirmation,
         icon: 'warning',
         buttons: true,
         dangerMode: true,
       })
       .then((willDelete) => {
         if (willDelete) 
         {

         	 $(that).find('button i').addClass('fa-spin fa-spinner');
         	 $(that).find('button i').removeClass('fa-trash');

             var user_page_id = $("#migrate_list").attr('button_id');

             $.ajax({
               type:'POST' ,
               url:base_url+"social_apps/delete_google_app",
               dataType: 'json',
               data:{table_id : table_id},
               success:function(response){ 
                  
                  
                  if(response.type == '1')
                  	swal(global_lang_success, response.message, 'success');
                  else
                    swal(global_lang_error, response.message, 'error');


                  $(that).find('button i').removeClass('fa-spin fa-spinner');
                  $(that).find('button i').addClass('fa-trash');
                  table.draw();
               }
             });
         } 
       });

	});

});
