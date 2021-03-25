<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fas fa-chart-pie"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
      <div class="breadcrumb-item active"><a href="<?php echo base_url('admin/settings'); ?>"><?php echo $this->lang->line("Settings"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
          <form action="<?php echo base_url("admin/analytics_settings_action"); ?>" method="POST">
            
          <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $this->session->userdata('csrf_token_session'); ?>">

          <div class="card">
            <div class="card-body">              
                <div class="form-group">
                     <label class="col-xs-12" for=""> <?php echo $this->lang->line("Facebook Pixel ID"); ?></label>
                     <div class="col-xs-12">                         
                         <input type="text" name="pixel_id" id="pixel_id" class="form-control" value="<?php echo $this->config->item("pixel_id");?>" placeholder="<?php echo $this->lang->line('Example : '); ?> 1123241077781024">        
                         <span class="red"><?php echo form_error('pixel_code'); ?></span>
                     </div>
                </div>

                <div class="form-group">
                     <label class="col-xs-12" for=""><?php echo $this->lang->line("Google Analytics ID"); ?></label>
                     <div class="col-xs-12">
                         <input type="text" name="google_id" id="google_id" class="form-control" value="<?php echo $this->config->item("google_id");?>" placeholder="<?php echo $this->lang->line('Example : '); ?> UA-118292462-1">     
                         <span class="red"><?php echo form_error('google_code'); ?></span>
                     </div>         
                </div>
            </div>

            <div class="card-footer bg-whitesmoke">
              <button class="btn btn-primary btn-lg" id="save-btn" type="submit"><i class="fas fa-save"></i> <?php echo $this->lang->line("Save");?></button>
              <button class="btn btn-secondary btn-lg float-right" onclick='goBack("admin/settings")' type="button"><i class="fa fa-remove"></i>  <?php echo $this->lang->line("Cancel");?></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
