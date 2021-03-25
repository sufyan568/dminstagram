<section class="section">
	<div class="section-header">
		<h1><i class="fas fa-plus-circle"></i> <?php echo $page_title; ?></h1>
		<div class="section-header-breadcrumb">
			<div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
			<div class="breadcrumb-item active"><a href="<?php echo base_url('multi_language/index'); ?>"><?php echo $this->lang->line("Language Editor"); ?></a></div>
			<div class="breadcrumb-item"><?php echo $page_title; ?></div>
		</div>
	</div>

	<?php $this->load->view('admin/theme/message'); ?>
	<div class="section-body">
		<div class="card">
          <div class="card-footer bg-whitesmoke language_name_field">
          	  <br>
              <div class="form-group">
	              <div class="input-group mb-3">
	                <input type="text" class="form-control" id="language_name" name="language_name" placeholder="<?php echo $this->lang->line("language name"); ?>" aria-label="">
	                <div class="input-group-append">
	                  <button class="btn btn-primary" type="submit" id="save_language_name"><i class="fas fa-plus-circle"></i> <?php echo $this->lang->line("Add Language"); ?></button>
	                </div>
	              </div>
            </div>
          </div>
          <div class="card-body">
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a id="main_tab" class="nav-link active" data-toggle="tab" href="#fbinboxer_languages_tab" role="tab" aria-selected="false"><?php echo $this->lang->line('System Languages'); ?></a>
              </li>              
              <li class="nav-item hidden">
                <a id="addon_tab" class="nav-link" data-toggle="tab" href="#addons_languages_tab" role="tab" aria-selected="true"> <?php echo $this->lang->line("Add-ons Languages"); ?></a>
              </li>
              <li class="nav-item">
                <a id="plugin_tab" class="nav-link" data-toggle="tab" href="#plugins_languages_tab" role="tab" aria-selected="false"><?php echo $this->lang->line("3rd Party Languages"); ?></a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane fade active show" id="fbinboxer_languages_tab" role="tabpanel" aria-labelledby="main_tab">
                <section id="main_app_section">
					<div class="row padding_0_0_0_9px">
						<?php  
						$i=0;
						foreach ($file_name as $value) :  ?>
							<div class="col-lg-3 col-md-3 col-sm-12 col-12 text-center language_file" file_type="main-application_<?php echo $i;?>" file_name="<?php echo $value; ?>">
								<div class="card">
				                  <div class="card-header langFile">
				                    <i class="far fa-file-alt"></i>&nbsp;<?php echo $value; ?>&nbsp;
									<i id="<?php echo str_replace(".php",'',$value); ?>" class="fa fa-check-circle c13d408_color d_none"></i>
				                  </div>
				                </div>
							</div> <?php 
							$i++; 
						endforeach; 
						?>
					</div>
				</section>
              </div>
              
              <div class="tab-pane fade" id="plugins_languages_tab" role="tabpanel" aria-labelledby="plugin_tab">
                <section id="plugin_section">
		      		<div class="row">
		      			<div class="language_file" file_type ="plugin_0" id="plugins">
		      				<div class="card">
		      					<div class="card-header langFile">
				                    <i class="fa fa-plug"></i> &nbsp;<?php echo $this->lang->line("Plugin Languages");?>
									&nbsp;<i id="plugins1" class="fa fa-check-circle c13d408_color d_none"></i>
				                </div>
				            </div>
		      			</div>
		      		</div>
		      	</section> 
              </div>
            </div>
          </div>          
        </div>
	</div>
</section>


<script src="<?php echo base_url('assets/js/system/multilanguage.js');?>"></script>


<div class="modal fade" tabindex="-1" role="dialog" id="language_file_modal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg min_width_90" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line("Add Language Translation");?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="section-title mt-0 d-none" id="language_type_modal"></div>
				<blockquote class="d-none" id="new_lang_val"></blockquote>

				<div class="row">
					<div id="response_status"></div>
				</div>
				<div class="row">
				    <div class="col-12 col-md-6">
				        <div class="form-group">
				            <input type="text" name="search_index" id="search_index" class="form-control width_50" placeholder="<?php echo$this->lang->line('search...');?>" onkeyup="search_in_td(this,'add_language_form_table')">
				        </div>
				    </div>
				</div>
				<div class="row">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div id="languageDataBody">

						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer bg-whitesmoke br">
				<button type="button" form_id="language_creating_form" class="btn btn-primary btn-lg save_language_button"><i class="fas fa-save"></i> <?php echo $this->lang->line("Save"); ?></button>
				<button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal"><i class="fa fa-remove"></i> <?php echo $this->lang->line("Close"); ?></button>
			</div>
		</div>
	</div>
</div>


<link rel="stylesheet" href="<?php echo base_url('assets/css/system/multilanguage.css');?>">