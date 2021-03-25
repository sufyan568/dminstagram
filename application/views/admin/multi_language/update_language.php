<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-edit"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
			<div class="breadcrumb-item active"><a href="<?php echo base_url('multi_language/index'); ?>"><?php echo $this->lang->line("Language Editor"); ?></a></div>
			<div class="breadcrumb-item"><?php echo $page_title; ?></div>
		</div>
	</div>

	<?php $this->load->view('admin/theme/message'); ?>
	<div class="section-body">
		<?php 
		if($languageName == "main_app") 
		{ ?>
			<div class="card">
	          <div class="card-footer bg-whitesmoke language_name_field">
	          	<?php 
	          	if($languagename != "english")
	          	{ ?>
		          	<br>
		            <div class="form-group">
			            <div class="input-group mb-3" id="languagename_field">
			                <input type="text" class="form-control" id="language_name" name="language_name" value="<?php echo $languagename; ?>">
			                <div class="input-group-append">
			                  <button class="btn btn-primary" type="submit" id="update_language_name"><i class="fas fa-save"></i> <?php echo $this->lang->line("Update Language"); ?></button>
			                </div>
			            </div>
		            </div>
		        <?php 
	          	} 
	          	else 
	          	{ ?>
	          		<input type="hidden" name="language_name" id="language_name" value="<?php echo $languagename; ?>">
	          		<div class="not_english text-center alert alert-warning">
	          			<?php echo $this->lang->line("English language name can not be updated. You Can update the content if you like."); ?>
	          		</div>
	          	<?php 
	            } ?>
	          </div>

	          <div class="card-header">
	          	<h4 class="text-center width_100"><?php echo $this->lang->line('System Languages')." : ".$languagename." (".count($folderFiles)." ".$this->lang->line('files').")"; ?></h4>
	          </div>

	          <div class="card-body">
	          	<?php 
	          	if(!empty($folderFiles)) 
	          	{
	          		$i = 0;
	          		echo '<div class="row">';
	          		foreach ($folderFiles as $value) 
	          		{ ?>

		          		<div class="col-lg-3 col-12 text-center allFiles" file_type="main-application_<?php echo $i;?>" file_name="<?php echo $value; ?>">
							<div class="card">
			                  <div class="card-header pointer">
			                    <i class="far fa-file-alt"></i>&nbsp;<?php echo $value; ?>&nbsp;
								<i id="<?php echo str_replace(".php",'',$value); ?>" class="fa fa-check-circle c13d408_color d_none"></i>
			                  </div>
			                </div>
						</div>
	          		<?php $i++;
	          		}
	          		echo '</div>';
	          	}
	          	else
	          	{ ?>
	          		<div class="text-center alert alert-warning">
	          			<?php echo $this->lang->line("English language name can not be updated. You Can update the content if you like."); ?>
	          		</div>
	          	<?php 
	          	} ?>
	          </div> 

	        </div>
	    <?php 
		}

		else  if($languageName == "plugin") 
		{ ?>
			<div class="card">

	          <div class="card-header">
	          	<h4 class="text-center width_100"><?php echo $this->lang->line('3rd Party Languages')." : ".$plugin_file; ?></h4>
	          </div>

	          <div class="card-body">
	          	<input type="hidden" id="language_name" name="language_name" value="<?php echo $plugin_file; ?>" class="form-control text-center">
			
				<div class="col-lg-6 col-12 text-center allFiles" file_type="plugin_0" id="plug">
					<div class="card">
	                  <div class="card-header pointer">
	                    <i class="fas fa-plug"></i>&nbsp;<?php echo $plugin_file; ?>&nbsp;
						<i id="plugins1" class="fa fa-check-circle c13d408_color d_none"></i>
	                  </div>
	                </div>
				</div>
	          </div> 

	        </div>
	    <?php 
		} ?>
	</div>
</section>


<script src="<?php echo base_url('assets/js/system/multilanguage.js');?>"></script>


<div class="modal fade" tabindex="-1" role="dialog" id="language_file_modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg min_width_90" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-edit"></i> <?php echo $this->lang->line("Edit Language Translation");?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		

		<div class="row">
			<div id="response_status"></div>
		</div>
		
        <div class="row">
            <div class="col-12">
            	<div class="section-title mt-0 d-none" id="languName"></div>
				<blockquote class="d-none" id="addon_languName"></blockquote>

				<div class="row">
				    <div class="col-12 col-md-6">
				        <div class="form-group">
				            <input type="text" name="search_update_index" id="search_update_index" class="form-control width_50" placeholder="<?php echo$this->lang->line('search...');?>" onkeyup="search_in_td(this,'update_language_form_table')">
				        </div>
				    </div>
				</div>
            	<div id="languageDataBody">
				
            	</div>
            </div>
        </div>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button form_id="language_creating_form" class="btn btn-primary btn-lg update_language_button"><i class="fas fa-save" aria-hidden="true"></i>  <?php echo $this->lang->line("Save"); ?> </button>
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Close"); ?></button>
      </div>
    </div>
  </div>
</div>


<link rel="stylesheet" href="<?php echo base_url('assets/css/system/multilanguage.css');?>">