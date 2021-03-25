<script>
$(function() {
	"use strict";
	$(document).ready(function()
	{
		$('.update').on('click',function()
		{
			swal({
			      title: '<?php echo $this->lang->line("Update System");?>',
			      text: '<?php echo $this->lang->line("You are about to update system files and database.");?>',
			      icon: 'warning',
			      buttons: true,
			      dangerMode: true,
			    })
			    .then((willDelete) => {
			      if (willDelete) {
	      			if($(this).is('[disabled=disabled]') == false)
	      			{				
	      				$("#update_success").modal();
	      				var warning_msg="<?php echo $this->lang->line('do not close this window or refresh page untill update done.');?>";
	      				var loading = warning_msg+'<br/><br/><img src="'+"<?php echo site_url();?>"+'assets/pre-loader/color/Preloader_9.gif" class="center-block" height="30" width="30">';
	             			$("#update_success_content").attr('class','text-center').html(loading);

	      				var updateVersionId = $(this).attr('updateid');
	      				var version = $(this).attr('version');

	      				var data = {"update_version_id" : updateVersionId,"version" : version};

	      				$.ajax({
	                          type: "POST",
	      					data: data,
	      					url: "<?php echo site_url() . 'update_system/initialize_update';?>",
	      					dataType: 'JSON',
	      					success : function(response)
	      					{
	      						var what_class="";
	      						if(response.status=='1') what_class='alert alert-success text-center';
	      						else what_class='alert alert-danger text-center';
	      						$("#update_success_content").attr('class',what_class).html(response.message);
	      					}
	      				})
	      				
	      			}
			      } 
			    });

			
		});

		<?php

			foreach($add_ons as $add_on) :

				if(isset($add_on_update_versions[$add_on['id']][0]->f_source_and_replace)) :

				$add_on_send_files = $add_on_update_versions[$add_on['id']][0]->f_source_and_replace;
				$add_on_send_sql = json_encode(explode(';', $add_on_update_versions[$add_on['id']][0]->sql_cmd));

		?>

		$("<?php echo '#addonupdate' . $add_on['id']; ?>").on('click',function()
		{
			swal({
			      title: '<?php echo $this->lang->line("Update Add-on");?>',
			      text: '<?php echo $this->lang->line("You are about to update add-on files and database.");?>',
			      icon: 'warning',
			      buttons: true,
			      dangerMode: true,
			    })
			    .then((willDelete) => {
			      if (willDelete) {
      				if($(this).is('[disabled=disabled]') == false)
      				{				
      					$("#update_success").modal();
      					var warning_msg="<?php echo $this->lang->line('do not close this window or refresh page untill update done.');?>";
      					var loading = warning_msg+'<br/><br/><img src="'+"<?php echo site_url();?>"+'assets/pre-loader/color/Preloader_9.gif" class="center-block" height="30" width="30">';
      	       			$("#update_success_content").attr('class','text-center').html(loading);

      					var updateVersionId = $(this).attr('updateid');
      					var version = $(this).attr('version');
      					var folder = $(this).attr('folder');

      					var data = {"update_version_id" : updateVersionId,"version" : version,"folder" : folder};
      					$.ajax({
      	                    type: "POST",
      						data: data,
      						url: "<?php echo site_url() . 'update_system/addon_initialize_update';?>",
      						dataType: 'JSON',
      						success : function(response)
      						{
      							var what_class="";
      							if(response.status=='1') what_class='alert alert-success text-center';
      							else what_class='alert alert-danger text-center';
      							$("#update_success_content").attr('class',what_class).html(response.message);
      						}
      					})
      					
      				}
			      } 
			    });

			
		});

		<?php
			endif;
			endforeach;

		?>	

		$('#update_success').on('hidden.bs.modal', function () { 
			location.reload(); 
		});

		$('.modal-dialog').parent().on('show.bs.modal', function(e){ if($(this).attr('id')!="update_success")$(e.relatedTarget.attributes['data-target'].value).appendTo('body'); })
	});
});
</script>
