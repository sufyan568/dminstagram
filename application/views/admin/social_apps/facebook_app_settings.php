<section class="section section_custom">
  <div class="section-header">
    <h1><i class="fab fa-facebook"></i> <?php echo $page_title; ?></h1>
    <div class="section-header-button">
        <a class="btn btn-primary" href="<?php echo base_url('social_apps/add_facebook_settings') ?>"><i class="fas fa-plus-circle"></i> <?php echo $this->lang->line('Add New APP'); ?></a>
    </div>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><?php echo $this->lang->line("System"); ?></div>
      <div class="breadcrumb-item"><a href="<?php echo base_url('social_apps/settings'); ?>"><?php echo $this->lang->line("Social Apps"); ?></a></div>
      <div class="breadcrumb-item"><?php echo $page_title; ?></div>
    </div>
  </div>

  <?php $this->load->view('admin/theme/message'); ?>

  <div class="section-body">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body data-card">

            <div class="table-responsive2">
              <table class="table table-bordered" id="mytable">
                <thead>
                  <tr>
                    <th>#</th>      
                    <th>
                        <input class="regular-checkbox" id="datatableSelectAllRows" type="checkbox"/><label for="datatableSelectAllRows"></label>        
                    </th>
                    <th><?php echo $this->lang->line("ID"); ?></th>      
                    <th><?php echo $this->lang->line("APP Name"); ?></th>      
                    <th><?php echo $this->lang->line("APP ID"); ?></th>      
                    <th><?php echo $this->lang->line("APP Secret"); ?></th>
                    <th><?php echo $this->lang->line("Status"); ?></th>
                    <th><?php echo $this->lang->line("Token Validity"); ?></th>
                    <th><?php echo $this->lang->line("Actions"); ?></th>
                  </tr>
                </thead>
              </table>
            </div>             
          </div>

        </div>
      </div>
    </div>
    
  </div>
</section>



<script src="<?php echo base_url('assets/js/system/fb_app_settings.js');?>"></script>