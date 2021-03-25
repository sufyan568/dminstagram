<link rel="stylesheet" href="<?php echo base_url('assets/css/system/account_import.css');?>">
<?php $fb_login_button=str_replace("ThisIsTheLoginButtonForFacebook",$this->lang->line("Login with Facebook"), $fb_login_button); ?>

<section class="section">
	<div class="section-header">
	  <h1><i class="fa fa-facebook-official"></i> <?php echo $this->lang->line("Facebook Accounts") ?></h1>
	  
	</div>

	<?php 
		if($this->session->userdata('success_message') == 'success')
		{
			echo "<div class='text-info text-center font_size_20px'><i class='fa fa-check-circle'></i> ".$this->lang->line('Your account has been imported successfully.')."</div><br/>";
			$this->session->unset_userdata('success_message');
		}

		if($this->session->userdata('limit_cross') != '')
		{
			echo "<div class='text-danger text-center font_size_20px'><i class='fa fa-remove'></i> ".$this->session->userdata('limit_cross')."</div><br/>";
			$this->session->unset_userdata('limit_cross');
		}
		$is_demo=$this->is_demo;
		
	?>
	
	<div class="section-body">
		<div class="">
			<?php  if($show_import_account_box==0) : ?>
				<br/>
				<div class="p-3">			
					<div class='alert alert-danger text-center'><i class='fa fa-times-circle'></i> <?php echo $this->lang->line('Due to system configuration change you have to delete one or more imported FB accounts and import again. Please check the following accounts and delete the account that has warning to delete.'); ?></div>
				</div>
			<?php endif; ?>


			<div class="row  justify-content-center pt-0 pb-0 pl-3 pr-3">
					<?php 
					if($is_demo && $this->session->userdata("user_type")=="Admin")  
					echo '<div class="alert alert-warning text-center">Account import has been disabled in admin account because you will not be able to unlink the Facebook account you import as admin. If you want to test with your own accout then <a href="'.base_url('home/sign_up').'" target="_BLANK">sign up</a> to create your own demo account then import your Facebook account there.</div>';
					else if($existing_accounts != '0') {?>		
						<div class="text-center">
							<p data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line("You must be logged in your facebook account for which you want to refresh your access token. for synch your new page, simply refresh your token. if any access token is restricted for any action, refresh your access token.");?>"> <?php if($this->config->item('developer_access') != '1') echo $fb_login_button; ?></p>
						</div>
				    <?php } ?>
			</div>

			<?php if($existing_accounts != '0') : ?>		
				<div>			
					<div class="row">
					<?php $i=0; foreach($existing_accounts as $value) : ?>
						<div class="col-12 col-sm-12 col-md-6 pl-1 pr-1">
							
							<?php $profile_picture="https://graph.facebook.com/me/picture?access_token={$value['user_access_token']}&width=150&height=150"; ?>

					    	<div class="card profile-widget mb-0">
					    		<div class="profile-widget-header">
	    		                    <img src="<?php echo $profile_picture; ?>" class="img-thumbnail profile-widget-picture">
	    		                    <div class="profile-widget-items">
	    		                      <div class="profile-widget-item">
	    		                        <div class="profile-widget-item-label">
	    		                        	<?php echo count($value['page_list']); ?> <?php echo $this->lang->line("pages"); ?> 
	    		                        	<?php if($this->config->item('facebook_poster_group_enable_disable')=='1') :?>
	    		                        	/ <?php echo count($value['group_list']); ?> <?php echo $this->lang->line("groups"); ?>
		    		                        <?php endif; ?>
	    		                        </div>
	    		                        <div class="profile-widget-item-value">
	    		                        	<?php  echo $value['name']; ?>
	    		                        	
	    		                        	  <button class="delete_account btn-circle btn btn-outline-danger btn-sm" table_id="<?php echo $value['userinfo_table_id']; ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line("Do you want to remove this account from our database? you can import again.");?>"><i class="fas fa-dumpster"></i> </button>
	    		                        	</div>
	    		                        </div>

	    		                    </div>
	    		                </div>

				    	  	</div>
							
							<div class="card">
							  <div class="card-body">
							    <div class="summary">
							    	<?php
							    		if($value['need_to_delete'] == 1)
							    		{
							    			echo "<div class='alert alert-danger text-center'><i class='fa fa-close'></i> ".$this->lang->line('you have to delete this account.')."</div>";
							    		} 
							    	?>
							    	<?php 
							    		if($value['validity'] == 'no')
							    		{
							    			echo "<div class='alert alert-danger text-center'><i class='fa fa-close'></i> ".$this->lang->line('your login validity has been expired.')."</div>";
							    		}
							    	?>
							     <div class="summary-item">
							      	<!-- page lists -->
							        <h6 class="mt-3"><?php echo $this->lang->line('Page List') ?> <span class="text-muted">(<?php echo count($value['page_list']); ?> <?php echo $this->lang->line("pages"); ?>)</span></h6>
							      	<div class="nicescroll height_310px" >
								        <ul class="list-unstyled list-unstyled-border">
								        	<?php foreach($value['page_list'] as $page_info) : ?>
										        <li class="media">
										            <div class="page_thumbnail">
										            	<img alt="image" class="mr-3 rounded" width="50" src="<?php echo $page_info['page_profile']; ?>">														
										            </div><!--/.page_thumbnail-->
										            
										            <div class="media-body"> 
										              	<div class="media-right">

										              		<?php if($page_info['bot_enabled'] == '1') :?>
										              			<button class="btn-sm btn btn-circle btn-outline-danger delete_full_bot mt-2 mr-1" bot-enable="<?php echo $page_info['id'];?>" id="bot-<?php echo $page_info['id'];?>" already_disabled="no" title="<?php echo $this->lang->line("Delete Bot Connection & all settings.");?>" data-placement="right" data-toggle="tooltip">
							              			              	<i class="fas fa-eraser"></i> 
							              		              	</button>
							              		            <?php elseif($page_info['bot_enabled'] == '2'): ?>
							              		            	<button class="btn-sm btn btn-circle btn-outline-danger delete_full_bot mt-2 mr-1" bot-enable="<?php echo $page_info['id'];?>" id="bot-<?php echo $page_info['id'];?>" already_disabled="yes" title="<?php echo $this->lang->line("Delete Bot Connection & all settings.");?>" data-placement="right" data-toggle="tooltip">
							              			              	<i class="fas fa-eraser"></i> 
							              		              	</button>
										              		<?php endif; ?>

				              		                      	<?php if($page_info['bot_enabled']=='0') : ?>
				              									<button restart='0' bot-enable="<?php echo $page_info['id'];?>" id="bot-<?php echo $page_info['id'];?>" class="btn btn-sm btn-outline-primary btn-circle enable_webhook mt-2 mr-1" title="<?php echo $this->lang->line("Enable Bot Connection");?>" data-placement="left" data-toggle="tooltip"><i class="fas fa-plug"></i></button>
				              								<?php elseif($page_info['bot_enabled']=='1') : ?>
				              									<button restart='0' bot-enable="<?php echo $page_info['id'];?>" id="bot-<?php echo $page_info['id'];?>" class="btn btn-sm btn-outline-dark btn-circle disable_webhook mt-2 mr-1" title="<?php echo $this->lang->line("Disable Bot Connection");?>" data-placement="left" data-toggle="tooltip"><i class="fas fa-power-off"></i></button>
				              								<?php else : ?>
				              									<button restart='1' bot-enable="<?php echo $page_info['id'];?>" id="bot-<?php echo $page_info['id'];?>" class="btn btn-sm btn-outline-primary btn-circle enable_webhook mt-2 mr-1" title="<?php echo $this->lang->line("Re-start Bot Connection");?>" data-placement="left" data-toggle="tooltip"><i class="fas fa-toggle-on"></i></button>
				              								<?php endif; ?>									              	  											
															
															<?php if($page_info['bot_enabled'] == 1) :?>
																<button class="btn-sm btn btn-outline-danger btn-circle right-button disabled" table_id="<?php echo $page_info['id']; ?>" title="<?php echo $this->lang->line("To enable delete button, first disable bot connection.");?>" data-placement="right" data-toggle="tooltip">
						              			              	  	<i class="fas fa-trash-alt"></i> 
						              		              	  	</button>
															<?php else : ?>
					              								<button class="btn-sm btn btn-outline-danger btn-circle page_delete" table_id="<?php echo $page_info['id']; ?>" title="<?php echo $this->lang->line("Delete this page from database.");?>" data-placement="right" data-toggle="tooltip">
							              			              	  	<i class="fas fa-trash-alt"></i> 
							              		              	</button>            	  	
															<?php endif; ?>
										              	</div>

										              	<div class="media-title mb-0">
										              		<a target="_BLANK" href="<?php echo base_url('messenger_bot_analytics/result/').$page_info['id'];?>" ><?php echo $page_info['page_name']; ?></a>
										              	</div>

										              	<div class="text-small text-muted line_height_12px">
										                  <?php echo $this->lang->line('email');?> : </b> <?php echo $page_info['page_email']; ?>
										              	</div>
										              	<div class="text-small text-muted">
										                  <?php echo $this->lang->line('Page ID');?> : </b> <a target="_BLANK" href="https://facebook.com/<?php echo $page_info['page_id'];?>" ><?php echo $page_info['page_id']; ?></a>
										              	</div>
										              	<?php if(isset($page_info['has_instagram']) && $page_info['has_instagram'] == '1') : ?>
										              		<div class="row">
										              			<div class="col-12 text-center">
										              				<i class="fab fa-instagram"></i> 
										              				<a href="https://www.instagram.com/<?php echo $page_info['insta_username']; ?>" target="_BLANK"><?php echo $page_info['insta_username']; ?></a> 
										              				<i class="fas fa-sync-alt update_account" table_id="<?php echo $page_info['id'];?>" title="<?php echo $this->lang->line("Update account info");?>" data-placement="right" data-toggle="tooltip"></i>
										              			</div>
										              			<br>
										              			<div class="col-12 text-center">
										              				<b><?php echo $this->lang->line('Media'); ?></b> : <span id="media_count_<?php echo $page_info['id'];?>"><?php echo custom_number_format($page_info['insta_media_count']); ?></span> | 
										              				<b><?php echo $this->lang->line('Followers'); ?></b> : <span id="follower_count_<?php echo $page_info['id'];?>"><?php echo custom_number_format($page_info['insta_followers_count']); ?></span>
										              			</div>
										              		</div>
											            <?php endif; ?>
										            </div>
										        </li>
								          	<?php endforeach; ?>
								        </ul>
							    	</div>
							      </div>
							    </div>
							  </div>
							</div>


						</div>

					<?php
						$i++;
						if($i%2 == 0)
							echo "</div><div class='row'>";
						endforeach;				
					?>
					</div> 
				</div>
			<?php else : ?>
				<div class="card" id="nodata">
				  <div class="card-body">
				    <div class="empty-state">
				      <img class="img-fluid height_200px" src="<?php echo base_url('assets/img/drawkit/drawkit-nature-man-colour.svg'); ?>" alt="image">
				      <h2 class="mt-0"><?php echo $this->lang->line("You haven not connected any account yet.")?></h2>
				      <br/>
				      <h4>
				      	<div class="text-center">
				      		<p data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line("you must be logged in your facebook account for which you want to refresh your access token. for synch your new page, simply refresh your token. if any access token is restricted for any action, refresh your access token.");?>"> <?php if($this->config->item('developer_access') != '1') echo $fb_login_button; ?></p>
				      	</div>
				      </h4>
				    </div>
				  </div>
				</div>
			<?php endif; ?>
		</div>
	</div>

</section>


<div class="modal fade" id="delete_confirmation" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center"><i class="fa fa-flag"></i> <?php echo $this->lang->line("Deletion Report") ?></h4>
            </div>
            <div class="modal-body" id="delete_confirmation_body">                

            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/system/account_import.js');?>"></script>